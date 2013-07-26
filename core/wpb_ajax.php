<?php

/**
 * Broadcast processor Class
 */
if ( !class_exists('WPB_Ajax') ) {
  class WPB_Ajax {

    /**
     * Construct.
     */
    function __construct() {
      add_action('wp_ajax_wpb_search_objects', array( __CLASS__, 'search_objects' ));
      add_action('wp_ajax_wpb_broadcast', array( __CLASS__, 'broadcast' ));
      add_action('wp_ajax_wpb_js_localization', array( __CLASS__, 'localize_scripts' ));
      add_action('wp_ajax_wpb_filter_load_ui', array( __CLASS__, 'filter_load_ui' ));
    }

    /**
     *
     */
    function filter_load_ui() {
      $data = array();

      if ( class_exists( $type = $_POST['filter_type'] ) ) {
       $data['filter_ui'] = $type::ui_callback();
      }

      die( json_encode( $data ) );
    }

    /**
     * Localization.
     */
    function localize_scripts() {
      $l10n = array();

      //** Include the list of translations */
      include_once WPB_PATH . 'l10n.php';

      //** All additional localizations must be added using the filter below. */
      $l10n = apply_filters( 'wpb::js::localization', $l10n );

      foreach( (array) $l10n as $key => $value ) {
        if( !is_scalar( $value ) ) {
          continue;
        }
        $l10n[ $key ] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' );
      }

      header( 'Content-type: application/x-javascript' );
      die( "var wpb_lang = ( typeof wpb_lang === 'object' ) ? wpb_lang : {}; wpb_lang = " . json_encode( $l10n ) . ';' );
    }

    /**
     * Search objects via Ajax
     * @author korotkov@ud
     */
    function search_objects() {
      $query = new WP_Query(array(
          'posts_per_page' => -1,
          'post_type' => $_POST['post_type'],
          's' => $_POST['s']
      ));
      $objects = array();
      foreach( $query->posts as $post ) {
        $objects[] = array(
            'id' => $post->ID,
            'title' => $post->post_title
        );
      }
      die( json_encode( $objects ) );
    }

    /**
     * Main broadcast process
     * @author korotkov@ud
     */
    public function broadcast() {

      $return = array();

      if ( !empty( $_POST ) ) {

        include_once( ABSPATH . WPINC . '/class-IXR.php' );
        include_once( ABSPATH . WPINC . '/class-wp-http-ixr-client.php' );

        $credential_id = $_POST['credential'];
        $object_id = $_POST['object'];

        $credentials = new WPB_Item_Credential( $credential_id );
        $credentials = $credentials->to_array();

        if ( $xml_rpc_client = new WP_HTTP_IXR_CLIENT( $credentials['end_point'].'/xmlrpc.php' ) ) {

          set_time_limit(0);

          //**  */
          if ( $user = $xml_rpc_client->query('wp.getProfile', array(
            $credentials['blog_id'],
            $credentials['username'],
            $credentials['password']
          )) ) {

            $post_object  = get_post( $object_id );

            $custom_fields = array();
            $meta_data = get_post_meta( $object_id );

            $keys_to_skip = array(
                'gallery_images',
                'slideshow_images',
                '_thumbnail_id'
            );

            foreach( $meta_data as $item_key => $item_value ) {
              if ( !in_array( $item_key, $keys_to_skip ) ) {
                $temp_object = new stdClass();
                $temp_object->key = $item_key;
                $temp_object->value = $item_value[0];
                $custom_fields[] = $temp_object;
              }
            }

            $broadcast_object = new stdClass();
            $broadcast_object->post_type = $post_object->post_type;
            $broadcast_object->post_status = $post_object->post_status;
            $broadcast_object->post_title = $post_object->post_title;
            $broadcast_object->post_author = $user->user_id;
            $broadcast_object->post_excerpt = $post_object->post_excerpt;
            $broadcast_object->post_content = $post_object->post_content;
            $broadcast_object->post_name = $post_object->post_name;
            $broadcast_object->post_password = $post_object->post_password;
            $broadcast_object->comment_status = $post_object->comment_status;
            $broadcast_object->ping_status = $post_object->ping_status;
            $broadcast_object->custom_fields = $custom_fields;

            $object_attachments = get_posts( array(
              'post_type' => 'attachment',
              'posts_per_page' => -1,
              'post_parent' => $object_id
            ) );

            $object_attachment_data = array();

            foreach( $object_attachments as $attachment ) {
              $object_attachment_data[] = array(
                'path' => get_attached_file( $attachment->ID )
              );
            }

            $images_uploaded = array();
            foreach ( $object_attachment_data as $url ) {

              $ft = getimagesize( $url['path'] );
              $fh = fopen( $url['path'], 'r');
              $fs = filesize( $url['path'] );
              $image_data = fread( $fh, $fs );
              fclose($fh);

              $params = array(
                  'name' => basename( $url['path'] ),
                  'type' => $ft['mime'],
                  'bits' => new IXR_Base64( $image_data ),
                  'overwrite' => true
              );

              if ( $result = $xml_rpc_client->query('wp.uploadFile', array(
                $credentials['blog_id'],
                $credentials['username'],
                $credentials['password'],
                $params
              )) ) {
                $images_uploaded[] = $xml_rpc_client->getResponse();
                $return['messages'][] = sprintf( __( 'File "%s" has been successfully uploaded.', WPB_DOMAIN ), $params['name'] );
              } else {
                $return['messages'][] = sprintf( __( 'Could not upload file "%s" due to error: %s', WPB_DOMAIN ), $params['name'], $xml_rpc_client->getErrorMessage() );
              }

            }

            $result = $xml_rpc_client->query('wp.newPost', array(
              $credentials['blog_id'],
              $credentials['username'],
              $credentials['password'],
              $broadcast_object
            ));

            if ( $result ) {

              $new_post_id = $xml_rpc_client->getResponse();
              $attachment_object = new stdClass();
              $attachment_object->post_parent = $new_post_id;

              //** Associate attachments and objects */
              foreach( $images_uploaded as $attachment ) {
                if( $result = $xml_rpc_client->query('wp.editPost', array(
                  $credentials['blog_id'],
                  $credentials['username'],
                  $credentials['password'],
                  $attachment['id'],
                  $attachment_object
                )) ) {
                  $return['messages'][] = sprintf( __( 'File "%s" has been attached to %s "%s".', WPB_DOMAIN ), $attachment['file'], $post_object->post_type, $post_object->post_title );
                } else {
                  $return['messages'][] = sprintf( __( 'Could not attach file "%s" to %s "%s" due to error: %s', WPB_DOMAIN ), $attachment['file'], $post_object->post_type, $post_object->post_title, $xml_rpc_client->getErrorMessage() );
                }
              }

              $return['success'] = 1;
              $return['messages'][] = sprintf( __( '%s "%s" has been broadcasted to "%s" successfully with ID %s. %s attachments uploaded.', WPB_DOMAIN ), ucfirst($post_object->post_type), $post_object->post_title, $credentials['end_point'], $new_post_id, count($images_uploaded) );
            } else {
              $return['messages'][] = sprintf( __( 'Could not broadcast %s "%s" due to error: %s', WPB_DOMAIN ), $post_object->post_type, $post_object->post_title, $xml_rpc_client->getErrorMessage() );
            }

          } else {
            $return['messages'][] = sprintf( __( 'Could not get remote user data due to error: %s', WPB_DOMAIN ), $xml_rpc_client->getErrorMessage() );
          }

        } else {
          $return['messages'][] = __( 'Cannot create XML-RPC Client', WPB_DOMAIN );
        }

      }

      die(json_encode($return));

    }
  }
}

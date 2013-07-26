<?php

/**
 * POST Actions Class
 */
if ( !class_exists('WPB_Actions') ) {
  class WPB_Actions {

    /**
     * Construct. Executes what he knows.
     */
    public function __construct() {
      if ( !empty( $_POST ) ) {
        if ( !empty( $_POST['wpb_action'] ) && is_callable( array(__CLASS__, $_POST['wpb_action']) ) ) {
          call_user_func( array(__CLASS__, $_POST['wpb_action']) );
        }
      }
      if ( !empty( $_GET['wpb_action'] ) ) {
        if ( is_callable( array(__CLASS__, $_GET['wpb_action']) ) ) {
          call_user_func( array(__CLASS__, $_GET['wpb_action']) );
        }
      }
    }

    /**
     * Actions from manage credentials page
     */
    function wpb_credentials() {
      if ( wp_verify_nonce( $_POST['wpb-credentials-nonce'], 'wpb-credentials' ) ) {
        if ( !empty( $_POST['save'] ) ) {
          foreach ( (array)$_POST['wpb_credentials'] as $credential_data ) {
            $credential = new WPB_Item_Credential( $credential_data['id'] );
            $credential->update( $credential_data );
          }
          add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_success_credentials_update' ) );
        }
        if ( !empty( $_POST['action'] ) ) {
          switch( $_POST['action'] ) {
            case 'delete':
              foreach ( (array)$_POST['wpb_delete_credentials'] as $id ) {
                $credential = new WPB_Item_Credential( $id );
                $credential->delete();
              }
              add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_success_credentials_delete' ) );
              break;

            default: break;
          }
        }
        do_action('wpb_credentials_action', $_REQUEST);
      }
    }

    /**
     *
     */
    function wpb_broadcasts() {
      if ( wp_verify_nonce( $_POST['wpb-broadcasts-nonce'], 'wpb-broadcasts' ) ) {
        if ( !empty( $_POST['save'] ) ) {
          foreach ( (array)$_POST['wpb_broadcasts'] as $broadcast_data ) {
            $credential = new WPB_Item_Broadcast( $broadcast_data['id'] );
            $credential->update( $broadcast_data );
          }
          add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_success_broadcasts_update' ) );
        }
        if ( !empty( $_POST['action'] ) ) {
          switch( $_POST['action'] ) {
            case 'delete':
              foreach ( (array)$_POST['wpb_delete_broadcasts'] as $id ) {
                $credential = new WPB_Item_Broadcast( $id );
                $credential->delete();
              }
              add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_success_broadcasts_delete' ) );
              break;

            default: break;
          }
        }
        do_action( 'wpb_broadcasts_action', $_REQUEST );
      }
    }

    /**
     *
     */
    function wpb_filters() {
      if ( wp_verify_nonce( $_POST['wpb-filters-nonce'], 'wpb-filters' ) ) {
        if ( !empty( $_POST['action'] ) ) {
          switch( $_POST['action'] ) {
            case 'delete':
              global $wpb_filters;
              $filters = $wpb_filters->get();
              foreach ( (array)$_POST['wpb_delete_filters'] as $id ) {
                if ( class_exists( $filters[$id]['type']['key'] ) ) {
                  $filter = new $filters[$id]['type']['key']( $id );
                  $filter->delete();
                }
              }
              add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_success_filters_delete' ) );
              break;

            default: break;
          }
        }
      }
    }

    /**
     * Add new credential action
     */
    function wpb_add_new_credential() {
      if ( wp_verify_nonce( $_POST['wpb-add-new-credential-nonce'], 'wpb-add-new-credential' ) ) {
        $new_credential = new WPB_Item_Credential( false, $_POST['end_point'], $_POST['blog_id'], $_POST['username'], $_POST['password'] );
        if ( wpb_validate_credentials( $new_credential ) ) {
          $new_credential->save();
          add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_success_add_new_credential' ) );
        } else {
          add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_error_invalid_credentials' ) );
        }
      } else {
        add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_error_add_new_credential' ) );
      }
      do_action('wpb_add_new_credential_action');
    }

    /**
     * Add new filter
     */
    function wpb_add_new_filter() {
      if ( wp_verify_nonce( $_POST['wpb-add-new-filter-nonce'], 'wpb-add-new-filter' ) ) {
        $new_filter = new $_POST['filter_type']( false, $_POST['name'], $_POST['filter_data'] );
        $new_filter->save();
        add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_success_add_new_filter' ) );
      } else {
        add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_error_add_new_filter' ) );
      }
      do_action('wpb_add_new_filter_action');
    }

    /**
     *
     */
    function wpb_add_new_broadcast() {
      if ( wp_verify_nonce( $_POST['wpb-add-new-broadcast-nonce'], 'wpb-add-new-broadcast' ) ) {
        $new_broadcast = new WPB_Item_Broadcast( false, $_POST['name'], $_POST['credentials'], $_POST['filters'] );
        $new_broadcast->save();
        add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_success_add_new_broadcast' ) );
      } else {
        add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_error_add_new_broadcast' ) );
      }
      do_action('wpb_add_new_broadcast_action');
    }

    /**
     * Delete credential action
     */
    function wpb_delete_credential() {
      $credintial_id = $_GET['wpb_credential_id'];
      $credential = new WPB_Item_Credential($credintial_id);
      $credential->delete();
      add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_success_single_credential_delete' ) );
      do_action('wpb_delete_credential_action');
    }

    /**
     * Delete credential action
     */
    function wpb_delete_filter() {
      global $wpb_filters;
      $filters = $wpb_filters->get();
      $filter_id = $_GET['wpb_filter_id'];
      if ( class_exists( $filters[$filter_id]['type']['key'] ) ) {
        $filter = new $filters[$filter_id]['type']['key']( $filter_id );
        $filter->delete();
        add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_success_single_filter_delete' ) );
      } else {
        add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_error_single_filter_delete' ) );
      }
      do_action('wpb_delete_filter_action');
    }

    /**
     *
     */
    function wpb_delete_broadcast() {
      $broadcast_id = $_GET['wpb_broadcast_id'];
      $broadcast = new WPB_Item_Broadcast($broadcast_id);
      $broadcast->delete();
      add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_success_single_broadcast_delete' ) );
      do_action( 'wpb_delete_broadcast_action' );
    }

    /**
     * Edit credential action
     */
    function wpb_edit_credential() {
      $credintial_id = $_GET['wpb_credential_id'];
      wp_redirect(admin_url('admin.php?page=wpb_add_new_credential&wpb_credential_id='.$credintial_id)); die();
    }

    /**
     * Edit credential action
     */
    function wpb_edit_filter() {
      $filter_id = $_GET['wpb_filter_id'];
      wp_redirect(admin_url('admin.php?page=wpb_add_new_filter&wpb_filter_id='.$filter_id)); die();
    }

    /**
     * Do edit of the filter
     */
    function wpb_do_edit_filter() {
      if ( wp_verify_nonce( $_POST['wpb-add-new-filter-nonce'], 'wpb-add-new-filter' ) ) {
        $old_filter = new $_POST['filter_type']( $_GET['wpb_filter_id'] );
        $old_filter->update(array(
            'name' => $_POST['name'],
            'data' => $_POST['filter_data']
        ));
        add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_success_updated_filter' ) );
      } else {
        add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_error_updated_filter' ) );
      }
      do_action('wpb_update_filter_action');
    }

    /**
     * Do edit of credential
     */
    function wpb_do_edit_credential() {
      if ( wp_verify_nonce( $_POST['wpb-add-new-credential-nonce'], 'wpb-add-new-credential' ) ) {
        $old_credential = new WPB_Item_Credential( $_GET['wpb_credential_id'] );
        $new_credential = new WPB_Item_Credential( false, $_POST['end_point'], $_POST['blog_id'], $_POST['username'], $_POST['password'] );
        if ( wpb_validate_credentials( $new_credential ) ) {
          $old_credential->update(array(
              'end_point' => $_POST['end_point'],
              'blog_id'   => $_POST['blog_id'],
              'username'  => $_POST['username'],
              'password'  => $_POST['password']
          ));
          add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_success_updated_credential' ) );
        } else {
          add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_error_invalid_credentials' ) );
        }
      } else {
        add_action( 'admin_notices', array( 'WPB_Manage_Page', 'notice_error_updated_credential' ) );
      }
      do_action('wpb_update_credential_action');
    }
  }
}

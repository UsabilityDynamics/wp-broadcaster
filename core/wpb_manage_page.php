<?php

/**
 * Manage Page Class
 */
if ( !class_exists('WPB_Manage_Page') ) {
  class WPB_Manage_Page {

    /**
     * Construct
     * @author korotkov@ud
     */
    function __construct() {
      add_action('admin_menu', array($this, 'admin_menu'));
    }

    /**
     * Create menu
     * @author korotkov@ud
     */
    function admin_menu() {
      $wpb_broadcasts = add_menu_page( __( 'Broadcasts', WPB_DOMAIN ), __( 'Broadcasts', WPB_DOMAIN ), 'manage_options', 'wpb_broadcasts', array( $this, 'broadcasts' ) );
      $wpb_add_new_broadcast = add_submenu_page( 'wpb_broadcasts', __( 'Broadcaster / New Broadcast', WPB_DOMAIN ), __( 'Add New Broadcast', WPB_DOMAIN ), 'manage_options', 'wpb_add_new_broadcast', array( $this, 'add_new_broadcast' ) );
      $wpb_credentials = add_submenu_page( 'wpb_broadcasts', __( 'Broadcaster / Credentials', WPB_DOMAIN ), __( 'Credentials', WPB_DOMAIN ), 'manage_options', 'wpb_credentials', array( $this, 'credentials' ) );
      $wpb_add_new_credential = add_submenu_page( 'wpb_broadcasts', __( 'Broadcaster / New Credentials', WPB_DOMAIN ), __( 'Add New Credentials', WPB_DOMAIN ), 'manage_options', 'wpb_add_new_credential', array( $this, 'add_new_credential' ) );
      $wpb_filters = add_submenu_page( 'wpb_broadcasts', __( 'Broadcaster / Filters', WPB_DOMAIN ), __( 'Filters', WPB_DOMAIN ), 'manage_options', 'wpb_filters', array( $this, 'filters' ) );
      $wpb_add_new_filter = add_submenu_page( 'wpb_broadcasts', __( 'Broadcaster / New Filter', WPB_DOMAIN ), __( 'Add New Filter', WPB_DOMAIN ), 'manage_options', 'wpb_add_new_filter', array( $this, 'add_new_filter' ) );

      add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
    }

    /**
     * Include assets
     * @author korotkov@ud
     */
    function assets() {
      global $current_screen;

      //** Register scripts */
      wp_register_script( 'wpb-localization',            admin_url( '/admin-ajax.php?action=wpb_js_localization' ), array(),         WPB_VERSION );
      wp_register_script( 'wpb_global',                  WPB_URL . 'assets/js/admin-global.js',                     array('jquery'), WPB_VERSION );
      wp_register_script( 'wpb_select2_js',              WPB_URL . 'third-party/select2/select2.js',                array('jquery'), WPB_VERSION );
      wp_register_script( 'wpb_admin_broadcasts',        WPB_URL . 'assets/js/admin-broadcasts.js',                 array('jquery', 'wpb_global'), WPB_VERSION );
      wp_register_script( 'wpb_admin_credentials',       WPB_URL . 'assets/js/admin-credentials.js',                array('jquery', 'wpb_global'), WPB_VERSION );
      wp_register_script( 'wpb_admin_add_new_filter',    WPB_URL . 'assets/js/admin-add-new-filter.js',             array('jquery', 'wpb_global'), WPB_VERSION );
      wp_register_script( 'wpb_admin_add_new_broadcast', WPB_URL . 'assets/js/admin-add-new-broadcast.js',          array('jquery'), WPB_VERSION );

      //** Register styles */
      wp_register_style( 'wpb_admin_styles', WPB_URL . 'assets/css/admin-styles.css',                            array(), WPB_VERSION );
      wp_register_style( 'wpb_select2_css',  WPB_URL . 'third-party/select2/select2.css',                        array(), WPB_VERSION );
      wp_register_style( 'jquery-ui-css',    'http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css', array(), WPB_VERSION );

      switch( $current_screen->id ) {

        case 'toplevel_page_wpb_broadcasts':
          wp_enqueue_script( 'wpb-localization' );
          wp_enqueue_style( 'wpb_admin_styles' );
          wp_enqueue_style( 'wpb_select2_css' );
          wp_enqueue_script( 'wpb_select2_js' );
          wp_enqueue_script( 'jquery-ui-progressbar' );
          wp_enqueue_style( 'jquery-ui-css' );
          wp_enqueue_script( 'wpb_admin_broadcasts' );
          break;

        case 'broadcasts_page_wpb_credentials':
          wp_enqueue_style( 'wpb_admin_styles' );
          wp_enqueue_style( 'jquery-ui-css' );
          wp_enqueue_script( 'wpb_admin_credentials' );
          wp_enqueue_script( 'jquery-ui-sortable' );
          break;

        case 'broadcasts_page_wpb_add_new_credential':
          wp_enqueue_style( 'wpb_admin_styles' );
          break;

        case 'broadcasts_page_wpb_add_new_broadcast':
          wp_enqueue_script( 'wpb-localization' );
          wp_enqueue_style( 'wpb_admin_styles' );
          wp_enqueue_style( 'wpb_select2_css' );
          wp_enqueue_script( 'wpb_select2_js' );
          wp_enqueue_script( 'wpb_admin_add_new_broadcast' );
          break;

        case 'broadcasts_page_wpb_add_new_filter':
          wp_enqueue_style( 'wpb_admin_styles' );
          wp_enqueue_style( 'wpb_select2_css' );
          wp_enqueue_script( 'wpb_select2_js' );
          wp_enqueue_script( 'wpb-localization' );
          wp_enqueue_script( 'wpb_admin_add_new_filter' );
          break;

        case 'broadcasts_page_wpb_filters':
          wp_enqueue_style( 'wpb_admin_styles' );
          wp_enqueue_script( 'wpb_global' );
          wp_enqueue_style( 'jquery-ui-css' );
          break;

        default:break;

      }

    }

    /**
     *
     * @global type $wpb_filters
     */
    function filters() {
      global $wpb_filters;

      //** Flush */
      $filters_objects = array();

      $filters = $wpb_filters->get();

      foreach( $filters as $f ) {
        if ( !empty( $f['type']['key'] ) && class_exists( $f['type']['key'] ) )
          $filters_objects[] = new $f['type']['key']($f['id']);
      }

      $data = array();

      $data['filters'] = $filters_objects;
      $data['bulk_actions'] = array(
          array(
              'value' => 'delete',
              'label' => __('Delete', WPB_DOMAIN)
          )
      );

      ob_start();
      include_once WPB_PATH.'templates/settings-filters.php';
      echo apply_filters( 'wpb_settings_filters', ob_get_clean() );
    }

    /**
     *
     */
    function add_new_filter() {
      $data = array();

      $data['filter_types'] = apply_filters('wpb_filter_types', array());

      $defaults = array(
          'heading'    => __( 'Add New Filter', WPB_DOMAIN ),
          'button'     => __( 'Save Filter', WPB_DOMAIN ),
          'id'         => '',
          'name'       => '',
          'type'       => '',
          'data'       => '',
          'wpb_action' => 'wpb_add_new_filter'
      );

      if ( isset( $_GET['wpb_filter_id'] ) ) {
        global $wpb_filters;
        $filters = $wpb_filters->get();
        $filter_id = $_GET['wpb_filter_id'];

        if ( class_exists($filters[$filter_id]['type']['key']) )
          if ( $filter = new $filters[$filter_id]['type']['key']( $filter_id ) ) {
            $data['heading']    = __( 'Edit Filter', WPB_DOMAIN );
            $data['button']     = __( 'Update Filter', WPB_DOMAIN );
            $data['id']         = $filter->id;
            $data['name']       = $filter->name;
            $data['type']       = $filter->type;
            $data['data']       = $filter->data;
            $data['wpb_action'] = 'wpb_do_edit_filter';
          }
      }

      $data = wp_parse_args($data, $defaults);

      ob_start();
      include_once WPB_PATH.'templates/settings-add_new_filter.php';
      echo apply_filters( 'wpb_settings_new_filter', ob_get_clean() );
    }

    /**
     * Main Settings
     * @author korotkov@ud
     */
    function broadcasts() {
      global $wpb_broadcasts, $wpb_credentials, $wpb_filters;

      //** Flush */
      $broadcasts_objects = array();

      $broadcasts  = $wpb_broadcasts->get();
      $filters     = $wpb_filters->get();
      $credentials = $wpb_credentials->get();

      foreach( $broadcasts as $b ) {
        $broadcasts_objects[] = new WPB_Item_Broadcast( $b['id'] );
      }

      $data = array();

      $data['broadcasts']  = $broadcasts_objects;
      $data['filters']     = $filters;
      $data['credentials'] = $credentials;
      $data['bulk_actions'] = array(
        array(
          'value' => 'delete',
          'label' => __('Delete', WPB_DOMAIN)
        )
      );

      ob_start();
      include_once WPB_PATH.'templates/settings-broadcasts.php';
      echo apply_filters( 'wpb_settings_broadcasts', ob_get_clean() );
    }

    /**
     * Credentials Settings
     * @author korotkov@ud
     */
    function credentials() {
      global $wpb_credentials;

      $credentials = $wpb_credentials->get();

      $credentials_objects = array();

      foreach( $credentials as $cred ) {
        $credentials_objects[] = new WPB_Item_Credential($cred['id']);
      }

      $data = array(
          'credentials' => $credentials_objects,
          'bulk_actions' => array(
              array(
                  'value' => 'delete',
                  'label' => __('Delete', WPB_DOMAIN)
              )
          )
      );

      ob_start();
      include_once WPB_PATH.'templates/settings-credentials.php';
      echo apply_filters( 'wpb_settings_credentials', ob_get_clean() );
    }

    /**
     * Add New Credential page
     * @global WPB_Core $wpb
     */
    function add_new_credential() {
      $defaults = array(
          'heading'    => __( 'Add New Credentials', WPB_DOMAIN ),
          'button'     => __( 'Save Credentials', WPB_DOMAIN ),
          'id'         => '',
          'end_point'  => '',
          'blog_id'    => '',
          'username'   => '',
          'password'   => '',
          'wpb_action' => 'wpb_add_new_credential'
      );

      $data = array();

      if ( isset( $_GET['wpb_credential_id'] ) ) {

        $credential_id = $_GET['wpb_credential_id'];
        if ( $credential = new WPB_Item_Credential($credential_id) ) {
          $data['heading']    = __( 'Edit Credential', WPB_DOMAIN );
          $data['button']     = __( 'Update Credential', WPB_DOMAIN );
          $data['id']         = $credential->id;
          $data['end_point']  = $credential->end_point;
          $data['blog_id']    = $credential->blog_id;
          $data['username']   = $credential->username;
          $data['password']   = $credential->password;
          $data['wpb_action'] = 'wpb_do_edit_credential';
        }
      }

      $data = wp_parse_args($data, $defaults);

      ob_start();
      include_once WPB_PATH.'templates/settings-add_new_credential.php';
      echo apply_filters( 'wpb_settings_new_credential', ob_get_clean() );
    }

    /**
     *
     */
    function add_new_broadcast() {
      global $wpb_credentials, $wpb_filters;

      //** Flush */
      $data = array();
      $credentials_objects = array();
      $filters_objects = array();

      $credentials = $wpb_credentials->get();
      $filters     = $wpb_filters->get();

      foreach( $credentials as $cred ) {
        $credentials_objects[] = new WPB_Item_Credential($cred['id']);
      }

      foreach( $filters as $f ) {
        if ( class_exists( $f['type']['key'] ) )
          $filters_objects[] = new $f['type']['key']($f['id']);
      }

      $data['credentials'] = $credentials_objects;
      $data['filters'] = $filters_objects;

      ob_start();
      include_once WPB_PATH.'templates/settings-add_new_broadcast.php';
      echo apply_filters( 'wpb_settings_new_broadcast', ob_get_clean() );
    }

    /**
     * Admin notice
     * @author korotkov@ud
     */
    function notice_success_add_new_credential() {
      ?>
      <div class="updated">
        <p><?php echo sprintf( __( 'Credentials have been created successfully. Add another one or <a href="%s">manage credentials</a>.', WPB_DOMAIN ), admin_url('admin.php?page=wpb_credentials') ); ?></p>
      </div>
      <?php
    }

    /**
     * Admin notice
     * @author korotkov@ud
     */
    function notice_error_add_new_credential() {
      ?>
      <div class="error">
        <p><?php _e( 'Could not save credentials. Please refresh and try again.', WPB_DOMAIN ); ?></p>
      </div>
      <?php
    }

    /**
     * Admin notice
     * @author korotkov@ud
     */
    function notice_error_add_new_broadcast() {
      ?>
      <div class="error">
        <p><?php _e( 'Could not save broadcast. Please refresh and try again.', WPB_DOMAIN ); ?></p>
      </div>
      <?php
    }

    /**
     * Admin notice
     * @author korotkov@ud
     */
    function notice_success_credentials_delete() {
      ?>
      <div class="updated">
        <p><?php echo sprintf( __( '%s credential(s) have been deleted.', WPB_DOMAIN ), count( $_POST['wpb_delete_credentials'] ) ); ?></p>
      </div>
      <?php
    }

    /**
     *
     */
    function notice_success_broadcasts_delete() {
      ?>
      <div class="updated">
        <p><?php echo sprintf( __( '%s broadcasts(s) have been deleted.', WPB_DOMAIN ), count( $_POST['wpb_delete_broadcasts'] ) ); ?></p>
      </div>
      <?php
    }

    /**
     * Admin notice
     * @author korotkov@ud
     */
    function notice_success_filters_delete() {
      ?>
      <div class="updated">
        <p><?php echo sprintf( __( '%s filter(s) have been deleted.', WPB_DOMAIN ), count( $_POST['wpb_delete_filters'] ) ); ?></p>
      </div>
      <?php
    }

    /**
     * Admin notice
     * @author korotkov@ud
     */
    function notice_success_single_credential_delete() {
      ?>
      <div class="updated">
        <p><?php _e( 'Credential has been deleted.', WPB_DOMAIN ); ?></p>
      </div>
      <?php
    }

    /**
     *
     */
    function notice_success_single_broadcast_delete() {
      ?>
      <div class="updated">
        <p><?php _e( 'Broadcast has been deleted.', WPB_DOMAIN ); ?></p>
      </div>
      <?php
    }

    /**
     *
     */
    function notice_success_single_filter_delete() {
      ?>
      <div class="updated">
        <p><?php _e( 'Filter has been deleted.', WPB_DOMAIN ); ?></p>
      </div>
      <?php
    }

    /**
     *
     */
    function notice_error_single_filter_delete() {
      ?>
      <div class="error">
        <p><?php _e( 'Could not delete filter. Refresh and try again.', WPB_DOMAIN ); ?></p>
      </div>
      <?php
    }

    /**
     *
     */
    function notice_success_add_new_broadcast() {
      ?>
      <div class="updated">
        <p><?php _e( 'Broadcast has been created successfully', WPB_DOMAIN ); ?></p>
      </div>
      <?php
    }

    /**
     * Admin notice
     * @author korotkov@ud
     */
    function notice_success_credentials_update() {
      ?>
      <div class="updated">
        <p><?php _e( 'Credentials have been successfully updated.', WPB_DOMAIN ); ?></p>
      </div>
      <?php
    }

    /**
     *
     */
    function notice_success_broadcasts_update() {
      ?>
      <div class="updated">
        <p><?php _e( 'Broadcasts have been successfully updated.', WPB_DOMAIN ); ?></p>
      </div>
      <?php
    }

    /**
     *
     */
    function notice_success_updated_credential() {
      ?>
      <div class="updated">
        <p><?php echo sprintf(__( 'Credential has been successfully updated. <a href="%s">Manage credentials</a>.', WPB_DOMAIN ), admin_url('admin.php?page=wpb_credentials')); ?></p>
      </div>
      <?php
    }

    /**
     *
     */
    function notice_success_updated_filter() {
      ?>
      <div class="updated">
        <p><?php echo sprintf(__( 'Filter has been successfully updated. <a href="%s">Manage filters</a>.', WPB_DOMAIN ), admin_url('admin.php?page=wpb_filters')); ?></p>
      </div>
      <?php
    }

    /**
     *
     */
    function notice_error_updated_credential() {
      ?>
      <div class="error">
        <p><?php _e( 'Could not update credential. Please refresh and try again.', WPB_DOMAIN ); ?></p>
      </div>
      <?php
    }

    /**
     *
     */
    function notice_error_invalid_credentials() {
      ?>
      <div class="error">
        <p><?php _e( 'Credentials are invalid. Check and try again.', WPB_DOMAIN ); ?></p>
      </div>
      <?php
    }

    /**
     * Admin notice
     * @author korotkov@ud
     */
    function notice_success_add_new_filter() {
      ?>
      <div class="updated">
        <p><?php echo sprintf( __( 'Filter has been created successfully. Add another one or <a href="%s">manage filters</a>.', WPB_DOMAIN ), admin_url('admin.php?page=wpb_filters') ); ?></p>
      </div>
      <?php
    }

  }
}
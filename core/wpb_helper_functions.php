<?php

/**
 * Helper Functions class
 */


if ( !function_exists('wpb_edit_credential_url') ) {
  /**
   *
   * @param type $credential_id
   * @param type $args
   * @return type
   */
  function wpb_edit_credential_url( $credential_id, $args = array() ) {
    $defaults = array(
        'return' => false
    );

    extract(wp_parse_args($args, $defaults));

    $url = admin_url('admin.php?page=wpb_credentials&wpb_action=wpb_edit_credential&wpb_credential_id='.$credential_id);

    if ( $return ) return $url;
    echo $url;
  }
}

if ( !function_exists('wpb_delete_credential_url') ) {
  /**
   *
   * @param type $credential_id
   * @param type $args
   * @return type
   */
  function wpb_delete_credential_url( $credential_id, $args = array() ) {
    $defaults = array(
        'return' => false
    );

    extract(wp_parse_args($args, $defaults));

    $url = admin_url('admin.php?page=wpb_credentials&wpb_action=wpb_delete_credential&wpb_credential_id='.$credential_id);

    if ( $return ) return $url;
    echo $url;
  }
}

if ( !function_exists('wpb_validate_credentials') ) {
  /**
   *
   * @param type $credential
   * @return boolean
   */
  function wpb_validate_credentials( $credentials ) {
    if ( !is_a( $credentials, 'WPB_Item_Credential' ) ) return false;
    $validation_test = new WPB_Broadcast_API();
    return $validation_test->valid( $credentials );
  }
}

if ( !function_exists('wpb_run_broadcast_url') ) {
  /**
   *
   * @param type $broadcast_id
   * @param type $args
   * @return type
   */
  function wpb_run_broadcast_url( $broadcast_id, $args = array() ) {
    $defaults = array(
        'return' => false
    );

    extract(wp_parse_args($args, $defaults));

    $url = admin_url('admin.php?page=wpb_broadcasts&wpb_action=wpb_run_broadcasts&wpb_broadcast_id[]='.$broadcast_id);

    if ( $return ) return $url;
    echo $url;
  }
}

if ( !function_exists('wpb_delete_broadcast_url') ) {
  /**
   *
   * @param type $credential_id
   * @param type $args
   * @return type
   */
  function wpb_delete_broadcast_url( $broadcast_id, $args = array() ) {
    $defaults = array(
        'return' => false
    );

    extract(wp_parse_args($args, $defaults));

    $url = admin_url('admin.php?page=wpb_broadcasts&wpb_action=wpb_delete_broadcast&wpb_broadcast_id='.$broadcast_id);

    if ( $return ) return $url;
    echo $url;
  }
}

if ( !function_exists('wpb_delete_filter_url') ) {
  /**
   *
   * @param type $credential_id
   * @param type $args
   * @return type
   */
  function wpb_delete_filter_url( $filter_id, $args = array() ) {
    $defaults = array(
        'return' => false
    );

    extract(wp_parse_args($args, $defaults));

    $url = admin_url('admin.php?page=wpb_filters&wpb_action=wpb_delete_filter&wpb_filter_id='.$filter_id);

    if ( $return ) return $url;
    echo $url;
  }
}

if ( !function_exists('wpb_edit_filter_url') ) {
  /**
   *
   * @param type $credential_id
   * @param type $args
   * @return type
   */
  function wpb_edit_filter_url( $filter_id, $args = array() ) {
    $defaults = array(
        'return' => false
    );

    extract(wp_parse_args($args, $defaults));

    $url = admin_url('admin.php?page=wpb_filters&wpb_action=wpb_edit_filter&wpb_filter_id='.$filter_id);

    if ( $return ) return $url;
    echo $url;
  }
}
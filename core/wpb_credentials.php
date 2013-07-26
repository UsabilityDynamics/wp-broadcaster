<?php
/**
 * Credentials Class
 */
if ( !class_exists('WPB_Credentials') ) {
  class WPB_Credentials {

    /**
     *
     * @var type
     */
    private $credentials = array();

    /**
     *
     */
    public function __construct() {
      global $wpb_settings;

      //** Get from settings */
      $raw_credentials = $wpb_settings->get( WPB_CREDENTIALS );
      foreach( $raw_credentials as $raw_credential ) {
        $this->credentials[ $raw_credential['id'] ] = $raw_credential;
      }
    }

    /**
     *
     * @return type
     */
    public function get() {

      //** Return if already loaded */
      if ( !empty( $this->credentials ) ) {
        return $this->credentials;
      }

      global $wpb_settings;

      //** Get from settings */
      $raw_credentials = $wpb_settings->get( WPB_CREDENTIALS );
      foreach( $raw_credentials as $raw_credential ) {
        $this->credentials[ $raw_credential['id'] ] = $raw_credential;
      }

      return $this->credentials;
    }

    /**
     *
     * @return type
     */
    public function save() {
      global $wpb_settings;

      foreach( $this->credentials as $key => $credential ) {
        if ( !$credential['id'] ) {
          $this->credentials[$key]['id'] = $key;
        }
      }

      $wpb_settings->set( WPB_CREDENTIALS, $this->credentials )->commit();
    }

    /**
     *
     * @param type $credential
     * @return boolean
     */
    public function add( $credential ) {
      if ( !is_a( $credential, 'WPB_Item_Credential' ) ) return false;

      $temp_credential = $credential->to_array();

      if ( !$temp_credential['id'] ) {
        $this->credentials[ md5($temp_credential['end_point'].$temp_credential['blog_id'].$temp_credential['username'].time()) ] = $temp_credential;
      } else {
        $this->credentials[ $temp_credential['id'] ] = $temp_credential;
      }

      return $this->save();
    }

    /**
     *
     * @param type $id
     * @return boolean
     */
    public function delete( $id ) {
      if ( !isset( $id ) ) return false;
      unset( $this->credentials[$id] );
      return $this->save();
    }
  }
}
<?php
/**
 * Settings Class
 */
if ( !class_exists('WPB_Settings') ) {
  class WPB_Settings {

    /**
     * Raw settings storage.
     * @var type
     */
    private $raw;

    /**
     * Construct. Load settings once.
     */
    public function __construct() {
      $this->raw = get_option( WPB_OPTION_SLUG, array() );
    }

    /**
     * Set settings data by key
     * @param type $key
     * @param type $data
     * @return boolean
     */
    public function set( $key=false, $data=array() ) {
      if ( !$key ) return $this;

      $this->raw[$key] = $data;

      return $this;
    }

    /**
     * Get options by key.
     * @param type $key
     * @return mixed
     */
    public function get( $key=false ) {
      if ( !$key ) return array();

      if ( !empty( $this->raw[$key] ) ) {
        return $this->raw[$key];
      }

      return array();
    }

    /**
     * Save settings to db
     */
    public function commit() {
      return update_option( WPB_OPTION_SLUG, $this->raw );
    }
  }
}

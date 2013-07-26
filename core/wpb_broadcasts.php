<?php
/**
 * Credentials Class
 */
if ( !class_exists('WPB_Broadcasts') ) {
  class WPB_Broadcasts {

    /**
     *
     * @var type
     */
    private $broadcasts = array();

    /**
     *
     */
    public function __construct() {
      global $wpb_settings;

      //** Get from settings */
      $raw_broadcasts = $wpb_settings->get( WPB_BROADCASTS );
      foreach( $raw_broadcasts as $raw_broadcast ) {
        $this->broadcasts[ $raw_broadcast['id'] ] = $raw_broadcast;
      }}

    /**
     *
     * @return type
     */
    public function get() {

      //** Return if already loaded */
      if ( !empty( $this->broadcasts ) ) {
        return $this->broadcasts;
      }

      global $wpb_settings;

      //** Get from settings */
      $raw_broadcasts = $wpb_settings->get( WPB_BROADCASTS );
      foreach( $raw_broadcasts as $raw_broadcast ) {
        $this->broadcasts[ $raw_broadcast['id'] ] = $raw_broadcast;
      }

      return $this->broadcasts;
    }

    /**
     *
     * @return type
     */
    public function save() {
      global $wpb_settings;

      foreach( $this->broadcasts as $key => $broadcast ) {
        if ( !is_numeric( $broadcast['id'] ) ) {
          $this->broadcasts[$key]['id'] = $key;
        }
      }

      $wpb_settings->set( WPB_BROADCASTS, $this->broadcasts )->commit();
    }

    /**
     *
     * @param type $credential
     * @return boolean
     */
    public function add( $broadcast ) {
      if ( !is_a( $broadcast, 'WPB_Item_Broadcast' ) ) return false;

      $temp_broadcast = $broadcast->to_array();

      if ( !is_numeric( $temp_broadcast['id'] ) ) {
        $this->broadcasts[] = $temp_broadcast;
      } else {
        $this->broadcasts[ $temp_broadcast['id'] ] = $temp_broadcast;
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
      unset( $this->broadcasts[$id] );
      return $this->save();
    }
  }
}
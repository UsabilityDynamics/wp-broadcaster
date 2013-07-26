<?php

/**
 * Credential Object Class
 */
if ( !class_exists('WPB_Item_Broadcast') ) {
  class WPB_Item_Broadcast {

    /**
     *
     * @var type
     */
    private $id;

    /**
     *
     * @var type
     */
    private $name;

    /**
     *
     * @var type
     */
    private $credentials;

    /**
     *
     * @var type
     */
    private $filters;

    /**
     *
     */
    public function __construct( $id=false, $name=false, $credentials=array(), $filters=false ) {

      //** ID passed - load credential, otherwise create new */
      if ( !is_numeric($id) ) {

        if ( !$name ) throw new Exception(__('Broadcast Name cannot be empty.', WPB_DOMAIN));
        if ( empty( $credentials ) ) throw new Exception(__('Broadcast Credentials cannot be empty', WPB_DOMAIN));

        $this->id         = $id;
        $this->name       = $name;
        $this->credentials = $credentials;
        $this->filters    = $filters;
      } else {
        $this->load( (int)$id );
      }

    }

    /**
     *
     * @param type $property
     * @return type
     */
    public function __get($property) {
      if ( property_exists( $this, $property ) ) {
        return $this->$property;
      }
      return;
    }

    /**
     *
     * @param type $property
     * @return type
     */
    public function __set($property, $value) {
      if ( property_exists( $this, $property ) ) {
        return $this->$property = $value;
      }
    }

    /**
     *
     * @global type $wpb_broadcasts
     * @param type $id
     */
    private function load( $id ) {
      global $wpb_broadcasts;

      $broadcasts = $wpb_broadcasts->get();

      if ( !empty( $broadcasts ) && array_key_exists( $id, $broadcasts ) ) {
        $this->id          = $broadcasts[ $id ]['id'];
        $this->name        = $broadcasts[ $id ]['name'];
        $this->credentials = $broadcasts[ $id ]['credentials'];
        $this->filters     = $broadcasts[ $id ]['filters'];
      }
    }

    /**
     *
     * @global type $wpb_broadcasts
     */
    public function save() {
      global $wpb_broadcasts;
      $wpb_broadcasts->add( $this );
    }

    /**
     *
     * @global type $wpb_credentials
     */
    public function delete() {
      global $wpb_broadcasts;
      $wpb_broadcasts->delete( $this->id );
    }

    /**
     *
     * @global type $wpb_broadcasts
     * @param type $data
     */
    public function update( $data ) {
      global $wpb_broadcasts;

      $this->name       = $data['name'];
      $this->credentials = $data['credentials'];
      $this->filters    = $data['filters'];

      $wpb_broadcasts->add( $this );
    }

    /**
     * To array object
     * @return type
     */
    public function to_array() {
      return array(
          'id'         => $this->id,
          'name'       => $this->name,
          'credentials' => $this->credentials,
          'filters'    => $this->filters
      );
    }
  }
}
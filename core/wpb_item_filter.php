<?php

/**
 * Filter Base Object Class.
 */
if ( !class_exists('WPB_Item_Filter') ) {
  abstract class WPB_Item_Filter {

    /**
     *
     * @var type
     */
    public $id;

    /**
     *
     * @var type
     */
    private $name;

    /**
     *
     * @var type
     */
    public $data;

    /**
     *
     * @var type
     */
    public $type;

    /**
     *
     */
    public function __construct( $id, $name, $data ) {

      //** If using w/o arguments then call parent construct because we want to register new filter */
      if ( !$id && !$name && !$data ) {
          add_filter('wpb_filter_types', array( $this, 'register_type' ));
      }

      //** Load filter if ID passed */
      if ( !empty( $id ) ) {
        return $this->load( $id );
      }

      //** Otherwise create new based on passed data */
      $this->id   = $id;
      $this->name = $name;
      $this->data = $data;
    }

    /**
     *
     * @return type
     */
    public function to_array() {
      return array(
        'id'   => $this->id,
        'name' => $this->name,
        'data' => $this->data,
        'type' => $this->type
      );
    }

    /**
     *
     * @global type $wpb_filters
     * @param type $id
     */
    private function load( $id ) {
      global $wpb_filters;

      $filters = $wpb_filters->get();

      if ( !empty( $filters ) && array_key_exists( $id, $filters ) ) {
        $this->id        = $filters[ $id ]['id'];
        $this->name      = $filters[ $id ]['name'];
        $this->data      = $filters[ $id ]['data'];
        $this->type      = $filters[ $id ]['type'];
      }
    }

    /**
     *
     * @param type $types
     * @return type
     */
    function register_type( $types ) {
      $types[] = $this->type;
      return $types;
    }

    /**
     *
     * @param type $property
     * @return type
     */
    public function __get($property) {
      if ( $property == 'name' ) {
        return stripslashes($this->$property);
      }
      return $this->$property;
    }

    /**
     *
     * @param type $property
     * @param type $value
     */
    public function __set( $property, $value ) {
        $this->$property = $value;
    }

    /**
     *
     * @global type $wpb_filters
     */
    public function save() {
      global $wpb_filters;
      $wpb_filters->add( $this );
    }

    /**
     *
     * @global type $wpb_filters
     * @param type $data
     */
    public function update( $data ) {
      global $wpb_filters;

      $this->name   = $data['name'];
      $this->data   = $data['data'];

      $wpb_filters->add( $this );
    }

    /**
     *
     * @global type $wpb_filters
     */
    public function delete() {
      global $wpb_filters;
      $wpb_filters->delete( $this->id );
    }

    abstract function ui_callback($data);
    abstract function filter();
  }
}
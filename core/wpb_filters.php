<?php
/**
 * Filters manager Class
 */
if ( !class_exists('WPB_Filters') ) {
  class WPB_Filters {

    /**
     *
     * @var type
     */
    private $filters = array();

    /**
     *
     */
    public function __construct() {
      global $wpb_settings;

      //** Get from settings */
      $raw_filters = $wpb_settings->get( WPB_FILTERS );
      foreach( $raw_filters as $raw_filter ) {
        $this->filters[ $raw_filter['id'] ] = $raw_filter;
      }
    }

    /**
     *
     * @return type
     */
    public function get() {

      //** Return if already loaded */
      if ( !empty( $this->filters ) ) {
        return $this->filters;
      }

      global $wpb_settings;

      //** Get from settings */
      $raw_filters = $wpb_settings->get( WPB_FILTERS );
      foreach( $raw_filters as $raw_filter ) {
        $this->filters[ $raw_filter['id'] ] = $raw_filter;
      }

      return $this->filters;
    }

    /**
     *
     * @return type
     */
    public function save() {
      global $wpb_settings;

      foreach( $this->filters as $key => $filter ) {
        if ( !$filter['id'] ) {
          $this->filters[$key]['id'] = $key;
        }
      }

      $wpb_settings->set( WPB_FILTERS, $this->filters )->commit();
    }

    /**
     *
     * @param type $credential
     * @return boolean
     */
    public function add( $filter ) {
      if ( 'WPB_Item_Filter' != get_parent_class( $filter ) ) return false;

      $temp_filter = $filter->to_array();

      if ( !$temp_filter['id'] ) {
        $this->filters[ md5($temp_filter['name'].time()) ] = $temp_filter;
      } else {
        $this->filters[ $temp_filter['id'] ] = $temp_filter;
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
      unset( $this->filters[$id] );
      return $this->save();
    }
  }
}
<?php
/**
 * Core Class
 */
if ( !class_exists('WPB_Core') ) {
  class WPB_Core {

    /**
     * Construct
     * @author korotkov@ud
     */
    public function __construct() {}

    /**
     * Initialize process
     * @author korotkov@ud
     */
    public function init() {

      //** Init manage page */
      new WPB_Manage_Page();

      //** Init actions handler */
      new WPB_Actions();

      //** Init ajax handlers */
      new WPB_Ajax();
    }

  }
}
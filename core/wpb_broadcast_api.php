<?php

/**
 * Broadcast processor Class
 */
if ( !class_exists('WPB_Broadcast_API') ) {
  class WPB_Broadcast_API {

    /**
     *
     * @var type
     */
    private $credentials;

    /**
     *
     * @var type
     */
    private $client;

    /**
     * Construct
     * @param type $credentials
     */
    public function __construct( $broadcast_item=false ) {
      include_once( ABSPATH . WPINC . '/class-IXR.php' );
      include_once( ABSPATH . WPINC . '/class-wp-http-ixr-client.php' );

      if ( !$broadcast_item ) return;

      if ( !is_a( $broadcast_item, 'WPB_Item_Broadcast' ) ) return;
    }

    /**
     * Init client if not initialized
     * @param type $end_point
     * @return type
     */
    private function maybe_init_client( $end_point ) {
      if ( !is_a( $this->client, 'WP_HTTP_IXR_CLIENT' ) ) {
        $this->client = new WP_HTTP_IXR_CLIENT( $end_point.'/xmlrpc.php' );
      }
      return $this->client;
    }

    /**
     * Test credentials
     * @return boolean
     */
    public function valid( $credentials ) {
      if ( !is_a( $credentials, 'WPB_Item_Credential' ) ) throw new Exception(__('Credentials have unknown type.', WPB_DOMAIN));
      $this->credentials = $credentials;

      if ( !$this->maybe_init_client( $this->credentials->end_point ) ) {
        throw new Exception(__('Cannot create XML-RPC Client.', WPB_DOMAIN));
      }

      if ( $user = $this->client->query('wp.getProfile', array(
        $this->credentials->blog_id,
        $this->credentials->username,
        $this->credentials->password
      )) ) {
        return true;
      }
      return false;
    }
  }
}

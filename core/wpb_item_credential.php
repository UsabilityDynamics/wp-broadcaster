<?php

/**
 * Credential Object Class
 */
if ( !class_exists('WPB_Item_Credential') ) {
  class WPB_Item_Credential {

    /**
     *
     * @var type
     */
    private $id;

    /**
     *
     * @var type
     */
    private $end_point;

    /**
     *
     * @var type
     */
    private $blog_id;

    /**
     *
     * @var type
     */
    private $username;

    /**
     *
     * @var type
     */
    private $password;

    /**
     *
     * @param type $property
     * @return type
     */
    public function __get($property) {
      if ( property_exists( $this, $property ) && $property == 'password' ) {
        return $this->password_decrypt( $this->$property );
      }
      return $this->$property;
    }

    /**
     * Construct
     * @author korotkov@ud
     * @param type $id
     * @param type $end_point
     * @param type $blog_id
     * @param type $username
     * @param type $password
     */
    public function __construct( $id = false, $end_point = '', $blog_id = 0, $username = '', $password = '' ) {

      //** ID passed - load credential, otherwise create new */
      if ( !$id ) {
        $this->id        = $id;
        $this->end_point = filter_var( $end_point, FILTER_VALIDATE_URL );
        $this->blog_id   = filter_var( $blog_id, FILTER_VALIDATE_INT );
        $this->username  = $username;
        $this->password  = $this->password_crypt($password);
      } else {
        $this->load( $id );
      }

    }

    /**
     * Convert credential object into array
     * @author korotkov@ud
     * @return type
     */
    public function to_array() {
      return array(
          'id'        => $this->id,
          'end_point' => $this->end_point,
          'blog_id'   => $this->blog_id,
          'username'  => $this->username,
          'password'  => $this->password
      );
    }

    /**
     * Save credential
     * @author korotkov@ud
     * @global WPB_Core $wpb
     */
    public function save() {
      global $wpb_credentials;
      $wpb_credentials->add( $this );
    }

    /**
     * Delete credential
     * @author korotkov@ud
     * @global WPB_Core $wpb
     */
    public function delete() {
      global $wpb_credentials;
      $wpb_credentials->delete( $this->id );
    }

    /**
     * Update credential
     * @author korotkov@ud
     * @global WPB_Core $wpb
     * @param type $data
     */
    public function update( $data ) {
      global $wpb_credentials;

      $this->blog_id   = $data['blog_id'];
      $this->end_point = $data['end_point'];
      $this->password  = $this->password_crypt( $data['password'] );
      $this->username  = $data['username'];

      $wpb_credentials->add( $this );
    }

    /**
     * Load credential if ID passed
     * @author korotkov@ud
     * @global WPB_Core $wpb
     * @param type $id
     * @return WPB_Item_Credential
     */
    private function load( $id ) {
      global $wpb_credentials;

      $credentials = $wpb_credentials->get();

      if ( !empty( $credentials ) && array_key_exists( $id, $credentials ) ) {
        $this->id        = $credentials[ $id ]['id'];
        $this->blog_id   = $credentials[ $id ]['blog_id'];
        $this->end_point = $credentials[ $id ]['end_point'];
        $this->password  = $credentials[ $id ]['password'];
        $this->username  = $credentials[ $id ]['username'];
      }
    }

    /**
     * Crypt password
     * @todo Need to utilize
     * @author korotkov@ud
     * @param type $password
     * @return type
     */
    private function password_crypt( $password ) {
      return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(AUTH_KEY), $password, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));;
    }

    /**
     * Decrypt password
     * @todo Need to utilize
     * @author korotkov@ud
     * @param type $password
     * @return type
     */
    private function password_decrypt( $password ) {
      return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(AUTH_KEY), base64_decode($password), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

  }
}
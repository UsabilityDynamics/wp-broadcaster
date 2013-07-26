<div class="wrap">
  <div class="icon32" id="icon-options-general"><br></div>
  <h2><?php echo $data['heading']; ?></h2>

  <div class="form-wrap">

    <form id="wpb-add-new-credential" class="wpb-add-new-form" action="" method="post">

      <div>
        <label for="end_point"><?php _e( 'Site URL', WPB_DOMAIN ); ?></label>
        <input required id="end_point" type="url" size="40" value="<?php echo $data['end_point']; ?>" name="end_point" />
        <p><?php _e( 'The URL of WordPress site with XML-RPC enabled. (e.g. http://site.com)', WPB_DOMAIN ); ?></p>
      </div>

      <div>
        <label for="blog_id"><?php _e( 'Blog ID', WPB_DOMAIN ); ?></label>
        <input required id="blog_id" type="text" size="40" value="<?php echo $data['blog_id']; ?>" name="blog_id" />
        <p><?php _e( 'The blog id within network. (e.g. 0)', WPB_DOMAIN ); ?></p>
      </div>

      <div>
        <label for="username"><?php _e( 'Username', WPB_DOMAIN ); ?></label>
        <input required id="username" type="text" size="40" value="<?php echo $data['username']; ?>" name="username" />
        <p><?php _e( 'Login of the user on destination site which has an ability to manage options.', WPB_DOMAIN ); ?></p>
      </div>

      <div>
        <label for="password"><?php _e( 'Password', WPB_DOMAIN ); ?></label>
        <input required id="password" type="password" size="40" value="<?php echo $data['password']; ?>" name="password" autocomplete="off" />
        <p><?php _e( 'Password of the user.', WPB_DOMAIN ); ?></p>
      </div>

      <input type="hidden" value="<?php echo $data['wpb_action']; ?>" name="wpb_action" />

      <?php wp_nonce_field( 'wpb-add-new-credential', 'wpb-add-new-credential-nonce' ); ?>
      <?php submit_button( $data['button'] ); ?>

    </form>

  </div>

</div>
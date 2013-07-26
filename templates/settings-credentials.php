<div class="wrap">
  <div class="icon32" id="icon-options-general"><br></div>
  <h2><?php _e( 'Broadcast Credentials', WPB_DOMAIN ); ?><a class="add-new-h2" href="<?php echo admin_url('admin.php?page=wpb_add_new_credential'); ?>"><?php _e( 'Add New', WPB_DOMAIN ); ?></a></h2>

  <form action="" method="post">
    <div class="tablenav top">
      <div class="alignleft actions">
        <select name="action">
          <option selected="selected" value="-1"><?php _e('Bulk Actions', WPB_DOMAIN); ?></option>
          <?php foreach( apply_filters('wpb_credentials_bulk_actions', $data['bulk_actions']) as $action ): ?>
            <option value="<?php echo $action['value']; ?>"><?php echo $action['label']; ?></option>
          <?php endforeach; ?>
        </select>
        <input type="submit" value="<?php _e('Apply', WPB_DOMAIN); ?>" class="button action" id="doaction" name="">
      </div>
    </div>
    <table id="wpb-credentials-list" class="widefat">
      <thead>
        <tr>
          <th style="width:15px;"></th>
          <th>
            <input type="checkbox" class="wpb-check-all" />
          </th>
          <th><abbr title="<?php _e( 'The URL of WordPress site with XML-RPC enabled. (e.g. http://site.com)', WPB_DOMAIN ); ?>"><?php _e( 'Site URL', WPB_DOMAIN ); ?></abbr></th>
          <th><abbr title="<?php _e( 'Login of the user on destination site which has an ability to manage options.', WPB_DOMAIN ); ?>"><?php _e( 'Username', WPB_DOMAIN ); ?></abbr></th>
          <th><abbr title="<?php _e( 'Password of the user.', WPB_DOMAIN ); ?>"><?php _e( 'Password', WPB_DOMAIN ); ?></abbr></th>
          <th><abbr title="<?php _e( 'The blog id within network. (e.g. 0)', WPB_DOMAIN ); ?>"><?php _e( 'Blog ID', WPB_DOMAIN ); ?></abbr></th>
        </tr>
      </thead>
      <tbody class="wpb_sortable">
        <?php if( !empty( $data['credentials'] ) ): ?>
        <?php $i=0; foreach( (array)$data['credentials'] as $credential ): ?>
        <tr>
          <td class="wpb_sortable_control"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td>
          <th>
            <input type="checkbox" name="wpb_delete_credentials[]" class="wpb-check" value="<?php echo $credential->id; ?>" />
          </th>
          <td>
            <input class="wpb_hidden_credential_id" name="wpb_credentials[<?php echo $i; ?>][id]" type="hidden" value="<?php echo $credential->id; ?>" />
            <input required name="wpb_credentials[<?php echo $i; ?>][end_point]" id="wpb-credential-endpoint-<?php echo $credential->id; ?>" class="wpb-endpoint" type="url" value="<?php echo $credential->end_point; ?>" />
            <div class="row-actions">
              <span class="edit">
                <a title="<?php _e('Edit this item', WPB_DOMAIN); ?>" href="<?php wpb_edit_credential_url($credential->id); ?>"><?php _e('Edit', WPB_DOMAIN); ?></a> |
              </span>
              <span class="delete">
                <a title="<?php _e('Delete this item', WPB_DOMAIN); ?>" href="<?php wpb_delete_credential_url($credential->id); ?>"><?php _e('Delete', WPB_DOMAIN); ?></a>
              </span>
            </div>
          </td>
          <td>
            <span><?php echo $credential->username; ?></span>
            <input required name="wpb_credentials[<?php echo $i; ?>][username]" id="wpb-credential-username-<?php echo $credential->id; ?>" class="wpb-username" type="hidden" value="<?php echo $credential->username; ?>" />
          </td>
          <td>
            <a class="wpb_toggle_password" href="javascript:void(0);"><?php _e('Toggle', WPB_DOMAIN); ?></a>
            <div class="hidden"><code><?php echo $credential->password; ?></code></div>
            <input required autocomplete="off" name="wpb_credentials[<?php echo $i; ?>][password]" id="wpb-credential-password-<?php echo $credential->id; ?>" class="wpb-password" type="hidden" value="<?php echo $credential->password; ?>" />
          </td>
          <td>
            <span><?php echo $credential->blog_id; ?></span>
            <input required name="wpb_credentials[<?php echo $i; ?>][blog_id]" id="wpb-credential-blogid-<?php echo $credential->id; ?>" class="wpb-blogid" type="hidden" value="<?php echo $credential->blog_id; ?>" />
          </td>
        </tr>
        <?php $i++; endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="6">
            <?php _e( 'No credentials found.', WPB_DOMAIN ); ?>
            <a href="<?php echo admin_url('admin.php?page=wpb_add_new_credential'); ?>"><?php _e( 'Add Credentials', WPB_DOMAIN ); ?></a>
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
      <tfoot>
        <tr>
          <th></th>
          <th>
            <input type="checkbox" class="wpb-check-all" />
          </th>
          <th><abbr title="<?php _e( 'The URL of WordPress site with XML-RPC enabled. (e.g. http://site.com)', WPB_DOMAIN ); ?>"><?php _e( 'Site URL', WPB_DOMAIN ); ?></abbr></th>
          <th><abbr title="<?php _e( 'Login of the user on destination site which has an ability to manage options.', WPB_DOMAIN ); ?>"><?php _e( 'Username', WPB_DOMAIN ); ?></abbr></th>
          <th><abbr title="<?php _e( 'Password of the user.', WPB_DOMAIN ); ?>"><?php _e( 'Password', WPB_DOMAIN ); ?></abbr></th>
          <th><abbr title="<?php _e( 'The blog id within network. (e.g. 0)', WPB_DOMAIN ); ?>"><?php _e( 'Blog ID', WPB_DOMAIN ); ?></abbr></th>
        </tr>
      </tfoot>
    </table>

    <input type="hidden" value="wpb_credentials" name="wpb_action" />

    <?php wp_nonce_field( 'wpb-credentials', 'wpb-credentials-nonce' ); ?>
    <?php submit_button( __('Save Credentials', WPB_DOMAIN), 'primary', 'save', false ); ?>

  </form>

</div>
<div class="wrap">
  <div class="icon32" id="icon-options-general"><br></div>
  <h2><?php _e('Add New Broadcast', WPB_DOMAIN); ?></h2>

  <?php if ( empty( $data['credentials'] ) ): ?>
    <div class="error">
      <p><?php echo sprintf( __( 'Currently you do not have any destinations you can broadcast objects to. <a href="%s">Add one</a> and proceed.', WPB_DOMAIN ), admin_url('admin.php?page=wpb_add_new_credential') ); ?></p>
    </div>
  <?php else: ?>

    <div class="form-wrap">

      <form id="wpb-add-new-broadcast" class="wpb-add-new-form" action="" method="post">

        <div>
          <label for="wpb-broadcast-name"><?php _e( 'Name', WPB_DOMAIN ); ?></label>
          <input required id="wpb-broadcast-name" type="text" size="40" value="" name="name" />
          <p><?php _e( 'The title of broadcast. Used internally to distinguish items only.', WPB_DOMAIN ); ?></p>
        </div>

        <div>
          <label for="wpb-destinations-list">
            <?php _e( 'Destinations', WPB_DOMAIN ); ?>
            <i> / <a href="<?php echo admin_url('admin.php?page=wpb_add_new_credential'); ?>"><?php _e( 'Add New', WPB_DOMAIN ); ?></a></i>
          </label>
          <select required class="widefat" multiple id="wpb-destinations-list" name="credentials[]">
            <?php foreach( (array)$data['credentials'] as $destination ): ?>
            <option value="<?php echo $destination->id; ?>"><?php echo $destination->end_point; ?></option>
            <?php endforeach; ?>
          </select>
          <p><?php _e( 'End-points for current broadcast process.', WPB_DOMAIN ); ?></p>
        </div>

        <div>
          <label for="wpb-filters-list">
            <?php _e( 'Filters', WPB_DOMAIN ); ?>
            <i> / <a href="<?php echo admin_url('admin.php?page=wpb_add_new_filter'); ?>"><?php _e( 'Add New', WPB_DOMAIN ); ?></a></i>
          </label>
          <select required class="widefat" multiple id="wpb-filters-list" name="filters[]">
            <?php foreach( (array)$data['filters'] as $filter ): ?>
            <option value="<?php echo $filter->id; ?>"><?php echo $filter->name; ?></option>
            <?php endforeach; ?>
          </select>
          <p><?php _e( 'Filters for objects of current broadcast process.', WPB_DOMAIN ); ?></p>
        </div>

        <input type="hidden" value="wpb_add_new_broadcast" name="wpb_action" />

        <?php wp_nonce_field( 'wpb-add-new-broadcast', 'wpb-add-new-broadcast-nonce' ); ?>
        <?php submit_button( __('Save', WPB_DOMAIN) ); ?>

      </form>

    </div>

  <?php endif; ?>

</div>
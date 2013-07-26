<div class="wrap">
  <div class="icon32" id="icon-options-general"><br></div>
  <h2><?php echo $data['heading']; ?></h2>

    <div class="form-wrap">

      <form id="wpb-add-new-filter" class="wpb-add-new-form" action="" method="post">

        <div>
          <label for="wpb-filter-name"><?php _e( 'Name', WPB_DOMAIN ); ?></label>
          <input required id="wpb-filter-name" type="text" size="40" value="<?php echo $data['name']; ?>" name="name" />
          <p><?php _e( 'The title of filter. Used internally to distinguish items only.', WPB_DOMAIN ); ?></p>
        </div>

        <div>
          <label for="wpb-filter-type"><?php _e( 'Type', WPB_DOMAIN ); ?></label>
          <select required name="filter_type" class="widefat" id="wpb-filter-type">
            <option value=""><?php _e('-- select type --', WPB_DOMAIN); ?></option>
            <?php foreach( (array)$data['filter_types'] as $type ): ?>
            <option <?php if(!empty($data['type']['key']))selected($type['key'], $data['type']['key']); ?> value="<?php echo $type['key']; ?>"><?php echo $type['label']; ?></option>
            <?php endforeach; ?>
          </select>
          <p><?php _e( 'The type of the filter. Different types provide different options', WPB_DOMAIN ); ?></p>
        </div>

        <div>
          <label for="wpb-filter-options"><?php _e('Options', WPB_DOMAIN); ?></label>
          <?php if ( empty( $data['data'] ) ): ?>
            <div class="filter_options empty"><?php _e('Select Type above first', WPB_DOMAIN); ?></div>
          <?php else: ?>
            <div class="filter_options loaded"><?php echo $data['type']['key']::ui_callback( $data['data'] ); ?></div>
          <?php endif; ?>
          <p><?php _e( 'Options for the filter of currently selected type.', WPB_DOMAIN ); ?></p>
        </div>

        <input type="hidden" value="<?php echo $data['wpb_action']; ?>" name="wpb_action" />

        <?php wp_nonce_field( 'wpb-add-new-filter', 'wpb-add-new-filter-nonce' ); ?>
        <?php submit_button( $data['button'] ); ?>

      </form>

    </div>

</div>
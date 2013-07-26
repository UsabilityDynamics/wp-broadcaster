<div class="wrap">
  <div class="icon32" id="icon-options-general"><br></div>
  <h2><?php _e( 'Broadcasts List', WPB_DOMAIN ); ?><a class="add-new-h2" href="<?php echo admin_url('admin.php?page=wpb_add_new_broadcast'); ?>"><?php _e( 'Add New', WPB_DOMAIN ); ?></a></h2>

  <form action="" method="post">
    <div class="tablenav top">
      <div class="alignleft actions">
        <select name="action">
          <option selected="selected" value="-1"><?php _e('Bulk Actions', WPB_DOMAIN); ?></option>
          <?php foreach( apply_filters('wpb_broadcasts_bulk_actions', $data['bulk_actions']) as $action ): ?>
            <option value="<?php echo $action['value']; ?>"><?php echo $action['label']; ?></option>
          <?php endforeach; ?>
        </select>
        <input type="submit" value="<?php _e('Apply', WPB_DOMAIN); ?>" class="button action" id="doaction" name="">
      </div>
    </div>
    <table id="wpb-broadcasts-list" class="widefat">
      <thead>
        <tr>
          <th style="width:15px;"></th>
          <th>
            <input type="checkbox" class="wpb-check-all" />
          </th>
          <th><abbr title="<?php _e( 'Text', WPB_DOMAIN ); ?>"><?php _e( 'Name', WPB_DOMAIN ); ?></abbr></th>
          <th style="width:30%;"><abbr title="<?php _e( 'Text', WPB_DOMAIN ); ?>"><?php _e( 'Destinations', WPB_DOMAIN ); ?></abbr></th>
          <th style="width:30%;"><abbr title="<?php _e( 'Text', WPB_DOMAIN ); ?>"><?php _e( 'Filters', WPB_DOMAIN ); ?></abbr></th>
        </tr>
      </thead>
      <tbody class="wpb_sortable">
        <?php if( !empty( $data['broadcasts'] ) ): ?>
        <?php $i=0; foreach( (array)$data['broadcasts'] as $broadcast ): ?>
        <tr>
          <td class="wpb_sortable_control"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td>
          <th>
            <input type="checkbox" name="wpb_delete_broadcasts[]" class="wpb-check" value="<?php echo $broadcast->id; ?>" />
          </th>
          <td>
            <input class="wpb_hidden_broadcast_id" name="wpb_broadcasts[<?php echo $i; ?>][id]" type="hidden" value="<?php echo $broadcast->id; ?>" />
            <input required name="wpb_broadcasts[<?php echo $i; ?>][name]" id="wpb-broadcast-name-<?php echo $broadcast->id; ?>" class="wpb_broadcast_name" type="text" value="<?php echo $broadcast->name; ?>" />
            <div class="row-actions">
              <span class="run">
                <a title="<?php _e('Run this item', WPB_DOMAIN); ?>" href="<?php wpb_run_broadcast_url($broadcast->id); ?>"><?php _e('Run', WPB_DOMAIN); ?></a> |
              </span>
              <span class="delete">
                <a title="<?php _e('Delete this item', WPB_DOMAIN); ?>" href="<?php wpb_delete_broadcast_url($broadcast->id); ?>"><?php _e('Delete', WPB_DOMAIN); ?></a>
              </span>
            </div>
          </td>
          <td>
            <select required class="widefat wpb-destinations-list" multiple name="wpb_broadcasts[<?php echo $i; ?>][credentials][]">
              <?php foreach( (array)$data['credentials'] as $credential ): ?>
              <option <?php echo in_array( $credential['id'], $broadcast->credentials )?'selected="selected"':''; ?> value="<?php echo $credential['id']; ?>"><?php echo $credential['end_point']; ?></option>
              <?php endforeach; ?>
            </select>
          </td>
          <td>
            <select required class="widefat wpb-filters-list" multiple name="wpb_broadcasts[<?php echo $i; ?>][filters][]">
              <?php foreach( (array)$data['filters'] as $filter ): ?>
              <option <?php echo in_array( $filter['id'], $broadcast->filters )?'selected="selected"':''; ?> value="<?php echo $filter['id']; ?>"><?php echo $filter['name']; ?></option>
              <?php endforeach; ?>
            </select>
          </td>
        </tr>
        <?php $i++; endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="5">
            <?php _e( 'No broadcasts found.', WPB_DOMAIN ); ?>
            <a href="<?php echo admin_url('admin.php?page=wpb_add_new_broadcast'); ?>"><?php _e( 'Add Broadcast', WPB_DOMAIN ); ?></a>
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
          <th><abbr title="<?php _e( 'Text', WPB_DOMAIN ); ?>"><?php _e( 'Name', WPB_DOMAIN ); ?></abbr></th>
          <th><abbr title="<?php _e( 'Text', WPB_DOMAIN ); ?>"><?php _e( 'Destinations', WPB_DOMAIN ); ?></abbr></th>
          <th><abbr title="<?php _e( 'Text', WPB_DOMAIN ); ?>"><?php _e( 'Filters', WPB_DOMAIN ); ?></abbr></th>
        </tr>
      </tfoot>
    </table>

    <input type="hidden" value="wpb_broadcasts" name="wpb_action" />

    <?php wp_nonce_field( 'wpb-broadcasts', 'wpb-broadcasts-nonce' ); ?>
    <?php submit_button( __('Save Broadcasts', WPB_DOMAIN), 'primary', 'save', false ); ?>

  </form>

</div>
<div class="wrap">
  <div class="icon32" id="icon-options-general"><br></div>
  <h2><?php _e( 'Broadcast Filters', WPB_DOMAIN ); ?><a class="add-new-h2" href="<?php echo admin_url('admin.php?page=wpb_add_new_filter'); ?>"><?php _e( 'Add New', WPB_DOMAIN ); ?></a></h2>

  <form action="" method="post">
    <div class="tablenav top">
      <div class="alignleft actions">
        <select name="action">
          <option selected="selected" value="-1"><?php _e('Bulk Actions', WPB_DOMAIN); ?></option>
          <?php foreach( apply_filters('wpb_filters_bulk_actions', $data['bulk_actions']) as $action ): ?>
            <option value="<?php echo $action['value']; ?>"><?php echo $action['label']; ?></option>
          <?php endforeach; ?>
        </select>
        <input type="submit" value="<?php _e('Apply', WPB_DOMAIN); ?>" class="button action" id="doaction" name="">
      </div>
    </div>
    <table id="wpb-filters-list" class="widefat">
      <thead>
        <tr>
          <th style="width:15px;"></th>
          <th>
            <input type="checkbox" class="wpb-check-all" />
          </th>
          <th><abbr title="<?php _e( 'Internal filter name', WPB_DOMAIN ); ?>"><?php _e( 'Filter Name', WPB_DOMAIN ); ?></abbr></th>
          <th><abbr title="<?php _e( 'Every filter has type. Different types filter differently.', WPB_DOMAIN ); ?>"><?php _e( 'Filter Type', WPB_DOMAIN ); ?></abbr></th>
        </tr>
      </thead>
      <tbody class="wpb_sortable">
        <?php if( !empty( $data['filters'] ) ): ?>
        <?php $i=0; foreach( (array)$data['filters'] as $filter ): ?>
        <tr>
          <td class="wpb_sortable_control"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td>
          <th>
            <input type="checkbox" name="wpb_delete_filters[]" class="wpb-check" value="<?php echo $filter->id; ?>" />
          </th>
          <td>
            <span><?php echo $filter->name ?></span>
            <div class="row-actions">
              <span class="edit">
                <a title="<?php _e('Edit this item', WPB_DOMAIN); ?>" href="<?php wpb_edit_filter_url($filter->id); ?>"><?php _e('Edit', WPB_DOMAIN); ?></a> |
              </span>
              <span class="delete">
                <a title="<?php _e('Delete this item', WPB_DOMAIN); ?>" href="<?php wpb_delete_filter_url($filter->id); ?>"><?php _e('Delete', WPB_DOMAIN); ?></a>
              </span>
            </div>
          </td>
          <td>
            <span><?php echo $filter->type['label']; ?></span>
          </td>
        </tr>
        <?php $i++; endforeach; ?>
        <?php else: ?>
        <tr>
          <td colspan="4">
            <?php _e( 'No filters found.', WPB_DOMAIN ); ?>
            <a href="<?php echo admin_url('admin.php?page=wpb_add_new_filter'); ?>"><?php _e( 'Add Filters', WPB_DOMAIN ); ?></a>
          </td>
        </tr>
        <?php endif; ?>
      </tbody>
      <tfoot>
        <tr>
          <th style="width:15px;"></th>
          <th>
            <input type="checkbox" class="wpb-check-all" />
          </th>
          <th><abbr title="<?php _e( 'Internal filter name', WPB_DOMAIN ); ?>"><?php _e( 'Filter Name', WPB_DOMAIN ); ?></abbr></th>
          <th><abbr title="<?php _e( 'Every filter has type. Different types filter differently.', WPB_DOMAIN ); ?>"><?php _e( 'Filter Type', WPB_DOMAIN ); ?></abbr></th>
        </tr>
      </tfoot>
    </table>
    <input type="hidden" value="wpb_filters" name="wpb_action" />
    <?php wp_nonce_field( 'wpb-filters', 'wpb-filters-nonce' ); ?>
  </form>

</div>
<?php

/**
 * Specific Objects Filter
 */
if ( !class_exists('WPB_Specific_Objects_Filter') && class_exists('WPB_Item_Filter') ) {
  class WPB_Specific_Objects_Filter extends WPB_Item_Filter {

    /**
     * Construct
     */
    public function __construct( $id=false, $name=false, $data=false ) {

      //** Set type of current filter */
      $this->type = array( 'key' => __CLASS__, 'label' => __( 'Specific Objects', WPB_DOMAIN ) );

      //** Call parent to do some important things */
      parent::__construct( $id, $name, $data );
    }

    /**
     * Implementation of filter options UI
     */
    public function ui_callback( $data=array() ) {
      //** Flush */
      $initial_data = array();
      $initial_object_ids = array();

      //** Get objects IDs from passed data */
      if( !empty( $data['objects'] ) )
        $initial_object_ids = explode( ',', $data['objects'] );

      //** Get objects by IDs got */
      foreach( $initial_object_ids as $id ) {
        $object = get_post($id);
        $initial_data[] = array(
            'id'    => $id,
            'title' => $object->post_title
        );
      }

      $post_types = get_post_types(array('public'=>true));

      ob_start();
      ?>
        <script type="text/javascript">
          jQuery( document ).ready(function(){
            jQuery("#wpb-specific-objects").select2({
              placeholder: wpb_lang.select_objects,
              multiple: true,
              width: '100%',
              minimumInputLength: 3,
              ajax: {
                url: ajaxurl,
                dataType: 'json',
                type: 'POST',
                data: function (term, page) {
                  return {
                    action: 'wpb_search_objects',
                    post_type: jQuery('#wpb-specific-objects-pt').val(),
                    s: term
                  };
                },
                results: function (data, page) {
                  return {results: data};
                }
              },
              initSelection: function(element, callback) {
                callback(<?php echo json_encode($initial_data); ?>);
              },
              formatResult: function(o) {
                return o.title;
              },
              formatSelection: function(o) {
                return o.title;
              },
              escapeMarkup: function (m) { return m; }
            });
          });
        </script>
        <table>
          <tr>
            <th><?php _e('Objects list', WPB_DOMAIN); ?></th>
            <td>
              <input type="text" value="<?php echo !empty($data['objects'])?$data['objects']:''; ?>" name="filter_data[objects]" id="wpb-specific-objects" />
            </td>
            <td style="width:60px">
              <select name="post_type" id="wpb-specific-objects-pt">
                <option value="any"><?php _e( 'Any', WPB_DOMAIN ); ?></option>
                <?php foreach( $post_types as $pt ): ?>
                <option value="<?php echo $pt; ?>"><?php echo ucfirst($pt); ?></option>
                <?php endforeach; ?>
              </select>
            </td>
          </tr>
        </table>
      <?php
      return apply_filters( __CLASS__.'_ui', ob_get_clean() );
    }

    /**
     * Implementation for filter
     * @todo Implement
     */
    public function filter() {}

  }

  new WPB_Specific_Objects_Filter();
}
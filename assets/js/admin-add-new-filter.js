jQuery( document ).ready(function(){

  var filter_options = jQuery('.filter_options');

  jQuery('#wpb-filter-type').on('change', function(){
    var type = jQuery(this).val();

    if ( type == '' ) {
      filter_options
        .html( wpb_lang.select_type_first )
        .removeClass('loaded')
        .addClass('empty');
    }

    if ( type != '' ) {
      wpb.filters.load_ui( filter_options, type, function(){
        filter_options.html( wpb_lang.loading );
      }, function(){
        filter_options
          .removeClass('empty')
          .addClass('loaded');
      });
    }
  });

});
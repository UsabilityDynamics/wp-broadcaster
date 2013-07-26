jQuery(document).ready(function(){
  jQuery('.wpb-check-all').click(function(){
    if ( jQuery( this ).attr( 'checked' ) ) {
      jQuery('.wpb-check-all').attr( 'checked', jQuery( this ).attr( 'checked' ) );
      jQuery( this ).parents('table').find('tbody input[type="checkbox"]').attr( 'checked', true );
    } else {
      jQuery('.wpb-check-all').removeAttr( 'checked' );
      jQuery( this ).parents('table').find('tbody input[type="checkbox"]').removeAttr( 'checked' );
    }
  });
});

var wpb = typeof wpb != 'undefined' ? wpb : {};

wpb.filters = {

  load_ui: function( container, type, process, cb ) {

    process();

    jQuery.ajax({
      type: 'POST',
      dataType: 'json',
      url: ajaxurl,
      data: {
        action: 'wpb_filter_load_ui',
        filter_type: type
      },
      success: function( response, status, e ) {
        if ( response.filter_ui ) {
          jQuery( container ).html( response.filter_ui );
          cb();
        }
      }
    });

  }
}
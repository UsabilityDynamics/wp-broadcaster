jQuery(document).ready(function(){

  //**  */
  jQuery( ".wpb_sortable" ).sortable({
    distance: 15,
    axis: "y",
    cursor: "move",
    update: function( e, ui ) {
      jQuery(ui.item).parent().find('tr').each(function(tr_key, tr){
        jQuery('.wpb_hidden_credential_id', tr).val(tr_key);
      });
    }
  });

  jQuery( ".wpb_toggle_password" ).click(function(){
    jQuery(this).next().toggle();
  });
});

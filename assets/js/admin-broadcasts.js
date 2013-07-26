jQuery( document ).ready(function(){

  //** Destinations */
  jQuery('.wpb-destinations-list').select2({
    placeholder: wpb_lang.select_destinations,
    width: '100%'
  });

  //** Filters */
  jQuery('.wpb-filters-list').select2({
    placeholder: wpb_lang.select_filters,
    width: '100%'
  });

  //** Clear button */
  jQuery("#wpb_clear").click(function(){
    jQuery('#wpb-destinations-list, #wpb-objects').select2('enable');
    jQuery('#wpb-destinations-list, #wpb-objects').select2('val', '');
    jQuery("#wpb_broadcast").removeAttr('disabled');
    jQuery( '.wpb-progress' ).slideUp('fast');
    jQuery( "#wpb-progress-log" ).empty();
  });

  //** Broadcast button */
  jQuery("#wpb_broadcast").click(function(){
    var destinations = jQuery('#wpb-destinations-list').select2('data');
    var objects   = jQuery('#wpb-objects').select2('data');

    var credentials  = [];
    var object_ids = [];
    for ( var destination in destinations ) {
      credentials.push( parseInt( destinations[destination].id ) );
    }
    for ( var object in objects ) {
      object_ids.push( objects[object].id );
    }

    if ( !credentials.length ) {
      jQuery('#wpb-destinations-list').select2('open');
      return;
    }
    if ( !object_ids.length ) {
      jQuery('#wpb-objects').select2('open');
      return;
    }

    jQuery('#wpb-destinations-list, #wpb-objects').select2('disable');
    jQuery(this).attr('disabled', true);
    jQuery("#wpb_clear").attr('disabled', true);

    var progressBar = jQuery( "#wpb-progress-bar" ),
    progressLabel = jQuery( "#wpb-progress-label" ),
    progressLog = jQuery( "#wpb-progress-log" ),
    step_size = 100 / ( credentials.length * object_ids.length ),
    progressPanel = jQuery( '.wpb-progress' );

    //** */
    var process_objects = function( current_credential, credentials, objects, callback, log, bar ) {
      var current_object = objects.shift();
      jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: ajaxurl,
        data: {
          action: 'wpb_broadcast',
          credential: current_credential,
          object: current_object
        },
        success: function( response, status, e ) {
          if ( response.success ) {
            for( var i in response.messages ) {
              log.prepend('<div class="wpb-success">'+response.messages[i]+'</div>');
            }
            if ( objects.length ) {
              process_objects( current_credential, credentials, objects, callback, log, bar );
            } else {
              if ( credentials.length ) {
                process_broadcast( credentials, [].concat(object_ids), callback, log, bar );
              } else {
                callback();
              }
            }
          } else {
            for( var i in response.messages ) {
              log.prepend('<div class="wpb-error">'+response.messages[i]+'</div>');
            }
            log.prepend('<div class="wpb-error">'+wpb_lang.process_canceled+'</div>');
          }
          var val = bar.progressbar( "value" ) || 0;
          bar.progressbar( "value", val + step_size );
        },
        error: function (xhr, ajaxOptions, thrownError) {
          log.prepend('<div class="wpb-error">AJAX ERROR: '+thrownError+' '+wpb_lang.please_refresh+'</div>');
          log.prepend('<div class="wpb-error">'+wpb_lang.process_canceled+'</div>');
        }
      });
    }

    var process_broadcast = function( credentials, objects, callback, log, bar ) {
      var current_credential = credentials.shift();
      process_objects( current_credential, credentials, objects, callback, log, bar );
    };

    progressBar.progressbar({
      value: false,
      change: function() {
        progressLabel.text( parseInt( progressBar.progressbar( "value" ) ) + "%" );
      },
      complete: function() {
        progressLabel.text( wpb_lang.complete );
      }
    });

    process_broadcast( credentials, [].concat(object_ids), function(){
      progressLog.prepend('<div>'+wpb_lang.complete+'</div>');
      jQuery("#wpb_clear").attr('disabled', false);
    }, progressLog, progressBar );

    progressPanel.slideDown('fast');
    progressLog.prepend('<div>'+wpb_lang.process_started+'</div>');

    return false;
  });
});
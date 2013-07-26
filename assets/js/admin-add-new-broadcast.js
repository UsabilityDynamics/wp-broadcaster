jQuery(document).ready(function(){

  //** Destinations */
  jQuery('#wpb-destinations-list').select2({
    placeholder: wpb_lang.select_destinations
  });

  //** Filters */
  jQuery('#wpb-filters-list').select2({
    placeholder: wpb_lang.select_filters
  });
});
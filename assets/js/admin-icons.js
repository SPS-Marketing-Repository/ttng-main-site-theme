/**
 * Icon picker previews on ACF edit screens (inc/icons.php).
 * Shows each icon beside its name in the dropdown and in the selected value.
 */
(function($) {
  if (typeof acf === 'undefined' || typeof ttngIconPicker === 'undefined') {
    return;
  }

  function ttng_iconOption(item) {
    // "Upload my own" and the search box's loading rows have no icon
    if (!item.id || item.id === 'upload') {
      return item.text;
    }

    const $option = $('<span class="ttng-icon-option"></span>');
    $('<img alt="">').attr('src', ttngIconPicker.baseUrl + item.id + '.svg').appendTo($option);
    $('<span></span>').text(item.text).appendTo($option);
    return $option;
  }

  acf.addFilter('select2_args', function(args, $select, data, field) {
    if (field && field.$el && field.$el.hasClass('ttng-icon-picker')) {
      args.templateResult = ttng_iconOption;
      args.templateSelection = ttng_iconOption;
    }
    return args;
  });
})(jQuery);

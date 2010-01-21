Drupal.behaviors.theme_settings = function (context) {
  // Add Farbtastic
  $('input.theme-colorpicker:not(.theme-processed)', context).each(function() {
    $(this).addClass('theme-processed');
    var target = $(this).parents('div.form-item');
    var id = $('div.colorpicker', target).attr('id');
    var farb = $.farbtastic('#' + id, $(this));
    $(this)
      .focus(function() {
        var target = $(this).parents('div.form-item');
        $('div.colorpicker', target).show('medium');
      })
      .blur(function() {
        var target = $(this).parents('div.form-item');
        $('div.colorpicker', target).hide('medium');
      });
  });
};

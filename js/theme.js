// $Id$

Drupal.behaviors.jake = function (context) {
  /**
   * Open feed item links in new windows.
   */
  $('.feeditem-title a:not(.processed), .feeditem-content a:not(.processed)').each(function() {
    $(this).addClass('processed');
    $(this).click(function() {
      window.open($(this).attr('href'));
      return false;
    });
  });

  /**
   * Growl-like messages.
   */
  $('.growl > div:not(.processed)').each(function() {
    $(this).addClass('processed');
    $('a.close', this).click(function() {
      $(this).parent().hide('fast');
    });
    if ($(this).is('.autoclose')) {
      // If a message contains a link, autoclosing is probably a bad idea.
      if ($('a', this).size() > 0) {
        $(this).removeClass('autoclose');
      }
      else {
        // This essentially adds a 3 second pause before hiding the message.
        $(this).animate({opacity:.95}, 5000, 'linear', function() {
          $(this).hide('fast');
        });
      }
    }
  });

  /**
   * Help display toggle.
   */
  $('a.help-link:not(.processed), #help div.help-close a:not(.processed)').each(function() {
    $(this).addClass('processed');
    $(this).click(function() {
      $('body').toggleClass('help');
      return false;
    });
  });

  /**
   * Palette links/block management.
   */
  $('#palette a.palette-link:not(.processed)').each(function() {
    $(this).addClass('processed');
    $(this).click(function() {
      if (!$(this).is('.palette-active')) {
        $('#palette a.palette-link').removeClass('palette-active');
        $('#palette div.block-toggle').hide();
        var block = '#block-' + $(this).attr('href').split('#')[1];
        $(block).show();
        $(this).addClass('palette-active');
      }
      else {
        $('#palette a.palette-link').removeClass('palette-active');
        $('#palette div.block-toggle').hide();
      }
      return false;
    });
  });
}

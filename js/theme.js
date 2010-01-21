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
   * Node form tabs.
   */
  $('ul.node-form-links a:not(.processed)').each(function() {
    $(this).addClass('processed');
    $(this).click(function() {
      $('.node-form-panel').hide();
      $('ul.node-form-links a').removeClass('selected');
      $(this).addClass('selected');
      var target = $(this).attr('href').split('#')[1];
      $('div.node-form .'+ target).show();
    });
  });

  /**
   * Growl-like messages.
   */
  $('#growl > div:not(.processed)').each(function() {
    $(this).addClass('processed');
    $('span.close', this).click(function() {
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
  $('div.palette-links a:not(.processed)').each(function() {
    $(this).addClass('processed');
    $(this).click(function() {
      if (!$(this).is('.palette-active')) {
        $('div.palette-links a').removeClass('palette-active');
        $('div.palette-blocks div.block').hide();

        var block = '#block-' + $(this).attr('href').split('#')[1];
        $(block).show();
        $(this).addClass('palette-active');
      }
      else {
        $('div.palette-links a').removeClass('palette-active');
        $('div.palette-blocks div.block').hide();
      }
      return false;
    });
  });
}

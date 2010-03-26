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

  $('.mark-link > a').bind('mark.drupalMark', function() { $(this).parents('li.views-row').hide(600);});

  // Change the z-index of the l10n client when it is expanded
  Drupal.jake.checkl10n();
  $('#l10n-client .toggle').click(function() {
    Drupal.jake.checkl10n();
  });

  // Hide the context ui category selector if there is only one option
  if ($('#context-ui-editor select.context-block-browser-categories option').size() == 1) {
    $('#context-ui-editor div.categories').hide();
    $('#context-ui-editor div.category').show();
  }

  /**
   * Palette links/block management.
   */
  $('#palette div.block:not(.processed)').each(function() {
    $(this).addClass('processed');
    $('.block-title', this).click(function() {
      var block = $(this).parents('div.block');
      if (!$(block).is('.palette-active')) {
        // Reset states
        $('#palette .palette-active').removeClass('palette-active');
        $('#palette .block-toggle .block-content').hide();

        // Show the clicked block
        $('.block-content', block).show();

        // Lazy load the widget preview
        if (block.attr('id') == 'block-mn_widgets-embed' && !Drupal.settings.widgetLoaded) {
          block.find("#mn_widgets_preview").html(Drupal.settings.mn_widgets_preview);
          Drupal.settings.widgetLoaded = true;
        }

        $(block).addClass('palette-active');
        if (jQuery().pageEditor && $('form', block).pageEditor) {
          $('form', block).pageEditor('start');
        }
      }
      else {
        $('#palette .palette-active').removeClass('palette-active');
        $('#palette .block-toggle .block-content').hide();
        if (jQuery().pageEditor && $('form', block).pageEditor) {
          $('form', block).pageEditor('end');
        }
      }
      return false;
    });
  });
};

Drupal.jake = {}
Drupal.jake.checkl10n = function() {
  client = $('#l10n-client');
  if (client.hasClass('hidden')) {
    index = 100;
  }
  else {
    index = 2000;
  }
  client.css('z-index', index);
}

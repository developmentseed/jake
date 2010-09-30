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

  // IE7 specific map hack
  if(jQuery.browser.msie && jQuery.browser.version < 8.0) {
    $('body.views-mode-map div.openlayers-views-map div.openlayers-map').height($('body.views-mode-map div.openlayers-views-map div.openlayers-container').height());
    $('body.views-mode-map div.openlayers-views-map div.openlayers-container').resize(function() {
      $('body.views-mode-map div.openlayers-views-map div.openlayers-map').height($('body.views-mode-map div.openlayers-views-map div.openlayers-container').height());
    });
  }

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

  $('.views-field-mark-trash .mark-link > a').bind('mark.drupalMark', function() { $(this).parents('li.views-row').hide(600);});

  // Change the z-index of the l10n client when it is expanded
  Drupal.jake.checkl10n();
  $('#l10n-client .toggle').click(function() {
    Drupal.jake.checkl10n();
  });

  // Hide the context ui category selector if there is only one option
  $('#block-spaces_dashboard-editor .block-title').click(function() {
    if ($('#spaces-dashboard-editor select.context-block-browser-categories option').size() == 1) {
      $('#spaces-dashboard-editor select.context-block-browser-categories').val('boxes').change();
      $('#spaces-dashboard-editor div.categories').hide();
    }
  });

  // Activate spaces dashboard editor when a box is edited
  $('.boxes-box-controls .links li.edit').children('a:not(.jake-processed)').each(function() {
    $(this).addClass('jake-processed');
    $(this).click(function() {
      var block = $('#palette div.block');
      if (!$('body').hasClass('context-editing')) {
        $('#block-spaces_dashboard-editor .block-title').click();
      }
    });
  });

  /**
   * Palette links/block management.
   */
  $('#palette div.block:not(.processed)').each(function() {
    $(this).addClass('processed');
    var dashboardTitleText;
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
          dashboardTitleText = $(this).text();
          $('form', block).pageEditor('start');
          $(this).text($('form #edit-cancel', block).val());
        }
      }
      else {
        $('#palette .palette-active').removeClass('palette-active');
        $('#palette .block-toggle .block-content').hide();
        if (jQuery().pageEditor && $('form', block).pageEditor) {
          $(this).text(dashboardTitleText);
          $('form', block).pageEditor('end');
          $('form #edit-cancel', block).click();
        }
      }
      return false;
    });
  });

  $('#content div.feeditem div.feeditem-labels:not(.jake-processed)')
    .addClass('jake-processed')
    .each(function() {
      $(this).hover(
        function() {
          $('div.data-taxonomy-tags', this).css('height', 'auto');
        },
        function() {
          $('div.data-taxonomy-tags', this).css('height', null);
        }
      );
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

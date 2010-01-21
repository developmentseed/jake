<?php

/**
 * Implementation of hook_theme().
 */
function jake_theme($existing, $type, $theme, $path) {
  return array(
    'blocks_palette' => array(),
    'color_css' => array(
      'arguments' => array('settings' => array()),
      'template' => 'color-css',
      'path' => drupal_get_path('theme', 'jake') .'/templates',
    ),
    'site_name' => array(),
    'print_logo' => array(),
  );
}

/**
 * Preprocessor for theme('page').
 */
function jake_preprocess_page(&$vars) {
  // Help link
  if (!empty($vars['help'])) {
    $vars['help_link'] = l('?', $_GET['q'], array('fragment' => 'help', 'attributes' => array('class' => 'help-link')));
  }
  // Admin link
  if (user_access('administer mn')) {
    $vars['admin_link'] = l(t('Admin'), 'admin/settings/site-information', array('attributes' => array('class' => 'admin-link')));
  }
  // Palette links
  $vars['palette_links'] = theme('blocks_palette', TRUE);

  // Custom coloring and styles
  $vars['styles'] .= theme('color_css', theme_get_settings('jake'));

  // Site name
  $vars['site_name'] = theme('site_name');

  // Determine stack height for fullscreen views.
  $class = array();
  if ($stackclass = context_get('theme', 'stackclass')) {
    $class[] = $stackclass;
  }
  if (!empty($vars['tabs'])) {
    $class[] = 'tabs';
  }
  if (!empty($class)) {
    $vars['attr']['class'] .= ' with-'. implode('-', $class);
  }
}

/**
 * Preprocessor for theme('block').
 */
function jake_preprocess_block(&$vars) {
  $classgroups = array(
    'utility-block' => array(
      'mn_search-status',
      'data_node-data_table_feedapi_feed',
      'stored_views-save',
    ),
  );
  $bid = "{$vars['block']->module}-{$vars['block']->delta}";
  if ($bid == 'stored_views-save') {
    context_set('theme', 'stackclass', 'search');
  }
  foreach ($classgroups as $class => $blocks) {
    if (in_array($bid, $blocks)) {
      $vars['attr']['class'] .= " {$class}";
    }
  }
}

/**
 * Theme function targeting palette block region.
 */
function jake_blocks_palette($get_links = FALSE) {
  static $links;
  static $dropdown;
  if (!isset($dropdown)) {
    $dropdown = '';
    $links = array();
    $blocks = function_exists('context_block_list') ? context_block_list('palette') : block_list('palette');
    foreach ($blocks as $block) {
      if (!empty($block->subject) || isset($_GET['print'])) {
        $links["{$block->module}-{$block->delta}"] = array(
          'title' => $block->subject,
          'href' => $_GET['q'],
          'fragment' => "{$block->module}-{$block->delta}",
          'attributes' => array('title' => $block->subject),
        );
        $dropdown .= theme('block', $block);
      }
      else {
        $links["{$block->module}-{$block->delta}"] = array(
          'title' => $block->content,
          'html' => TRUE,
          '#weight' => 100,
        );
      }
    }
    uasort($links, 'element_sort');
    $links = theme('links', $links, array('class' => 'links'));
  }
  return $get_links ? $links : $dropdown;
}

/**
 * Helper function to render views fields.
 */
function jake_views_render_field($field) {
  $output = '';
  if (!empty($field->content)) {
    $output .= !empty($field->separator) ? $field->separator : '';
    $output .= "<{$field->inline_html} class='views-field-{$field->class}'>";
    $output .= !empty($field->label) ? "<label class='views-label-{$field->class};'>{$field->label}</label>" : '';
    $output .= "<{$field->element_type} class='field-content'>{$field->content}</{$field->element_type}>";
    $output .= "</{$field->inline_html}>";
  }
  return $output;
}

/**
 * Preprocessor for theme('node').
 */
function jake_preprocess_node(&$vars) {
  // Don't show node title on page views
  if (menu_get_object() === $vars['node']) {
    if (!isset($_GET['print'])) {
      unset($vars['title']);
    }
    $vars['attr']['class'] .= ' node-page';
  }
  else {
    $vars['attr']['class'] .= ' node-teaser';
  }
  // We don't want the print friendly header on individual nodes.
  if (isset($vars['pre_object'])) {
    unset($vars['pre_object']);
  }
}

/**
 * Preprocessor for theme('flot_views_style').
 */
function jake_preprocess_flot_views_style(&$vars) {
  static $id = 0;
  $id++;

  $settings = theme_get_settings('jake');
  $vars['options']->colors = array(!empty($settings['foreground_color']) ? $settings['foreground_color'] : '#ace');
  $vars['options']->lines->fill = .25;
  $vars['options']->grid->tickColor = '#eee';
  $vars['options']->grid->backgroundColor = '#fff';

  $id_string = $vars['element']['id'] = "jake-flot-{$id}";

  // Add js to vars rather than calling drupal_add_js() directly.
  // This gives subthemes a chance to override this JS or omit it altogether
  // by overriding via preprocess or altering the flot-views-style template.
  $vars['js'] = array();

  // Label js.
  $labels = array();
  foreach ($vars['data'][0]->data as $point) {
    $labels[$point[0]] = format_date($point[0], 'custom', 'g:00a F j Y');
  }
  $vars['js']['flot_labels'] = $labels;

  // Hover tips.
  $inline_js = "$('#{$id_string}').bind('plothover', function (event, pos, item) {
    if (item) {
      var parent = $('#{$id_string}').offset();
      if (Drupal.settings.flot_labels[item.datapoint[0]]) {
        var date = Drupal.settings.flot_labels[item.datapoint[0]];
        if (item.datapoint[1] == 1) {
          var text = '<strong>' + item.datapoint[1] + ' story</strong> ' + date;
        }
        else {
          var text = '<strong>' + item.datapoint[1] + ' stories</strong> ' + date;
        }
        $('#{$id_string}').siblings('div.flot-caption').html(text);
      }
    }
    else {
      $('#{$id_string}').siblings('div.flot-caption').html('');
    }
  });";
  $vars['js']['inline'] = $inline_js;
}

/**
 * Override of theme_status_message().
 */
function jake_status_messages($display = NULL) {
  $output = '';
  $first = TRUE;
  $autoclose = array('status' => 1, 'warning' => 0, 'error' => 0);

  foreach (drupal_get_messages($display) as $type => $messages) {
    $class = $first ? 'first' : '';
    $class .= !empty($autoclose[$type]) || !isset($autoclose[$type]) ? ' autoclose' : '';
    $first = FALSE;

    $output .= "<div class='messages clear-block $type $class'>";
    $output .= "<span class='close'>". t('Hide') ."</span>";
    $output .= "<div class='message-content'>";
    if (count($messages) > 1) {
      $output .= "<ul>";
      foreach ($messages as $k => $message) {
        if ($k == 0) {
          $output .= "<li class='message-item first'>$message</li>";
        }
        else if ($k == count($messages) - 1) {
          $output .= "<li class='message-item last'>$message</li>";
        }
        else {
          $output .= "<li class='message-item'>$message</li>";
        }
      }
      $output .= "</ul>";
    }
    else {
      $output .= $messages[0];
    }
    $output .= "</div>";
    $output .= "</div>";
  }
  return $output;
}

/**
 * Crunch out some color variations for our CSS.
 */
function jake_preprocess_color_css(&$vars) {
  $settings = !empty($vars['settings']) ? $vars['settings'] : array();

  $vars['wallpaper_path'] = !empty($settings['wallpaper_path']) && file_exists($settings['wallpaper_path']) ? file_create_url($settings['wallpaper_path']) : '';
  $vars['wallpaper_position'] = !empty($settings['wallpaper_position']) ? $settings['wallpaper_position'] : 'bottom right';

  if (module_exists('color')) {
    $defaults = array(
      'background' => '#e0e0e0',
      'header' => '#222222',
      'foreground' => '#aaccee'
    );
    foreach ($defaults as $key => $default) {
      $vars[$key] = !empty($settings["{$key}_color"]) ? $settings["{$key}_color"] : $default;
      $rgb = _color_unpack($vars[$key], TRUE);
      $rgb = $rgb ? $rgb : _color_unpack($default, TRUE);
      $hsl = _color_rgb2hsl($rgb);
      $vars["{$key}_reverse"] = $hsl[2] > .65 ? FALSE : TRUE;

      $modifiers = array(
        'w10' => array('+', .1),
        'w25' => array('+', .25),
        'w50' => array('+', .5),
        'b10' => array('-', .1),
        'b25' => array('-', .25),
        'b50' => array('-', .5),
        'text' => $hsl[2] > .65 ? array('-', .9) : array('+', 1000),
      );
      foreach ($modifiers as $id => $modifier) {
        $color_hsl = $hsl;
        switch ($modifier[0]) {
          case '-':
            $color_hsl[2] = $color_hsl[2] * (1 - $modifier[1]);
            break;
          default:
            $color_hsl[2] = $color_hsl[2] * (1 + $modifier[1]);
            $color_hsl[2] = ($color_hsl[2] > 1) ? 1 : $color_hsl[2];
            break;
        }
        $color_rgb = _color_hsl2rgb($color_hsl);
        $vars["{$key}_{$id}"] = _color_pack($color_rgb, TRUE);
      }
    }
  }
}

/**
 * Theme function for generating a site logo/name.
 */
function jake_site_name() {
  $settings = theme_get_settings('jake');
  $name = check_plain(variable_get('site_name', 'Drupal'));
  if (!empty($settings['logo_path']) && file_exists($settings['logo_path']) && module_exists('imagecache') && imagecache_preset_by_name('logo')) {
    $url = imagecache_create_url('logo', $settings['logo_path']);
    return l(t($name), '<front>', array('attributes' => array('class' => 'logo', 'style' => 'background-image:url('.$url.')')));
  }
  // Last resort, use just the name
  return l(t($name), '<front>');
}

/**
 * Preprocess for theme('print_header').
 */
function jake_preprocess_print_header(&$vars) {
  $vars['site_name'] = theme('print_logo');
  $vars['date'] = format_date(time(), 'large');
}

/**
 * Print logo.
 */
function jake_print_logo() {
  $settings = theme_get_settings('jake');
  $name = check_plain(variable_get('site_name', 'Drupal'));

  if (!empty($settings['printlogo_path']) && file_exists($settings['printlogo_path']) && module_exists('imagecache') && imagecache_preset_by_name('logo_print')) {
    $url = imagecache_create_url('logo_print', $settings['printlogo_path']);
    $image = "<img src='{$url}' title='{$name}' class='logo'/>";
    return l($image, '<front>', array('html' => TRUE, 'attributes' => array('class' => 'logo')));
  }
  // Last resort, use just the name
  return l(t($name), '<front>');
}

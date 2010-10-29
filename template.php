<?php

/**
 * Implementation of hook_theme().
 */
function jake_theme($existing, $type, $theme, $path) {
  return array(
    'site_name' => array(),
    'print_logo' => array(),
  );
}

/**
 * Move taxonomy back to first node edit page.
 */
function jake_preprocess_node_form(&$vars) {
  $vars['form']['taxonomy'] = $vars['sidebar']['taxonomy'];
  unset($vars['sidebar']['taxonomy']);
}

/**
 * Preprocessor for theme('help').
 */
function jake_preprocess_help(&$vars) {
  $vars['title'] = t('Need help?');
  $vars['layout'] = FALSE;
  $vars['attr'] = array();
  $vars['links'] = '';
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

  // Add body class for layout.
  $vars['attr']['class'] .= !empty($vars['template_files']) ? ' '. end($vars['template_files']) : '';

  $header_blocks = block_list('header');
  if (!empty($header_blocks)) {
    $vars['attr']['class'] .= !empty($vars['header']) ? ' with-header' : '';
  }

  // Site name
  $vars['site_name'] = theme('site_name');

  // Display mission in a block
  $vars['mission_block'] = '';
  if (!empty($vars['mission']) && drupal_is_front_page()) {
    $mission_block = new stdClass();
    $mission_block->content = $vars['mission'];
    $vars['mission_block'] = theme('block', $mission_block);
  }

  // Don't show title on dashboard == frontpage.
  $context = context_get('context');
  if (isset($context['mn-dashboard'])) {
    $vars['title'] = '';
  }

  // Truncate the slogan so it doesn't break the header
  $vars['site_slogan'] = truncate_utf8($vars['site_slogan'], 35);

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
  if ($vars['block']->region === 'palette') {
    $vars['attr']['class'] .= !empty($vars['title']) ? ' block-toggle' : ' widget';
  }
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
 * Helper function to render views fields.
 */
function jake_views_render_field(&$field, $skip = TRUE) {
  $output = '';
  if (is_array($field) && count($field) && is_object(current($field))) {
    foreach ($field as $k => $f) {
      $output .= jake_views_render_field($field[$k]);
    }
  }
  else if (is_object($field)) {
    // Skip rendered fields
    if (empty($field->rendered) || !$skip) {
      if (!empty($field->content)) {
        $output .= !empty($field->separator) ? $field->separator : '';
        $output .= "<{$field->inline_html} class='views-field-{$field->class}'>";
        $output .= !empty($field->label) ? "<label class='views-label-{$field->class};'>{$field->label}</label>" : '';
        $output .= "<{$field->element_type} class='field-content'>{$field->content}</{$field->element_type}>";
        $output .= "</{$field->inline_html}>";
      }
      $field->rendered = TRUE;
    }
  }
  return $output;
}

/**
 * Preprocessor for theme('node').
 */
function jake_preprocess_node(&$vars) {
  $vars['layout'] = FALSE;

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
 * Supply DesignKit colors to OpenLayers styles.
 */
function jake_preprocess_flot_views_style(&$vars) {
  static $id = 0;
  $id++;

  $color = variable_get('designkit_color', array());
  $vars['options']->colors = array(!empty($color['foreground_color']) ? $color['foreground_color'] : '#ace');
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
          var text = '<strong>' + item.datapoint[1] + ' " . t('story') . "</strong> ' + date;
        }
        else {
          var text = '<strong>' + item.datapoint[1] + ' " . t('stories') . "</strong> ' + date;
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
 * Override of theme_openlayers_styles().
 * Supply DesignKit colors to OpenLayers styles.
 */
function jake_openlayers_styles($styles = array(), $map = array()) {
  $color = variable_get('designkit_color', array());
  if (isset($styles['default'])) {
    $styles['default']['fillColor'] = !empty($color['foreground_color']) ? $color['foreground_color'] : '#ace';
    $styles['default']['strokeColor'] = !empty($color['foreground_color']) ? $color['foreground_color'] : '#ace';
  }
  return $styles;
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
    $output .= l(t('Close'), $_GET['q'], array('fragment' => 'close', 'attributes' => array('class' => 'close')));
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
 * Theme function for generating a site logo/name.
 */
function jake_site_name() {
  $image = variable_get('designkit_image', array());
  $name = variable_get('site_name', 'Drupal');
  if (!empty($image['logo'])) {
    return l(t($name), '<front>', array('attributes' => array('class' => 'logo')));
  }
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

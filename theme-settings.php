<?php
/**
 * Implementation of hook_settings() for themes.
 */
function jake_settings($settings) {
  // Add js & css
  drupal_add_css('misc/farbtastic/farbtastic.css', 'module', 'all', FALSE);
  drupal_add_js('misc/farbtastic/farbtastic.js');
  drupal_add_js(drupal_get_path('theme', 'jake') .'/js/settings.js');
  drupal_add_css(drupal_get_path('theme', 'jake') .'/css/settings.css');

  file_check_directory(file_directory_path(), FILE_CREATE_DIRECTORY, 'file_directory_path');

  $form = array();

  $logo_types = array('logo' => t('Logo'), 'printlogo' => t('Print'));
  foreach ($logo_types as $key => $label) {
    // Check for a new uploaded logo, and use that instead.
    if ($file = file_save_upload("{$key}_file", array('file_validate_is_image' => array()))) {
      $parts = pathinfo($file->filename);
      $filename = "theme_{$key}_{$parts['extension']}";
      if (file_copy($file, $filename, FILE_EXISTS_REPLACE)) {
        $settings["{$key}_path"] = $file->filepath;
        // Flush any imagecache variants.
        if (module_exists('imagecache')) {
          imagecache_image_flush($file->filepath);
        }
      }
    }
    $form["{$key}_file"] = array(
      '#type' => 'file',
      '#title' => t('!type image', array('!type' => $label)),
      '#maxlength' => 40,
      '#description' => $key == 'logo' ?
        t('This image will replace the site title shown in the header.') :
        t('This image will be used in the header of any print-friendly pages.'),
    );
    if (!empty($settings["{$key}_path"])) {
      $form["{$key}_wrapper"] = array(
        '#type' => 'item',
        '#tree' => FALSE,
        '#description' => !empty($settings["{$key}_path"]) ? theme('image', $settings["{$key}_path"], NULL, NULL, array('width' => '200'), FALSE) : '',
      );
      $form["{$key}_wrapper"]["{$key}_delete"] = array(
        '#value' => t('Delete the current logo'),
        '#type' => 'submit',
        '#submit' => array("jake_settings_delete_{$key}_submit"),
      );
    }
    $form["{$key}_path"] = array(
      '#type' => 'value',
      '#value' => !empty($settings["{$key}_path"]) ? $settings["{$key}_path"] : '',
    );
  }

  // Determine default bg color
  if (!empty($settings['background_color'])) {
    $default_bgcolor = $settings['background_color'];
  }
  else {
    $autocolor = !empty($settings['logo_path']) ? _jake_design_image_autocolor($settings['logo_path']) : '';
    $default_bgcolor = !empty($autocolor) ? $autocolor : '#222';
    $settings['background_color'] = $default_bgcolor;
    variable_set('theme_jake_settings', $settings);
  }
  $form['background_color'] = array(
    '#title' => t('background color'),
    '#type' => 'textfield',
    '#size' => '7',
    '#maxlength' => '7',
    '#default_value' => $default_bgcolor,
    '#description' => t('Leave blank to attempt to autodetect color from uploaded logo.') . '<div id="colorpicker-background" class="colorpicker"></div>',
    '#attributes' => array('class' => 'theme-colorpicker'),
  );
  $form['foreground_color'] = array(
    '#title' => t('foreground color'),
    '#type' => 'textfield',
    '#size' => '7',
    '#maxlength' => '7',
    '#default_value' => !empty($settings['foreground_color']) ? $settings['foreground_color'] : '#ace',
    '#description' => '<div id="colorpicker-foreground" class="colorpicker"></div>',
    '#attributes' => array('class' => 'theme-colorpicker'),
  );
  return $form;
}

/**
 * Logo deletion submit handler.
 */
function jake_settings_delete_logo_submit(&$form, &$form_state) {
  if (file_delete($form_state['values']['logo_path'])) {
    $settings = variable_get('theme_jake_settings', array());
    if (isset($settings['logo_path'])) {
      unset($settings['logo_path']);
      variable_set('theme_jake_settings', $settings);
    }
  }
}

/**
 * Print logo deletion submit handler.
 */
function jake_settings_delete_printlogo_submit(&$form, &$form_state) {
  if (file_delete($form_state['values']['printlogo_path'])) {
    $settings = variable_get('theme_jake_settings', array());
    if (isset($settings['printlogo_path'])) {
      unset($settings['printlogo_path']);
      variable_set('theme_jake_settings', $settings);
    }
  }
}

/**
 * Attempt to retrieve a suitable background color value from an image.
 * Taken from the spaces_design module (http://drupal.org/project/spaces).
 */
function _jake_design_image_autocolor($filepath) {
  $autocolor = '';
  if (module_exists('imageapi') && module_exists('color')) {
    // Do additional handling post-save
    $image = imageapi_image_open($filepath);
    $toolkit = variable_get('imageapi_image_toolkit', 'imageapi_gd');

    // Currently we only handle background color selection through the GD library.
    if ($toolkit == 'imageapi_gd' && !empty($image->resource)) {
      $raw = array();
      $raw['nw'] = imagecolorat($image->resource, 0, 0);
      $raw['ne'] = imagecolorat($image->resource, $image->info['width'] - 1, 0);
      $raw['se'] = imagecolorat($image->resource, $image->info['width'] - 1, $image->info['height'] - 1);
      $raw['sw'] = imagecolorat($image->resource, 0, $image->info['height'] - 1);

      $colors = array();
      foreach ($raw as $k => $index) {
        $rgb = imagecolorsforindex($image->resource, $index);

        $color = array();
        $color[] = str_pad(dechex($rgb['red']), 2, '0', STR_PAD_LEFT);
        $color[] = str_pad(dechex($rgb['green']), 2, '0', STR_PAD_LEFT);
        $color[] = str_pad(dechex($rgb['blue']), 2, '0', STR_PAD_LEFT);
        $color = "#". implode('', $color);

        $colors[$color] = $colors[$color] + 1;
      }
      $max = 1;
      $excluded = array('#ffffff', '#000000');
      foreach ($colors as $color => $count) {
        $unpacked = _color_unpack($color, TRUE);
        $hsl = _color_rgb2hsl($unpacked);

        if ($count > $max && !in_array($color, $excluded) && $hsl[2] < .95 &&  $hsl[2] > .05) {
          $autocolor = $color;
        }
      }
    }
  }
  return $autocolor;
}

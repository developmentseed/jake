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

  $image_types = array(
    'logo' => array(
      '#title' => t('Site logo'),
      '#description' => t('This image will replace the site title shown in the header.'),
    ),
    'printlogo' => array(
      '#title' => t('Print logo'),
      '#description' => t('This image will be used in the header of any print-friendly pages.'),
    ),
    'wallpaper' => array(
      '#title' => t('Site wallpaper'),
      '#description' => t('This image will be displayed as a site background.'),
    ),
  );
  foreach ($image_types as $key => $base) {
    // Check for a new uploaded logo, and use that instead.
    if ($file = file_save_upload("{$key}_file", array('file_validate_is_image' => array()))) {
      $parts = pathinfo($file->filename);
      $filename = "theme_{$key}.{$parts['extension']}";
      if (file_copy($file, $filename, FILE_EXISTS_REPLACE)) {
        $settings["{$key}_path"] = $file->filepath;
        // Flush any imagecache variants.
        if (module_exists('imagecache')) {
          imagecache_image_flush($file->filepath);
        }
      }
    }
    if (empty($settings["{$key}_path"])) {
    $form["{$key}_file"] = array(
      '#type' => 'file',
      '#maxlength' => 40,
    ) + $base;
    }
    else {
      $preview = !empty($settings["{$key}_path"]) ? theme('image', $settings["{$key}_path"], NULL, NULL, array('width' => '200'), FALSE) : '';
      $form["{$key}_wrapper"] = array(
        '#type' => 'item',
        '#tree' => FALSE,
        'preview' => array(
          '#type' => 'markup',
          '#value' => "<div class='image-preview'>{$preview}</div>",
        ),
        "{$key}_delete" => array(
          '#value' => t('Delete the current image'),
          '#type' => 'submit',
          '#submit' => array("jake_settings_delete_{$key}_submit"),
        ),
      ) + $base;
    }
    $form["{$key}_path"] = array(
      '#type' => 'value',
      '#value' => !empty($settings["{$key}_path"]) ? $settings["{$key}_path"] : '',
    );
  }

  $form['wallpaper_position'] = array(
    '#title' => t('Wallpaper position'),
    '#type' => 'select',
    '#options' => array(
      t('Top') => array(
        'top center' => t('Top center'),
        'top right' => t('Top right'),
        'top left' => t('Top left'),
      ),
      t('Bottom') => array(
        'bottom center' => t('Bottom center'),
        'bottom right' => t('Bottom right'),
        'bottom left' => t('Bottom left'),
      ),
    ),
    '#default_value' => !empty($settings['wallpaper_position']) ? $settings['wallpaper_position'] : 'bottom right',
    '#description' => t('Select the wallpaper image position.'),
  );

  // Autodetect colors from uploaded images.
  $autodetect = array(
    'background_color' => 'logo_path',
    'header_color' => 'wallpaper_path',
  );
  foreach ($autodetect as $key => $imagepath) {
    if (empty($settings[$key])) {
      $autocolor = !empty($settings[$imagepath]) ? _jake_design_image_autocolor($settings[$imagepath]) : '';
      if (!empty($autocolor)) {
        $settings[$key] = $autocolor;
        variable_set('theme_jake_settings', $settings);
      }
    }
  }

  $form['background_color'] = array(
    '#title' => t('Background color'),
    '#type' => 'textfield',
    '#size' => '7',
    '#maxlength' => '7',
    '#default_value' => !empty($settings['background_color']) ? $settings['background_color'] : '#e8e8e8',
    '#description' => t('Leave blank to attempt to autodetect color from uploaded wallpaper.') . '<div id="colorpicker-background" class="colorpicker"></div>',
    '#attributes' => array('class' => 'theme-colorpicker'),
  );
  $form['header_color'] = array(
    '#title' => t('Header color'),
    '#type' => 'textfield',
    '#size' => '7',
    '#maxlength' => '7',
    '#default_value' => !empty($settings['header_color']) ? $settings['header_color'] : '#222',
    '#description' => t('Leave blank to attempt to autodetect color from uploaded logo.') . '<div id="colorpicker-header" class="colorpicker"></div>',
    '#attributes' => array('class' => 'theme-colorpicker'),
  );
  $form['foreground_color'] = array(
    '#title' => t('Foreground color'),
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
 * Helper to image delete submit handlers.
 */
function _jake_settings_delete_image($key, &$form_state) {
  if (file_delete($form_state['values'][$key])) {
    $settings = variable_get('theme_jake_settings', array());
    if (isset($settings[$key])) {
      unset($settings[$key]);
      variable_set('theme_jake_settings', $settings);
    }
  }
}

/**
 * Logo deletion submit handler.
 */
function jake_settings_delete_logo_submit(&$form, &$form_state) {
  _jake_settings_delete_image('logo_path', $form_state);
}

/**
 * Print logo deletion submit handler.
 */
function jake_settings_delete_printlogo_submit(&$form, &$form_state) {
  _jake_settings_delete_image('printlogo_path', $form_state);
}

/**
 * Logo deletion submit handler.
 */
function jake_settings_delete_wallpaper_submit(&$form, &$form_state) {
  _jake_settings_delete_image('wallpaper_path', $form_state);
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

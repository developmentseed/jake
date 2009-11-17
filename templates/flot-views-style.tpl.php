<?php
// $Id: flot-views-summary-style.tpl.php,v 1.1.2.2 2009/05/12 03:19:43 yhahn Exp $

/**
 * @file flot-views-summary-style.tpl.php
 * Template to display a flot summary view.
 *
 * - $element : An array representation of the flot DOM element.
 * - $data: A flotData object.
 * - $options: A flotStyle object.
 */

if (isset($js['flot_labels'], $js['inline'])) {
  drupal_add_js(array('flot_labels' => $js['flot_labels']), 'setting');
  drupal_add_js($js['inline'], 'inline', 'footer', FALSE, FALSE, FALSE);
}
?>

<div class="views-flot">
  <?php print theme('flot_graph', $element, $data, $options); ?>
  <div class='flot-caption'></div>
</div>

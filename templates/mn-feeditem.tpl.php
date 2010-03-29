<?php
// Render fields with known formatting first.
$date        = jake_views_render_field($fields['timestamp_1']);
$feed        = jake_views_render_field($fields['title_1']);
$title       = jake_views_render_field($fields['title']);
$description = jake_views_render_field($fields['description']);
$labels      = jake_views_render_field($fields['data_taxonomy_form']);
$links       = jake_views_render_field($fields['simpleshare_link']) . jake_views_render_field($fields['mark_trash']);

// All other fields.
$other       = jake_views_render_field($fields);
?>
<div class='feeditem clear-block'>

  <div class='feeditem-meta clear-block'><?php print $date ?><?php print $feed ?></div>
  <h2 class='feeditem-title clear-block'><?php print $title ?></h2>

  <?php if ($description): ?>
    <div class='feeditem-content prose clear-block'><?php print $description ?></div>
  <?php endif; ?>

  <?php if ($other): ?>
    <div class='feeditem-other clear-block'><?php print $other ?></div>
  <?php endif; ?>

  <?php if ($labels): ?>
    <div class='feeditem-labels clear-block'><?php print $labels ?></div>
  <?php endif; ?>

  <div class='feeditem-links clear-block'><?php print $links ?></div>

</div>
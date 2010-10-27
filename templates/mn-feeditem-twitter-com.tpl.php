<?php
// Render fields with known formatting first.
$date        = jake_views_render_field($fields['timestamp_1']);
$feed        = jake_views_render_field($fields['title_1']);
$author      = jake_views_render_field($fields['author']);
$description = jake_views_render_field($fields['description']);
$labels      = jake_views_render_field($fields['data_taxonomy_form']);
$links       = jake_views_render_field($fields['simpleshare_link']) . jake_views_render_field($fields['mark_trash']);

// All other fields.
$other       = jake_views_render_field($fields);
?>
<div class='feeditem feeditem-twitter clear-block'>

  <div class='feeditem-meta clear-block'><?php print $date ?><?php print $feed ?></div>
  <div class='feeditem-content prose clear-block'><?php print $author ?> <?php print $description ?></div>

  <?php if ($labels): ?>
    <div class='feeditem-labels clear-block'><?php print $labels ?></div>
  <?php endif; ?>

  <div class='feeditem-links clear-block'><?php print $links ?></div>

</div>

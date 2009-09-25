<div class='feeditem clear-block'>
  
  <div class='feeditem-meta clear-block'>
    <?php print jake_views_render_field($fields['timestamp_1']) ?>
    <?php print jake_views_render_field($fields['title_1']) ?>
  </div>

  <h2 class='feeditem-title clear-block'>
    <?php print jake_views_render_field($fields['title']) ?>
  </h2>

  <div class='feeditem-content prose clear-block'>
    <?php print jake_views_render_field($fields['description']) ?>
  </div>

  <div class='feeditem-labels clear-block'>
    <?php print jake_views_render_field($fields['data_node_list']) ?>
  </div>

  <div class='feeditem-links clear-block'>
    <?php print jake_views_render_field($fields['mn_share_link']) ?>
    <?php print jake_views_render_field($fields['data_node_add_remove']) ?>
  </div>

</div>
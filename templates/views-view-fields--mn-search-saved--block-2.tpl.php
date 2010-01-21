<div class='utility clear-block'>

<?php
$delete = jake_views_render_field($fields['link_delete']);
if ($delete):
?>
<div class='utility-links clear-block'><?php print $edit ?><?php print $delete ?></div>
<?php endif; ?>

<?php print jake_views_render_field($fields['title']) ?>

</div>
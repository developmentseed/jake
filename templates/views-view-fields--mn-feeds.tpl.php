<div class='utility clear-block'>

<?php
$edit = jake_views_render_field($fields['edit_node']);
$delete = jake_views_render_field($fields['delete_node']);
if ($edit || $delete):
?>
<div class='utility-links clear-block'><?php print $edit ?><?php print $delete ?></div>
<?php endif; ?>

<?php print jake_views_render_field($fields['title']) ?>

</div>
<div class='form node-form clear-block <?php print $form_classes ?>'>

<?php if ($sidebar = drupal_render($sidebar)): ?>
  <?php
    // Generate tabs for the node form
    $links = array(
      'main' => array('title' => t('Main'), 'href' => $_GET['q'], 'fragment' => 'main', 'attributes' => array('class' => 'selected')),
      'sidebar' => array('title' => t('Additional information'), 'href' => $_GET['q'], 'fragment' => 'sidebar'),
    );
    print theme('links', $links, array('class' => 'node-form-links links clear-block'));
  ?>
  <div class='node-form-panel sidebar'><?php print $sidebar ?></div>
<?php endif; ?>

<div class='node-form-panel main'>
  <?php
    $form_settings = variable_get('seed_forms', array());
    print !empty($form_settings['numbered']) ? seed_number_form($form) : drupal_render($form)
  ?>
</div>

<?php if ($buttons): ?>
  <div class='buttons clear-block'><?php print drupal_render($buttons) ?></div>
<?php endif; ?>

<?php if ($hidden): ?>
  <div class='hidden' style='display:none'><?php print drupal_render($hidden) ?></div>
<?php endif; ?>

</div>

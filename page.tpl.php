<?php include 'page.header.inc'; ?>


<div id='left'><div class='navbar clear-block'>
  <?php if (!empty($context_links)): ?>
    <div class='context-links clear-block'><?php print $context_links ?></div>
  <?php endif; ?>
  <?php print $left ?>
</div></div>

<div id='canvas' class='clear-block'>

  <?php include 'page.title.inc'; ?>

  <?php if ($show_messages && $messages): ?>
    <div class='growl'><?php print $messages; ?></div>
  <?php endif; ?>

  <div id='main'>
    <div id='content' class='page-content clear-block'>
      <div id='content-wrapper'><?php print $content ?></div>
      <div id='content-region-wrapper'><?php print $content_region ?></div>
    </div>
  </div>

  <?php if ($right): ?>
    <div id='right'><div class='sidebar clear-block'>
      <?php print $mission_block ?>
      <?php print $right ?>
    </div></div>
  <?php endif; ?>

</div>

<?php include 'page.footer.inc'; ?>

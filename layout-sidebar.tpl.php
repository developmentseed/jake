<?php include 'page.header.inc'; ?>

<div id='left'><div class='navbar clear-block'>
  <?php if (!empty($context_links)): ?>
    <div class='context-links clear-block'><?php print $context_links ?></div>
  <?php endif; ?>
  <?php if (!empty($left)) print $left ?>
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

</div>

<?php include 'page.footer.inc'; ?>
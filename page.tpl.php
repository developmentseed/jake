<?php include 'page.header.inc'; ?>

<?php if ($left): ?>
  <div id='left'><div class='navbar clear-block'>
    <?php if (!empty($context_links)): ?>
      <div class='context-links clear-block'><?php print $context_links ?></div>
    <?php endif; ?>
    <?php print $left ?>
  </div></div>
<?php endif; ?>

<div id='canvas' class='clear-block'>

  <div id='page-header' class='clear-block'>
    <div id='page-tools'><?php print $page_tools ?></div>
    <div id='page-title'>
      <?php if ($page_title) print $page_title ?>
      <?php if ($title): ?><h2 class='page-title'><?php print $title ?></h2><?php endif; ?>
    </div>
    <?php if ($tabs): ?>
      <div class='tabs clear-block'><?php if ($tabs) print $tabs ?><?php if ($tabs2) print $tabs2 ?></div>
    <?php endif; ?>
  </div>

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

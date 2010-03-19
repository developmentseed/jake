<?php include 'page.header.inc'; ?>

<div id='left'><div class='navbar clear-block'>
  <?php if (!empty($context_links)): ?>
    <div class='context-links clear-block'><?php print $context_links ?></div>
  <?php endif; ?>
  <?php if (!empty($left)) print $left ?>
</div></div>

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

  <div id='main'>
    <div id='content' class='page-content clear-block'>
      <div id='content-wrapper'><?php print $content ?></div>
      <div id='content-region-wrapper'><?php print $content_region ?></div>
    </div>
  </div>

</div>

<?php include 'page.footer.inc'; ?>
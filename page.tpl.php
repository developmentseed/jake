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
      <?php if ($title && empty($page_title)): ?><h2 class='page-title'><?php print $title ?></h2><?php endif; ?>
    </div>
    <?php if ($tabs): ?>
      <div class='tabs clear-block'><?php if ($tabs) print $tabs ?><?php if ($tabs2) print $tabs2 ?></div>
    <?php endif; ?>
  </div>

  <div id='main'><div id='content' class='main-wrapper clear-block'><?php print $content ?></div></div>

  <?php if ($right): ?>
    <div id='right'><div class='sidebar clear-block'><?php print $right ?></div></div>
  <?php endif; ?>

</div>

<?php include 'page.footer.inc'; ?>
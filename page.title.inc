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
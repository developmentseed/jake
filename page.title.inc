<div id='page-header' class='clear-block'>
  <?php if ($page_tools): ?>
    <div id='page-tools'><?php print $page_tools ?></div>
  <?php endif; ?>
  <?php if ($page_title || $title): ?>
    <div id='page-title'>
      <?php if ($page_title) print $page_title ?>
      <?php if ($title && empty($page_title)): ?><h2 class='page-title'><?php print $title ?></h2><?php endif; ?>
    </div>
  <?php endif; ?>
  <?php if ($tabs): ?>
    <div class='tabs clear-block'><?php if ($tabs) print $tabs ?><?php if ($tabs2) print $tabs2 ?></div>
  <?php endif; ?>
</div>
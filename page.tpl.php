<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">

<head>
<?php print $head ?>
<?php print $styles ?>
<?php print $scripts ?>
<title><?php print $head_title ?></title>
</head>

<body <?php print drupal_attributes($attr) ?>>

<?php if (!empty($admin)) print $admin ?>

<?php if ($show_messages && $messages): ?>
  <div id='growl'><?php print $messages; ?></div>
<?php endif; ?>

<div id='branding' class='clear-block'>
  <?php if ($admin_link): ?><div class="admin-link"><?php print $admin_link; ?></div><?php endif; ?>
  <?php if ($site_name): ?><h1 class='site-name'><?php print $site_name ?></h1><?php endif; ?>
  <div class="help-link"><?php if ($help_link) print $help_link; ?></div>

  <?php if (isset($primary_links)) : ?>
    <?php print theme('links', $primary_links, array('class' => 'links primary-links')) ?>
  <?php endif; ?>
</div>

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

<div id="footer" class='clear-block'>
  <div class='footer-message'><?php print $footer_message ?></div>
</div>

<?php if ($help): ?>
  <div id='help'>
    <div class='help-wrapper clear-block'>
      <div class='help-close'><?php print l(t('Hide this'), $_GET['q'], array('fragment' => 'close')) ?></div>
      <h2 class='help-title'><?php print t('Need help?') ?></h2>
      <div class='help-content'><?php print $help; ?></div>
    </div>
  </div>
<?php endif; ?>

<?php if ($palette): ?>
  <div id='palette'>
    <div class='palette-links clear-block'><?php print $palette_links ?></div>
    <div class='palette-blocks'><?php print $palette ?></div>
  </div>
<?php endif; ?>

<?php print $closure ?>

</body>

</html>

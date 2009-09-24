<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>">
<head>
  <?php print $head ?>
  <?php print $styles ?>
  <?php print $scripts ?>
  <title><?php print $head_title ?></title>
</head>

<body <?php print drupal_attributes($attr) ?>>

  <?php print $palette ?>

  <div class='limiter clear-block'>

  <?php print $print_header ?>

  <div id="page">
    <div id='page-header'>
      <h1 class='page-title'><?php print $title ?></h1>
    </div>
    <div id='content'><div class='main-wrapper clear-block'>
      <?php print $content ?>
    </div></div>
  </div>

  <?php if ($footer_message): ?>
    <div id="footer"><?php print $footer_message ?></div>
  <?php endif; ?>

  </div>

  <?php print $closure ?>

</body>

</html>

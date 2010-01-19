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

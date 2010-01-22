<div id="footer" class='clear-block'>
  <div class='footer-message'><?php print $footer_message ?></div>
</div>

<?php if ($help): ?>
  <div id='help'>
    <div class='help-wrapper clear-block'>
      <div class='help-close'><?php print l(t('Hide this'), $_GET['q'], array('fragment' => 'close')) ?></div>
      <?php print $help; ?>
    </div>
  </div>
<?php endif; ?>

<?php if ($palette): ?>
  <div id='palette'><?php print $palette ?></div>
<?php endif; ?>

<?php print $closure ?>

</body>

</html>

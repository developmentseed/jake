<?php if ($background && $header && $foreground): ?>

<style type='text/css'>
body {
  background-color:<?php print $background ?>;
  <?php if ($wallpaper_path): ?>
    background-image: url("<?php print $wallpaper_path?>");
  <?php endif; ?>
  <?php if ($wallpaper_position): ?>
    background-position:<?php print $wallpaper_position ?>;
  <?php endif; ?>
  }

/**
 * Background color ===================================================
 */
#branding {
  background-color:<?php print $header ?>;
  border-color:<?php print $header_b10; ?>
  }

#branding a { color:<?php print $header_text ?>; }

#branding div.help-link a,
#branding div.admin-link a,
#branding ul.links a:hover {
  background-color:<?php print $header_reverse ? $header_b25 : $header_w25 ?>;
  color:<?php print $header_text ?>;
  }

#branding ul.links li.active a,
#branding ul.links li a.active,
body.help #branding a.help-link {
  background-color:<?php print $header_text ?>;
  color:<?php print $header_reverse ? $header_b25 : $header_w25 ?>;
  }

/**
 * Foreground color ===================================================
 */
div.sidebar div.view-mn-search-saved div.views-field-title a,
#canvas div.utility-block h2.block-title {
  background-color:<?php print $foreground ?>;
  }

span.data-node-label,
#canvas div.utility-block {
  border-color:<?php print $foreground_reverse ? $foreground_b25 : $foreground ?>;
  background-color:<?php print $foreground_reverse ? $foreground_b10 : $foreground_w10 ?>;
  }

span.data-node-label a,
#canvas div.utility-block,
#canvas div.utility-block h2.block-title,
div.utility-block div.form-item label {
  color:<?php print $foreground_text; ?>
  }

div.utility-block input.form-submit {
  background-color:<?php print $foreground_reverse ? $foreground_b50 : $foreground_w50 ?>;
  color:<?php print $foreground_text ?>;
  }

</style>

<?php endif; ?>

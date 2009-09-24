<?php if ($background && $foreground): ?>

<style type='text/css'>
/**
 * Background color ===================================================
 */
#branding {
  background-color:<?php print $background ?>;
  border-color:<?php print $background_b10; ?>
  }

#branding a { color:<?php print $background_text ?>; }

#branding div.help-link a,
#branding div.admin-link a,
#branding ul.links a:hover {
  background-color:<?php print $background_reverse ? $background_b25 : $background_w25 ?>;
  color:<?php print $background_text ?>;
  }

#branding ul.links li.active a,
#branding ul.links li a.active,
body.help #branding a.help-link {
  background-color:<?php print $background_text ?>;
  color:<?php print $background_reverse ? $background_b25 : $background_w25 ?>;
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
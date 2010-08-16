<?php
$header_reverse = (designkit_colorhsl($header_color, 'l') > .75);
$background_reverse = (designkit_colorhsl($background_color, 'l') < .5);
?>

<style type='text/css'>

/**
 * Logo ===============================================================
 */
a.logo {
  background-image:url("<?php print $logo ?>");
  }

/**
 * Background =========================================================
 */
body {
  background-color:<?php print $background_color ?>;
  <?php if ($wallpaper): ?>
    background-image: url("<?php print $wallpaper?>");
  <?php endif; ?>
  }

#footer,
#footer a { color:<?php print $background_reverse ? '#fff' : '#666' ?>; }

.growl .messages,
.sidebar .block,
.page-content .node,
.page-content .block,
.page-content div.pager,
.page-content .view-content .item-list,
.page-content .view-content table,
.page-content > form {
  -moz-box-shadow:<?php print designkit_colorshift($background_color, '#000000', .25) ?> 0px 0px 3px;
  -webkit-box-shadow:<?php print designkit_colorshift($background_color, '#000000', .25) ?> 0px 1px 3px;
  }

/**
 * Header =============================================================
 */
#branding {
  background-color:<?php print $header_color ?>;
  border-color:<?php print designkit_colorshift($header_color, '#000000', .1); ?>;
  }

#branding a { color: <?php print $header_reverse ? '#000': '#fff'; ?>; }

#branding div.help-link a,
#branding div.admin-link a,
#branding ul.links a:hover {
  background-color:<?php print $header_reverse ? designkit_colorshift($header_color, '#ffffff', .25) : designkit_colorshift($header_color, '#000000', .25) ?>;
  color: <?php print $header_reverse ? '#000': '#fff'; ?>;
  }

#branding ul.links li.active a,
#branding ul.links li a.active,
body.help #branding a.help-link {
  background-color: <?php print $header_reverse ? '#000': '#fff'; ?>;
  color:<?php print $header_reverse ? designkit_colorshift($header_color, '#ffffff', .25) : designkit_colorshift($header_color, '#000000', .25) ?>;
  }

/**
 * Foreground color ===================================================
 */
div.pager li.pager-current {
  background-color:<?php print $foreground_color ?>;
  color:#fff;
  }

div.pager li a:hover {
  background-color:<?php print designkit_colorshift($foreground_color, '#ffffff', .5) ?>;
  color:#fff;
  }

div.item-list h3 { color:<?php print $foreground_color ?>; }

div.data-taxonomy-tags ul.data-taxonomy-tags li a,
div.sidebar div.view-mn-search-saved div.views-field-title a,
#canvas div.utility-block {
  background-color:<?php print designkit_colorshift($foreground_color, '#ffffff', .85) ?>;
  border-color:<?php print designkit_colorshift($foreground_color, '#ffffff', .75) ?>;
  color: <?php print designkit_colorshift($foreground_color, '#333333', .5) ?>;
  }

#canvas div.utility-block,
#canvas div.utility-block h2.block-title,
#canvas div.utility-block div.form-item label { color: <?php print designkit_colorshift($foreground_color, '#333333', .5) ?>; }

#canvas div.utility-block input.form-submit {
  background-color:<?php print $foreground_color ?>;
  border-color:<?php print designkit_colorshift($foreground_color, '#000000', .1) ?>;
  color:#fff;
  }

/**
 * Map color ==========================================================
 */
div.openlayers-views-map,
div.openlayers-views-map div.openlayers-map {
  background-color:<?php print $map_color ?>;
}

</style>

<!DOCTYPE html>
<html lang="<?php print $language->language; ?>">
  <head>
    <?php print $head; ?>
    <title><?php print $head_title; ?></title>
    <?php print $styles; ?>
    <?php print $scripts; ?>
    <!--[if (gte IE 6)&(lte IE 8)]>
      <script src="<?php print $base_path . $path_to_vrinda; ?>/js/selectivizr-min.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
      <script src="<?php print $base_path . $path_to_vrinda; ?>/js/html5-respond.js"></script>
    <![endif]-->
  </head>
  <body class="<?php print $classes; ?>" <?php print $attributes;?>>
    <?php print $page_top; ?>
    <?php print $page; ?>
    <?php print $page_bottom; ?>
  </body>
</html>
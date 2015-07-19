<div id="page-wrapper"><div id="page">

  <?php if ($page['top_bar_left'] || $page['top_bar_right']): ?>
    <div id="top-bar" class="clearfix"><div class="region-wrapper"><div class="region-wrapper-inner">

        <?php if ($page['top_bar_left']): ?>
          <div id="top-bar-left" class="top-bar-region"><div class="region-padding">
            <?php print render($page['top_bar_left']); ?>
          </div></div> <!-- /.region-padding /#top-bar-left -->
        <?php endif; ?>

        <?php if ($page['top_bar_right']): ?>
          <div id="top-bar-right" class="top-bar-region"><div class="region-padding">
            <?php print render($page['top_bar_right']); ?>
          </div></div> <!-- /.region-padding /#top-bar-right -->
        <?php endif; ?>

    </div></div></div> <!-- /.region-padding /.region-wrapper-inner, /.region-wrapper /#top-bar -->
  <?php endif; ?>


  <header id="header" class="clearfix"><div class="region-wrapper"><div class="region-wrapper-inner"><div class="region-padding">

    <?php if ($logo): ?>
      <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
        <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
      </a>
    <?php endif; ?>

    <?php if ($site_name || $site_slogan): ?>
      <div id="name-and-slogan">

        <?php if ($site_name): ?>
          <?php if ($title): ?>
            <div id="site-name">
              <strong>
                <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
              </strong>
            </div>
          <?php else: /* Use h1 when the content title is empty */ ?>
            <h1 id="site-name">
              <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
            </h1>
          <?php endif; ?>
        <?php endif; ?>

        <?php if ($site_slogan): ?>
          <div id="site-slogan">
            <?php print $site_slogan; ?>
          </div>
        <?php endif; ?>

      </div> <!-- /#name-and-slogan -->
    <?php endif; ?>

    <?php print render($page['header']); ?>

    <?php if ($main_menu): ?>
      <nav id="navigation" class="navigation" role="navigation"><div id="main-menu">
        <?php if (module_exists('i18n_menu')) {
            $main_menu_tree = i18n_menu_translated_tree(variable_get('menu_main_links_source', 'main-menu'));
          } else {
            $main_menu_tree = menu_tree(variable_get('menu_main_links_source', 'main-menu'));
          }
          print drupal_render($main_menu_tree); ?>
      </div></nav> <!-- /#main-menu -->
    <?php endif; ?>

  </div></div></div></header> <!-- /.region-padding /.region-wrapper-inner, /.region-wrapper /#header -->


  <?php if ($page['banner_area']): ?>
    <div id="banner-area" class="clearfix" role="banner">
      <?php print render($page['banner_area']); ?>
    </div> <!--  /#banner-area -->
  <?php endif; ?>


  <?php if ($title): ?>
    <div id="page-title-wrapper" class="clearfix" role="title"><div class="region-wrapper"><div class="region-wrapper-inner"><div class="region-padding">
      <?php if ($breadcrumb): ?>
        <div id="breadcrumb"><?php print $breadcrumb; ?></div>
      <?php endif; ?>

      <?php print render($title_prefix); ?>
        <h1 class="title" id="page-title">
          <?php print $title; ?>
        </h1>
      <?php print render($title_suffix); ?>
      
    </div></div></div></div> <!-- /.region-padding /.region-wrapper-inner, /.region-wrapper /#page-title-wrapper -->
  <?php endif; ?>


  <?php if ($page['featured']): ?>
    <aside id="featured" class="fullbg clearfix"><div class="region-wrapper"><div class="region-wrapper-inner"><div class="region-padding">
      <?php print render($page['featured']); ?>
    </div></div></div></aside> <!-- /.region-padding /.region-wrapper-inner, /.region-wrapper /#featured -->
  <?php endif; ?>


  <?php if ($messages): ?>
    <div id="messages"><div class="region-wrapper"><div class="region-wrapper-inner"><div class="region-padding">
      <?php print $messages; ?>
    </div></div></div></div> <!-- /.region-padding /.region-wrapper-inner, /.region-wrapper /#messages -->
  <?php endif; ?>


  <div id="main-wrapper" class="clearfix"><div id="main" class="clearfix"><div class="region-wrapper"><div class="region-wrapper-inner">

    <main id="content" class="column" role="main"><section class="region-padding">
      <?php if ($page['highlighted']): ?><div id="highlighted"><?php print render($page['highlighted']); ?></div><?php endif; ?>
      <a id="main-content"></a>
      <?php if (render($tabs)): ?>
        <nav class="tabs" role="navigation">
          <?php print render($tabs); ?>
        </nav>
      <?php endif; ?>
      <?php print render($page['help']); ?>
      <?php if ($action_links): ?>
        <ul class="action-links">
          <?php print render($action_links); ?>
        </ul>
      <?php endif; ?>
      <?php print render($page['content']); ?>
      <?php print $feed_icons; ?>

    </section></main> <!-- /.region-padding, /#content -->

    <?php if ($page['sidebar_first']): ?>
      <div id="sidebar-first" class="column sidebar"><aside class="region-padding">
        <?php print render($page['sidebar_first']); ?>
      </aside></div> <!-- /.region-padding, /#sidebar-first -->
    <?php endif; ?>
    
    <?php if ($page['sidebar_second']): ?>
      <div id="sidebar-second" class="column sidebar"><aside class="region-padding">
        <?php print render($page['sidebar_second']); ?>
      </aside></div> <!-- /.region-padding /#sidebar-second -->
    <?php endif; ?>

  </div></div></div></div> <!-- /.region-wrapper-inner /.region-wrapper, /#main, /#main-wrapper -->


  <?php if ($page['block_with_full_bg_wrapper']): ?>
    <div id="block-with-full-bg-wrapper" class="clearfix">
      <?php print render($page['block_with_full_bg_wrapper']); ?>
    </div> <!-- /#block-with-full-bg-wrapper -->
  <?php endif; ?>


  <?php if ($page['postscript_bottom']): ?>
    <div id="postscript-bottom" class="clearfix"><div class="region-wrapper"><div class="region-wrapper-inner"><div class="region-padding">
      <?php print render($page['postscript_bottom']); ?>
    </div></div></div></div> <!-- /.region-padding /.region-wrapper-inner /.region-wrapper /#postscript-bottom -->
  <?php endif; ?>


  <?php if ($page['footer_firstcolumn'] || $page['footer_secondcolumn'] || $page['footer_thirdcolumn'] || $page['footer_fourcolumn']): ?>
    <div id="footer-columns-wrapper" class="clearfix"><div class="region-wrapper"><div class="region-wrapper-inner">
      <div id="footer-columns" class="clearfix">

        <?php if ($page['footer_firstcolumn']): ?>
          <div id="footer-firstcolumn" class="footer-columns-region"><div class="region-padding">
            <?php print render($page['footer_firstcolumn']); ?>
          </div></div> <!-- /.region-padding /#footer-firstcolumn -->
        <?php endif; ?>

        <?php if ($page['footer_secondcolumn']): ?>
          <div id="footer-secondcolumn" class="footer-columns-region"><div class="region-padding">
            <?php print render($page['footer_secondcolumn']); ?>
          </div></div> <!-- /.region-padding /#footer-secondcolumn -->
        <?php endif; ?>

        <?php if ($page['footer_thirdcolumn']): ?>
          <div id="footer-thirdcolumn" class="footer-columns-region"><div class="region-padding">
            <?php print render($page['footer_thirdcolumn']); ?>
          </div></div> <!-- /.region-padding /#footer-thirdcolumn -->
        <?php endif; ?>

        <?php if ($page['footer_fourcolumn']): ?>
          <div id="footer-fourcolumn" class="footer-columns-region"><div class="region-padding">
            <?php print render($page['footer_fourcolumn']); ?>
          </div></div> <!-- /.region-padding /#footer-fourcolumn -->
        <?php endif; ?>

      </div> <!-- /#footer-columns -->
    </div></div></div> <!-- /.region-wrapper-inner /.region-wrapper /#footer-columns-wrapper -->
  <?php endif; ?>


  <?php if ($page['footer_left'] || $page['footer_right']): ?>
    <div id="footer-wrapper" class="clearfix"><div class="region-wrapper" role="contentinfo"><div class="region-wrapper-inner">
      <div id="footer" role="contentinfo" class="clearfix">

        <?php if ($page['footer_left']): ?>
          <div id="footer-left" class="footer-region"><div class="region-padding">
            <?php print render($page['footer_left']); ?>
          </div></div> <!-- /.region-padding /#footer-left -->
        <?php endif; ?>

        <?php if ($page['footer_right']): ?>
          <div id="footer-right" class="footer-region"><div class="region-padding">
            <?php print render($page['footer_right']); ?>
          </div></div> <!-- /.region-padding /#footer-right -->
        <?php endif; ?>

      </div> <!-- /#footer -->
    </div></div></div> <!-- /.region-padding /.region-wrapper-inner, /.region-wrapper /#footer-wrapper -->
  <?php endif; ?>

</div></div> <!-- /#page, /#page-wrapper -->

<div id="page-wrapper"><div id="page">

  <?php if ($page['top_bar_left'] || $page['top_bar_right']): ?>
    <div id="top-bar" class="clearfix"><div class="region-wrapper"><div class="region-wrapper-inner"><div class="region-padding">
      <?php print render($page['top_bar_left']); ?>
      <?php print render($page['top_bar_right']); ?>
    </div></div></div></div> <!-- /.region-padding /.region-wrapper-inner, /.region-wrapper /#top-bar -->
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


  <?php if ($title): ?>
    <div id="page-title-wrapper" class="clearfix" role="title"><div class="region-wrapper"><div class="region-wrapper-inner"><div class="region-padding">
      <h1 class="title" id="page-title">
        <?php print $title; ?>
      </h1>
    </div></div></div></div> <!-- /.region-padding /.region-wrapper-inner, /.region-wrapper /#page-title-wrapper -->
  <?php endif; ?>

  <?php if ($messages): ?>
    <div id="messages"><div class="region-wrapper"><div class="region-wrapper-inner"><div class="region-padding">
      <?php print $messages; ?>
    </div></div></div></div> <!-- /.region-padding /.region-wrapper-inner, /.region-wrapper /#messages -->
  <?php endif; ?>

  <div id="main-wrapper" class="clearfix"><div id="main" class="clearfix"><div class="region-wrapper"><div class="region-wrapper-inner">

    <main id="content" class="column" role="main"><section class="region-padding">

    <div id="accessdenied" class="columns">
	  <div class="accessdenied-message col-one-half">
	  <h2 class="accessdenied-title">403!</h2>
      <p>Sorry, Access is Restricted!<br /> Please login to Access this page.</p>
	  </div>
      <div class="accessdenied-login-page col-one-half"><h2 class="title">Login</h2><?php $login = drupal_get_form('user_login'); print drupal_render($login); ?> </div>
	</div>

    </section></main> <!-- /.region-padding, /#content -->
  </div></div></div></div> <!-- /.region-wrapper-inner /.region-wrapper, /#main, /#main-wrapper -->


  <?php if ($page['footer_firstcolumn'] || $page['footer_secondcolumn'] || $page['footer_thirdcolumn']): ?>
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

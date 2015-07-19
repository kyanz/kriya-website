<div id="page">

  <?php if ($page['featured']): ?>
    <aside id="featured"><div class="section clearfix">
      <?php print render($page['featured']); ?>
    </div></aside> <!-- /.section, /#featured -->
  <?php endif; ?>

  <?php if ($messages): ?>
    <div id="messages"><div class="section clearfix">
      <?php print $messages; ?>
    </div></div> <!-- /.section, /#messages -->
  <?php endif; ?>

  <div id="main-wrapper" class="clearfix"><div id="main" class="clearfix">

    <main id="content" class="column" role="main"><section class="section">
      <?php if ($page['highlighted']): ?><div id="highlighted"><?php print render($page['highlighted']); ?></div><?php endif; ?>
      <a id="main-content"></a>
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h1 class="title" id="page-title">
          <?php print $title; ?>
        </h1>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
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

    </section></main> <!-- /.section, /#content -->

    <?php if ($page['sidebar_first']): ?>
      <div id="sidebar-first" class="column sidebar"><aside class="section">
        <?php print render($page['sidebar_first']); ?>
      </aside></div> <!-- /.section, /#sidebar-first -->
    <?php endif; ?>
    
    <?php if ($page['sidebar_second']): ?>
      <div id="sidebar-second" class="column sidebar"><aside class="section">
        <?php print render($page['sidebar_second']); ?>
      </aside></div> <!-- /.section, /#sidebar-second -->
    <?php endif; ?>

  </div></div> <!-- /#main, /#main-wrapper -->

  <?php if ($page['postscript_bottom']): ?>
    <div id="postscript-bottom"><div class="section clearfix">
      <?php print render($page['postscript_bottom']); ?>
    </div></div> <!-- /.section, /#postscript-bottom -->
  <?php endif; ?>

</div> <!-- /#page -->

<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?> role="article">

  <?php if($title_prefix || $title_suffix || $title): ?>
    <header>
      <?php print render($title_prefix); ?>
      <?php if (!$page): ?>
        <h2<?php print $title_attributes; ?>>
          <a href="<?php print $node_url; ?>"><?php print $title; ?></a>
        </h2>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
    </header>
  <?php endif; ?>

      <?php if ($display_submitted): ?>
        <p class="submitted-detail">
          <span>Posted By: <?php print $name; ?></span>
          <span> &nbsp;&nbsp;|&nbsp;&nbsp;<i class="fa fa-calendar-o"></i> <?php print $date; ?></span>
          <span><?php print '&nbsp;&nbsp;|&nbsp;&nbsp; <a href="'.$node_url.'#comments">'.$comment_count.' <i class="fa fa-comment-o"></i></a>'; ?></span>
        </p>
      <?php endif; ?>

  <div class="content clearfix"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
    ?>

    <?php if (!$page): ?>
      <?php print render($content); ?>
    <?php else: ?>
      <div class="columns">
        <div class="col-one-third"><?php print render($content['field_photo']); ?></div>
        <div class="col-two-third">
        <h2><?php print $title; ?></h2>
        <?php print render($content); ?>
        </div>
      </div>
    <?php endif; ?>
 
  </div>

  <?php
    // Remove the "Add new comment" link on the teaser page or if the comment
    // form is being displayed on the same page.
    if ($teaser || !empty($content['comments']['comment_form'])) {
      //unset($content['links']['comment']['#links']);
      $links = render($content['links']['node']);
    }
    else {
      $links = render($content['links']['comment']);
    }
  ?>

  <?php if ($links):  ?>
    <footer class="link-wrapper">
    <?php print $links; ?>
    </footer>
  <?php endif; ?>

  <?php print render($content['comments']); ?>

</article>
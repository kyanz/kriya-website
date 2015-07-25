<?php

/**
 * Add body classes if certain regions have content.
 */
function vrinda_preprocess_html(&$variables) {
  // Add variables for path to theme.
  $variables['base_path'] = base_path();
  $variables['path_to_vrinda'] = drupal_get_path('theme', 'vrinda');

  if (theme_get_setting('layout-style-type')== 'Boxed') {
    $variables['classes_array'][] = 'layout-boxed';
  }
  if (theme_get_setting('sidebar-style-type')== 'layout2') {
    $variables['classes_array'][] = 'both-sidebar-left';
  }
  if (theme_get_setting('sidebar-style-type')== 'layout3') {
    $variables['classes_array'][] = 'both-sidebar-right';
  }
  if (!empty($variables['page']['featured'])) {
    $variables['classes_array'][] = 'featured';
  }
  if (!empty($variables['page']['banner_area'])) {
    $variables['classes_array'][] = 'banner-area';
  }
  if (!empty($variables['page']['postscript_bottom'])) {
    $variables['classes_array'][] = 'postscript-bottom';
  }
  if (!empty($variables['page']['footer_firstcolumn'])
    || !empty($variables['page']['footer_secondcolumn'])
    || !empty($variables['page']['footer_thirdcolumn'])
    || !empty($variables['page']['footer_fourthcolumn'])) {
    $variables['classes_array'][] = 'footer-columns';
  }

  drupal_add_css(drupal_get_path('theme', 'vrinda') . '/css/lte-ie9.css', array(
    'group' => CSS_THEME,
    'browsers' => array(
      'IE' => 'lte IE 9',
      '!IE' => FALSE
      ),
    'preprocess' => FALSE
  ));
}

/**
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return a string containing the breadcrumb output.
 */
function vrinda_breadcrumb($variables) {
  $breadcrumb = $variables['breadcrumb'];
  $breadcrumb_separator = theme_get_setting('breadcrumb-separator','vrinda');

  if (!empty($breadcrumb) && theme_get_setting('breadcrumb-display')) {
    // Use CSS to hide titile .element-invisible.
    $output = '<h3 class="element-invisible">' . t('You are here') . '</h3>';

    if (theme_get_setting('breadcrumb-title') == 1) { // show the title setting
      $breadcrumb[] = truncate_utf8(drupal_get_title(), theme_get_setting('breadcrumb-title-length'), $wordsafe = TRUE, $dots = TRUE);
    }

    $output .= '<div class="breadcrumb">' . implode(' <span class="breadcrumb-separator">' . $breadcrumb_separator . '</span> ', $breadcrumb) . '</div>';
    return $output;
  }

}

/**
 * Implements hook_html_head_alter().
 * This will overwrite the default meta character type tag with HTML5 version.
 */
function vrinda_html_head_alter(&$head_elements) {
  $head_elements['system_meta_content_type']['#attributes'] = array(
    'charset' => 'utf-8'
  );
}

function vrinda_page_alter($page) {
  // <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
  $viewport = array(
    '#type' => 'html_tag',
    '#tag' => 'meta',
    '#attributes' => array(
    'name' =>  'viewport',
    'content' =>  'width=device-width, initial-scale=1, maximum-scale=1'
    )
  );
  drupal_add_html_head($viewport, 'viewport');

  // Look in each visible region for blocks.
  foreach (system_region_list($GLOBALS['theme'], REGIONS_VISIBLE) as $region => $name) {
    if (!empty($page[$region])) {
      // Find the last block in the region.
      $blocks = array_reverse(element_children($page[$region]));
      while ($blocks && !isset($page[$region][$blocks[0]]['#block'])) {
        array_shift($blocks);
      }
      if ($blocks) {
        $page[$region][$blocks[0]]['#block']->last_in_region = TRUE;
      }
    }
  }
}

/**
 * Override or insert variables into the page template.
 */
function vrinda_preprocess_page(&$vars) {
  if (isset($vars['main_menu'])) {
    $vars['main_menu'] = theme('links__system_main_menu', array(
      'links' => $vars['main_menu'],
      'attributes' => array(
        'id' => 'main-menu-links',
        'class' => array('links', 'menu', 'clearfix'),
      ),
      'heading' => array(
        'text' => t('Main menu'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
    ));
  }
  else {
    $vars['main_menu'] = FALSE;
  }
  if (isset($vars['secondary_menu'])) {
    $vars['secondary_menu'] = theme('links__system_secondary_menu', array(
      'links' => $vars['secondary_menu'],
      'attributes' => array(
        'id' => 'secondary-menu-links',
        'class' => array('links', 'inline', 'clearfix'),
      ),
      'heading' => array(
        'text' => t('Secondary menu'),
        'level' => 'h2',
        'class' => array('element-invisible'),
      )
    ));
  }
  else {
    $vars['secondary_menu'] = FALSE;
  }

  $status = drupal_get_http_header("status");
  if($status == "403 Forbidden") {
    $vars['theme_hook_suggestions'][] = 'page__403';
  }
  if($status == "404 Not Found") {
    $vars['theme_hook_suggestions'][] = 'page__404';
  }

  // Add layout specific CSS to the page.
  vrinda_add_layout_css($vars);
}

/**
 * Override or insert variables into the page template for HTML output.
 */
function vrinda_process_html(&$variables) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_html_alter($variables);
  }
}

/**
 * Override or insert variables into the page template.
 */
function vrinda_process_page(&$variables) {
  // Hook into color.module.
  if (module_exists('color')) {
    _color_page_alter($variables);
  }
  // Since the title and the shortcut link are both block level elements,
  // positioning them next to each other is much simpler with a wrapper div.
  if (!empty($variables['title_suffix']['add_or_remove_shortcut']) && $variables['title']) {
    // Add a wrapper div using the title_prefix and title_suffix render elements.
    $variables['title_prefix']['shortcut_wrapper'] = array(
      '#markup' => '<div class="shortcut-wrapper clearfix">',
      '#weight' => 100,
    );
    $variables['title_suffix']['shortcut_wrapper'] = array(
      '#markup' => '</div>',
      '#weight' => -99,
    );
    // Make sure the shortcut link is the first item in title_suffix.
    $variables['title_suffix']['add_or_remove_shortcut']['#weight'] = -100;
  }
}

/**
 * Implements hook_preprocess_maintenance_page().
 */
function vrinda_preprocess_maintenance_page(&$variables) {
  // By default, site_name is set to Drupal if no db connection is available
  // or during site installation. Setting site_name to an empty string makes
  // the site and update pages look cleaner.
  // @see template_preprocess_maintenance_page
  if (!$variables['db_is_active']) {
    $variables['site_name'] = '';
  }
  drupal_add_css(drupal_get_path('theme', 'vrinda') . '/css/maintenance-page.css');
}

/**
 * Override or insert variables into the node template.
 */
function vrinda_preprocess_node(&$variables) {
  if ($variables['view_mode'] == 'full' && node_is_page($variables['node'])) {
    $variables['classes_array'][] = 'node-full';
  }
}

/**
 * Override or insert variables into the block template.
 */
function vrinda_preprocess_block(&$variables) {

  // Classes describing the position of the block within the region.
  if ($variables['block_id'] == 1) {
    $variables['classes_array'][] = 'first';
  }
  // The last_in_region property is set in vrinda_page_alter().
  if (isset($variables['block']->last_in_region)) {
    $variables['classes_array'][] = 'last';
  }

  $variables['classes_array'][] = $variables['block_zebra'];

  $variables['title_attributes_array']['class'][] = 'block-title';

  // In the header region visually hide block titles.
  if ($variables['block']->region == 'header' || $variables['block']->region == 'top_bar_left' || $variables['block']->region == 'top_bar_right'  || $variables['block']->region == 'footer_left' || $variables['block']->region == 'footer_right') {
    $variables['title_attributes_array']['class'][] = 'element-invisible';
  }
}

/**
 * Override or insert variables into the views template.
 */
function vrinda_preprocess_views_view(&$variables) {

  $view = $variables['view'];
  $view_total = count($view->result);

  if($view_total%2==0) { $view_total_class = 'view-total-even';}
  else { $view_total_class = 'view-total-odd';}

  if ($view) {
    $variables['classes_array'][] = $view_total_class;
  }
}

/**
 * Override variables into the uc cart block content .
 */
function vrinda_uc_cart_block_content($variables) {
  $help_text = $variables['help_text'];
  $items = $variables['items'];
  $item_count = $variables['item_count'];
  $item_text = $variables['item_text'];
  $total = $variables['total'];
  $summary_links = $variables['summary_links'];
  $collapsed = $variables['collapsed'];
  $output = '';

  $cart_item_cost = $item_text . ' / ' . theme('uc_price', array('price' => $total));

  $summary_links['cart-item-cost'] = array(
    'title' => $cart_item_cost,
    'html' => TRUE,
  );

  $output .=  theme('links', array('links' => $summary_links));

  return $output;
}


/**
 * Implements theme_menu_tree().
 */
function vrinda_menu_tree($variables) {
  return '<ul class="menu clearfix">' . $variables['tree'] . '</ul>';
}

/**
 * Implements theme_field__field_type().
 */
function vrinda_field__taxonomy_term_reference($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<h3 class="field-label">' . $variables['label'] . ': </h3>';
  }

  // Render the items.
  $output .= ($variables['element']['#label_display'] == 'inline') ? '<ul class="links inline">' : '<ul class="links">';
  foreach ($variables['items'] as $delta => $item) {
    $output .= '<li class="taxonomy-term-reference-' . $delta . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</li>';
  }
  $output .= '</ul>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . (!in_array('clearfix', $variables['classes_array']) ? ' clearfix' : '') . '"' . $variables['attributes'] .'>' . $output . '</div>';

  return $output;
}

function vrinda_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'search_block_form') {
    $form['search_block_form']['#title'] = t('Search'); // Change the text on the label element
    $form['search_block_form']['#title_display'] = 'invisible'; // Toggle label visibilty
    $form['search_block_form']['#size'] = 25;  // define size of the textfield
    $form['search_block_form']['#default_value'] = t('Search'); // Set a default value for the textfield
    $form['actions']['submit']['#value'] = t('GO!'); // Change the text on the submit button

    // Add extra attributes to the text box
    $form['search_block_form']['#attributes']['onblur'] = "if (this.value == '') {this.value = 'Search';}";
    $form['search_block_form']['#attributes']['onfocus'] = "if (this.value == 'Search') {this.value = '';}";
    // Prevent user from searching the default text
    $form['#attributes']['onsubmit'] = "if(this.search_block_form.value=='Search'){ alert('Please enter a search'); return false; }";

    // Alternative (HTML5) placeholder attribute instead of using the javascript
    $form['search_block_form']['#attributes']['placeholder'] = t('Search');
  }
}

/**
 * Add dimensions and other layout specific styles.
 */
function vrinda_add_layout_css(&$variables) {
  $layout_style_type = theme_get_setting('layout-style-type','vrinda');
  $body_background_color = theme_get_setting('body-background-color','vrinda');
  $body_background_patterns = variable_get('body-background-patterns-selected');
  $font_families = theme_get_setting('base-font-type','vrinda');
  $websafe_font_families = variable_get('websafe-fonts-selected');
  $google_font_families = theme_get_setting('google-font','vrinda');
  $custom_font_stack_families = theme_get_setting('custom-font-stack','vrinda');

  $font_families_heading = theme_get_setting('base-font-type-heading','vrinda');
  $websafe_font_families_heading = variable_get('websafe-fonts-selected-heading');
  $google_font_families_heading = theme_get_setting('google-font-heading','vrinda');
  $custom_font_stack_families_heading = theme_get_setting('custom-font-stack-heading','vrinda');


  if (!empty($body_background_patterns) && $body_background_patterns != 'none') {
    preg_match('/src="([^"]*)"/i', $body_background_patterns, $body_background_patterns_path);
    $body_background_patterns = 'url('. $body_background_patterns_path[1] .') repeat center;';
  }

  if($layout_style_type == 'Boxed') {
    $css_data = 'body {background: '. $body_background_color .' '.$body_background_patterns .'}';
    drupal_add_css($css_data, array('type' => 'inline', 'preprocess' => FALSE, 'weight' => '999', 'group' => CSS_THEME,));
  }

/* --- body fonts ---*/
  if (!empty($websafe_font_families) && $font_families == 'wsf') {
    $websafe_font_data = 'body {font-family: '. $websafe_font_families .';}';

    drupal_add_css($websafe_font_data, array('type' => 'inline', 'preprocess' => FALSE, 'weight' => '999', 'group' => CSS_THEME,));
  }

  if (!empty($google_font_families) && $font_families == 'gwf') {
    $google_fonts = preg_replace('/[^\w\d_ -]/si', '', $google_font_families);
    $google_fonts_data = 'body {font-family: '. $google_fonts .';}';

    drupal_add_css('http://fonts.googleapis.com/css?family=' . str_replace(' ', '+', $google_font_families), 
    array('type' => 'external', 'preprocess' => FALSE, 'weight' => -1, 'group' => CSS_THEME,));
    drupal_add_css($google_fonts_data, array('type' => 'inline', 'preprocess' => FALSE, 'weight' => '999', 'group' => CSS_THEME,));
  }

  if (!empty($custom_font_stack_families) && $font_families == 'cfs') {
    $custom_font_stack_data = 'body {font-family: '. $custom_font_stack_families .';}';

    drupal_add_css($custom_font_stack_data, array('type' => 'inline', 'preprocess' => FALSE, 'weight' => '999', 'group' => CSS_THEME,));
  }

/* --- heading fonts ----*/
  if (!empty($websafe_font_families_heading) && $font_families_heading == 'wsf') {
    $websafe_font_data = 'h1, h2, h3, h4, h5, h6 {font-family: '. $websafe_font_families_heading .';}';

    drupal_add_css($websafe_font_data, array('type' => 'inline', 'preprocess' => FALSE, 'weight' => '999', 'group' => CSS_THEME,));
  }

  if (!empty($google_font_families_heading) && $font_families_heading == 'gwf') {
    $google_fonts = preg_replace('/[^\w\d_ -]/si', '', $google_font_families_heading);
    $google_fonts_data = 'h1, h2, h3, h4, h5, h6 {font-family: '. $google_fonts .';}';

    drupal_add_css('http://fonts.googleapis.com/css?family=' . str_replace(' ', '+', $google_font_families_heading), 
    array('type' => 'external', 'preprocess' => FALSE, 'weight' => -1, 'group' => CSS_THEME,));
    drupal_add_css($google_fonts_data, array('type' => 'inline', 'preprocess' => FALSE, 'weight' => '999', 'group' => CSS_THEME,));
  }

  if (!empty($custom_font_stack_families_heading) && $font_families_heading == 'cfs') {
    $custom_font_stack_data = 'h1, h2, h3, h4, h5, h6 {font-family: '. $custom_font_stack_families_heading .';}';

    drupal_add_css($custom_font_stack_data, array('type' => 'inline', 'preprocess' => FALSE, 'weight' => '999', 'group' => CSS_THEME,));
  }
}

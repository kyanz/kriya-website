<?php // $Id$

/**
 * Advanced theme settings.
 */
function vrinda_form_system_theme_settings_alter(&$form, $form_state) {

  $theme_key = $form_state['build_info']['args'][0];
  
  $form['vrinda-settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('vrinda Theme Settings'),
    '#collapsible' => FALSE,
	'#collapsed' => FALSE,
  );

  $form['vrinda-settings']['vertical-tabs'] = array(
    '#type' => 'vertical_tabs',
	'#attached' => array(
      'css' => array(drupal_get_path('theme', $theme_key) . '/css/vrinda-settings-form.css'),
    ),
  );

/**
 * Layout settings.
 */
  $form['vrinda-settings']['vertical-tabs']['layout-style'] = array(
    '#type' => 'fieldset',
    '#title' => t('Layout'),
    '#weight' => -2,
  );
  $form['vrinda-settings']['vertical-tabs']['layout-style']['layout-style-type'] = array(
    '#type' => 'select',
    '#title' => t('Choose layout style'),
    '#options' => array(
      "Wide" => t('Wide'),
      "Boxed" => t('Boxed'),
    ),
    '#default_value' => theme_get_setting('layout-style-type', $theme_key),
  );

/**
 * background color settings.
 */
  $form['vrinda-settings']['vertical-tabs']['layout-style']['body-background-color'] = array(
    '#type' => 'textfield',
    '#title' => t('Background Color'),
    '#size' => 10,
    '#maxlenght' => 4,
    '#description' => t('Select background color'),
    '#default_value' => check_plain(theme_get_setting('body-background-color', $theme_key)),
    '#states' => array(
      'visible' => array('select[name="layout-style-type"]' => array('value' => 'Boxed')),
      'required' => array('select[name="layout-style-type"]' => array('value' => 'Boxed')),
    ),
    '#prefix' => '<div class="body-background-color-palette">',
    '#suffix' => '<div id="colorpicker"></div></div>',
  );
  $form['colorpicker_example'] = array(
    '#type' => 'item',
    '#description' => "<script type='text/javascript'>
    (function($){
      $('#colorpicker').farbtastic('#edit-body-background-color');
    })(jQuery)
    </script>",
  );

/**
 * background patterns settings.
 */
  $form['vrinda-settings']['vertical-tabs']['layout-style']['body-background-patterns'] = array(
    '#type' => 'fieldset',
    '#title' => t('Background Patterns'),
    '#description' => t('Select background patterns'),
    '#default_value' => check_plain(theme_get_setting('body-background-patterns', $theme_key)),
    '#states' => array(
      'visible' => array('select[name="layout-style-type"]' => array('value' => 'Boxed')),
    ),
  );
  // Get background patterns from background patterns array.
  $form['vrinda-settings']['vertical-tabs']['layout-style']['body-background-patterns']['body-background-patterns-list'] = array(
    '#type'    => 'radios',
    '#options' => vrinda_background_patterns_list(),
    '#default_value' => check_plain(theme_get_setting('body-background-patterns-list', $theme_key)),
  );
  // Get selected background patterns.
  $body_background_patterns_list_set = vrinda_background_patterns_list();
  foreach (array_keys($body_background_patterns_list_set) as $i) {
    if ($i == theme_get_setting('body-background-patterns-list', $theme_key)) {
      variable_set('body-background-patterns-selected', $body_background_patterns_list_set[$i]);
    }
  }

/**
 * Sidebar Settings.
 */
  $form['vrinda-settings']['vertical-tabs']['layout-style']['sidebar-style-type'] = array(
    '#title' => t('Choose sidebar positions'),
    '#type' => 'radios',
    '#options' => array(
      "layout1" => t('<strong>Layout #1</strong>') . '<div class="layout-example"><div class="sidebar-first"></div><div class="content-region">1</div><div class="sidebar-second"></div></div>' . t('<p class="layout-type")>Three column layout with sidebar first on the left and sidebar second on right of the main content area.</p>'),
      "layout2" => t('<strong>Layout #2</strong>') . '<div class="layout-example"><div class="sidebar-first"></div><div class="sidebar-second"></div><div class="content-region">2</div></div>' . t('<p class="layout-type")>Three column layout with sidebar first and second on the left of the main content area.</p>'),
      "layout3" => t('<strong>Layout #3</strong>') . '<div class="layout-example"><div class="content-region">3</div><div class="sidebar-first"></div><div class="sidebar-second"></div></div>' . t('<p class="layout-type")>Three column layout with sidebar first and second on the right of the main content area.</p>'),
    ),
    '#default_value' => theme_get_setting('sidebar-style-type', $theme_key),
  );


/**
 * Color palettes settings.
 */
  $form['#process'][] = 'vrinda_make_colorable';

/**
 * Breadcrumb settings.
 */
  $form['vrinda-settings']['vertical-tabs']['breadcrumb'] =  array(
    '#type' => 'fieldset',
    '#title' => t('Breadcrumb'),
  );
  $form['vrinda-settings']['vertical-tabs']['breadcrumb']['breadcrumb-display'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show breadcrumb'),
  	'#description'   => t('Use the checkbox to enable or disable Breadcrumb.'),
	'#default_value' => theme_get_setting('breadcrumb-display', $theme_key),
  );
  $form['vrinda-settings']['vertical-tabs']['breadcrumb']['breadcrumb-separator'] = array(
    '#type' => 'textfield',
    '#title' => t('Breadcrumb separator'),
	'#default_value' => theme_get_setting('breadcrumb-separator', $theme_key),
    '#size' => 5,
    '#maxlength' => 10,
  );
  $form['vrinda-settings']['vertical-tabs']['breadcrumb']['breadcrumb-title'] = array(
    '#type' => 'select',
    '#title' => t('Breadcrumb title'),
    '#description' => t('Do you want to show the title of the page in the breadcrumb?'),
    '#options' => array(
      0 => t('No'),
      1 => t('Yes'),
    ),
    '#default_value' => theme_get_setting('breadcrumb-title', $theme_key),
  );
  $form['vrinda-settings']['vertical-tabs']['breadcrumb']['breadcrumb-title-length'] = array(
    '#type' => 'select',
    '#title' => t('Title length'),
    '#description' => t('The title in the breadcrumb will be truncated after this number of character'),
    '#options' => array(
      10 => 10,
      20 => 20,
      30 => 30,
      40 => 40,
      50 => 50,
      60 => 60,
      70 => 70,
      80 => 90,
      100 => 100,
    ),
    '#default_value' => theme_get_setting('breadcrumb-title-length', $theme_key),
  );


/**
 * Global Font settings
 */
  $form['vrinda-settings']['vertical-tabs']['font'] = array(
    '#type' => 'fieldset',
    '#title' => t('Font settings'),
  );
  $form['vrinda-settings']['vertical-tabs']['font']['base-font'] = array(
    '#type' => 'fieldset',
    '#title' => t('Default font'),
  );
  $form['vrinda-settings']['vertical-tabs']['font']['base-font']['base-font-type'] = array(
    '#type' => 'select',
    '#title' => t('Type'),
    '#options' => array(
      'default' => t('Default'),
      'wsf' => t('Websafe fonts'),
      'gwf' => t('Basic Google font'),
      'cfs' => t('Custom font stack'),
    ),
    '#default_value' => theme_get_setting('base-font-type', $theme_key),
  );
  $form['vrinda-settings']['vertical-tabs']['font']['base-font']['websafe-fonts'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('websafe-fonts', $theme_key),
    '#options' => str_replace("'", "", vrinda_websafe_fonts_list('websafe-fonts')),
    '#states' => array('visible' => array('select[name="base-font-type"]' => array('value' => 'wsf'))),
  );
  // Google web font
  $form['vrinda-settings']['vertical-tabs']['font']['base-font']['google-font'] = array(
    '#type' => 'textfield',
    '#title' => t('Google Font Name'),
    '#default_value' => theme_get_setting('google-font', $theme_key),
    '#description' => t('Paste the Google font name, e.g. Open Sans Condensed. Only add one font. To preview and gather required information for adding Google fonts see: <a href="http://www.google.com/webfonts" target="_blank">google.com/webfonts</a>.'),
    '#states' => array('visible' => array('select[name="base-font-type"]' => array('value' => 'gwf'))),
  );
  // Custom font stacks
  $form['vrinda-settings']['vertical-tabs']['font']['base-font']['custom-font-stack'] = array(
    '#type' => 'textfield',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('custom-font-stack', $theme_key),
    '#description' => t("Enter a comma seperated list of fonts, with no trailing comma. Names with spaces should be wrapped in single quotes, for example 'Times New Roman'."),
    '#states' => array(
      'visible' => array('select[name="base-font-type"]' => array('value' => 'cfs')),
      'required' => array('select[name="base-font-type"]' => array('value' => 'cfs')),
    ),
  );
  // Get selected websafe font.
  $vrinda_websafe_fonts_list_set = vrinda_websafe_fonts_list('websafe-fonts');
  foreach (array_keys($vrinda_websafe_fonts_list_set) as $i) {
    if ($i == theme_get_setting('websafe-fonts', $theme_key)) {
      variable_set('websafe-fonts-selected', $vrinda_websafe_fonts_list_set[$i]);
    }
  }

/**
 * Heading Font settings
 */
  $form['vrinda-settings']['vertical-tabs']['font']['base-font-heading'] = array(
    '#type' => 'fieldset',
    '#title' => t('Heading font (h1,h2,h3,h4,h5,h6)'),
  );
  $form['vrinda-settings']['vertical-tabs']['font']['base-font-heading']['base-font-type-heading'] = array(
    '#type' => 'select',
    '#title' => t('Type'),
    '#options' => array(
      'default' => t('Default'),
      'wsf' => t('Websafe fonts'),
      'gwf' => t('Basic Google font'),
      'cfs' => t('Custom font stack'),
    ),
    '#default_value' => theme_get_setting('base-font-type-heading', $theme_key),
  );
  $form['vrinda-settings']['vertical-tabs']['font']['base-font-heading']['websafe-fonts-heading'] = array(
    '#type' => 'select',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('websafe-fonts-heading', $theme_key),
    '#options' => str_replace("'", "", vrinda_websafe_fonts_list('websafe-fonts')),
    '#states' => array('visible' => array('select[name="base-font-type-heading"]' => array('value' => 'wsf'))),
  );
  // Google web font for heading
  $form['vrinda-settings']['vertical-tabs']['font']['base-font-heading']['google-font-heading'] = array(
    '#type' => 'textfield',
    '#title' => t('Google Font Name'),
    '#default_value' => theme_get_setting('google-font-heading', $theme_key),
    '#description' => t('Paste the Google font name, e.g. Open Sans Condensed. Only add one font. To preview and gather required information for adding Google fonts see: <a href="http://www.google.com/webfonts" target="_blank">google.com/webfonts</a>.'),
    '#states' => array('visible' => array('select[name="base-font-type-heading"]' => array('value' => 'gwf'))),
  );
  // Custom font stacks for heading
  $form['vrinda-settings']['vertical-tabs']['font']['base-font-heading']['custom-font-stack-heading'] = array(
    '#type' => 'textfield',
    '#title' => t('Font'),
    '#default_value' => theme_get_setting('custom-font-stack-heading', $theme_key),
    '#description' => t("Enter a comma seperated list of fonts, with no trailing comma. Names with spaces should be wrapped in single quotes, for example 'Times New Roman'."),
    '#states' => array(
      'visible' => array('select[name="base-font-type-heading"]' => array('value' => 'cfs')),
      'required' => array('select[name="base-font-type-heading"]' => array('value' => 'cfs')),
    ),
  );
  // Get selected websafe font heading.
  $vrinda_websafe_fonts_list_set = vrinda_websafe_fonts_list('websafe-fonts');
  foreach (array_keys($vrinda_websafe_fonts_list_set) as $i) {
    if ($i == theme_get_setting('websafe-fonts-heading', $theme_key)) {
      variable_set('websafe-fonts-selected-heading', $vrinda_websafe_fonts_list_set[$i]);
    }
  }



  drupal_add_js(
    "
    (function ($) {
      Drupal.behaviors.vrindaThemeSettings = {
        attach : function(context, settings) {
          var layout = $('select[name=layout-style-type]').attr('value');
          if (layout == 'Boxed') {
            $('.body-background-color-palette').show();
          }
          else {
            $('.body-background-color-palette').hide();
          }
          $('select[name=layout-style-type]').change(function() {
            var layout1 = $(this).attr('value');
            if (layout1 == 'Boxed') {
              $('.body-background-color-palette').show();
            }
            else {
              $('.body-background-color-palette').hide();
            }
          });
        }
      }
    })(jQuery);
    ",
    "inline"
  );
}

/**
 * Get the color palettes.
 */
function vrinda_make_colorable($form) {
  $vrinda_color = $form['color'];
  $form['color'] ='';

  $form['vrinda-settings']['vertical-tabs']['color-scheme'] = $vrinda_color;
  //$form['color']['#collapsible'] = TRUE;
  //$form['color']['#collapsed'] = TRUE;
  return $form;
}

/**
 * Get the patterns icons array.
 */
function vrinda_background_patterns_list() {
  $vrinda_background_patterns_dir = drupal_get_path('theme', 'vrinda') . '/images/patterns';
  $images = file_scan_directory($vrinda_background_patterns_dir, '/.*\.png$/');
  $vrinda_background_patterns = array();

  $vrinda_background_patterns[0] = 'none';
  foreach ($images as $file) {
    $image_info = array(
      'path' => base_path() . $file->uri,
      'alt' => 'background patterns',
      'title' => 'background patterns',
    );
    $vrinda_background_patterns[] = theme('image', $image_info);
  }
  return $vrinda_background_patterns;
}


/**
 * Websafe fonts list.
 */
function vrinda_websafe_fonts_list($vars) {
  $fonts = array(
    "$vars-1" => t("Arial, Helvetica, sans-serif"),
    "$vars-2" => t("'Arial Black', Gadget, sans-serif"),
    "$vars-3"   => t("'Comic Sans MS', cursive, sans-serif"),
    "$vars-4"  => t("Calibri, Candara, Arial, Helvetica, sans-serif"),
    "$vars-5"   => t("'Courier New', Courier, monospace"),
    "$vars-6"   => t("Georgia, serif"),
    "$vars-7"   => t("Impact, Charcoal, sans-serif"),
    "$vars-8"  => t("'Lucida Console', Monaco, monospace"),
    "$vars-9"  => t("'Lucida Sans Unicode', 'Lucida Grande', sans-serif"),
    "$vars-10"  => t("'Palatino Linotype', 'Book Antiqua', Palatino, serif"),
    "$vars-11"   => t("Tahoma, Geneva, sans-serif"),
    "$vars-12"   => t("'Times New Roman', Times, serif"),
    "$vars-13"   => t("'Trebuchet MS', Helvetica, sans-serif"),
    "$vars-14"  => t("Verdana, Geneva, sans-serif"),
  );

  return $fonts;
}

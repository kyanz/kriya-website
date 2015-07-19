<?php

// Put the logo path into JavaScript for the live preview.
drupal_add_js(array('color' => array('logo' => theme_get_setting('logo', 'vrinda'))), 'setting');

$info = array();

// Define the possible replaceable items and their labels.
$info['fields'] = array(
  'base' => t('Page background'),
  'text' => t('Text color'),
  'link' => t('Link color'),
  'linkhover' => t('Link hover color'),
  'pagetitebg' => t('Page title background'),
  'pagetitecolor' => t('Page title Color'),
  'footercolumnsbg' => t('Footer columns background'),
  'footercolumnscolor' => t('Footer columns text color'),
  'footercolumnslink' => t('Footer columns link color'),
  'footerbg' => t('Footer background'),
);

// Color schemes for the site.
$info['schemes'] = array(
  // Define the default scheme.
  'default' => array(
    // Scheme title.
    'title' => t('Our site default colors'),
    // Scheme colors (Keys are coming from $info['fields']).
    'colors' => array(
      'base' => '#f6f6f6',
      'text' => '#686868',
      'link' => '#444444',
      'linkhover' => '#f65287',
      'pagetitebg' => '#fbc59c',
      'pagetitecolor' => '#f6f6f6',
      'footercolumnsbg' => '#444444',
      'footercolumnscolor' => '#d0d3d4',
      'footercolumnslink' => '#d0d3d4',
      'footerbg' => '#f6f6f6',
    ),
  ),
  'redish' => array(
    // Scheme title.
    'title' => t('Redish'),
    // Scheme colors (Keys are coming from $info['fields']).
    'colors' => array(
      'base' => '#f6f6f6',
      'text' => '#686868',
      'link' => '#444444',
      'linkhover' => '#fe5200',
      'pagetitebg' => '#f29163',
      'pagetitecolor' => '#f6f6f6',
      'footercolumnsbg' => '#444444',
      'footercolumnscolor' => '#d0d3d4',
      'footercolumnslink' => '#d0d3d4',
      'footerbg' => '#f6f6f6',
    ),
  ),
  'greenish' => array(
    // Scheme title.
    'title' => t('Greenish'),
    // Scheme colors (Keys are coming from $info['fields']).
    'colors' => array(
      'base' => '#f6f6f6',
      'text' => '#686868',
      'link' => '#444444',
      'linkhover' => '#65bb2c',
      'pagetitebg' => '#bbdaa6',
      'pagetitecolor' => '#f6f6f6',
      'footercolumnsbg' => '#444444',
      'footercolumnscolor' => '#d0d3d4',
      'footercolumnslink' => '#d0d3d4',
      'footerbg' => '#f6f6f6',
    ),
  ),
  'blueish' => array(
    // Scheme title.
    'title' => t('Blueish'),
    // Scheme colors (Keys are coming from $info['fields']).
    'colors' => array(
      'base' => '#f6f6f6',
      'text' => '#686868',
      'link' => '#444444',
      'linkhover' => '#095d79',
      'pagetitebg' => '#7fb8cb',
      'pagetitecolor' => '#f6f6f6',
      'footercolumnsbg' => '#444444',
      'footercolumnscolor' => '#d0d3d4',
      'footercolumnslink' => '#d0d3d4',
      'footerbg' => '#f6f6f6',
    ),
  ),
  'yellow' => array(
    // Scheme title.
    'title' => t('Yellow'),
    // Scheme colors (Keys are coming from $info['fields']).
    'colors' => array(
      'base' => '#f6f6f6',
      'text' => '#686868',
      'link' => '#444444',
      'linkhover' => '#c1b919',
      'pagetitebg' => '#ccc996',
      'pagetitecolor' => '#f6f6f6',
      'footercolumnsbg' => '#444444',
      'footercolumnscolor' => '#d0d3d4',
      'footercolumnslink' => '#d0d3d4',
      'footerbg' => '#f6f6f6',
    ),
  ),
);

// Define the CSS file(s) that we want the Color module to use as a base.
$info['css'] = array(
  'css/colors.css',
);


/** Copying **/

// Files we want to copy along with the CSS files.
$info['copy'] = array(
  'logo.png'
);


/** Preview **/

// HTML file to be used in the preview window.
$info['preview_html'] = 'color/preview.html';

// CSS file to be used in the preview window.
$info['preview_css'] = 'color/preview.css';

// Javascript file to use in the preview window.
// This is the one that handles the color changes on the preview form when you're 
// clicking on the color picker.
$info['preview_js'] = 'color/preview.js';


/** Image colors, gradients, slices. **/

// Base file for image generation.
$info['base_image'] = 'color/base.png';

// Reference color used for blending. Matches the base.png's colors.
$info['blend_target'] = '#ffffff';

// Gradients.
$info['gradients'] = array(
  array(
    // Where to apply and with what dimension. (x, y, width, height).
    'dimension' => array(0, 0, 150, 100),
    // Direction of gradient ('vertical' or 'horizontal').
    'direction' => 'vertical',
    // Keys of colors to use for the gradient.
    'colors' => array('linkhover', 'linkhover'),
  ),
);

// Color areas to fill (x, y, width, height).
$info['fill'] = array(
  'linkhover' => array(0, 0, 150, 100),
);

// Coordinates of all the theme slices (x, y, width, height)
// with their filename as used in the stylesheet.
$info['slices'] = array(
  /*'
  images/list/arrow1.png' => array(0, 0, 10, 10),
  */
);

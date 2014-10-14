<?php

/**
 * Plugin Name: Slider WD
 * Plugin URI: http://web-dorado.com/products/wordpress-slider-plugin.html
 * Description: Slider WD is an effective tool for adding responsive sliders to your website. It uses large number of transition effects.
 * Version: 1.0.1
 * Author: WebDorado
 * Author URI: http://web-dorado.com/
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

define('WD_S_DIR', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
define('WD_S_URL', plugins_url(plugin_basename(dirname(__FILE__))));

// Plugin menu.
function wds_options_panel() {
  $sliders_page = add_menu_page('Slider WD', 'Slider WD', 'manage_options', 'sliders_wds', 'wd_sliders', WD_S_URL . '/images/wd_slider.png');

  add_action('admin_print_styles-' . $sliders_page, 'wds_styles');
  add_action('admin_print_scripts-' . $sliders_page, 'wds_scripts');

  add_submenu_page('sliders_wds', 'Featured Plugins', 'Featured Plugins', 'manage_options', 'featured_plugins_wds', 'wds_featured');

  $uninstall_page = add_submenu_page('sliders_wds', 'Uninstall', 'Uninstall', 'manage_options', 'uninstall_wds', 'wd_sliders');
  add_action('admin_print_styles-' . $uninstall_page, 'wds_styles');
  add_action('admin_print_scripts-' . $uninstall_page, 'wds_scripts');
}
add_action('admin_menu', 'wds_options_panel');

function wd_sliders() {
  require_once(WD_S_DIR . '/framework/WDW_S_Library.php');
  $page = WDW_S_Library::get('page');
  if (($page != '') && (($page == 'sliders_wds') || ($page == 'uninstall_wds') || ($page == 'WDSShortcode'))) {
    require_once(WD_S_DIR . '/admin/controllers/WDSController' . (($page == 'WDSShortcode') ? $page : ucfirst(strtolower($page))) . '.php');
    $controller_class = 'WDSController' . ucfirst(strtolower($page));
    $controller = new $controller_class();
    $controller->execute();
  }
}

function wds_featured() {
  require_once(WD_S_DIR . '/featured/featured.php');
  wp_register_style('wds_featured', WD_S_URL . '/featured/style.css', array(), get_option("wds_version"));
  wp_print_styles('wds_featured');
  spider_featured('slider');
}

function wds_frontend() {
  require_once(WD_S_DIR . '/framework/WDW_S_Library.php');
  $page = WDW_S_Library::get('action');
  if (($page != '') && ($page == 'WDSShare')) {
    require_once(WD_S_DIR . '/frontend/controllers/WDSController' . ucfirst($page) . '.php');
    $controller_class = 'WDSController' . ucfirst($page);
    $controller = new $controller_class();
    $controller->execute();
  }
}

function wds_ajax() {
  require_once(WD_S_DIR . '/framework/WDW_S_Library.php');
  $page = WDW_S_Library::get('action');
  if ($page != '' && (($page == 'WDSShortcode'))) {
    require_once(WD_S_DIR . '/admin/controllers/WDSController' . ucfirst($page) . '.php');
    $controller_class = 'WDSController' . ucfirst($page);
    $controller = new $controller_class();
    $controller->execute();
  }
}

function wds_shortcode($params) {
  shortcode_atts(array('id' => 0), $params);
  ob_start();
  wds_front_end($params['id']);
  return str_replace(array("\r\n", "\n", "\r"), '', ob_get_clean());
  // return ob_get_clean();
}
add_shortcode('wds', 'wds_shortcode');

function wd_slider($id) {
  echo wds_front_end($id);
}

$wds = 0;
function wds_front_end($id) {
  require_once(WD_S_DIR . '/frontend/controllers/WDSControllerSlider.php');
  $controller = new WDSControllerSlider();
  global $wds;
  $controller->execute($id, 1, $wds);
  $wds++;
  return;
}

// Add the Slider button.
function wds_add_button($buttons) {
  array_push($buttons, "wds_mce");
  return $buttons;
}

// Register Slider button.
function wds_register($plugin_array) {
  $url = WD_S_URL . '/js/wds_edit_button.js';
  $plugin_array["wds_mce"] = $url;
  return $plugin_array;
}

function wds_admin_ajax() {
  ?>
  <script>
    var wds_admin_ajax = '<?php echo add_query_arg(array('action' => 'WDSShortcode'), admin_url('admin-ajax.php')); ?>';
  </script>
  <?php
}
add_action('admin_head', 'wds_admin_ajax');

// Add the Slider button to editor.
add_action('wp_ajax_WDSShortcode', 'wds_ajax');
add_filter('mce_external_plugins', 'wds_register');
add_filter('mce_buttons', 'wds_add_button', 0);

// Slider Widget.
if (class_exists('WP_Widget')) {
  require_once(WD_S_DIR . '/admin/controllers/WDSControllerWidgetSlideshow.php');
  add_action('widgets_init', create_function('', 'return register_widget("WDSControllerWidgetSlideshow");'));
}

// Activate plugin.
function wds_activate() {
  $version = get_option("wds_version");
  $new_version = '1.0.0';
  if ($version && version_compare($version, $new_version, '<')) {
    require_once WD_S_DIR . "/sliders-update.php";
    wds_update($version);
    update_option("wds_version", $new_version);
  }
  else {
    require_once WD_S_DIR . "/sliders-insert.php";
    wds_insert();
    add_option("wds_version", $new_version, '', 'no');
  }
}
register_activation_hook(__FILE__, 'wds_activate');

if (!isset($_GET['action']) || $_GET['action'] != 'deactivate') {
  add_action('admin_init', 'wds_activate');
}

// Plugin styles.
function wds_styles() {
  wp_admin_css('thickbox');
  wp_enqueue_style('wds_tables', WD_S_URL . '/css/wds_tables.css');
}

// Plugin scripts.
function wds_scripts() {
  $version = get_option("wds_version");
  wp_enqueue_media();
  wp_enqueue_script('jquery-ui-draggable');
  wp_enqueue_script('thickbox');
  wp_enqueue_script('wds_admin', WD_S_URL . '/js/wds.js', array(), get_option("wds_version"));
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-sortable');
  wp_enqueue_script('jscolor', WD_S_URL . '/js/jscolor/jscolor.js', array(), '1.3.9');
  wp_enqueue_style('wds_font-awesome', WD_S_URL . '/css/font-awesome-4.0.1/font-awesome.css', array(), '4.0.1');
  wp_enqueue_style('wds_effects', WD_S_URL . '/css/wds_effects.css', array(), $version);
}

function wds_front_end_scripts() {
  $version = get_option("wds_version");
  wp_enqueue_script('jquery');
  wp_enqueue_style('wds_frontend', WD_S_URL . '/css/wds_frontend.css', array(), $version);
  wp_enqueue_style('wds_effects', WD_S_URL . '/css/wds_effects.css', array(), $version);

  wp_enqueue_style('wds_font-awesome', WD_S_URL . '/css/font-awesome-4.0.1/font-awesome.css', array(), '4.0.1');
}
add_action('wp_enqueue_scripts', 'wds_front_end_scripts');

// Languages localization.
function wds_language_load() {
  load_plugin_textdomain('wds', FALSE, basename(dirname(__FILE__)) . '/languages');
}
add_action('init', 'wds_language_load');

?>
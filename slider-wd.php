<?php

/**
 * Plugin Name: Slider WD
 * Plugin URI: http://web-dorado.com/products/wordpress-slider-plugin.html
 * Description: Slider WD is a great tool for creating responsive sliders. It uses various transition effects.
 * Version: 1.0.2
 * Author: WebDorado
 * Author URI: http://web-dorado.com/
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

define('WD_S_DIR', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
define('WD_S_URL', plugins_url(plugin_basename(dirname(__FILE__))));
global $wpdb;
if (/*$wpdb->query("SHOW TABLES LIKE '" . $wpdb->prefix . "bwg_option'")*/ false) {
  $WD_S_UPLOAD_DIR = $wpdb->get_var($wpdb->prepare('SELECT images_directory FROM ' . $wpdb->prefix . 'bwg_option WHERE id="%d"', 1)) . '/slider-wd';
}
else {
  $upload_dir = wp_upload_dir();
  $WD_S_UPLOAD_DIR = str_replace(ABSPATH, '', $upload_dir['basedir']) . '/slider-wd';
}

// Plugin menu.
function wds_options_panel() {
  $sliders_page = add_menu_page('Slider WD', 'Slider WD', 'manage_options', 'sliders_wds', 'wd_sliders', WD_S_URL . '/images/wd_slider.png');

  add_action('admin_print_styles-' . $sliders_page, 'wds_styles');
  add_action('admin_print_scripts-' . $sliders_page, 'wds_scripts');

  add_submenu_page('sliders_wds', 'Licensing', 'Licensing', 'manage_options', 'licensing_wds', 'wds_licensing');
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

function wds_licensing() {
  wp_register_style('wds_licensing', WD_S_URL . '/licensing/style.css', array(), get_option("wds_version"));
  wp_print_styles('wds_licensing');
  require_once(WD_S_DIR . '/licensing/licensing.php');
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

function wds_media_button($context) {
  global $pagenow;
  if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php'))) {
    $context .= '
      <a onclick="tb_click.call(this); wds_thickDims(); return false;" href="' . add_query_arg(array('action' => 'WDSShortcode', 'TB_iframe' => '1'), admin_url('admin-ajax.php')) . '" class="wds_thickbox button" style="padding-left: 0.4em;" title="Select slider">
        <span class="wp-media-buttons-icon wds_media_button_icon" style="vertical-align: text-bottom; background: url(' . WD_S_URL . '/images/wd_slider.png) no-repeat scroll left top rgba(0, 0, 0, 0);"></span>
        Add Slider WD
      </a>';
  }
  return $context;
}
add_filter('media_buttons_context', 'wds_media_button');

// Add the Slider button to editor.
add_action('wp_ajax_WDSShortcode', 'wds_ajax');

function wds_admin_ajax() {
  ?>
  <script>
    var wds_thickDims, wds_tbWidth, wds_tbHeight;
    wds_tbWidth = 400;
    wds_tbHeight = 200;
    wds_thickDims = function() {
      var tbWindow = jQuery('#TB_window'), H = jQuery(window).height(), W = jQuery(window).width(), w, h;
      w = (wds_tbWidth && wds_tbWidth < W - 90) ? wds_tbWidth : W - 40;
      h = (wds_tbHeight && wds_tbHeight < H - 60) ? wds_tbHeight : H - 40;
      if (tbWindow.size()) {
        tbWindow.width(w).height(h);
        jQuery('#TB_iframeContent').width(w).height(h - 27);
        tbWindow.css({'margin-left': '-' + parseInt((w / 2),10) + 'px'});
        if (typeof document.body.style.maxWidth != 'undefined') {
          tbWindow.css({'top':(H-h)/2,'margin-top':'0'});
        }
      }
    };
  </script>
  <?php
}
add_action('admin_head', 'wds_admin_ajax');

// Add images to Slider
add_action('wp_ajax_wds_UploadHandler', 'wds_UploadHandler');
add_action('wp_ajax_addImage', 'wds_filemanager_ajax');

// Upload.
function wds_UploadHandler() {
  require_once(WD_S_DIR . '/filemanager/UploadHandler.php');
}

function wds_filemanager_ajax() { 
  if (function_exists('current_user_can')) {
    if (!current_user_can('publish_posts')) {
      die('Access Denied');
    }
  }
  else {
    die('Access Denied');
  }
  global $wpdb;
  require_once(WD_S_DIR . '/framework/WDW_S_Library.php');
  $page = WDW_S_Library::get('action');
  if (($page != '') && (($page == 'addImage') || ($page == 'addMusic'))) {
    require_once(WD_S_DIR . '/filemanager/controller.php');
    $controller_class = 'FilemanagerController';
    $controller = new $controller_class();
    $controller->execute();
  }
}
// Slider Widget.
if (class_exists('WP_Widget')) {
  require_once(WD_S_DIR . '/admin/controllers/WDSControllerWidgetSlideshow.php');
  add_action('widgets_init', create_function('', 'return register_widget("WDSControllerWidgetSlideshow");'));
}

// Activate plugin.
function wds_activate() {
  global $wpdb;
  wds_install();
  if (!$wpdb->get_var("SELECT * FROM " . $wpdb->prefix . "wdsslider")) {
    $wpdb->query('INSERT INTO `' . $wpdb->prefix . 'wdsslider` VALUES(1, "Default slider", 1, 0, 800, 300, "cover", "center", "none", 5, 1, 0, 0, "", 1, "000000", 100, 0, "none", "FFFFFF", "", 0, "", 0, 1, 1, 0, "hover", "fa-angle", 40, 40, "FFFFFF", 100, "CCCCCC", 0, "none", "FFFFFF", "20px", "FFFFFF", "bottom", "fa-square-o", 20, "FFFFFF", "FFFFFF", 3, "none", 100, 50, "000000", 0, 0, "none", "FFFFFF", 50, "none", "middle-center", 15, "", "", 20, "arial.ttf", "FFFFFF", 70, "#wds_0_slide2_layer2 {\r\n  text-align: center !important;\r\n}", "none", 5, "FFFFFF", 50, 1)');
  }
  if (!$wpdb->get_var("SELECT * FROM " . $wpdb->prefix . "wdsslide")) {
    $wpdb->query('INSERT INTO `' . $wpdb->prefix . 'wdsslide` VALUES(1, 1, "Slide 1", "image", "' . WD_S_URL . '/demo/1.jpg", "' . WD_S_URL . '/demo/1-150x150.jpg", 1, "", 1)');
    $wpdb->query('INSERT INTO `' . $wpdb->prefix . 'wdsslide` VALUES(2, 1, "Slide 2", "image", "' . WD_S_URL . '/demo/2.jpg", "' . WD_S_URL . '/demo/2-150x150.jpg", 1, "", 2)');
    $wpdb->query('INSERT INTO `' . $wpdb->prefix . 'wdsslide` VALUES(3, 1, "Slide 3", "image", "' . WD_S_URL . '/demo/3.jpg", "' . WD_S_URL . '/demo/3-150x150.jpg", 1, "", 3)');
  }
}
register_activation_hook(__FILE__, 'wds_activate');

function wds_install() {
  $version = get_option("wds_version");
  $new_version = '1.0.2';
  if ($version && version_compare($version, $new_version, '<')) {
    require_once WD_S_DIR . "/sliders-update.php";
    wds_update($version);
    update_option("wds_version", $new_version);
  }
  else {
    require_once WD_S_DIR . "/sliders-insert.php";
    wds_insert();
    if (!$version) {
      var_dump('aa');
      add_option("wds_theme_version", '1.0.0', '', 'no');
    }
    add_option("wds_version", $new_version, '', 'no');
  }
}
if (!isset($_GET['action']) || $_GET['action'] != 'deactivate') {
  add_action('admin_init', 'wds_install');
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
  wp_enqueue_script('thickbox');
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-sortable');
  wp_enqueue_script('jquery-ui-draggable');
  wp_enqueue_script('wds_admin', WD_S_URL . '/js/wds.js', array(), get_option("wds_version"));
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
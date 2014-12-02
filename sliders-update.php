<?php

function wds_update($version) {
  global $wpdb;
  if (version_compare($version, '1.0.2') == -1) {
   // Add spider uploader option.
   $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `spider_uploader` tinyint(1) NOT NULL DEFAULT 0");
  }
   if (version_compare($version, '1.0.4') == -1) {
    // Add stop animation on hover and link target options.
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `stop_animation` tinyint(1) NOT NULL DEFAULT 0");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslide ADD `target_attr_slide` tinyint(1) NOT NULL DEFAULT 1");
    $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdslayer ADD `target_attr_layer` tinyint(1) NOT NULL DEFAULT 1");
  }
  return;
}

?>
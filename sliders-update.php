<?php

function wds_update($version) {
  global $wpdb;
  if (version_compare($version, '1.0.2') == -1) {
   // Add spider uploader option.
   $wpdb->query("ALTER TABLE " . $wpdb->prefix . "wdsslider ADD `spider_uploader` tinyint(1) NOT NULL DEFAULT 0");
  }
  return;
}

?>
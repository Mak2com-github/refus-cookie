<?php

// exit if uninstall constant is not defined
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

// delete database table
global $wpdb;

$cookie_table_name = $wpdb->prefix. 'refus_cookie';
$wpdb->query("DROP TABLE IF EXISTS $cookie_table_name");

$cookie_table_name_config = $wpdb->prefix . 'refus_cookie_config';
$wpdb->query("DROP TABLE IF EXISTS $cookie_table_name_config");
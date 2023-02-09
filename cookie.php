<?php
/*
    Plugin name: Refus Cookie
    Plugin URI: https://wordpress.com
    Description: Permet de savoir le taux de refus des cookies sur le site. 
    Version : 1.2
    Author: Anaïs
    Auhtos URI: https://worsdpress.com
    Text Domain: refus-cookie
*/

define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR.'cookie_dashboard.php');

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

register_activation_hook(__FILE__, 'create_db');

function create_db() {
    
    global $wpdb;
    $charset_collate = $wpdb->charset;

    $wpdb_collate = $wpdb->collate;
    $wpdb_charset = $wpdb->charset;

    $cookie_table_name = $wpdb->prefix . 'refus_cookie';

    $sql_cookie = "CREATE TABLE IF NOT EXISTS {$cookie_table_name} (
        `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
        `refus` INT(10) UNSIGNED NOT NULL,
        `created_at` DATETIME NULL,
        `updated_at` DATETIME NULL
        )";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
    
        dbDelta($sql_cookie);

        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        $wpdb->insert($cookie_table_name, array(
            'refus' => 0,
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ));
}

add_action('admin_menu', 'init_plugin_menu');

function init_plugin_menu()
{
    add_menu_page(
        'refus-cookie',
        'refus-cookie',
        'manage_options',
        'refus_cookie',
        'cookie_dashboard',
        '',
        3
    );
}

register_deactivation_hook(__FILE__, 'delete_db');
function delete_db() {

    global $wpdb;
    $cookie_table_name = $wpdb->prefix. 'refus_cookie';
    $wpdb->query("DROP TABLE IF EXISTS $cookie_table_name");   
}

add_action('admin_init', 'cookie_custom_scripts');
function cookie_custom_styles() {
    wp_enqueue_style('style_cookie');
    wp_register_style('style_cookie', plugins_url('/css/style.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'cookie_custom_scripts');

function cookie_custom_scripts() {
    wp_enqueue_style('style_cookie_front');
    wp_register_style('style_cookie_front', plugins_url('/css/style_front.css', __FILE__));
    wp_enqueue_script('cookie_js', plugins_url('/js/main.js', __FILE__) , array('jquery'), false, true);
}

add_action( 'wp_ajax_update_data', 'update_data' );
add_action( 'wp_ajax_nopriv_update_data', 'update_data' );

function update_data() {

    global $wpdb;
    $charset_collate = $wpdb->charset;

    $wpdb_collate = $wpdb->collate;
    $wpdb_charset = $wpdb->charset;
    $cookie_table_name = $wpdb->prefix . 'refus_cookie';

    $sql = "UPDATE `wp_refus_cookie` SET `refus` = `refus` + 1, `updated_at`= NOW()";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql);
}


add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
function my_custom_dashboard_widgets() {
    global $wp_meta_boxes;
    wp_add_dashboard_widget('custom_help_widget', 'Refus Cookie', 'custom_dashboard_help');
}

function custom_dashboard_help() {
    global $wpdb;
    $charset_collate = $wpdb->charset;

    $wpdb_collate = $wpdb->collate;
    $wpdb_charset = $wpdb->charset;

    $sql = "SELECT `refus` FROM `wp_refus_cookie`";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php');

    $results = $wpdb->get_results($sql, ARRAY_A);
    foreach($results as $result) {
        echo "Taux de refus des cookies: " . $result['refus'];}
}
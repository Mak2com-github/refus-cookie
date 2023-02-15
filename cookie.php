<?php
/*
 * @link              http://mak2com.fr
 * @since             1.2
 * @package           refus-cookie
 *
 * @wordpress-plugin
 * Plugin name: Refus Cookie
 * Plugin URI: https://wordpress.com
 * Description: Permet de savoir le taux de refus des cookies sur le site.
 * Version : 1.2
 * Author: Anaïs
 * Author URI: https://worsdpress.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: refus-cookie
 * Domain Path:       /languages
*/

if ( ! defined( 'WPINC' ) ) {
    exit;
}

register_activation_hook(__FILE__, 'create_db');
function create_db() {

    global $wpdb;

    $charset_collate = $wpdb->charset;
    $wpdb_collate = $wpdb->collate;
    $wpdb_charset = $wpdb->charset;
    $cookie_table_name = $wpdb->prefix . 'refus_cookie';
    $cookie_table_name_config = $wpdb->prefix . 'refus_cookie_config';

    if ( $wpdb->get_var("SHOW TABLES LIKE '$cookie_table_name'") != $cookie_table_name ) {
        $sql_cookie =
            "CREATE TABLE IF NOT EXISTS {$cookie_table_name} (
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
    if ( $wpdb->get_var("SHOW TABLES LIKE '$cookie_table_name_config'") != $cookie_table_name_config ) {
        $sql_cookie =
            "CREATE TABLE IF NOT EXISTS {$cookie_table_name_config} (
                `id` BIGINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
                `settings_datas` JSON DEFAULT NULL,
                `created_at` DATETIME NULL,
                `updated_at` DATETIME NULL
            )";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_cookie);

        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        $defaults = array(
            'element_id' => "",
            'ips_setting' => array(
                'ips'   => array(
                    'id'    =>  '0',
                    'ip'    =>  getUserIP(),
                ),
            ),
        );

        $wpdb->insert($cookie_table_name_config, array(
            'settings_datas' => json_encode($defaults),
            'created_at' => $created_at,
            'updated_at' => $updated_at,
        ));
    }
}

/** INITIALISATION DU PLUGIN **/
add_action('admin_menu','init_plugin_menu');
function init_plugin_menu(){
    add_menu_page(
        'Réglages',
        'Refus Cookies',
        'manage_options',
        'refus-cookie-settings',
        'refus_cookie_settings',
        'dashicons-hammer',
        10
    );
}

add_action('admin_init', 'cookie_custom_styles');
function cookie_custom_styles() {
    wp_enqueue_style('style_cookie');
    wp_register_style('style_cookie', plugins_url('/css/style.css', __FILE__));
}

add_action('admin_init', 'dbOperatorFunctions');
function dbOperatorFunctions() {
    $RefusSettings = new RefusSettings();
    if (isset($_POST['add_ip'])) {
        $RefusSettings->addSettingsIP($_POST);
    }
    if (isset($_POST['add_element'])) {
        $RefusSettings->addSettingsElement($_POST);
    }
    if (isset($_POST['ip_delete'])) {
        $RefusSettings->deleteSettingsIp($_POST);
    }
}

add_action('wp_enqueue_scripts', 'cookie_custom_scripts');
function cookie_custom_scripts() {
    wp_enqueue_style('style_cookie_front');
    wp_register_style('style_cookie_front', plugins_url('/css/style_front.css', __FILE__));
    wp_enqueue_script('cookie_js', plugins_url('/js/main.js', __FILE__) , array('jquery'), false, true);
    wp_localize_script( 'cookie_js', 'php_datas',
        array(
            'home_url'      => home_url(),
            'visitor_ip'    => getUserIP(),
        )
    );
}

define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'cookie-settings.php');
require_once(ROOTDIR . 'classes/RefusSettings.php');

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
        echo "Taux de refus des cookies: " . $result['refus'];
    }
}

function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = $_SERVER['HTTP_CLIENT_IP'];
    $forward = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

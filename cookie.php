<?php
/*
    Plugin name: Refus Cookie
    Plugin URI: https://wordpress.com
    Description: Permet de savoir le taux de refus des cookies sur le site. 
    Version : 0.1
    Author: Anaïs
    Auhtos URI: https://worsdpress.com
    Text Domain: refus-cookie
*/

// //je définie ROOTDIR en tant que chemin vers le fichier -> cookie_dashboard.php
define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR.'cookie_dashboard.php');

// //vérifi que l'on est bien au sein de wordpress
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// //constante 

// //récupère le chemin complet de l'extension

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

//lien dans le menu vers la page de configuration
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

//supprimer la table lors de la désactivation du plugin
register_deactivation_hook(__FILE__, 'delete_db');
function delete_db() {

    global $wpdb;
    $cookie_table_name = $wpdb->prefix. 'refus_cookie';
    $wpdb->query("DROP TABLE IF EXISTS $cookie_table_name");   
}

//j'appelle, je lis le javascript ici
//pour afficher dans la console de l'inspecteur d'élément le js, le 'wp_enqueue_scripts' va s'afficher que en front
// si cela aurait été le hook admin_init, cela sera affiché seulement en back office
add_action('wp_enqueue_scripts', 'cookie_custom_scripts');
function cookie_custom_scripts() {
    wp_enqueue_script('cookie_js', plugin_dir_url(__FILE__) . '/js/main.js', array('jquery'), false, true);
}
//j'indique que je vais utiliser du jquery dans le main.js


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

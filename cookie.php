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

// // include('src/CookiePlugin.php');
// //je définie ROOTDIR en tant que chemin vers le fichier -> cookie_dashboard.php
define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR.'cookie_dashboard.php');

// //vérifi que l'on est bien au sein de wordpress
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// //constante 
// define('COOKIE_PLUGIN_DIR', plugin_dir_path(__FILE__));

// //récupère le chemin complet de l'extension
// require_once COOKIE_PLUGIN_DIR . 'vendor/autoload.php';

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

        $wpdb->insert($colonne, array(
            'refus' => 1,
            'created_at' => current_time('mysql'),
            'updated_at'=>current_time('mysql'),
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
register_deactivation_hook(__FILE__, 'delate_db');
function delate_db() {

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

header("Content-Type: application/json");

$host = 'localhost';
$username = 'root';
$password = 'root';
$dbname = 'wordpress_test_1';

// $analytics = $_POST["1"];
$db = new mysqli($host, $username, $password, $dbname);
$sql = "INSERT INTO `wp_refus_cookie` (`refus`) VALUES TRUE";
$result = mysqli_query($db, $sql);

// $count = mysqli_fetch_row($result)[1];
// echo json_encode($data);

$db->close();

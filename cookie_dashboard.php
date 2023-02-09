<?php 

function cookie_dashboard() {

    ?>
    <div class="config">
        <h1>Analyse Cookie</h1>
        <h4>Ici vous trouverez le taux de click de refus des cookies.</h4>
    </div>
    <div class="overview-cookie">
        <?php
            global $wpdb;
            $charset_collate = $wpdb->charset;

            $wpdb_collate = $wpdb->collate;
            $wpdb_charset = $wpdb->charset;

            $sql = "SELECT `refus` FROM `wp_refus_cookie`";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
            
            $results = $wpdb->get_results($sql, ARRAY_A);
            foreach($results as $result) {
                echo "Taux de refus : " . $result['refus'];
            }
        ?>
    </div>
<?php
            
    }
?>


<?php

if ( ! defined( 'WPINC' ) ) {
    exit;
}

function refus_cookie_settings() {
    global $wpdb;
    $cookie_table_name_config = $wpdb->prefix . 'refus_cookie_config';

    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');

    ?>
    <div class="wrap">
        <h1>Réglages</h1>
        <form method="post" action="admin.php?page=refus-cookie-settings">
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="ip_settings">IP à exclure</label>
                        </th>
                        <td>
                            <p>Ajouter une adresse IP à exclure pour que les évènements de refus des cookies ne soit pas comptabilisés pour cette adresse IP</p>
                            <input type="text" name="ip_settings" id="ipSettings" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="xxx.xxx.xx.xx">
                        </td>
                        <th>
                            <p class="submit">
                                <input type="submit" name="submit" id="submit" class="button button-primary" value="Ajouter">
                            </p>
                        </th>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>

    <script>
        $('#ipSettings').mask('099.099.099.099');
    </script>
    <?php

    if (isset($_POST['ip_settings']) && !empty($_POST['ip_settings'])) {
        // Prépare la requete
        $sql = $wpdb->prepare(
            "INSERT INTO {$cookie_table_name_config}
                        (exclude_ip, created_at, updated_at) VALUES (%s,%s,%s)",
            $_POST['ip_settings'],
            $created_at,
            $updated_at
        );
        // Execution de la requete
        $wpdb->query($sql);
    }
}
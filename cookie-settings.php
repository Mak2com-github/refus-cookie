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
        <div class="message_container">
            <p>
                <?php
                if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
                    echo $_SESSION['error_message'];
                }
                if (isset($_SESSION['success_message']) && !empty($_SESSION['success_message'])) {
                    echo $_SESSION['success_message'];
                }
                ?>
            </p>
        </div>
        <div class="settings_container">
            <div class="settings_col_left">
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
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="element_id">Élement à cibler</label>
                            </th>
                            <td>
                                <p>L'identifiant unique de l'élément sur lequel l'évènement au click doit être attribué</p>
                                <input type="text" name="element_id" id="elementId" pattern="[a-zA-Z0-9]+" placeholder="elementId" prefix="#">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <p class="submit">
                        <input type="submit" name="add_settings" id="submit" class="button button-primary" value="Ajouter">
                    </p>
                </form>
            </div>
            <div class="settings_col_right">
                <table class="wp-list-table widefat fixed striped table-view-list">
                    <thead>
                        <tr>
                            <th id="title" class="manage-column column-title column-primary sortable desc" scope="col">
                                <p>Réglage</p>
                            </th>
                            <th id="value" class="manage-column column-title column-primary sortable desc" scope="col">
                                <p>Valeur</p>
                            </th>
                            <th id="action" class="manage-column column-title column-primary sortable desc" scope="col">
                                <p>Actions</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="settingsList">
                        <?php
                        $Settings = new RefusSettings();
                        var_dump($Settings->getAllSettings());
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
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
<?php

if ( ! defined( 'WPINC' ) ) {
    exit;
}

function refus_cookie_settings() {
    global $wpdb;
    $cookie_table_name_config = $wpdb->prefix . 'refus_cookie_config';

    $settingsID = new RefusSettings();
    $settingsID = $settingsID->getAllSettings();
    $settingsID = json_decode(json_encode($settingsID[0]), true);
    $settingsID = $settingsID['id'];

    ?>
    <div class="wrap">
        <h1>Réglages</h1>
        <div class="message_container">
            <p>Votre adresse IP actuelle : <span><?= getUserIP() ?></span></p>
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
            <div class="settings_top">
                <form class="form-left form-container" method="post" action="admin.php?page=refus-cookie-settings">
                    <input type="hidden" name="settings_id" value="<?= $settingsID ?>">
                    <div class="form-row">
                        <label for="setting_name">Ajouter un nom à cette adresse IP</label>
                        <input type="text" name="setting_name" id="SettingsName" placeholder="Agence" required>
                    </div>
                    <div class="form-row">
                        <label for="setting_ip">IP à exclure</label>
                        <p>Ajouter une adresse IP à exclure pour que les évènements de refus des cookies ne soit pas comptabilisés pour cette adresse IP</p>
                        <input type="text" name="setting_ip" id="SettingsIp" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="xxx.xxx.xx.xx" required>
                    </div>
                    <p class="submit">
                        <input type="submit" name="add_ip" id="submit" class="button button-primary" value="Ajouter">
                    </p>
                </form>
                <form class="form-right form-container" method="post" action="admin.php?page=refus-cookie-settings">
                    <input type="hidden" name="settings_id" value="<?= $settingsID ?>">
                    <div class="form-row">
                        <label for="element_id">Élement à cibler</label>
                    </div>
                    <div class="form-row">
                        <p>L'identifiant unique de l'élément sur lequel l'évènement au click doit être attribué</p>
                        <input type="text" name="element_id" id="elementId" pattern="[a-zA-Z0-9]+" placeholder="elementId" required>
                    </div>
                    <div class="form-row">
                        <select name="target_type" id="targetType" required>
                            <option value="">Type de sélecteur</option>
                            <option value="class">Class "."</option>
                            <option value="id">ID "#"</option>
                        </select>
                    </div>
                    <p class="submit">
                        <input type="submit" name="add_element" id="submit" class="button button-primary" value="Ajouter">
                    </p>
                </form>
            </div>
            <div class="settings_body">
                <div class="section-left settings-body-section">
                    <table class="wp-list-table widefat fixed striped table-view-list">
                        <thead>
                        <tr>
                            <th class="manage-column column-title column-primary sortable desc" scope="col">
                                <p>Nom</p>
                            </th>
                            <th class="manage-column column-title column-primary sortable desc" scope="col">
                                <p>Adresses IP</p>
                            </th>
                            <th class="manage-column column-title column-primary sortable desc" scope="col">
                                <p>Valeur</p>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="settingsList">
                        <?php
                        $Settings = new RefusSettings();
                        $Ips = $Settings->getAllIps();
                        $Ips = json_decode($Ips, true);
                        foreach ($Ips as $Ip) {
                            ?>
                            <tr class="hentry entry">
                                <th class="value column-value has-row-actions column-primary">
                                    <?php
                                    if ($Ip['name']) {
                                        ?>
                                        <p><?= $Ip['name'] ?></p>
                                        <?php
                                    }
                                    ?>
                                </th>
                                <th class="value column-value has-row-actions column-primary">
                                    <?php
                                    if ($Ip['ip']) {
                                        ?>
                                        <p><?= $Ip['ip'] ?></p>
                                        <?php
                                    }
                                    ?>
                                </th>
                                <th class="value column-value has-row-actions column-primary">
                                    <form action="" method="post">
                                        <input type="hidden" name="settings_ip_name" value="<?= $Ip['name'] ?>">
                                        <input type="hidden" name="settings_ip_id" value="<?= $settingsID ?>">
                                        <input type="hidden" name="settings_ip" value="<?= $Ip['ip'] ?>">
                                        <input type="submit" value="modifier" name="ip_edit">
                                    </form>
                                    <form action="" method="post">
                                        <input type="hidden" name="settings_ip_name" value="<?= $Ip['name'] ?>">
                                        <input type="hidden" name="settings_ip_id" value="<?= $settingsID ?>">
                                        <input type="hidden" name="settings_ip" value="<?= $Ip['ip'] ?>">
                                        <input type="submit" value="supprimer" name="ip_delete">
                                    </form>
                                </th>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="section-right settings-body-section">
                    <table class="wp-list-table widefat fixed striped table-view-list">
                        <thead>
                        <tr>
                            <th class="manage-column column-title column-primary sortable desc title" scope="col">
                                <p>Cibles</p>
                            </th>
                            <th class="manage-column column-title column-primary sortable desc type" scope="col">
                                <p>Type</p>
                            </th>
                            <th class="manage-column column-title column-primary sortable desc actions" scope="col">
                                <p>Actions</p>
                            </th>
                        </tr>
                        </thead>
                        <tbody id="settingsList">
                        <?php
                        $Settings = new RefusSettings();
                        $Targets = $Settings->getAllElements();
                        foreach ($Targets as $target) {
                            $elements = json_decode($target->targets);
                            foreach ((array)$elements as $element) {
                                ?>
                                <tr class="hentry entry">
                                    <th class="value column-value has-row-actions column-primary">
                                    <?php
                                    if ($element->element) {
                                        ?>
                                        <p><?= $element->element ?></p>
                                        <?php
                                    }
                                    ?>
                                    </th>
                                    <th class="value column-value has-row-actions column-primary">
                                        <?php
                                        if ($element->type) {
                                            ?>
                                            <p><?php if ($element->type === "class") { echo "Classe"; } else { echo "ID"; } ?></p>
                                            <?php
                                        }
                                        ?>
                                    </th>
                                    <th class="value column-value has-row-actions column-primary">
                                        <form action="" method="post">
                                            <input type="hidden" name="settings_id" value="<?= $settingsID ?>">
                                            <input type="hidden" name="target_id" value="<?= $element->id ?>">
                                            <input type="button" value="modifier" name="target_edit">
                                        </form>
                                        <form action="" method="post">
                                            <input type="hidden" name="settings_id" value="<?= $settingsID ?>">
                                            <input type="hidden" name="target_id" value="<?= $element->id ?>">
                                            <input type="button" value="supprimer" name="target_delete">
                                        </form>
                                    </th>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#ipSettings').mask('099.099.099.099');
    </script>
    <?php
}
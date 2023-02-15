<?php
/*
 * Refus cookie settings class
 */
if(!class_exists('My_Class')) {
    class RefusSettings
    {

        private $wpdb;
        private $charset_collate;
        private $wpdb_collate;
        private $wpdb_charset;
        private $data_table;
        private $settings_table;
        private $datetime;

        public function __construct()
        {
            global $wpdb;
            $this->wpdb = $wpdb;
            $this->charset_collate = $this->wpdb->charset;
            $this->wpdb_collate = $this->wpdb->collate;
            $this->wpdb_charset = $this->wpdb->charset;
            $this->data_table = $this->wpdb->prefix . 'refus_cookie';
            $this->settings_table = $this->wpdb->prefix . 'refus_cookie_config';
            $this->datetime = current_datetime()->format('Y-m-d H:i:s');
        }

        public function getAllSettings()
        {
            $query = $this->wpdb->get_results("SELECT * FROM $this->settings_table");
            return $query;
        }

        public function getAllElements() {
            $query = $this->wpdb->get_results("SELECT settings_datas->'$.targets' AS targets FROM $this->settings_table WHERE id = 1");
            return $query;
        }

        public function getAllIps() {
            $query = $this->wpdb->get_results("SELECT settings_datas->'$.ips' AS ips FROM $this->settings_table WHERE id = 1");
            return $query[0];
        }

        public function getAllIpsAlt() {
            $query = $this->wpdb->get_var("SELECT settings_datas->'$.ips' AS ips FROM $this->settings_table WHERE id = 1");
            return $query;
        }

        public function addSettingsIP($datas) {
            if (isset($datas['setting_ip']) && !empty($datas['setting_ip'])) {
                $row_id = $datas['settings_id'];
                $ip_address = htmlspecialchars($datas['setting_ip'],ENT_NOQUOTES,'UTF-8', true);

                if (isset($datas['setting_name']) && !empty($datas['setting_name'])) {
                    $ip_name = $datas['setting_name'];
                    $ip_name = htmlspecialchars($ip_name, ENT_QUOTES,'UTF-8', true);

                    $existing = $this->getAllIps();
                    $existing = (array)$existing;
                    $existing = json_decode($existing['ips']);
                    $existing = (array)$existing;

                    $formatedDatas = array(
                        $ip_name => [
                            array(
                                "ip" => $ip_address,
                                "name"  =>  $ip_name
                            )
                        ]
                    );
                    array_push($existing, $formatedDatas);
                    $updated = $existing;
                    $updated = json_encode($updated);

                    $this->wpdb->query(
                        $this->wpdb->prepare("UPDATE $this->settings_table SET settings_datas = JSON_SET(settings_datas, '$.ips', '$updated') WHERE id = $row_id")
                    );

                    if ($this->wpdb->last_error) {
                        return $_SESSION['error_message'] = "Erreur lors de l'insertion des données. <br>Erreur : " . $this->wpdb->last_error;
                    } else {
                        return $_SESSION['success_message'] = "Les données ont bien été enregistrées";
                    }
                } else {
                    return $_SESSION['success_message'] = "Le champ Nom n'est pas renseigné ou vide";
                }
            } else {
                return $_SESSION['success_message'] = "Le champ de l'adresse IP n'est pas renseignée ou vide";
            }
        }

        public function addSettingsElement($datas) {
            if (isset($datas['element_id']) && !empty($datas['element_id'])) {
                $row_id = $datas['settings_id'];
                $element_id = htmlspecialchars($datas['element_id'], ENT_QUOTES, 'UTF-8', true);
                $element_id = json_encode($element_id);

                $this->wpdb->prepare("UPDATE $this->settings_table SET settings_datas = JSON_SET(settings_datas, '$.element_id', $element_id) WHERE id = $row_id");

                if ($this->wpdb->last_error) {
                    $_SESSION['error_message'] = "Erreur lors de l'insertion des données. <br>Erreur : " . $this->wpdb->last_error;
                } else {
                    $_SESSION['success_message'] = "Les données ont bien été enregistrées";
                }
            }
        }

        public function deleteSettingsIp($datas) {
            if (isset($datas) && !empty($datas)) {
                $ip = $datas['settings_ip'];
                $row_id = $datas['settings_ip_id'];
                $pattern = "/^[0-9\.]+$/";

                // Test the input string against the pattern
                if (preg_match($pattern, $ip)) {
                    $existing = $this->getAllIpsAlt();
                    $existing = json_decode(json_decode($existing, true), true);
                    var_dump($existing);
                    foreach ($existing as $key => $value) {
                        if (isset($value[$datas['settings_ip_name']])) {
                            unset($existing[$key]);
                        }
                    }

                    $updated = json_encode($existing);

//                    $this->wpdb->query(
//                        $this->wpdb->prepare("UPDATE $this->settings_table SET settings_datas = JSON_SET(settings_datas, '$.ips', '$updated') WHERE id = $row_id")
//                    );

                    if ($this->wpdb->last_error) {
                        $_SESSION['success_message'] = "There is an error when execute the sql request: ". $this->wpdb->last_error;
                    } else {
                        $_SESSION['success_message'] = "Informations has been correctly updated !";
                    }

                } else {
                    // The input string contains other characters
                    $_SESSION['success_message'] = "The input string contains other characters unauthorized in ip field";
                }
            }
        }
    }
}
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
            $query = $this->wpdb->get_results("SELECT * FROM $this->settings_table WHERE id = 1");
            return $query;
        }

        public function getAllElements() {
            $query = $this->wpdb->get_results("SELECT settings_datas->'$.targets' AS targets FROM $this->settings_table WHERE id = 1");
            return $query;
        }

        public function getAllIps() {
            $query = $this->wpdb->get_var("SELECT settings_datas FROM $this->settings_table WHERE id = 1");
            $queryDatas = json_decode($query, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return 'Error decoding : '.json_last_error_msg();
            }
            return $queryDatas['ips'];
        }

        public function getJSONDatas() {
            $query = $this->wpdb->get_var("SELECT settings_datas FROM $this->settings_table WHERE id = 1");
            $queryDatas = json_decode($query, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return 'Error decoding : '.json_last_error_msg();
            }
            return $queryDatas;
        }

        public function addSettingsIP($datas) {
            if (!isset($datas['setting_ip']) && empty($datas['setting_ip'])) {
                return $_SESSION['success_message'] = "Le champ de l'adresse IP n'est pas renseignée ou vide";
            }
            if (!isset($datas['setting_name']) && empty($datas['setting_name'])) {
                return $_SESSION['success_message'] = "Le champ Nom n'est pas renseigné ou vide";
            }

            $row_id = $datas['settings_id'];
            $ip_address = htmlspecialchars($datas['setting_ip'], ENT_NOQUOTES, 'UTF-8', true);
            $ip_name = htmlspecialchars($datas['setting_name'], ENT_QUOTES,'UTF-8', true);

            $existing = $this->getJSONDatas();
            $existing['ips'][$ip_name] = array(
                    "ip" => $ip_address,
                    "name"  =>  $ip_name
            );

            $updated = $existing;
            $updated = json_encode($updated, true);

            $this->wpdb->query(
                $this->wpdb->prepare("UPDATE $this->settings_table SET settings_datas = '$updated' WHERE id = $row_id")
            );

            if ($this->wpdb->last_error) {
                return $_SESSION['error_message'] = "Erreur lors de l'insertion des données. <br>Erreur : " . $this->wpdb->last_error;
            } else {
                return $_SESSION['success_message'] = "Les données ont bien été enregistrées";
            }
        }

        public function addSettingsTarget($datas) {
            if (!isset($datas['target_name']) && empty($datas['target_name'])) {
                return $_SESSION['success_message'] = "Le champ de nom de l'élément à cibler n'est pas renseignée ou invalide";
            }
            if (!isset($datas['target_type']) && empty($datas['target_type'])) {
                return $_SESSION['success_message'] = "Le champ Type n'est pas renseigné ou vide";
            }

            $row_id = $datas['settings_id'];
            $target_name = htmlspecialchars($datas['target_name'], ENT_NOQUOTES, 'UTF-8', true);
            $target_type = $datas['target_type'];

            $existing = $this->getJSONDatas();
            $existing['targets'][$target_name] = array(
                "type" => $target_type,
                "element"  =>  $target_name
            );

            $updated = $existing;
            $updated = json_encode($updated, true);

            $this->wpdb->query(
                $this->wpdb->prepare("UPDATE $this->settings_table SET settings_datas = '$updated' WHERE id = $row_id")
            );

            if ($this->wpdb->last_error) {
                $_SESSION['error_message'] = "Erreur lors de l'insertion des données. <br>Erreur : " . $this->wpdb->last_error;
            } else {
                $_SESSION['success_message'] = "Les données ont bien été enregistrées";
            }

        }

        public function deleteSettingsIp($datas) {
            if (isset($datas) && !empty($datas)) {
                $ip = $datas['settings_ip'];
                $row_id = $datas['settings_id'];
                $pattern = "/^[0-9\.]+$/";
                // Test the input string against the pattern
                if (preg_match($pattern, $ip)) {
                    $existing = $this->getJSONDatas();
                    if (isset($existing['ips'][$datas['settings_ip_name']])) {
                        unset($existing['ips'][$datas['settings_ip_name']]);
                    }
                    $updated = json_encode($existing);

                    $this->wpdb->query(
                        $this->wpdb->prepare("UPDATE $this->settings_table SET settings_datas = '$updated' WHERE id = $row_id")
                    );

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

        public function deleteSettingsTarget($datas) {
            if (isset($datas) && !empty($datas)) {
                $name = $datas['target_name'];
                $row_id = $datas['settings_id'];

                // Test the input string against the pattern
                $existing = $this->getJSONDatas();
                if (isset($existing['targets'][$name])) {
                    unset($existing['targets'][$name]);
                }
                $updated = json_encode($existing);

                $this->wpdb->query(
                    $this->wpdb->prepare("UPDATE $this->settings_table SET settings_datas = '$updated' WHERE id = $row_id")
                );

                if ($this->wpdb->last_error) {
                    $_SESSION['success_message'] = "There is an error when execute the sql request: ". $this->wpdb->last_error;
                } else {
                    $_SESSION['success_message'] = "Informations has been correctly updated !";
                }
            }
        }
    }
}
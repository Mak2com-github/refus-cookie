<?php
/*
 * Refus cookie settings class
 */
if(!class_exists('RefusSettings')) {
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

        /**
         * @return string|null
         */

         // Fonction pour la table refus_cookie
        public function getRefusDate()
        {
            $query = $this->wpdb->get_var("SELECT DATE_FORMAT(created_at, '%e %b %Y') FROM $this->data_table");
            return $query;
        }

        // ARRAY_N permet de retourner un tableau 
        public function getRefusCookie() {
            $query = $this->wpdb->get_results("SELECT refus, created_at FROM $this->data_table WHERE refus = 0", ARRAY_N);
            return $query;       
        }

        public function getRefusNumber()
        {
            $query = $this->wpdb->get_var("SELECT count(*) FROM $this->data_table WHERE refus = 0");
            return $query;
        }

        public function getRefusInterval() 
        {
            if(isset($_POST['submit_date']) && !empty($_POST['submit_date'])){
                $dt1 = $_POST['debut'];
                $dt2 = $_POST['fin'];
                $dt1 = preg_replace('#([0-3].)/([0-1].)/(2.{3})#', '$3-$2-$1', $dt1); 
                $dt2 = preg_replace('#([0-3].)/([0-1].)/(2.{3})#', '$3-$2-$1', $dt2);
                // $query = $this->wpdb->get_results("SELECT refus FROM $this->data_table WHERE refus = 0 AND created_at BETWEEN '2023-03-07' AND '2023-03-08' ", ARRAY_N);
                $query = $this->wpdb->get_results("SELECT created_at FROM $this->data_table WHERE refus = 0 AND created_at BETWEEN '$dt1' AND '$dt2' ", ARRAY_N);
            
                return $query;
            }
            // $query= "SELECT FROM $this->data_table ";
        }

        // Fonction pour la table refus_cookie_config
        /**
         * @return array|object|null
         */
        public function getAllSettings()
        {
            $query = $this->wpdb->get_results("SELECT * FROM $this->settings_table WHERE id = 1");
            return $query;
        }

        /**
         * @return mixed|string
         */
        public function getAllElements() {
            $query = $this->wpdb->get_var("SELECT settings_datas FROM $this->settings_table WHERE id = 1");
            $queryDatas = json_decode($query, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return 'Error decoding : '.json_last_error_msg();
            }
            return $queryDatas['targets'];
        }

        /**
         * @return mixed|string
         */
        public function getAllIps() {
            $query = $this->wpdb->get_var("SELECT settings_datas FROM $this->settings_table WHERE id = 1");
            $queryDatas = json_decode($query, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return 'Error decoding : '.json_last_error_msg();
            }
            return $queryDatas['ips'];
        }

        /**
         * @return mixed|string
         */
        public function getAllTargets() {
            $query = $this->wpdb->get_var("SELECT settings_datas FROM $this->settings_table WHERE id = 1");
            $queryDatas = json_decode($query, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return 'Error decoding : '.json_last_error_msg();
            }
            return $queryDatas['targets'];
        }

        /**
         * @return mixed|string
         */
        public function getJSONDatas() {
            $query = $this->wpdb->get_var("SELECT settings_datas FROM $this->settings_table WHERE id = 1");
            $queryDatas = json_decode($query, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return 'Error decoding : '.json_last_error_msg();
            }
            return $queryDatas;
        }

        /**
         * @param $datas
         * @return string
         */
        public function addSettingsIP($datas)
        {
            if (!isset($datas['setting_ip']) && empty($datas['setting_ip'])) {
                return $_SESSION['success_message'] = "Le champ de l'adresse IP n'est pas renseignée ou vide";
            }
            if (!isset($datas['setting_name']) && empty($datas['setting_name'])) {
                return $_SESSION['success_message'] = "Le champ Nom n'est pas renseigné ou vide";
            }

            $updated_at = date('Y-m-d H:i:s');
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
                $this->wpdb->prepare("UPDATE $this->settings_table SET settings_datas = %s, updated_at = %s WHERE id = %d", $updated, $updated_at, $row_id)
            );

            if ($this->wpdb->last_error) {
                return $_SESSION['error_message'] = "Erreur lors de l'insertion des données. <br>Erreur : " . $this->wpdb->last_error;
            } else {
                return $_SESSION['success_message'] = "Les données ont bien été enregistrées";
            }
        }

        /**
         * @param $datas
         * @return string|void
         */
        public function addSettingsTarget($datas) {
            if (!isset($datas['target_name']) && empty($datas['target_name'])) {
                return $_SESSION['success_message'] = "Le champ de nom de l'élément à cibler n'est pas renseignée ou invalide";
            }
            if (!isset($datas['target_type']) && empty($datas['target_type'])) {
                return $_SESSION['success_message'] = "Le champ Type n'est pas renseigné ou vide";
            }

            $updated_at = date('Y-m-d H:i:s');
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
                $this->wpdb->prepare("UPDATE $this->settings_table SET settings_datas = %s, updated_at = %s WHERE id = %d", $updated, $updated_at, $row_id)
            );

            if ($this->wpdb->last_error) {
                $_SESSION['error_message'] = "Erreur lors de l'insertion des données. <br>Erreur : " . $this->wpdb->last_error;
            } else {
                $_SESSION['success_message'] = "Les données ont bien été enregistrées";
            }
        }

        /**
         * @param $datas
         */
        public function deleteSettingsIp($datas) {
            if (isset($datas) && !empty($datas)) {
                $updated_at = date('Y-m-d H:i:s');
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
                        $this->wpdb->prepare("UPDATE $this->settings_table SET settings_datas = %s, updated_at = %s WHERE id = %d", $updated, $updated_at, $row_id)
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

        /**
         * @param $datas
         */
        public function deleteSettingsTarget($datas) {
            if (isset($datas) && !empty($datas)) {
                $updated_at = date('Y-m-d H:i:s');
                $name = $datas['target_name'];
                $row_id = $datas['settings_id'];

                // Test the input string against the pattern
                $existing = $this->getJSONDatas();
                if (isset($existing['targets'][$name])) {
                    unset($existing['targets'][$name]);
                }
                $updated = json_encode($existing);

                $this->wpdb->query(
                    $this->wpdb->prepare("UPDATE $this->settings_table SET settings_datas = %s, updated_at = %s WHERE id = %d", $updated, $updated_at, $row_id)
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
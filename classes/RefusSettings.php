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

        public function addSettingsData($datas) {
            if (isset($datas['add_settings']) && !empty($datas['add_settings'])) {
                $json_data = [];
                if (
                    isset($datas['ip_settings']) &&
                    !empty($datas['ip_settings'])
                ) {
                    $ip_settings = htmlspecialchars($datas['ip_settings'], ENT_COMPAT,'UTF-8', true);
                    $ip_settings = json_encode($ip_settings);
                    array_push($json_data, array("ip_setting" => $ip_settings));
                }

                if (
                    isset($datas['element_id']) &&
                    !empty($datas['element_id'])
                ) {
                    $element_id = htmlspecialchars($datas['element_id'], ENT_COMPAT,'UTF-8', true);
                    $element_id = json_encode($element_id);
                    array_push($json_data, array("element_id" => $element_id));
                }

                $this->wpdb->insert($this->settings_table, array('settings_datas' => json_encode($json_data), 'updated_at' => $this->datetime, 'created_at' => $this->datetime));

                if ($this->wpdb->last_error) {
                    $_SESSION['error_message'] = "Erreur lors de l'insertion des données. <br>Erreur : " . $this->wpdb->last_error;
                } else {
                    $_SESSION['success_message'] = "Les données ont bien été enregistrées";
                }
            }
        }
    }
}
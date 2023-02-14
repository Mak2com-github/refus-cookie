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

        public function addSettingsIP($datas) {
            if (isset($datas['setting_ip']) && !empty($datas['setting_ip'])) {
                $row_id = $datas['settings_id'];
                $ip_settings = htmlspecialchars($datas['setting_ip'],ENT_NOQUOTES,'UTF-8', true);
                $ip_settings = json_encode($ip_settings);

                $this->wpdb->query(
                    $this->wpdb->prepare("UPDATE $this->settings_table SET settings_datas = JSON_SET(settings_datas, '$.ip_setting', $ip_settings) WHERE id = $row_id")
                );

                if ($this->wpdb->last_error) {
                    $_SESSION['error_message'] = "Erreur lors de l'insertion des données. <br>Erreur : " . $this->wpdb->last_error;
                } else {
                    $_SESSION['success_message'] = "Les données ont bien été enregistrées";
                }
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
    }
}
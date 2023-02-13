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

        public function __construct()
        {
            global $wpdb;
            $this->wpdb = $wpdb;
            $this->charset_collate = $this->wpdb->charset;
            $this->wpdb_collate = $this->wpdb->collate;
            $this->wpdb_charset = $this->wpdb->charset;
            $this->data_table = $this->wpdb->prefix . 'refus_cookie';
            $this->settings_table = $this->wpdb->prefix . 'refus_cookie_config';
        }

        public function getAllSettings()
        {
            $query = $this->wpdb->get_results("SELECT * FROM $this->data_table");
            return $query;
        }


    }
}
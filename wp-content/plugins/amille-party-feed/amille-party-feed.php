<?php
/**
 * 
 *
 * Plugin Name: Amille's Party Feed
 * Plugin URI:  https://wordpress.org
 * Description: Feed de Fotos
 * Version:     1.0
 * Author:      Matheus Arruda
 * Text Domain: amille-party-feed
 * Requires PHP: 5.0
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

 if(!defined('ABSPATH')){
    exit;
 }

 if(!class_exists('Amille_Party_Feed')){
    class Amille_Party_Feed{
        function __construct(){
            $this->define_constants();
            require_once(AMILLE_PARTY_FEED_PATH.'post-types/class.amille_party_feed_cpt.php');
            $amille_party_feed_post_type = new Amille_Party_Feed_Post_Type();
            add_action('wp_ajax_update_photos_feed', array($this, 'update_photos_feed'));
            add_action('wp_ajax_get_photos_feed', array($this, 'get_photos_feed'));
        }

        public function define_constants(){
            define('AMILLE_PARTY_FEED_PATH', plugin_dir_path(__FILE__));
            define('AMILLE_PARTY_FEED_URL', plugin_dir_url(__FILE__));
            define('AMILLE_PARTY_FEED_VERSION', '1.0.0');
        }

        public static function activate(){
            update_option('rewrite_rules', '');

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            global $wpdb;

            $table_name = $wpdb->prefix.'users_feed';
            $charset_collate = $wpdb->get_charset_collate();

            $version = get_option('users_feed_version');

            if($version)
            {

                // $sql = "CREATE TABLE " . $table_name . " (
                //     id INT NOT NULL AUTO_INCREMENT,
                //     name VARCHAR(30) NOT NULL,
                //     ) $charset_collate;";

                // require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

                $sql = "CREATE TABLE " . $table_name . " (
                    id INT NOT NULL AUTO_INCREMENT, 
                    name TEXT NOT NULL, 
                    PRIMARY KEY  (id)
                ) ". $charset_collate .";";

                dbDelta($sql);

                $version = '1.0.0';

                add_option('users_feed_version', $version);
            }
        }

        public static function deactivate(){
            flush_rewrite_rules();
            unregister_post_type('amille_guest_list');
        }

        public static function uninstall(){
            
        }

        public function die_json_status_code(array $array, int $statusCode)
        {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code($statusCode);
            die(json_encode($array));
        }

        public static function update_photos_feed(){
            $resp = update_post_meta($_POST['postId'], 'photos-feed', $_POST['obj']);
            if($resp){
                die_json_status_code(['msg' =>  'Salvo com sucesso!'], 200);
            }else{
                die_json_status_code(['msg' => 'Erro ao salvar lista!'], 404);
            }
        }

        public static function get_photos_feed($post_id){
            $data = get_post_meta($_POST['postId'], 'photos-feed', true);
            if($data){
                die_json_status_code(['data' =>  $data], 200);
            }else{
                die_json_status_code(['msg' => 'Erro buscar fotos!'], 404);
            }
        }
    }
 }

 if(class_exists('Amille_Party_Feed')){
    register_activation_hook(__FILE__, array('Amille_Party_Feed', 'activate'));
    register_deactivation_hook(__FILE__, array('Amille_Party_Feed', 'deactivate'));
    register_uninstall_hook(__FILE__, array('Amille_Party_Feed', 'uninstall'));
    $amille_party_feed = new Amille_Party_Feed();
 }

 
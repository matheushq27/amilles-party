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
            //add_action('admin_menu', array($this, 'add_menu'));
            require_once(AMILLE_PARTY_FEED_PATH.'post-types/class.amille_party_feed_cpt.php');
            $amille_party_feed_post_type = new Amille_Party_Feed_Post_Type();
            add_action('wp_ajax_update_photos_feed', array($this, 'update_photos_feed'));
            add_action('wp_ajax_get_photos_feed', array($this, 'get_photos_feed'));
            //require_once(AMILLE_PARTY_FEED_PATH.'class.feed_settings.php');
            //$amille_party_feed_settings = new Feed_Settings();

            add_action('admin_footer', array($this, 'remove_genre_fields'));
            add_filter('manage_edit-category_feed_columns', array($this, 'remove_columns'));
            add_action('manage_category_feed_custom_column', array($this, 'manage_custom_columns'), 10, 3);
            //add_action('category_feed_add_form_fields', array($this, 'add_form_field'));
            add_action('category_feed_edit_form_fields', array($this, 'edit_form_field'), 10, 2);
            add_action('created_category_feed', array($this, 'created_category_photo'));
            add_action('edited_category_feed', array($this, 'created_category_photo'));
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

        public function add_menu(){
            add_menu_page('Feed', 'Feed', 'manage_options', 'feed_admin', array($this, 'page_settings_feed'), 'dashicons-instagram', 10);
            add_submenu_page('feed_admin', 'Todos os Feeds', 'Todos os Feeds', 'manage_options', 'edit.php?post_type=amille_feed', null, null);
            add_submenu_page('feed_admin', 'Adicionar Feed', 'Adicionar Feed', 'manage_options', 'post-new.php?post_type=amille_feed', null, null);
            add_submenu_page('feed_admin', 'Categorias', 'Categorias', 'manage_options', 'edit-tags.php?taxonomy=category_feed&post_type=amille_feed', null, null);
        }

        public function page_settings_feed(){
            require(AMILLE_PARTY_FEED_PATH . 'views/settings-page.php');
        }

        public function remove_genre_fields()
        {
            global $current_screen;
            if($current_screen->id == 'edit-category_feed'){
                wp_enqueue_media();
                require_once(AMILLE_PARTY_FEED_PATH.'remove_genre_fields.php');
            }
        }

        public function add_form_field()
        {
            require_once(AMILLE_PARTY_FEED_PATH.'form_field_category_photo.php');
        }

        public function edit_form_field($term, $taxonomy)
        {
            require_once(AMILLE_PARTY_FEED_PATH.'form_field_edit_category_photo.php');
        }

        public function created_category_photo($term_id){
            update_term_meta($term_id, 'photo-category', sanitize_text_field($_POST['photo-category']));
        }

        public function remove_columns($columns){
            if($columns['description'])
            {
                unset($columns['description']);
                unset($columns['posts']);
                unset($columns['slug']);
                $columns['photo'] = 'Foto';
            }
            
            return $columns;
        }

        public function manage_custom_columns($string, $columns, $term_id){
            switch($columns){
                case 'photo':
                    echo '<img src="'.get_term_meta($term_id, 'photo-category', true).'" width="80">';
                    break;
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

 
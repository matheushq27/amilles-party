<?php
/**
 * 
 *
 * Plugin Name: Amille's Party Guest List
 * Plugin URI:  https://wordpress.org
 * Description: Lista de convidados da festa da Amille
 * Version:     1.0
 * Author:      Matheus Arruda
 * Text Domain: amille-party-guest-list
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

 if(!class_exists('Amille_Party_Guest_Lit')){
    class Amille_Party_Guest_Lit{
        function __construct(){
            $this->define_constants();
            require_once(AMILLE_PARTY_GUEST_LIST_PATH.'post-types/class.amille_party_guest_list_cpt.php');
            $amille_party_guest_list_post_type = new Amille_Party_Guest_List_Post_Type();
            add_action('wp_ajax_update_guest', array($this, 'update_guest'));
        }

        public function define_constants(){
            define('AMILLE_PARTY_GUEST_LIST_PATH', plugin_dir_path(__FILE__));
            define('AMILLE_PARTY_GUEST_LIST_URL', plugin_dir_url(__FILE__));
            define('AMILLE_PARTY_GUEST_LIST_VERSION', '1.0.0');
        }

        public static function activate(){
            update_option('rewrite_rules', '');
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

        public static function update_guest(){
            $resp = update_post_meta($_POST['postId'], 'guests', $_POST['obj']);
            if($resp){
                die_json_status_code(['msg' =>  'Lista atualizada com sucesso!'], 200);
            }else{
                die_json_status_code(['msg' => 'Erro ao atualizar lista!'], 404);
            }
        }
    }
 }

 if(class_exists('Amille_Party_Guest_Lit')){
    register_activation_hook(__FILE__, array('Amille_Party_Guest_Lit', 'activate'));
    register_deactivation_hook(__FILE__, array('Amille_Party_Guest_Lit', 'deactivate'));
    register_uninstall_hook(__FILE__, array('Amille_Party_Guest_Lit', 'uninstall'));
    $amille_party_guest_lit = new Amille_Party_Guest_Lit();
 }

 
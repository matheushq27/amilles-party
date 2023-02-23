<?php

if(!class_exists('Amille_Party_Guest_Lit_Post_Type')){
    class Amille_Party_Guest_List_Post_Type{

        function __construct(){
            add_action('init', array($this, 'create_post_type'));
            add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        }

        public function create_post_type(){

            $labels = array(
                'name'                  => 'Lista de Convidados',
                'singular_name'         => 'Lista de Convidado',
                'menu_name'             => 'Lista de Convidados',
                'name_admin_bar'        => 'Lista de Convidados',
                'add_new'               => 'Adicionar Convidao',
                'add_new_item'          => 'Adicionar novo Convidado',
                'new_item'              => 'Novo Convidado',
                'edit_item'             => 'Editar Convidado',
                'view_item'             => 'Visualizar Convidado',
                'all_items'             => 'Todos os convidados',
                'search_items'          => 'Procurar Convidados',
            );
        
            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'book' ),
                'hierarchical'       => false,
                'menu_position'      => 5,
                'supports'           => array( 'title'),
                'exclude_from_search' => false,
                'menu_icon' => 'dashicons-media-text'
            );

            register_post_type( 'amille_guest_list', $args );

            $argsTaxonomy = array(
                'hierarchical'      => true,
                //'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array( 'slug' => 'genre' ),
            );


            register_taxonomy( 'category_list', 'amille_guest_list', $argsTaxonomy );
        }

        public function add_meta_boxes(){
            add_meta_box(
                'amille_guest_list_meta_box',
                'Lista de Acompanhantes',
                array($this, 'add_amille_guest_list_meta_box'),
                'amille_guest_list',
                'normal',
                'high'
            );
        }

        public function add_amille_guest_list_meta_box($post){
           require_once(AMILLE_PARTY_GUEST_LIST_PATH.'views/amille_guest_list_meta_box.php');
        }

        public function admin_enqueue_scripts(){
            global $typenow;
            if($typenow != 'amille_guest_list'){
                return;
            }

            wp_register_style('save-guests', AMILLE_PARTY_GUEST_LIST_URL.'assets/css/SaveGuests.css', array(), '1.0', 'all');
            wp_register_script('save-guests', AMILLE_PARTY_GUEST_LIST_URL.'assets/js/SaveGuests.js', array('jquery'), '1.0', true);
            wp_enqueue_script('save-guests');
            wp_enqueue_style('save-guests');
        }
    }
}
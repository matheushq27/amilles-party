<?php

if(!class_exists('Amille_Party_Feed_Post_Type')){
    class Amille_Party_Feed_Post_Type{

        function __construct(){
            add_action('init', array($this, 'create_post_type'));
            add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
            
        }

        public function create_post_type(){

            $labels = array(
                'name'                  => 'Feed',
                'singular_name'         => 'Feed',
                'menu_name'             => 'Feed',
                'name_admin_bar'        => 'Feed',
                'add_new'               => 'Adicionar Feed',
                'add_new_item'          => 'Adicionar novo Feed',
                'new_item'              => 'Novo Feed',
                'edit_item'             => 'Editar Feed',
                'view_item'             => 'Visualizar Feed',
                'all_items'             => 'Todos os Feeds',
                'search_items'          => 'Procurar Feed',
            );
        
            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'foto' ),
                'hierarchical'       => false,
                'menu_position'      => 5,
                'supports'           => array( 'title', 'editor', 'comments'),
                'exclude_from_search' => false,
                'menu_icon' => 'dashicons-instagram'
            );

            register_post_type( 'amille_feed', $args );

            $argsTaxonomy = array(
                'hierarchical'      => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => array( 'slug' => 'category' ),
            );


            register_taxonomy( 'category_feed', 'amille_feed', $argsTaxonomy );
        }

        public function add_meta_boxes(){
            add_meta_box(
                'amille_feed_meta_box',
                'Fotos',
                array($this, 'add_amille_feed_meta_box'),
                'amille_feed',
                'normal',
                'high'
            );
        }

        public function add_amille_feed_meta_box($post){
           require_once(AMILLE_PARTY_FEED_PATH.'views/amille_feed_meta_box.php');
        }

        public function admin_enqueue_scripts($hook_suffix){
            global $typenow;
            if($typenow != 'amille_feed'){
                return;
            }

            wp_enqueue_media();
            wp_register_style('styles', AMILLE_PARTY_FEED_URL.'assets/css/styles.css', array(), '1.0', 'all');
            wp_register_script('scripts-feed', AMILLE_PARTY_FEED_URL.'assets/js/FeedPhotos.js', array('jquery'), '1.0', true);
            wp_enqueue_script('scripts-feed');
            wp_enqueue_style('styles');
        }
    }
}
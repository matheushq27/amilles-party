<?php

if(!class_exists('Feed_Settings')){
    class Feed_Settings{
        public static $options;

        public function __construct(){
            self::$options = get_option('feed_options');
            add_action('admin_init', array($this, 'admin_init'));
            add_action('admin_enqueue_scripts', array($this, 'my_admin_enqueue'));
        }

        public function admin_init(){
            register_setting('feed_group', 'feed_options');

            add_settings_section(
                'feed_main_section',
                'Configurações de Usuários',
                null,
                'feed_page1'
            );

            add_settings_field(
                'feed_settings_name',
                'Nome Usuário',
                array($this, 'feed_config_callback_name'),
                'feed_page1',
                'feed_main_section'
            );

            add_settings_field(
                'feed_settings_photo',
                'Foto de Perfil',
                array($this, 'feed_config_callback_photo'),
                'feed_page1',
                'feed_main_section'
            );
        }

        public function feed_config_callback_name(){
            ?>
            <input type="text" value="<?= isset(self::$options['feed_settings_name']) ? esc_attr(self::$options['feed_settings_name']) : ''  ?>" name="feed_options[feed_settings_name]">
            <?php
        }

        public function feed_config_callback_photo(){
            ?>
            <input type="hidden" value="<?= isset(self::$options['feed_settings_photo']) ? esc_attr(self::$options['feed_settings_photo']) : ''  ?>" name="feed_options[feed_settings_photo]">
            <div id="box-img-1" class="box-img-1">

            </div>
            <div id="metabox-wrapper">
                <input type="hidden" id="img-hidden-field" class="img-hidden-field" name="custom_image_data" data-user="#box-img-1">
                <input type="button" id="img-upload-button" class="button img-upload-button" value="Adicionar Foto">
                <input type="button" id="img-delete-button" class="button img-delete-button" value="Remover Foto">
                <span class="spinner spinner-save-photos"></span>
            </div>
            <?php
        }

        public function my_admin_enqueue(){
            $my_current_screen = get_current_screen();
            if($my_current_screen->base != 'toplevel_page_feed_admin'){
                return;
            }
            wp_enqueue_style('styles', AMILLE_PARTY_FEED_URL.'assets/css/FeedSettings.css', array(), '1.0', 'all');
            wp_enqueue_media();
            wp_enqueue_script('scripts-feed', AMILLE_PARTY_FEED_URL.'assets/js/FeedSettings.js', array('jquery'), '1.0', true);
        }
    }
}

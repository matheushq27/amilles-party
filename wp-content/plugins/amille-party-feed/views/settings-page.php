<div class="wrap">
    <h1><?= esc_html(get_admin_page_title()) ?></h1>
    <form action="options.php" method="post">
        <?php
            settings_fields('feed_group');
            do_settings_sections('feed_page1');
            submit_button('Salvar')
        ?>
    </form>
</div>
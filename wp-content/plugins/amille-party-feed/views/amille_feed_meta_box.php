<?php
$data = get_post_meta($post->ID, 'photos-feed', true);
//var_dump($data);
?>

<div class="main-photos">
    <div id="container-photo">
        <?php if(!empty($data)): ?>
            <?php foreach($data as $d): ?>
                <div id="item-photo-<?= $d['id'] ?>" class="item-photo" style="background-image: url(<?= $d['url'] ?>);">
                    <button type="button" class="remove-photo" data-id="<?= $d['id'] ?>">x</button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center">
                <p class="photo-null">Nenhuma foto selecionada</p>
            </div>
        <?php endif; ?>
    </div>
    <div id="metabox-wrapper">
        <input type="hidden" id="img-hidden-field" name="custom_image_data">
        <input type="button" id="img-upload-button" class="button" value="Adicionar Foto">
        <input type="button" id="img-delete-button" class="button" value="Remover Todas as Fotos">
        <input type="button" id="save-photos" class="button button-primary button-large" value="Salvar Fotos" post-id="<?= $post->ID ?>">
        <span class="spinner spinner-save-photos"></span>
    </div>
</div>

<div id="message-update"></div>

<?php
wp_localize_script('scripts-feed', 'url', array('ajax_url' => admin_url('admin-ajax.php')));
?>

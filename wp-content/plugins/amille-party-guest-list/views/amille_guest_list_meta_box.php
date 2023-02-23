<?php
$data = get_post_meta($post->ID, 'guests', true);
if(empty($data)){
    $data = [
        array(
            'id' => '',
            'name' => '',
            'surname' => '',
            'category' => 'adult',
            'guestType' => 'guest'
        )
    ];
}
?>

<div style="overflow-x: auto;">
<table class="form-table">
    <tbody class="body-table">
    <?php foreach($data as $key => $guest): ?>
       
        <tr class="tr-infos" id-guest="<?= $guest['id'] ?>" guest-type="accompanying">
            <th scope="row">
                <label for="<?= 'name-'.$key ?>">Nome</label>
                <input key-guest="name"  name="<?= 'name-'.$key ?>" type="text" id="<?= 'name-'.$key ?>" class="regular-text guest-value" value="<?= $guest['name'] ?>">
            </th>
            <th>
                <label for="<?= 'surname-'.$key ?>">Sobrenome</label>
                <input key-guest="surname" name="<?= 'surname-'.$key ?>" type="text" id="<?= 'surname-'.$key ?>" class="regular-text guest-value" value="<?= $guest['surname'] ?>">
            </th>
            <td>
                <?php
                    $categoryAdult = $guest['category'] == 'adult' ? 'selected': '';
                    $categoryAdolescent = $guest['category'] == 'adolescent' ? 'selected': '';
                ?>
                <div><label><b>Idade</b></label></div>
                <select key-guest="category"  class="guest-value" name="<?= 'category-'.$key ?>" id="<?= 'category-'.$key ?>">
                    <option value="adult" <?= $categoryAdult ?>>Adulto</option>
                    <option  value="adolescent" <?= $categoryAdolescent ?>>Adolescente (10 a 17 anos)</option>
                </select>
            </td>
            <?php if(!empty($guest['id'])): ?>
            <td><div><span class="dashicons dashicons-trash remove-item"></span></div></td>
            <?php endif; ?>
        </tr>
        
    <?php endforeach; ?>
    </tbody>
</table>

<div style="display: flex; gap: 5px;margin-bottom: 20px;"><input post-id="<?= $post->ID ?>" type="submit" id="save-guests" class="button button-primary" value="Salvar">
<input type="submit" id="add-guests" class="button button-primary" value="Adicionar">
    <span class="spinner spinner-save-guest"></span>
    <span id="message-update"></span>
</div>
</div>

<?php
wp_localize_script('save-guests', 'url', array('ajax_url' => admin_url('admin-ajax.php')));
?>
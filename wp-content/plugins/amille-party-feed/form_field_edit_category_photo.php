<?php
$velue = get_term_meta($term->term_id, 'photo-category', true);
$no_img = get_template_directory_uri().'/assets/img/no-perfil.jpg';
$velue = $velue ? $velue : $no_img ;
?>

<style>
                .box-img{
                    background-position: center;
                    background-repeat: no-repeat;
                    background-size: cover;
                    height: 80px;
                    width: 80px;
                }

                #metabox-wrapper{
                    margin-top: 15px;
                }
            </style>

                        <tr class="form-field">
                            <th scope="row"><label>Foto de Perfil</label></th>
                            <td>
                            <input type="hidden" name="photo-category" id="photo-category" value="<?= $velue ?>">
                            <div class="box-img" style="background-image: url(<?= $velue ?>)" data-img="<?= $no_img  ?>"></div>
                                <p>Adicione uma foto a sua categoria</p>
                                <div id="metabox-wrapper">
                                    <input type="button" id="img-upload-button" class="button img-upload-button" value="Adicionar Foto">
                                    <input type="button" id="img-delete-button" class="button img-delete-button" value="Remover Foto">
                                </div>
                            </td>
                        </tr>

                        <?php
                        include 'remove_genre_fields.php';
                        ?>
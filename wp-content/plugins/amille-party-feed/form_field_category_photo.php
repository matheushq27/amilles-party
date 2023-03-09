<style>
                .box-img{
                    background-position: center;
                    background-repeat: no-repeat;
                    background-size: cover;
                    height: 80px;
                    width: 80px;
                    background-color: red;
                }

                #metabox-wrapper{
                    margin-top: 15px;
                }
            </style>
                <div class="form-field">
                    <label for="photo-category">Foto</label>
                    <input type="hidden" name="photo-category" id="photo-category">
                    <div class="box-img"></div>
                    <p>Adicione uma foto a sua categoria</p>
                    <div id="metabox-wrapper">
                        <input type="button" id="img-upload-button" class="button img-upload-button" value="Adicionar Foto">
                        <input type="button" id="img-delete-button" class="button img-delete-button" value="Remover Foto">
                    </div>
                        </div>
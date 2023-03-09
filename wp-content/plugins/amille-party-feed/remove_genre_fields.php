<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#tag-description').parent().remove()
        $('#tag-slug').parent().remove()
        $('.term-parent-wrap, .term-description-wrap, .term-slug-wrap').remove()

        let customUploader = wp.media({
            title: 'Selecione a imagem',
            library: {type: "image"},
            button: {
                text: 'Selecionar'
            },
            multiple: true
        })

        $('#img-upload-button').on('click', function(e){
            let userCurrent = jQuery(e.currentTarget).attr('data-user')
            this.userCurrent = userCurrent
            if(customUploader){
                customUploader.open()
            } 
        })

        customUploader.on('select', () => {
            let attachment = customUploader.state().get('selection').first().toJSON()
            $('.box-img').css('background-image', 'url(' + attachment.url + ')');
            $('#photo-category').val(attachment.url)
            $('.media-modal-close').click()
            console.log(attachment)
        })

        $('#img-delete-button').on('click', function(e){
            $('#photo-category').val('')
            let noImage = $('.box-img').attr('data-img')
            $('.box-img').css('background-image', 'url(' + noImage + ')');
        })
        
    })
</script>
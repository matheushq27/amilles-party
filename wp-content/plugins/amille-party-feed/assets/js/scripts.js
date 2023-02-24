let addButton = jQuery('#img-upload-button')
let deleteButton = jQuery('#img-delete-button')
let hidden = jQuery('#img-hidden-field')

let customUploader = wp.media({
    title: 'Selecione a imagem',
    library: {type: "image"},
    button: {
        text: 'Selecionar'
    },
    multiple: true
})

addButton.on('click', function(){
    if(customUploader){
        customUploader.open()
    } 
})

customUploader.on('select', function(){
    let attachment = customUploader.state().get('selection').first().toJSON()
    //jQuery('.item-photo').css('background-image', 'url(' + attachment.url + ')')
    console.log(attachment)
})

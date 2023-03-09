class FeedSettings{
    constructor(){

        this.addButton = jQuery('.img-upload-button')
        this.deleteAllPhotosButton = jQuery('.img-delete-button')
        this.userCurrent = ''

        this.photoNull = jQuery('.photo-null')

        this.arrayObjImg = []

        let customUploader = wp.media({
            title: 'Selecione a imagem',
            library: {type: "image"},
            button: {
                text: 'Selecionar'
            },
            multiple: true
        })

        this.addButton.on('click', (e) => {
            let userCurrent = jQuery(e.currentTarget).attr('data-user')
            this.userCurrent = userCurrent
            if(customUploader){
                customUploader.open()
            } 
        })

        this.deleteAllPhotosButton.on('click', () => {
            this.deleteArrayObjImg()
            jQuery('.item-photo').remove()
            this.showMessagePhotoNull(true)
        })

        jQuery('body').on('click', '.remove-photo',(e) => {
            let id = jQuery(e.currentTarget).attr('data-id')
            this.removeLayoutItemPhotos(id)
            let filter = this.filterById(id)
            this.setArrayObjImg(filter)
            console.log(this.getArrayObjImg())
            if(this.getArrayObjImg().length <= 0){
                this.showMessagePhotoNull(true)
            }
            //console.log(this.getArrayObjImg()) 
        })

        customUploader.on('select', () => {
            this.showMessagePhotoNull(false)
            let attachment = customUploader.state().get('selection').first().toJSON()
            let filter = this.filterById(attachment.id, 'checkIfThereIsInTheArray')
            if(!filter){
                this.pushArrayObjImg({id: attachment.id, url: attachment.url})
                jQuery('#container-photo').append(this.layoutItemPhotos(attachment))
            }
            console.log(attachment)
            
        })

        this.init()
    }

    getArrayObjImg(){
        return this.arrayObjImg
    }

    setArrayObjImg(array){
        this.arrayObjImg = array
    }

    deleteArrayObjImg(){
        this.arrayObjImg = []
    }

    pushArrayObjImg(array){
        this.arrayObjImg.push(array)
    }

    filterById(id, method = "remove"){

        switch(method){
            case 'remove':
                let newArray = this.getArrayObjImg().filter(photo => photo.id != id)
                return newArray
            
            case 'checkIfThereIsInTheArray':
                let array = this.getArrayObjImg().filter(photo => photo.id == id)
                let check = false
                if(array.length > 0){
                    check = true
                }
                return check
        }
    }

    init(){
        this.getPhotosFeed()
    }

    async getPhotosFeed(){
        let id = jQuery('#save-photos').attr('post-id')
        let resp = ''
        if(id){
            try{
                resp = await jQuery.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data:{
                        action: 'get_photos_feed',
                        postId: id
                    }
                })
                resp = resp.data
                this.setArrayObjImg(resp)
            }catch(error){
                console.log(error.responseJSON.msg)
            }
        }
    }

    removeLayoutItemPhotos(idRemove){
        let id = '#item-photo-'+idRemove
        jQuery(id).remove()
    }

    layoutItemPhotos(objImage){
        return `
        <div id="item-photo-${objImage.id}" class="item-photo" style="background-image: url(${objImage.url});">
            <button type="button" class="remove-photo" data-id="${objImage.id}">x</button>
        </div>
        `
    }

    showMessagePhotoNull(show = true){
        if(show){
            this.photoNull.removeClass('d-none')
        }else{
            this.photoNull.addClass('d-none')
        }
    }

    messageUpdate(msg, type = "success"){
        let messageUpdate = jQuery('#message-update')
        let layout = `<span class="message-success">${msg}</span>`
        if(type == "error"){
            layout = `<span class="message-error">${msg}</span>`
        }
        messageUpdate.html(layout)
        setTimeout(()=> messageUpdate.html(''), 5000)
    }

    showLoadingSpinner(active = true){
        let spinner =  jQuery('.spinner-save-photos')
        if(active){
           spinner.css('visibility', 'visible')
        }else{
           spinner.css('visibility', 'hidden')
        }
    }
}

new FeedSettings()





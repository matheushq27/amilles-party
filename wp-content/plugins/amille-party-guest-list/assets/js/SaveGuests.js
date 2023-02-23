class SaveGuests{
    constructor(){
        this.getValuesArray()

        jQuery('#add-guests').on('click', async (e)=>{
            e.preventDefault()
            jQuery('.body-table').append(this.layoutNewGuest())
        })
        
        jQuery('#save-guests').on('click', async (e)=>{
            e.preventDefault()
            this.showLoadingSpinner()
            let id = jQuery(e.target).attr('post-id')
            let resp = ''
            let typeMessage = 'success'
            try{
                resp = await jQuery.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data:{
                        action: 'update_guest',
                        obj: this.getValuesArray(),
                        postId: id
                    }
                })
                resp = resp.msg
            }catch(error){
                resp = error.responseJSON.msg
                typeMessage = 'error'
            }
            
            this.showLoadingSpinner(false)
            this.messageUpdate(resp, typeMessage)
        })

        jQuery('body').on('click', '.remove-item', async (e)=>{
            jQuery(e.target).parent().parent().parent().remove()
        })
    }

    getValuesArray(){
        let array = []
        let obj = {}
        jQuery('.tr-infos').map((i, e)=>{
            let idGuest = jQuery(e).attr('id-guest')
            let guestType = jQuery(e).attr('guest-type')
            jQuery(e).find('.guest-value').map((index, el)=>{
                let key = jQuery(el).attr('key-guest')
                let value  = jQuery(el).val()
                obj[key] = value
            })
            obj.id = this.create_UUID()
            obj.guestType = guestType
            array.push(obj)
            obj = {}
        })

       return array
    }

    showLoadingSpinner(active = true){
        let spinner =  jQuery('.spinner-save-guest')
        if(active){
           spinner.css('visibility', 'visible')
        }else{
           spinner.css('visibility', 'hidden')
        }
    }

    messageUpdate(msg, type = "success"){
        let messageUpdate = jQuery('#message-update')
        let layout = `<span style="display: flex;align-items: center;font-weight: bold;background-color: green;color: white;padding: 5px;">${msg}</span>`
        if(type == "error"){
            layout = `<span style="display: flex;align-items: center;font-weight: bold;background-color: red;color: white;padding: 5px;">${msg}</span>`
        }
        messageUpdate.html(layout)
        setTimeout(()=> messageUpdate.html(''), 5000)
    }

   layoutNewGuest(){
    let layout = `
    <tr class="tr-infos" id-guest="" guest-type="accompanying">
        <th scope="row">
            <label for="">Nome</label>
            <input key-guest="name"  name="" type="text" id="" class="regular-text guest-value">
        </th>
        <th scope="row">
            <label for="">Sobrenome</label>
            <input key-guest="surname" name="" type="text" id="" class="regular-text guest-value" value="">
        </th>
        <td>
            <div><label><b>Idade</b></label></div>
            <select key-guest="category" class="guest-value" name="" id="">
                <option value="adult" selected>Adulto</option>
                <option  value="adolescent">Adolescente (10 a 17 anos)</option>
            </select>
        </td>
        <td><div><span class="dashicons dashicons-trash remove-item"></span></div></td>
    </tr>
    `
    return layout
    }

    create_UUID(){
        var dt = new Date().getTime();
        var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = (dt + Math.random()*16)%16 | 0;
            dt = Math.floor(dt/16);
            return (c=='x' ? r :(r&0x3|0x8)).toString(16);
        });
        return uuid;
    }

    
}

new SaveGuests()
class ConfirmPresence{
    constructor()
    {
        /*ELEMENTS*/
         this.confirmPresenceForm = document.querySelector('#confirm-presence-form')

         this.btnCategoryGuest = document.querySelectorAll('.btn-category-guest')
         this.btnAddGuest = document.querySelector('#add-guest')
         this.btnDeleteGuest = document.querySelectorAll('.delete-guest')
         this.btnSaveList = document.querySelector('#btn-save-list')
         this.btnDeleteList = document.querySelector('#btn-delete-list')

         this.elName = document.querySelector('#name')
         this.elSurname = document.querySelector('#surname')
         this.elNameRequired = document.querySelector('.name-required')
         this.elSurnameRequired = document.querySelector('.surname-required')
         this.elAreaAlerts = document.querySelector('#area-alerts')

         this.listGuestArea = document.querySelector('#list-guest-area')
         this.listGuestModel = document.querySelector('#list-guest-model .list-item-guest')


         /*GLOBALS*/
        this.arrayGuest = []
        this.keyListGuest = 'list-guest-amille-party'
        this.idList = ''

        /*ACTIONS*/
         this.btnCategoryGuest.forEach((btn) => {
            btn.addEventListener("click", (e) => {
                e.preventDefault()
                this.categoryGuestActive(e.currentTarget)
            })
        })

        this.btnSaveList.addEventListener("click", async (e) => {
            e.preventDefault()
            let list = localStorage.getItem(this.keyListGuest)
            let listGuestEnd = this.getGuest()
            let idList = this.getIdList()
            let resp = ''
            
            try{
                resp = await jQuery.ajax({
                    type: 'POST',
                    url: url.ajax_url,
                    data:{
                        action: 'insert_guest',
                        obj: listGuestEnd,
                        idList: idList
                    }
                })
            }catch(error){

                if(error.status == 500){
                    let responseJSON = {msg: 'Erro nos nossos servidores. Tente novamente mais tarde.'}
                    resp = {responseJSON}
                }else{
                    resp = error
                }
            }
            
            if(typeof resp.idList != "undefined" && resp.idList)
            {
                listGuestEnd = {idList: resp.idList, list: this.getGuest()}
                this.alert(true, resp.msg)
                this.showBtnSave(false)
            }else
            {
                this.alert(false, resp.responseJSON.msg)
            }

            if(typeof resp.idList != 'undefined'){
                this.setIdList(resp.idList)
                localStorage.setItem(this.keyListGuest, JSON.stringify(resp.idList))
            }
        })

        this.btnDeleteList.addEventListener("click", async (e) => {
            e.preventDefault()
            let resp = await this.deleteGuestsList(this.getIdList())
            if(resp){
                this.dropListGuest()
                this.showBtnsActions()
                this.listGuestArea.innerHTML = ''
                localStorage.setItem(this.keyListGuest, '')
            }
        })

       
        this.btnAddGuest.addEventListener("click", (e) => {
            e.preventDefault()
            let name =  this.elName.value
            let surname = this.elSurname.value
            let guestType = this.getGuest().length == 0 ? 'guest' : 'accompanying'
            let resp = this.infosGuestValidate(name, surname)
            if(resp){
                let category = document.querySelector('.btn-category-guest.category-guest-active').getAttribute('data-category-guest')
                let obj = {id: this.create_UUID(), name: name, surname: surname, guestType: guestType,category: category}
                this.addGuest(obj)
                this.toCleanInputs()
                console.log(this.getGuest())
                this.showBtnSave(true)
                this.guestItemLayout(obj, guestType)
            }
        })

        document.querySelector('#send-email').addEventListener("click", async (e) => {
            e.preventDefault()
            console.log('CLickou')
            let resp = ''
            try{
                resp = await jQuery.ajax({
                    type: 'POST',
                    url: url.ajax_url,
                    data:{
                        action: 'send_email'
                    }
                })
                
            }catch(error){

                if(error.status == 500){
                    let responseJSON = {msg: 'Erro nos nossos servidores. Tente novamente mais tarde.'}
                    resp = {responseJSON}
                }else{
                    resp = error
                }
            }
            console.log(resp)    

        })
        
    
        this.init()
        
    }

    /*METHODS ARRAY GUEST*/
    getGuest(){
        return this.arrayGuest
    }

    setGuest(array){
        this.arrayGuest = array
    }

    addGuest(objGuest){
        this.arrayGuest.push(objGuest)
    }

    deleteGuest(id){
        let newArrayGuest = this.getGuest().filter(guest => guest.id != id)
        this.setGuest(newArrayGuest)
    }

    checkIfYouAreGuest(id){
        let resp = false
        this.getGuest().map((guest)=>{
            if(guest.guestType == 'guest'){
                resp = true
            }
        })
        return resp
    }

    changeGuest(id){
        let newArray = []
        this.deleteGuest(id)
        this.getGuest().map((guest, index, array)=>{
            if(index == array.length - 1){
                guest.guestType = 'guest'
                newArray.push(guest)
            }else{
                newArray.push(guest) 
            }
        })
        this.setGuest(newArray)
    }

    dropListGuest(){
        this.arrayGuest = []
    }

    getIdList(){
        return this.idList
    }

    setIdList(id){
         this.idList = id
    }

    checkIfTheListIsEmpty(){
        if(this.getGuest().length > 0){
            return false
        }else{
            return true
        }
    }

    getListGuestLocalStorage(){
        let list = localStorage.getItem(this.keyListGuest)
        if(list != "" && list){
            list = JSON.parse(list)
            this.setGuest(list.list)
        }else{
            list = []
        }
        return list
    }

    dropListGuestLocalStorage(){
        localStorage.removeItem(this.keyListGuest)
    }


    /*METHODS ELEMENTS*/

    async init(){
        let id = localStorage.getItem(this.keyListGuest)
        this.setIdList(id)
        if(id){
            let data = await this.getGuestsList(id)
            if(data){
                this.showBtnDelete()
                this.setGuest(data)
                data.map((obj, index)=>{
                    this.guestItemLayout(obj, obj.guestType)
                })
            }
        }
    }

    categoryGuestActive(currentElement){
        this.btnCategoryGuest.forEach((btn) => {
            btn.classList.remove('category-guest-active');
        })
        currentElement.classList.add('category-guest-active');
    }

    toCleanInputs(){
        this.elName.value = ''
        this.elSurname.value = ''
    }

    showBtnsActions(){
        if(this.checkIfTheListIsEmpty()){
            this.btnSaveList.classList.add('d-none')
            this.btnDeleteList.classList.add('d-none')
        }else{
            this.btnSaveList.classList.remove('d-none')
            this.btnDeleteList.classList.remove('d-none')
        }
    }

    showBtnSave(show = true){
        if(show){
            this.btnSaveList.classList.remove('d-none') 
        }else{
            this.btnSaveList.classList.add('d-none') 
        } 
    }

    showBtnDelete(show = true){
        if(show){
            this.btnDeleteList.classList.remove('d-none') 
        }else{
            this.btnDeleteList.classList.add('d-none') 
        } 
    }

    /*METHODS LAYOUT*/
    guestItemLayout(obj, guestType){
        let itemList = this.listGuestModel.cloneNode(true)
        let categoryGuest = obj.category == 'adult' ? 'Adulto' : 'Adolescente (10 a 17 anos)'
        itemList.querySelector('.item-name').innerHTML = obj.name+' '+obj.surname
        itemList.querySelector('.item-category').innerHTML = categoryGuest
        itemList.querySelector('.type-guest').innerHTML = guestType == 'guest' ? 'Convidado' : 'Acompanhante'
        itemList.querySelector('.delete-guest').setAttribute('data-id', obj.id)
        itemList.querySelector('.delete-guest').addEventListener("click", (e) => {
            let id = e.currentTarget.getAttribute('data-id')
            let isGuestMain = this.checkIfYouAreGuest(id)
            if(isGuestMain){
                this.changeGuest(id)
            }else{
                this.deleteGuest(id)
            }
            this.listGuestArea.innerHTML = ''
            this.updateGuestItemLayout()
            //e.currentTarget.closest('.list-item-guest').remove()
            this.showBtnsActions()
        })
        this.listGuestArea.append(itemList)
    }

    updateGuestItemLayout(){
        this.getGuest().map((e)=>{
            this.guestItemLayout(e, e.guestType)
        })
    }

    async getGuestsList(id){
        let resp = false
        try{
            resp = await jQuery.ajax({
                type: 'POST',
                url: url.ajax_url,
                data:{
                    action: 'layout_list_guests',
                    idList: id
                }
            })
        }catch(error){
            console.log(error)
        }
        return resp
    }

    async deleteGuestsList(id){
        try{
            let resp = await jQuery.ajax({
                type: 'POST',
                url: url.ajax_url,
                data:{
                    action: 'delete_list',
                    idList: id
                }
            })
            this.alert(true, resp.msg)
            return true
        }catch(error){
            this.alert(false, error.responseJSON.msg)
            return false
        }
    }

    /*AUXILIARY METHODS*/
    alert(alert, message){
        let layoutAlert = ''
        if(alert){
            layoutAlert = `<div class="alert alert-success register-success-alert" role="alert">${message}</div>`
        }else{
            layoutAlert = `<div class="alert alert-danger register-failed-alert" role="alert">${message}</div>`
        }
        this.elAreaAlerts.innerHTML = layoutAlert
        setTimeout(()=> this.elAreaAlerts.innerHTML = '', 5000)
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

    infosGuestValidate(name, surname){
        let result
        if(!name){
            this.elNameRequired.classList.remove('d-none')
            result = false
        }else{
            this.elNameRequired.classList.add('d-none')
            result = true
        }

        if(!surname){
            this.elSurnameRequired.classList.remove('d-none')
            result = false
        }else{
            this.elSurnameRequired.classList.add('d-none')
            result = true
        }
        return result
    }
}

new ConfirmPresence()

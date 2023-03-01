class Presence{
    constructor()
    {
        /*ELEMENTS*/

        if(jQuery('#modal-confirm-presence').length > 0){
            
            this.modalPresence = new bootstrap.Modal('#modal-confirm-presence', {
                keyboard: false
            })

            /*ACTIONS*/
            jQuery('.not-going').on('click', ()=>{
                this.modalPresence.hide() 
            })

            /*GLOBALS*/
            this.keyListGuest = 'list-guest-amille-party'

            this.init()
        }   
    }

    init(){
        let list = this.getListGuestLocalStorage()
        if(list){
            jQuery('#menu-item-24').removeClass('d-none')
        }else{
            jQuery('#menu-item-24').addClass('d-none')
            this.modalPresence.show() 
        }
    }

    getListGuestLocalStorage(){
        let list = localStorage.getItem(this.keyListGuest)
        if(list){
            list = JSON.parse(list)
        }
        return list
    }
}

new Presence()
class Feed{
    constructor(){

        this.keyUserFeed = 'key-user-feed'
        this.idUserFeed = ''
        this.currentIdPost = ''

        jQuery('body').on('click', '.like', async (e)=>{
            let id = jQuery(e.currentTarget).attr('post-id')
            let amountLikes = jQuery(e.currentTarget).attr('amount-likes')
            if(!amountLikes){
                amountLikes = 0
            }
            amountLikes = parseFloat(amountLikes)
            amountLikes++
            let resp = ''
            debugger
            try{
                resp = await jQuery.ajax({
                    type: 'POST',
                    url: url.ajax_url,
                    data:{
                        action: 'like_post',
                        postId: id,
                        amountLikes: amountLikes
                    }
                })
                resp = true
            }catch(error){
                resp = false
            }

            
            let likesText = amountLikes > 1 ? ' curtidas' : ' curtida'
            let textAmountLikes = amountLikes + likesText

            if(resp){
                jQuery('.amount-likes-'+id).html(textAmountLikes)
            }
        })

        jQuery('body').on('click', '#comment-button', async (e)=>{
            let id = jQuery(e.currentTarget).attr('post-id')
            this.setCurrentIdPost(id)
            if(this.checIfUserExists())
            {
                this.commentsLayout()
            }
            else
            {
                this.registerUserLayout()
            }
        })

        jQuery('body').on('click', '#register-user-button', async (e)=>{
            let name = jQuery('#register-user-input').val()
            let resp = await this.registerUser(name)
            if(resp){
                console.log('Registrou')
            }else{
                console.log('Erro')
            }
        })

        this.getIdUserFeedLocalStorage()
        
    }

    getIdUserFeedLocalStorage()
    {
        let resp = localStorage.getItem(this.keyUserFeed)
        if(resp)
        {
            this.setIdUserFeed(parseFloat(resp))
        }
    }

    getIdUserFeed()
    {
        return this.idUserFeed
    }

    setIdUserFeed(id)
    {
        this.idUserFeed = id
    }

    getCurrentIdPost()
    {
        return this.currentIdPost
    }

    setCurrentIdPost(id)
    {
        this.currentIdPost = id
    }

    checIfUserExists()
    {
        let resp = false
        if(this.getIdUserFeed())
        {
            resp = true
        }
        return resp
    }

/*comentário de teste*/

    async registerUser(name, comment = '')
    {
        debugger
        let obj = {id: this.create_UUID(), name: name, comment: comment}
        let resp = ''
        try{
            resp = await jQuery.ajax({
                type: 'POST',
                url: url.ajax_url,
                data:{
                    action: 'register_user',
                    postId: this.getCurrentIdPost(),
                    users: obj
                }
            })
            resp = true
        }catch(error){
            resp = false
        }


        return resp
    }

    async getUsers()
    {
        let array = []
        try{
            resp = await jQuery.ajax({
                type: 'POST',
                url: url.ajax_url,
                data:{
                    action: 'register_user',
                    postId: this.getCurrentIdPost(),
                    users: array
                }
            })
            resp = true
        }catch(error){
            resp = false
        }
    }



    /*LAYOUTS*/

    commentsLayout()
    {
        let layout =  `
        <input type="text" name="comments" id="comments" class="w-100 border-0" placeholder="Adicione um comentário">
        <div class="mt-3 text-end">
        <button id="send-comment" class="btn amille-party-bg-primary amille-party-bg-primary-hover text-white">Publicar</button>
        </div> 
        `
        jQuery('#box-comment-user').html(layout)
    }

    resetCommentsLayout()
    {
        jQuery('#box-comment-user').html('')
    }

    registerUserLayout()
    {
        let layout =  `
        <input type="text" name="register-user" id="register-user-input" class="form-control" placeholder="Nome">
        <div class="form-text">Insira seu nome para poder comentar.</div>
        <div class="text-end">
            <button id="register-user-button" class="btn amille-party-bg-primary amille-party-bg-primary-hover text-white">Registrar-se</button>
        </div>
        `
        jQuery('#box-register-user').html(layout)
    }

    resetRegisterUserLayout()
    {
        jQuery('#box-register-user').html('')
    }


    /*AUXILIARY METHODS*/

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

new Feed()
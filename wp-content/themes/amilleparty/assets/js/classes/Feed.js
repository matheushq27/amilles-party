class Feed{
    constructor(){

        this.keyUserFeed = 'key-user-feed'
        this.nameUserFeedStorage = 'name-user-feed'
        this.keyPostLikesStorage = 'likes-user-feed'

        this.idUserFeed = ''
        this.nameUserFeed = ''
        this.currentIdPost = ''
        this.modalFeed = ''
        this.categoryCurrent = 'amigos'
        this.pageCurrent = jQuery('#main-section').attr('data-page')
        this.feedUser = jQuery('#main-section').attr('data-user')
        this.postsLikes = []

        this.modalRegister = new bootstrap.Modal('#modalRegister', {
            keyboard: false
        })

        // jQuery('body').on('click', '.carousel-control-next', async (e)=>{
        //     let value = '.carousel-item-'+jQuery(e.currentTarget).attr('post-id')
        //     let carouselItem = jQuery(value)
        //     let indexActive = ''
        //     carouselItem.map((i,e)=>{
        //        if(jQuery(e).hasClass('active'))
        //        {
        //         indexActive = i + 1
        //        }
        //     })
            
        //     if(indexActive < carouselItem.length)
        //     {
        //         jQuery(carouselItem).removeClass('active')
        //         jQuery(carouselItem[indexActive]).addClass('active')
        //     }
        // })

        // jQuery('body').on('click', '.carousel-control-prev', async (e)=>{
        //     let value = '.carousel-item-'+jQuery(e.currentTarget).attr('post-id')
        //     let carouselItem = jQuery(value)
        //     let indexActive = ''
        //     carouselItem.map((i,e)=>{
        //        if(jQuery(e).hasClass('active'))
        //        {
        //         indexActive = i - 1
        //        }
        //     })
            
        //     if(indexActive < carouselItem.length && indexActive > -1)
        //     {
        //         jQuery(carouselItem).removeClass('active')
        //         jQuery(carouselItem[indexActive]).addClass('active')
        //     }
        // })

        jQuery('body').on('click', '.like', async (e)=>{

            this.getAllLocalStorage()
            let id = jQuery(e.currentTarget).attr('post-id')
            let amountLikes = jQuery(e.currentTarget).attr('amount-likes')
            amountLikes = parseFloat(amountLikes)
            let userLike = {id: this.getIdUserFeed(), name: this.getNameUserFeed()}
            let likesUserFeed = this.getPostLikesUserFeed()

            if(this.checIfUserExists())
            {
                  
                if(Array.isArray(likesUserFeed) && likesUserFeed.length > 0)
                {

                    if(!likesUserFeed.includes(id))
                    {
                        amountLikes++
                        this.addPostLikesUserFeed(id)
                        localStorage.setItem(this.keyPostLikesStorage, JSON.stringify(this.getPostLikesUserFeed()))
                    }
                    else
                    {
                        amountLikes--
                        let newArray = this.filterPostLikesUserFeed(id, 'different')
                        this.setPostLikesUserFeed(newArray)
                        localStorage.setItem(this.keyPostLikesStorage, JSON.stringify(this.getPostLikesUserFeed()))
                    }
                }
                else
                {
                    this.addPostLikesUserFeed(id)
                    localStorage.setItem(this.keyPostLikesStorage, JSON.stringify(this.getPostLikesUserFeed()))
                    amountLikes++ 
                }
            
            
                let likesText = amountLikes > 1 ? ' curtidas' : ' curtida'
                let textAmountLikes = amountLikes + likesText

                let resp = ''
        
                try{
                    resp = await jQuery.ajax({
                        type: 'POST',
                        url: url.ajax_url,
                        data:{
                            action: 'like_post',
                            postId: id,
                            userLike: userLike,
                        }
                    })
                    resp = true
                }catch(error){
                    resp = false
                }

                if(resp)
                {
                    if(amountLikes <= 0)
                    {
                        textAmountLikes = ''
                    }
                    jQuery('.amount-likes-'+id).html(textAmountLikes)
                    jQuery(e.currentTarget).attr('amount-likes', amountLikes)
                    this.likeOn(id)
                }

            }
            else
            {
                this.showModalRegister()
            }

        })

        jQuery('body').on('click', '.comment-button', async (e)=>{
            let id = jQuery(e.currentTarget).attr('post-id')
            this.setCurrentIdPost(id)
            if(this.checIfUserExists())
            {
                this.getModalComments()
                this.showModalRegister(false)
            }
            else
            {
                this.showModalRegister()
            }
        })

        jQuery('body').on('click', '.register-user-button', async (e)=>{
            let name = jQuery('.register-user-input').val()
            let idUser = await this.registerUser(name)
            if(idUser && name)
            {
                localStorage.setItem(this.keyUserFeed, idUser)
                localStorage.setItem(this.nameUserFeedStorage, name)
                this.setIdUserFeed(idUser)
                this.setNameUserFeed(name)
                this.showModalRegister(false)
            }
            else
            {
                console.log('Erro ao registrar-se')
                this.showModalRegister(false)
            }
        })

        jQuery('body').on('click', '.send-comment', async (e)=>{
            let postId = this.getCurrentIdPost()
            let comment = jQuery('#comments-'+postId)
            let amountComments = jQuery(e.currentTarget).attr('amount-of-comments')

            if(this.checIfUserExists())
            {
                if(comment.val())
                {
                    this.commentLayout(comment.val(), amountComments)
                    await this.registerComment(comment.val())
                }
                else
                {
                    console.log('O comentário não pode estar vazio.')
                }
            }
            else
            {
                this.showModal(false)
                this.showModalRegister()
            }
            
            comment.val('')        
        })

        jQuery('body').on('click', '.btn-see-comments', async (e)=>{
            let postId = jQuery(e.currentTarget).attr('post-id')
            this.setCurrentIdPost(postId)
            this.getModalComments()
        })

        jQuery('body').on('click', '.button-tab', async (e)=>{
            let categoryId = jQuery(e.currentTarget).attr('category-id')
            this.setCategoryCurrent(categoryId)
            this.preloader()
            await this.getFeed()
        })

        jQuery('body').on('click', '.page-item', async (e)=>{
            this.preloader()
            let currentTarget = jQuery(e.currentTarget)
            let page = currentTarget.attr('page')
            await this.getFeed(page)
        })

        this.init()
        
    }

    async init()
    {
        this.getAllLocalStorage()
        await this.getFeed()
    }

    getAllLocalStorage()
    {
        this.getIdUserFeedLocalStorage()
        this.getNameUserFeedLocalStorage()
        this.getPostsLikesLocalStorage()
    }

    getIdUserFeedLocalStorage()
    {
        let resp = localStorage.getItem(this.keyUserFeed)
        if(resp)
        {
            this.setIdUserFeed(parseFloat(resp))
        }
    }

    getNameUserFeedLocalStorage()
    {
        let resp = localStorage.getItem(this.nameUserFeedStorage)
        if(resp)
        {
            this.setNameUserFeed(resp)
        }
    }

    getPostsLikesLocalStorage()
    {
        let resp = localStorage.getItem(this.keyPostLikesStorage)
        if(resp)
        {
            this.setPostLikesUserFeed(JSON.parse(resp))
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

    getNameUserFeed()
    {
        return this.nameUserFeed
    }

    setNameUserFeed(name)
    {
        this.nameUserFeed = name
    }

    getPostLikesUserFeed()
    {
        return this.postsLikes
    }

    addPostLikesUserFeed(val)
    {
        this.postsLikes.push(val)
    }

    setPostLikesUserFeed(val)
    {
        this.postsLikes = val
    }

    getCurrentIdPost()
    {
        return this.currentIdPost
    }

    setCurrentIdPost(id)
    {
        this.currentIdPost = id
    }

    getCategoryCurrent()
    {
        return this.categoryCurrent
    }

    setCategoryCurrent(value)
    {
        this.categoryCurrent = value
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

    filterPostLikesUserFeed(postLikeId, mode = 'equal')
    {
        let array = ''
        if(mode == 'equal')
        {
            array = this.getPostLikesUserFeed().filter(id => id == postLikeId)
        }
        else
        {
            array = this.getPostLikesUserFeed().filter(id => id != postLikeId)
        }
        return array
    }

    showModal(show = true){
        if(show){
            this.modalFeed.show() 
        }else{
            this.modalFeed.hide() 
        }
    }

    showModalRegister(show = true){
        if(show){
            this.modalRegister.show() 
        }else{
            this.modalRegister.hide() 
        }
    }

    async getFeed(pageCurrent = 1)
    {
        let resp = ''
        let page = this.pageCurrent
        let category = this.getCategoryCurrent()
        try{
            resp = await jQuery.ajax({
                type: 'POST',
                url: url.ajax_url,
                data:{
                    action: 'layout_feed',
                    categoryCurrent: this.getCategoryCurrent(),
                    postsLikes: this.getPostLikesUserFeed(),
                    userFeed: this.feedUser,
                    mainPageCurrent: page,
                    pageCurrent: pageCurrent
                }
            })

            this.preloader(false)

        }catch(error){
           console.log(error)
           jQuery('.post-notice').html('Aguarde... logo realizaremos os posts.')
           jQuery('#continue-current-page').removeClass('d-none')
           jQuery('#back-home').removeClass('d-none')
        }
        
       
        if(category == 'amigos'){
            jQuery('#feed-friends-photos').html(resp)
        }else{
            jQuery('#feed-family-photos').html(resp) 
        }

    }

    async getModalComments()
    {
        let postId = this.getCurrentIdPost()
        let resp = ''
        try{
            resp = await jQuery.ajax({
                type: 'POST',
                url: url.ajax_url,
                data:{
                    action: 'modal_feed',
                    postId: postId
                }
            })

        }catch(error){
           console.log(error)
        }

        jQuery('#modal-feed').html(resp)

        this.modalFeed = new bootstrap.Modal('#modalComments', {
            keyboard: false
        })

        this.showModal()
    }


    async registerComment(comment)
    {
        let resp = ''
        let name = this.getNameUserFeed()
        try{
            resp = await jQuery.ajax({
                type: 'POST',
                url: url.ajax_url,
                data:{
                    action: 'register_comment',
                    postId: this.getCurrentIdPost(),
                    name: name,
                    comment: comment
                }
            })
            resp = true
        }catch(error){
            resp = false
        }

        return resp
    }


    // async registerComment(name, comment = '')
    // {
    //     let obj = {id: this.create_UUID(), name: name, comment: comment}
    //     let resp = ''
    //     try{
    //         resp = await jQuery.ajax({
    //             type: 'POST',
    //             url: url.ajax_url,
    //             data:{
    //                 action: 'register_comment',
    //                 postId: this.getCurrentIdPost(),
    //                 users: obj
    //             }
    //         })
    //         resp = true
    //     }catch(error){
    //         resp = false
    //     }

    //     return resp
    // }

    async registerUser(name)
    {
        let resp = ''
        try{
            resp = await jQuery.ajax({
                type: 'POST',
                url: url.ajax_url,
                data:{
                    action: 'register_user',
                    name: name,
                }
            })
            resp = resp.id
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


    likeOn(id)
    {
        let like = jQuery('#like-'+id)

        if(like.hasClass('ph-heart'))
        {
            like.addClass('ph-heart-fill')
            like.removeClass('ph-heart')
        }
        else
        {
            like.removeClass('ph-heart-fill')
            like.addClass('ph-heart')
        } 
    }

    preloader(show = true){
        jQuery('#continue-current-page').addClass('d-none')
        jQuery('#back-home').addClass('d-none')
        let preloader = jQuery('#preloader')
        if(show)
        {
            preloader.removeClass('d-none')
        }
        else
        {
            preloader.addClass('d-none')
        }
    }

    /*LAYOUTS*/

    commentsLayout(show = true, destination = '#box-comment-user')
    {
        let layout = ''
        if(show)
        {
            layout =  `
            <input type="text" name="comments" id="comments-${this.getCurrentIdPost()}" class="w-100 border-0" placeholder="Adicione um comentário">
            <div class="mt-3 text-end">
            <button id="send-comment" class="btn amille-party-bg-primary amille-party-bg-primary-hover text-white send-comment">Publicar</button>
            </div> 
            `
        }
        
        jQuery(destination).html(layout)
    }

    resetCommentsLayout()
    {
        jQuery('#box-comment-user').html('')
    }

    registerUserLayout(show = true)
    {
        let layout = ''
        if(show){
        layout =  `
            <input type="text" name="register-user" id="register-user-input" class="form-control register-user-input" placeholder="Nome">
            <div class="form-text">Registre-se antes de curtir ou comentar.</div>
            <div class="text-end">
                <button id="register-user-button" class="register-user-button btn amille-party-bg-primary amille-party-bg-primary-hover text-white">Registrar-se</button>
            </div>
            `  
        }
        jQuery('#box-register-user').html(layout)
    }

    resetRegisterUserLayout()
    {
        jQuery('#box-register-user').html('')
    }

    commentLayout(comment, amountComments)
    {
        let layout = ''
       
        layout =  `
            <p class="mb-1">
                <strong class="me-1 amille-party-color-primary">${this.getNameUserFeed()}</strong>
                <span>${comment}</span>
            </p>
        `
        let modalBodyComments = jQuery('.modal-body-comments')
        if(amountComments > 0){
            modalBodyComments.append(layout)
        }else{
            modalBodyComments.html(layout)
        }
        
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
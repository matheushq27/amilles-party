class Feed{
    constructor(){
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
    }
}

new Feed()
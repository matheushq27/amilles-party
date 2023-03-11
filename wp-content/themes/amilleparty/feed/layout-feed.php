<?php
function layout_feed(){

    $postsLikes = $_POST['postsLikes'];
    $likeOn = false;

    $paged = $_POST['pageCurrent'];
    $mainPageCurrent = $_POST['mainPageCurrent'];
    $friendsOrFamily = $_POST['categoryCurrent'];

    // var_dump(
    //     array(
    //         $mainPageCurrent,
    //         $friendsOrFamily
    //     )
    // );

    $the_posts = query($mainPageCurrent,  $friendsOrFamily, $paged);

    //var_dump( $the_posts->posts, 'amigos');

    if($the_posts->post_count == 0)
    {
        $the_posts = query($mainPageCurrent, 'familia', $paged);
        //var_dump( $the_posts->posts, 'familia');
    }

    $total_pages = $the_posts->max_num_pages;

    if($the_posts->post_count > 0){

    // $term = get_term_by('slug', $_POST['userFeed'], 'category_feed');
    // $name = $term->name;
    // $term = $term->term_id;
    
    $photo_profile = get_term_meta($term, 'photo-category', true);

        foreach($the_posts->posts as $post): 
            $data = get_post_meta($post->ID, 'photos-feed', true);
            $likes = get_post_meta($post->ID, 'likes_feed', true);
            $terms = get_the_terms($post->ID, 'category_feed');

            //var_dump($terms);

            foreach($terms as $term)
            {
                if($term->slug == 'cibelle' || $term->slug == 'amille')
                {
                    $name = $term->name;
                    $photo_profile = get_term_meta($term->term_id, 'photo-category', true);
                }
            }

            if(is_array($likes)){
                $likes = count($likes); 
            }
            
            if(!empty($postsLikes))
            {
                foreach($postsLikes as $postLike)
                {
                    if($postLike == $post->ID)
                    {
                        $likeOn = true;
                    }
                }
            }

            $date = new DateTimeImmutable($post->post_date);
            $date = $date->format('d/m/Y');
            if($data):
?>    
       
                    <div class="row justify-content-center">
                        <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5 col-xxl-5">
                            <div class="feed-header d-flex align-items-center justify-content-between mt-5">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="feed-header-photo">
                                        <div class="box-img-profile" style="background-image: url(<?= $photo_profile ?>)">
                                        </div>
                                    </div>
                                    <div class="feed-header-infos ms-3">
                                        <h2 class="feed-header-title mb-0 fs-4"><?= $name ?></h2>
                                    </div>
                                </div>
                                <div class="feed-header-post-day">
                                    <p class="m-0 fs-6 text-secondary mb-3"><?= $date ?></p>
                                </div>
                            </div>
                            <div class="feed-body text-center">

            

                                <div id="carouselFeed" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                    <?php foreach($data as $key => $d): ?>
                                        <div class="carousel-item-<?= $post->ID ?> carousel-item <?= $key == 0 ? 'active'  : '' ?>">
                                            <div id="image-<?= $d['id'] ?>" class="image-item-photo" style="background-image: url(<?= $d['url'] ?>);"></div>
                                        </div>
                                    <?php endforeach; ?>
                                    </div>
                                    <?php if(count($data) > 1): ?>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselFeed" data-bs-slide="prev" post-id="<?= $post->ID ?>">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselFeed" data-bs-slide="next" post-id="<?= $post->ID ?>">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                    <?php endif; ?>
                                </div>
                               

                            </div>
                            <div class="feed-footer d-flex align-items-center justify-content-between mt-2 gap-2 mb-3">
                                <div class="d-flex align-items-center mt-2 gap-2">
                                    <i id="like-<?= $post->ID ?>" amount-likes="<?= $likes ? $likes : 0 ?>" post-id="<?= $post->ID ?>" class="<?= $likeOn ? 'ph-heart-fill' : 'ph-heart' ?> fs-3 cursor-pointer like"></i>
                                    <!-- <i class="ph-heart-fill fs-3"></i> -->
                                    <i id="comment-button" class="ph-chat-circle fs-3 cursor-pointer comment comment-button" post-id="<?= $post->ID ?>"></i>
                                </div>
                                <span class="amount-likes amount-likes-<?= $post->ID ?>">
                    
                                    <?php
                                        if($likes > 1){
                                            echo $likes.' curtidas';
                                        }else{
                                            if($likes == 1){
                                                echo $likes.' curtida';
                                            }
                                        }
                                    ?>
                                </span>
                            </div>
                        
                        
                            <p class="mb-1">
                                <strong class="me-1 amille-party-color-primary"><?= $name ?></strong>
                                <?= $post->post_content ?>
                            </p>

                            <?php if($post->comment_count > 0): ?>
                                <button class="btn-see-comments border-0 p-2 bg-white text-secondary" post-id="<?= $post->ID ?>">Ver todos os <?= $post->comment_count > 1 ? $post->comment_count : '' ?> comentários</button>
                            <?php endif; ?>
                            <div id="box-comment-user" class="mt-3"></div>
                            <!-- <div id="box-register-user" class="box-register-user mt-3 box-register-user"></div> -->
                            
                            <hr>
                        </div>
                    </div>
                    <?php else: ?>
                        <div class="row justify-content-center">
                            <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5 col-xxl-5">
                                <div class="image-item-photo-empty" style="background-image: url(<?= get_template_directory_uri().'/assets/img/empty.png'; ?>);" >
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if($total_pages > 1): ?>
                    <nav aria-label="Page navigation example" class="mt-3">
                        <ul class="pagination justify-content-center">
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= $i == $paged ? 'active':''; ?>" page="<?= $i ?>"><a class="page-link" href="#"><?= $i ?></a></li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
 
<?php
}else{
    die_json_status_code(['msg' => 'sem postagens'], 404);
}
exit;
}


function query($pageMain, $friendsOrFamily, $pageCurrent)
{
        $argsTax = array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'category_feed',
                'field'    => 'slug',
                'terms'    => array('amille', $pageMain, $friendsOrFamily),
                'operator' => 'AND',
            ),
            array(
                'taxonomy' => 'category_feed',
                'field'    => 'slug',
                'terms'    => array('cibelle', $pageMain, $friendsOrFamily),
                'operator' => 'AND',
            )
        );


    $paged = $pageCurrent;
    $the_posts = new WP_Query(array('post_type' => 'amille_feed', 'posts_per_page' => 10, 'paged' => $paged, 'orderby' => 'date', 'order' => 'DESC', 'tax_query' => $argsTax));

    return $the_posts;
}

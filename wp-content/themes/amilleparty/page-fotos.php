<?php get_header(); ?>
<?php wp_enqueue_style('feed'); ?>
<section class="mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-5">
            <nav>
                <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                        Amigos
                    </button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">
                        Família
                    </button>
                </div>
            </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                <div class="container">

                <?php
                    $the_posts = get_posts(array('post_type' => 'amille_feed', 'orderby' => 'date', 'order' => 'ASC'));
                    //var_dump($the_posts);
                ?>
                <?php 
                foreach($the_posts as $post): 
                    $data = get_post_meta($post->ID, 'photos-feed', true);
                    $likes = get_post_meta($post->ID, 'likes_feed', true);

                    $date = new DateTimeImmutable($post->post_date);
                    $date = $date->format('d/m/Y');

                ?>
                    <div class="row justify-content-center">
                        <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5 col-xxl-5">
                            <div class="feed-header d-flex align-items-center justify-content-between mt-5">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="feed-header-photo">
                                        <img src="<?= get_template_directory_uri().'/assets/img/girl-profile.jpg' ?>" alt="Foto do usuário">
                                    </div>
                                    <div class="feed-header-infos ms-3">
                                        <h2 class="feed-header-title mb-0 fs-4">Amille Leal</h2>
                                        <!-- <p class="feed-header-city mb-0">Cuiabá</p> -->
                                    </div>
                                </div>
                                <div class="feed-header-post-day">
                                    <p class="m-0 fs-5 text-secondary mb-3"><?= $date ?></p>
                                </div>
                            </div>
                            <div class="feed-body text-center">
                                <!-- <img class="w-100 rounded cursor-pointer" src="<?= get_template_directory_uri().'/assets/img/girl-profile.jpg' ?>" alt="Foto do usuário"> -->


                                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                    <?php foreach($data as $key => $d): ?>
                                        <div class="carousel-item <?= $key == 0 ? 'active'  : '' ?>">
                                            <!-- <img src="<?= $d['url'] ?>" class="d-block w-100"> -->
                                            <div id="image-<?= $d['id'] ?>" class="image-item-photo" style="background-image: url(<?= $d['url'] ?>);"></div>
                                        </div>
                                    <?php endforeach; ?>
                                    </div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>                                

                            </div>
                            <div class="feed-footer d-flex align-items-center justify-content-between mt-2 gap-2">
                                <div class="d-flex align-items-center mt-2 gap-2">
                                    <i amount-likes="<?= $likes ?>" post-id="<?= $post->ID ?>" class="ph-heart fs-3 cursor-pointer like"></i>
                                    <!-- <i class="ph-heart-fill fs-3"></i> -->
                                    <i class="ph-chat-circle fs-3 cursor-pointer comment"></i>
                                </div>
                                <span class="amount-likes amount-likes-<?= $post->ID ?>"><?= $likes ?> <?= $likes > 1 ? 'curtidas' : 'curtida'?></span>
                            </div>
                            <?php comment_form(); ?>
                            <hr>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">...</div>
            </div>
            </div>
        </div>
    </div>
</section>
<?php wp_localize_script('feed', 'url', array('ajax_url' => admin_url('admin-ajax.php'))); ?>
<?php wp_enqueue_script('feed'); ?>
<?php get_footer(); ?>
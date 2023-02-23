<?php get_header(); ?>
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
                    <div class="row justify-content-center">
                        <div class="col-sm-12 col-md-12 col-lg-5 col-xl-5 col-xxl-5">
                            <div class="feed-header d-flex align-items-center justify-content-between mt-5">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="feed-header-photo">
                                        <img src="<?= get_template_directory_uri().'/assets/img/girl-profile.jpg' ?>" alt="Foto do usuário">
                                    </div>
                                    <div class="feed-header-infos ms-3">
                                        <h2 class="feed-header-title mb-0 fs-4">Amille Leal</h2>
                                        <p class="feed-header-city mb-0">Cuiabá</p>
                                    </div>
                                </div>
                                <div class="feed-header-post-day">
                                    <p class="m-0 fs-4">5 d</p>
                                </div>
                            </div>
                            <div class="feed-body text-center">
                                <img class="w-100 rounded cursor-pointer" src="<?= get_template_directory_uri().'/assets/img/girl-profile.jpg' ?>" alt="Foto do usuário">
                            </div>
                            <div class="feed-footer d-flex align-items-center justify-content-between mt-2 gap-2">
                                <div class="d-flex align-items-center mt-2 gap-2">
                                    <i class="ph-heart fs-3 cursor-pointer like"></i>
                                    <!-- <i class="ph-heart-fill fs-3"></i> -->
                                    <i class="ph-chat-circle fs-3 cursor-pointer comment"></i>
                                </div>
                                50 curtidas
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">...</div>
            </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
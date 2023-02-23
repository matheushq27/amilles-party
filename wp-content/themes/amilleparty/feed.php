<?php get_header(); ?>

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


<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Modal 1</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Show a second modal and hide this one with the button below.
      </div>
      <!-- <div class="modal-footer">
        <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal">Open second modal</button>
      </div> -->
    </div>
  </div>
</div>
<a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle" role="button">Open first modal</a>

<?php get_footer(); ?>


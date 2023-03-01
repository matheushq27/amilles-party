<?php get_header('confirm-presence'); ?>
<?php wp_enqueue_style('confirm-presence');?>

<div class="main bg-section-img" style="background-image: url(<?= get_template_directory_uri().'/assets/img/background-amille-party.png' ?>);">
  <div class="container">
    <div class="mb-5">
      <a href="/" class="btn-back-home text-white shadow-sm"><i class="ph-arrow-circle-left-fill"></i></a>
    </div>
    <div class="row justify-content-center">
      <div class="col-sm-12 col-md-8 col-lg-6 col-xl-6 col-xxl-6">
        <div id="area-alerts"></div>       
        <h5 class="mb-3">Adicione você e seus acompanhantes:</h5>
        <form id="confirm-presence-form" class="shadow-sm p-4 border rounded-3 mb-3">
          <div class="mb-3">
            <input id="name" type="text" class="form-control input-confirm-presence" placeholder="Nome">
            <div class="form-text text-danger name-required d-none">O nome é obrigatório</div>
          </div>
          <div class="mb-3">
            <input id="surname" type="text" class="form-control input-confirm-presence" placeholder="Sobrenome">
            <div class="form-text text-danger surname-required d-none">O sobrenome é obrigatório</div>
          </div>
          <div class="mb-4">
            <button type="button" class="btn-category-guest p-2 rounded-3 category-guest-active mb-1" data-category-guest="adult">Adulto</button>
            <button type="button" class="btn-category-guest p-2 rounded-3 mb-1" data-category-guest="adolescent">Adolescente (10 a 17 anos)</button>
          </div>
          <button id="add-guest" type="submit" class="btn amille-party-bg-primary text-white">Adicionar</button>
        </form>

        <div id="list-guest-area"></div>

        <div class="mt-2">
          <button id="btn-save-list" type="button" class="btn amille-party-bg-primary text-white d-none">Salvar</button>
          <button id="btn-delete-list" type="button" class="btn btn-danger text-white d-none">Deletar lista</button>
        </div>

        <div id="list-guest-model">
          <div class="list-item-guest shadow-sm pt-2 pb-2 ps-3 pe-3 border rounded-3 d-flex align-items-center justify-content-between mb-2">
        
                <div>  
                  <h5 class="mb-1 item-name"></h5>
                  <div class="d-flex align-items-center">
                    <p class="m-0 p-1 item-category"></p>
                    <span>-</span>
                    <span class="p-1 type-guest"></span>
                  </div>
                </div>
                
                <i class="ph-trash-fill fs-3 cursor-pointer delete-guest"></i>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>



<?php wp_enqueue_script('jquery') ?>
<?php wp_localize_script('confirm-presence', 'url', array('ajax_url' => admin_url('admin-ajax.php'))); ?>
<?php wp_enqueue_script('js-pdf') ?>
<?php wp_enqueue_script('confirm-presence') ?>
<?php get_footer('confirm-presence'); ?>
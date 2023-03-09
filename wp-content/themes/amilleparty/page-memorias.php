<?php get_header('feed'); ?>
<?php wp_enqueue_style('feed'); ?>

<?php

global $wp;

$userFeedCurrent = array('taxonomy' => 'category_feed', 'field' => 'slug', 'terms' => array( 'cibelle' ), 'include_children' => false);

$argsTaxFriends = array(
    'relation' => 'AND', 
    array('taxonomy' => 'category_feed', 'field' => 'slug', 'terms' => array( 'amigos' ), 'include_children' => false),
   $userFeedCurrent
);

$argsTaxFamilly = array(
    'relation' => 'AND', 
    array('taxonomy' => 'category_feed', 'field' => 'slug', 'terms' => array( 'familia' ), 'include_children' => false),
   $userFeedCurrent
);


$friends = get_posts(array('post_type' => 'amille_feed', 'orderby' => 'date', 'order' => 'DESC', 'tax_query' => $argsTaxFriends));
$photosFriends = false;

$familly = get_posts(array('post_type' => 'amille_feed', 'orderby' => 'date', 'order' => 'DESC', 'tax_query' => $argsTaxFamilly));
$photosFamilly = false;

if(!empty($friends))
{
    foreach($friends as $photos)
    {
        $data = get_post_meta($photos->ID, 'photos-feed', true);
        if($data){
            $photosFriends = true;
        }
    }
}else{
    $friends = false ;
}

if(!empty($familly))
{
    foreach($familly as $photos)
    {
        $data = get_post_meta($photos->ID, 'photos-feed', true);
        var_dump($data);
        if($data){
            $photosFamilly = true;
        }
    }
}else{
    $photosFamilly = false ;
}

$friends = !empty($friends) ? true : false;
$familly = !empty($familly) ? true : false;

?>
<div id="preloader">
    <div class="inner">
        <img class="live m-auto" src="<?= get_template_directory_uri().'/assets/img/logo.png' ?>">
        <p class="text-white post-notice text-center"></p>
        <a id="back-home" href="/" class="btn btn-secondary amille-party-bg-secondary amille-party-color-border-secondary d-none">voltar para home</a>
        <a id="continue-current-page" href="<?= home_url( $wp->request ); ?>" class="btn btn-secondary amille-party-bg-secondary amille-party-color-border-secondary d-none">Continuar em Memórias</a>
    </div>
</div>
<section class="mt-5" id="main-section" data-user="cibelle">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-5 mb-3">
                <nav>
                    <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
                        <?php if($friends && $photosFriends): ?>
                            <button class="nav-link active button-tab" id="nav-home-tab" category-id="amigos" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">
                                Amigos
                            </button>
                        <?php endif; ?>

                        <?php if($familly && $photosFamilly): ?>
                            <button class="nav-link button-tab" id="nav-profile-tab" category-id="familia" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">
                                Família
                            </button>
                        <?php endif; ?>
                    </div>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
                    <div class="container" id="feed-friends-photos">
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                    <div class="container" id="feed-family-photos">
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div id="box-register-user" class="mt-3"></div>
    </div>
</section>


<div id="modal-feed"></div>

<div class="modal fade" id="modalRegister" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Registre-se</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body modal-body-comments">
            <input type="text" name="register-user" id="register-user-input" class="form-control register-user-input" placeholder="Nome">
            <div class="form-text">Para curtir ou comentar você precisa inserir seu nome.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="register-user-button btn amille-party-bg-primary amille-party-bg-primary-hover text-white">Registrar-se</button>
      </div>
    </div>
  </div>
</div>

<?php wp_localize_script('feed', 'url', array('ajax_url' => admin_url('admin-ajax.php'))); ?>
<?php wp_enqueue_script('feed'); ?>
<?php get_footer(); ?>
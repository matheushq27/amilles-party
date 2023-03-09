<!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php  wp_head(); ?>
</head>
<body <?php body_class(); ?> >
<nav class="navbar navbar-expand-lg w-100 text-white nav-top amille-party-bg-primary" style="z-index: 999;">
  <div class="container-fluid">
    <a class="navbar-brand" href="/">
      <img src="<?= get_template_directory_uri().'/assets/img/logo.png' ?>" alt="Bootstrap" width="50">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <?php wp_nav_menu(
        array(
        'theme_location' => 'amille_party_main_menu', 
        'deph' => 2, 
        'menu_class' => 'navbar-nav fs-5',
        'li_class' => 'nav-item',
        'a_class' => 'nav-link text-white nav-link-header',
        'active_class' => 'active'
        )); ?>
    </div>
  </div>
</nav>


    
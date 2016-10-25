<?php

// Get Controller
$controller = get_controller( $this );

// Get State
$state = get_state( $controller );

// Theme root
$theme_root = "/themes/angle/";

// Web root
$webroot = x_objects::instance()->webroot();

$data = $controller->data();

$content = $state->get_content();


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        require_once $webroot."/app/views/components/head.php";
        ?>
    </head>
    <body>
    <?php
    require_once $webroot."/app/views/components/header.php";
    ?>

    <div id="content" role="main">
            <section class="section swatch-red-white section-nopadding">
                <div class="container-fullwidth">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="tp-banner-container">
                                <div class="tp-banner">
                                    <ul style="display:none;">
                                        <li data-transition="fade" data-slotamount="4">
                                            <div class="tp-caption sfl" data-x="80" data-y="70" data-speed="800" data-start="900" data-easing="easeInBack">
                                                <img src="<?=$theme_root?>assets/images/design/revolution/slide-1-dev-2.png">
                                            </div>
                                            <div class="tp-caption sfr" data-x="120" data-y="70" data-speed="800" data-start="1500" data-easing="easeInBack">
                                                <img src="<?=$theme_root?>assets/images/design/revolution/slide-1-dev-3.png">
                                            </div>
                                            <div class="tp-caption sfb" data-x="100" data-y="60" data-speed="1200" data-start="2000" data-easing="easeInBack">
                                                <img src="<?=$theme_root?>assets/images/design/revolution/slide-1-dev-center.png">
                                            </div>
                                            <div class="tp-caption sfl super light" data-x="0" data-y="180" data-speed="1200" data-start="1800" data-easing="easeInBack">
                                                Flexible & Responsive
                                            </div>
                                            <div class="tp-caption sfl super light" data-x="50" data-y="230" data-speed="1200" data-start="2100" data-easing="easeInBack">
                                                create your website
                                            </div>
                                            <div class="tp-caption sfl  super light" data-x="267" data-y="280" data-speed="1200" data-start="2400" data-easing="easeInBack">
                                                with a style
                                            </div>
                                        </li>
                                        <li data-transition="boxslide" data-slotamount="4">
                                            <div class="caption sfl" data-x="80" data-y="40" data-speed="600" data-start="1500" data-easing="easeInBack">
                                                <img src="<?=$theme_root?>assets/images/design/revolution/slide-2-dev-2.png">
                                            </div>
                                            <div class="caption sfr" data-x="-100" data-y="40" data-speed="600" data-start="2000" data-easing="easeInBack">
                                                <img src="<?=$theme_root?>assets/images/design/revolution/slide-2-dev-3.png">
                                            </div>
                                            <div class="caption lfb fadeout" data-x="45" data-y="55" data-speed="1000" data-endspeed="1200" data-start="3500" d data-easing="easeInBack">
                                                <img src="<?=$theme_root?>assets/images/design/revolution/slide-2-dev-center.png">
                                            </div>
                                            <div class="caption fade" data-x="-8" data-y="20" data-speed="300" data-start="6000" data-easing="Power4.easeOutCubic">
                                                <img src="<?=$theme_root?>assets/images/design/revolution/slide-2-dev-center-big.png">
                                            </div>
                                            <div class="caption sfb stt super light" data-x="750" data-y="180" data-speed="400" data-endspeed="400" data-start="2000" data-end="4000" data-easing="easeInBack">
                                                hi there
                                            </div>
                                            <div class="caption sfb stt super light" data-x="750" data-y="180" data-speed="400" data-endspeed="400" data-start="4000" data-end="5500" data-easing="easeInBack">
                                                this is Angle
                                            </div>
                                            <div class="caption sfb stt super light" data-x="750" data-y="130" data-speed="400" data-start="5500" data-easing="easeInBack">
                                                the most
                                            </div>
                                            <div class="caption sfb stt super light" data-x="750" data-y="200" data-speed="400" data-start="5700" data-easing="easeInBack">
                                                advanced theme
                                            </div>
                                            <div class="caption sfb stt super light" data-x="750" data-y="270" data-speed="400" data-start="5900" data-easing="easeInBack">
                                                we've ever made
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="section swatch-white-red">
                <div class="container">
                    <header class="section-header text-center underline">
                        <h1 class="headline super hairline">Features</h1>
                        <p class="">Angle is a unique and outstanding wordpress theme. Powered by html5 and CSS3 angle is the theme you want to have to take your site to the next level.</p>
                    </header>
                    <div class="row">
                        <ul class="list-unstyled row box-list ">
                            <li class="col-md-3 text-center" data-os-animation="" data-os-animation-delay="">
                                <div class="box-round box-medium">
                                    <div class="box-dummy"></div>
                                    <a class="box-inner " href="single-service.html">
                                        <i class="fa fa-android" data-animation="bounce"></i>
                                    </a>
                                </div>
                                <h3 class="text-center ">
    
        <a href="single-service.html">
    
        Mobile Ready
    
        </a>
    
</h3>
                                <p class="text-center">Your bones don&#x27;t break, mine do. That&#x27;s clear. Your cells react to bacteria and viruses differently than mine.</p>
                            </li>
                            <li class="col-md-3 text-center" data-os-animation="" data-os-animation-delay="">
                                <div class="box-round box-medium">
                                    <div class="box-dummy"></div>
                                    <a class="box-inner " href="single-service.html">
                                        <i class="fa fa-html5" data-animation="swing"></i>
                                    </a>
                                </div>
                                <h3 class="text-center ">
    
        <a href="single-service.html">
    
        HTML5 valid
    
        </a>
    
</h3>
                                <p class="text-center">Your bones don&#x27;t break, mine do. That&#x27;s clear. Your cells react to bacteria and viruses differently than mine.</p>
                            </li>
                            <li class="col-md-3 text-center" data-os-animation="" data-os-animation-delay="">
                                <div class="box-round box-medium">
                                    <div class="box-dummy"></div>
                                    <a class="box-inner " href="single-service.html">
                                        <i class="fa fa-css3" data-animation="shake"></i>
                                    </a>
                                </div>
                                <h3 class="text-center ">
    
        <a href="single-service.html">
    
        CSS3 magic
    
        </a>
    
</h3>
                                <p class="text-center">Your bones don&#x27;t break, mine do. That&#x27;s clear. Your cells react to bacteria and viruses differently than mine.</p>
                            </li>
                            <li class="col-md-3 text-center" data-os-animation="" data-os-animation-delay="">
                                <div class="box-round box-medium">
                                    <div class="box-dummy"></div>
                                    <a class="box-inner " href="single-service.html">
                                        <i class="fa fa-heart" data-animation="tada"></i>
                                    </a>
                                </div>
                                <h3 class="text-center ">
    
        <a href="single-service.html">
    
        Amazing design
    
        </a>
    
</h3>
                                <p class="text-center">Your bones don&#x27;t break, mine do. That&#x27;s clear. Your cells react to bacteria and viruses differently than mine.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
            <section class="section swatch-red-white has-top">
                <div class="decor-top">
                    <svg class="decor" height="100%" preserveaspectratio="none" version="1.1" viewbox="0 0 100 100" width="100%" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0 L50 100 L100 0 L100 100 L0 100" stroke-width="0"></path>
                    </svg>
                </div>
                <div class="container">
                    <header class="section-header text-center underline">
                        <h1 class="headline super hairline">Portfolio</h1>
                    </header>
                    <div class="row">
                        <div class="text-default os-animation" data-os-animation="fadeInUp" data-os-animation-delay=".0s">
                            <ul class="isotope-filters text-center">
                                <li class="active">
                                    <a class="pseudo-border active" data-filter="*" href="#">   all
                </a>
                                </li>
                                <li>
                                    <a class="pseudo-border" data-filter=".filter-gallery" href="#">gallery</a>
                                </li>
                                <li>
                                    <a class="pseudo-border" data-filter=".filter-image" href="#">image</a>
                                </li>
                                <li>
                                    <a class="pseudo-border" data-filter=".filter-video" href="#">video</a>
                                </li>
                            </ul>
                            <div class="portfolio isotope no-transition portfolio-hex portfolio-shadows row">
                                <div class="portfolio-item infinite-item col-md-4 col-sm-4 filter-gallery filter-image">
                                    <figure class="portfolio-figure">
                                        <a href="portfolio-item-big.html">
                                            <img width="600" height="518" src="<?=$theme_root?>assets/images/design/vector/img-8-600x518.png" class="img-responsive" alt="some image">
                                        </a>
                                        <figcaption>
                                            <h4>
                            <a href="portfolio-item-big.html">
                                Gallery items
                            </a>
                        </h4>
                                            <p>You think water moves fast? You should see ice.</p>
                                            <a class="magnific-gallery more" data-links="
    
        assets/images/design/vector/img-4-1200x600.png ,
    
        assets/images/design/vector/img-3-1200x600.png
    " data-prev-text="Previous" data-next-text="Next">
                                                <i class="fa fa-search-plus"></i>
                                            </a>
                                            <a class="link" href="portfolio-item-big.html">
                                                <i class="fa fa-link"></i>
                                            </a>
                                        </figcaption>
                                    </figure>
                                </div>
                                <div class="portfolio-item infinite-item col-md-4 col-sm-4 filter-video">
                                    <figure class="portfolio-figure">
                                        <a href="portfolio-item-big.html">
                                            <img width="600" height="518" src="<?=$theme_root?>assets/images/design/vector/img-4-600x518.png" class="img-responsive" alt="some image">
                                        </a>
                                        <figcaption>
                                            <h4>
                            <a href="portfolio-item-big.html">
                                Vimeo Item
                            </a>
                        </h4>
                                            <p>You think water moves fast? You should see ice. It moves like it has a mind.</p>
                                            <a class="magnific-vimeo more" href="http://vimeo.com/41336551">
                                                <i class="fa fa-search-plus"></i>
                                            </a>
                                            <a class="link" href="portfolio-item-big.html">
                                                <i class="fa fa-link"></i>
                                            </a>
                                        </figcaption>
                                    </figure>
                                </div>
                                <div class="portfolio-item infinite-item col-md-4 col-sm-4 filter-video filter-image">
                                    <figure class="portfolio-figure">
                                        <a href="portfolio-item-big.html">
                                            <img width="600" height="518" src="<?=$theme_root?>assets/images/design/vector/img-1-600x518.png" class="img-responsive" alt="some image">
                                        </a>
                                        <figcaption>
                                            <h4>
                            <a href="portfolio-item-big.html">
                                Integer sollicibulum
                            </a>
                        </h4>
                                            <p>After the avalanche it took us a week to climb out.</p>
                                            <a class="magnific more" href="assets/images/design/vector/img-1-1200x600.png" title="Quisque porta" data-prev-text="Previous" data-next-text="Next">
                                                <i class="fa fa-search-plus"></i>
                                            </a>
                                            <a class="link" href="portfolio-item-big.html">
                                                <i class="fa fa-link"></i>
                                            </a>
                                        </figcaption>
                                    </figure>
                                </div>
                                <div class="portfolio-item infinite-item col-md-4 col-sm-4 filter-video filter-image filter-gallery">
                                    <figure class="portfolio-figure">
                                        <a href="portfolio-item-big.html">
                                            <img width="600" height="518" src="<?=$theme_root?>assets/images/design/vector/img-5-600x518.png" class="img-responsive" alt="some image">
                                        </a>
                                        <figcaption>
                                            <h4>
                            <a href="portfolio-item-big.html">
                                Quisque porta
                            </a>
                        </h4>
                                            <p>I was living in his world, a world where chaos rules not order.</p>
                                            <a class="magnific more" href="assets/images/design/vector/img-5-1200x600.png" title="Quisque porta" data-prev-text="Previous" data-next-text="Next">
                                                <i class="fa fa-search-plus"></i>
                                            </a>
                                            <a class="link" href="portfolio-item-big.html">
                                                <i class="fa fa-link"></i>
                                            </a>
                                        </figcaption>
                                    </figure>
                                </div>
                                <div class="portfolio-item infinite-item col-md-4 col-sm-4 filter-video">
                                    <figure class="portfolio-figure">
                                        <a href="portfolio-item-big.html">
                                            <img width="600" height="518" src="<?=$theme_root?>assets/images/design/vector/img-2-600x518.png" class="img-responsive" alt="some image">
                                        </a>
                                        <figcaption>
                                            <h4>
                            <a href="portfolio-item-big.html">
                                Youtube video
                            </a>
                        </h4>
                                            <p>You think water moves fast? You should see ice.</p>
                                            <a class="magnific-vimeo more" href="http://www.youtube.com/watch?v=Sv3xVOs7_No">
                                                <i class="fa fa-search-plus"></i>
                                            </a>
                                            <a class="link" href="portfolio-item-big.html">
                                                <i class="fa fa-link"></i>
                                            </a>
                                        </figcaption>
                                    </figure>
                                </div>
                                <div class="portfolio-item infinite-item col-md-4 col-sm-4 filter-image filter-gallery">
                                    <figure class="portfolio-figure">
                                        <a href="portfolio-item-big.html">
                                            <img width="600" height="518" src="<?=$theme_root?>assets/images/design/vector/img-3-600x518.png" class="img-responsive" alt="some image">
                                        </a>
                                        <figcaption>
                                            <h4>
                            <a href="portfolio-item-big.html">
                                Quisque porta
                            </a>
                        </h4>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                                            <a class="magnific more" href="assets/images/design/vector/img-3-1200x600.png" title="Quisque porta" data-prev-text="Previous" data-next-text="Next">
                                                <i class="fa fa-search-plus"></i>
                                            </a>
                                            <a class="link" href="portfolio-item-big.html">
                                                <i class="fa fa-link"></i>
                                            </a>
                                        </figcaption>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="section swatch-white-red has-top has-bottom">
                <div class="decor-top">
                    <svg class="decor" height="100%" preserveaspectratio="none" version="1.1" viewbox="0 0 100 100" width="100%" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0 L50 100 L100 0 L100 100 L0 100" stroke-width="0"></path>
                    </svg>
                </div>
                <div class="container">
                    <header class="section-header underline">
                        <h1 class="headline super hairline">Meet the team</h1>
                    </header>
                    <div class="row">
                        <ul class="list-unstyled row box-list">
                            <li class="col-md-4 os-animation" data-os-animation="fadeInUp" data-os-animation-delay=".0s">
                                <div class="box-hex flat-shadow box-big">
                                    <div class="box-dummy"></div>
                                    <figure class="box-inner ">
                                        <img class="svg-inject" src="<?=$theme_root?>assets/images/design/people/man-1-600x518.png" alt="a man with a mustache" />
                                        <figcaption class="box-caption">
                                            <h4>Likes</h4>
                                            <p>Coffee and Beer</p>
                                        </figcaption>
                                    </figure>
                                </div>
                                <h3 class="text-center">
    
        <a href="about-me.html">
    
        John Langan
    
        </a>
    
    
        <small class="block">Art Directors</small>
    
</h3>
                                <p class="text-center ">Your bones don’t break, mine do. That’s clear. Your cells react to bacteria and viruses differently than mine. You don’t get sick, I do. That’s also clear. But for some reason, you and I react the exact same way to water.
                                    We swallow it too fast, we choke.</p>
                                <ul class="list-inline text-center social-icons social-simple">
                                    <li>
                                        <a href="about-me.html" target="_self">
                                            <i class="fa fa-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="about-me.html" target="_self">
                                            <i class="fa fa-twitter"></i>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="col-md-4 text-center os-animation" data-os-animation="fadeInUp" data-os-animation-delay=".3s">
                                <div class="box-hex flat-shadow box-big">
                                    <div class="box-dummy"></div>
                                    <figure class="box-inner ">
                                        <img class="svg-inject" src="<?=$theme_root?>assets/images/design/people/woman-1-600x518.png" alt="a woman" />
                                        <figcaption class="box-caption">
                                            <h4>Says</h4>
                                            <p>I like ancient stuff</p>
                                        </figcaption>
                                    </figure>
                                </div>
                                <h3 class="text-center">
    
        <a href="about-me.html">
    
        Kate Ross
    
        </a>
    
    
        <small class="block">Creative Director</small>
    
</h3>
                                <p class="text-center ">Your bones don’t break, mine do. That’s clear. Your cells react to bacteria and viruses differently than mine. You don’t get sick, I do. That’s also clear. But for some reason, you and I react the exact same way to water.
                                    We swallow it too fast, we choke.</p>
                                <ul class="list-inline text-center social-icons social-simple">
                                    <li>
                                        <a href="about-me.html" target="_self">
                                            <i class="fa fa-pinterest"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="about-me.html" target="_self">
                                            <i class="fa fa-instagram"></i>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="col-md-4 text-center os-animation" data-os-animation="fadeInUp" data-os-animation-delay=".6s">
                                <div class="box-hex flat-shadow box-big">
                                    <div class="box-dummy"></div>
                                    <figure class="box-inner ">
                                        <img class="svg-inject" src="<?=$theme_root?>assets/images/design/people/man-2-600x518.png" alt="man" />
                                        <figcaption class="box-caption">
                                            <h4>Moto</h4>
                                            <p>Live and let die</p>
                                        </figcaption>
                                    </figure>
                                </div>
                                <h3 class="text-center">
    
        <a href="about-me.html">
    
        Manos Jones
    
        </a>
    
    
        <small class="block">IOS Developer</small>
    
</h3>
                                <p class="text-center ">Your bones don’t break, mine do. That’s clear. Your cells react to bacteria and viruses differently than mine. You don’t get sick, I do. That’s also clear. But for some reason, you and I react the exact same way to water.
                                    We swallow it too fast, we choke.</p>
                                <ul class="list-inline text-center social-icons social-simple">
                                    <li>
                                        <a href="about-me.html" target="_self">
                                            <i class="fa fa-facebook-square"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="about-me.html" target="_self">
                                            <i class="fa fa-dribbble"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="about-me.html" target="_self">
                                            <i class="fa fa-google-plus-square"></i>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="decor-bottom">
                    <svg class="decor" height="100%" preserveaspectratio="none" version="1.1" viewbox="0 0 100 100" width="100%" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0 L50 100 L100 0" stroke-width="0"></path>
                    </svg>
                </div>
            </section>
            <section class="section swatch-red-white">
                <div class="background-media" style="background-image: url('assets/images/design/section-bg/devices.jpg'); background-repeat: ; background-size: ; background-attachment: fixed; background-position: ; background-size: cover;">
                </div>
                <div class="background-overlay" style="background-color:rgba(231,76,60,0.8)"></div>
                <div class="container">
                    <header class="section-header underline">
                        <h1 class="headline super hairline">Why people buy it?</h1>
                    </header>
                    <div class="row">
                        <div class="col-md-12 os-animation" data-os-animation="fadeInUp" data-os-animation-delay=".1s">
                            <div id="slider-flex2" class="flexslider" data-flex-speed="7000" data-flex-animation="slide" data-flex-directions="hide" data-flex-controls="show" data-flex-controlsalign="center">
                                <ul class="slides">
                                    <li>
                                        <blockquote class="fancy-blockquote">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p>Nullam vitae sollicitudin eros. Cras varius vehicula velit ac congue quam dictum sed. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.Donec sed odio dui. Nulla
                                                        vitae elit libero a pharetra augue. Nullam id dolor id ultricies ut vehicula ut id. Integer posuere erat a venenatis dapibus posuere velit aliquet duis mollis. Pellentesque ornare sem lacinia quam
                                                        venenatis vestibulum.</p>
                                                    <small>Manos Proistak
                                    
                                        <cite title="Source Title">Manager</cite>
                                    
                                </small>
                                                </div>
                                            </div>
                                        </blockquote>
                                    </li>
                                    <li>
                                        <blockquote class="fancy-blockquote">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <p>Cras aliquet felis in magna accumsan, sit amet mattis arcu auctor. Nunc sollicitudin auctor adipiscing. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Etiam lacus
                                                        ante, egestas id pellentesque vel, tempus at justo.</p>
                                                    <small>Christos Pantazis
                                    
                                        <cite title="Source Title">Executive</cite>
                                    
                                </small>
                                                </div>
                                            </div>
                                        </blockquote>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="section swatch-white-red has-top">
                <div class="decor-top">
                    <svg class="decor" height="100%" preserveaspectratio="none" version="1.1" viewbox="0 0 100 100" width="100%" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 100 L50 0 L100 100" stroke-width="0"></path>
                    </svg>
                </div>
                <div class="container">
                    <header class="section-header ">
                        <h1 class="headline super hairline">Ready to rock?</h1>
                        <p class="">He&#x27;s the exact opposite of the hero. And most times they&#x27;re friends, like you and me! I should&#x27;ve known way back when... You know why, David? Because of the kids. They called me Mr Glass.</p>
                    </header>
                    <div class="text-center">
                        <a class="btn btn-primary btn-lg btn-icon-right pull-center" href="#">
          Lets make it happen
          <div class="hex-alt hex-alt-big">
            <i class="fa fa-rocket" data-animation="tada"></i>
          </div>
        </a>
                    </div>
                </div>
            </section>
            <footer id="footer" role="contentinfo">
                <section class="section swatch-red-white has-top">
                    <div class="decor-top">
                        <svg class="decor" height="100%" preserveaspectratio="none" version="1.1" viewbox="0 0 100 100" width="100%" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0 L50 100 L100 0 L100 100 L0 100" stroke-width="0"></path>
                        </svg>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div id="swatch_social-2" class="sidebar-widget  widget_swatch_social">
                                    <ul class="unstyled inline small-screen-center social-icons social-background social-big">
                                        <li>
                                            <a target="_blank" href="http://www.oxygenna.com">
                                                <i class="fa fa-facebook"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank" href="http://www.oxygenna.com">
                                                <i class="fa fa-twitter"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a target="_blank" href="http://www.oxygenna.com">
                                                <i class="fa fa-google-plus"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div id="text-4" class="sidebar-widget widget_text">
                                    <div class="textwidget">ANGLE 2014 ALL RIGHTS RESERVED
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </footer>
        </div>
        <a class="go-top hex-alt" href="javascript:void(0)">
            <i class="fa fa-angle-up"></i>
        </a>
        <script src="<?=$theme_root?>assets/js/packages.min.js"></script>
        <script src="<?=$theme_root?>assets/js/theme.min.js"></script>
        <script src="<?=$theme_root?>assets/js/tools.min.js"></script>
        <script src="<?=$theme_root?>assets/js/revolution.min.js"></script>
        <script type="text/javascript">
        jQuery(document).ready(function()
        {
            jQuery('.tp-banner').show().revolution(
            {
                delay: 8000,
                startwidth: 1170,
                startheight: 480,
                onHoverStop: "on", // Stop Banner Timer at Hover on Slide on/off
                thumbWidth: 100, // Thumb With and Height and Amount (only if navigation Tyope set to thumb !)
                thumbHeight: 50,
                thumbAmount: 3,
                hideThumbs: 0,
                navigationType: "bullet", // bullet, thumb, none
                navigationArrows: "solo", // nexttobullets, solo (old name verticalcentered), none
                navigationStyle: "round", // round,square,navbar,round-old,square-old,navbar-old, or any from the list in the docu (choose between 50+ different item), custom
                navigationHAlign: "center", // Vertical Align top,center,bottom
                navigationVAlign: "bottom", // Horizontal Align left,center,right
                navigationHOffset: 0,
                navigationVOffset: 20,
                soloArrowLeftHalign: "left",
                soloArrowLeftValign: "center",
                soloArrowLeftHOffset: 20,
                soloArrowLeftVOffset: 0,
                soloArrowRightHalign: "right",
                soloArrowRightValign: "center",
                soloArrowRightHOffset: 20,
                soloArrowRightVOffset: 0,
                touchenabled: "on", // Enable Swipe Function : on/off
                stopAtSlide: -1, // Stop Timer if Slide "x" has been Reached. If stopAfterLoops set to 0, then it stops already in the first Loop at slide X which defined. -1 means do not stop at any slide. stopAfterLoops has no sinn in this case.
                stopAfterLoops: -1, // Stop Timer if All slides has been played "x" times. IT will stop at THe slide which is defined via stopAtSlide:x, if set to -1 slide never stop automatic
                hideCaptionAtLimit: 0, // It Defines if a caption should be shown under a Screen Resolution ( Basod on The Width of Browser)
                hideAllCaptionAtLilmit: 0, // Hide all The Captions if Width of Browser is less then this value
                hideSliderAtLimit: 0, // Hide the whole slider, and stop also functions if Width of Browser is less than this value
                fullWidth: "on",
                shadow: 0
            });
        });
        </script>
    </body>
</html>
<?php

function get_controller( \__name__\controllers\app_controller $controller ){

    return $controller;
}

/**
 * @param \__name__\controllers\app_controller $controller
 * @return \__name__\components\state
 */
function get_state( \__name__\controllers\app_controller $controller ){

    return $controller->state();
}

/**
 * @param \__name__\components\state $state
 * @return \xobjects\components\page_content
 */
function get_content( \__name__\components\state $state ){

    return $state->get_content();

}

?>
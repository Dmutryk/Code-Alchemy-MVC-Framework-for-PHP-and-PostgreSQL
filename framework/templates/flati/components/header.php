<?php

$controller = get_controller( $this );

$is_logged_in = $controller->is_logged_in();

$is_admin = $controller->is_admin();

$theme_root = $controller->theme_root();

?>
<!--header-->
<div class="header">
    <!--menu-->
    <nav id="main_menu" class="navbar" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <!--toggle-->
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
                    <i class="fa fa-bars"></i>
                </button>
                <!--logo-->
                <div class="logo">
                    <a href="index.html"><img src="<?=$theme_root?>img/logo.png" alt="" class="animated bounceInDown" /></a>
                </div>
            </div>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown active"><a href="javascript:{}">Home</a>
                        <ul class="dropdown-menu">
                            <li><a href="index.html">Slider Revolution</a></li>
                            <li><a href="index2.html">Nerve Slider</a></li>
                            <li><a href="index3.html">NivoSlider</a></li>
                            <li><a href="index4.html">Slides</a></li>
                            <li><a href="index5.html">Html5 Video</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="javascript:{}">Pages</a>
                        <ul class="dropdown-menu">
                            <li><a href="team.html">The Team</a></li>
                            <li><a href="about.html">About</a></li>
                            <li><a href="services.html">Services</a></li>
                            <li><a href="testimonials.html">Testimonials</a></li>
                            <li><a href="timeline.html">Timeline</a></li>
                            <li><a href="full.html">Full Width</a></li>
                            <li><a href="left_sidebar.html">Left Sidebar</a></li>
                            <li><a href="right_sidebar.html">Right Sidebar</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="javascript:{}">Work</a>
                        <ul class="dropdown-menu">
                            <li><a href="portfolio_2columns.html">2 Columns</a></li>
                            <li><a href="portfolio_3columns.html">3 Columns</a></li>
                            <li><a href="portfolio_4columns.html">4 Columns</a></li>
                            <li><a href="portfolio_masonry.html">Masonry</a></li>
                            <li><a href="gallery.html">Paginated Gallery</a></li>
                            <li><a href="video_gallery.html">Video Gallery</a></li>
                            <li><a href="single_portfolio.html">Single Slider</a></li>
                            <li><a href="single_video.html">Single Video</a></li>
                            <li><a href="single_image.html">Single Image</a></li>
                            <li><a href="full_slider.html">Full Slider</a></li>
                            <li><a href="full_video.html">Full Video</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="javascript:{}">Style</a>
                        <ul class="dropdown-menu">
                            <li><a href="scaffolding.html">Scaffolding</a></li>
                            <li><a href="shortcodes.html">Shortcodes</a></li>
                            <li><a href="icons.html">Icons</a></li>
                            <li><a href="script_examples.html">JS Examples</a></li>
                            <li><a href="javascript:{}">Sub Menu</a>
                                <ul class="dropdown-menu sub-menu">
                                    <li><a href="#">Sub One</a></li>
                                    <li><a href="#">Sub Two</a></li>
                                    <li><a href="#">Sub Three</a></li>
                                    <li><a href="#">Sub Four</a></li>
                                </ul>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="javascript:{}">Extras</a>
                        <ul class="dropdown-menu">
                            <li><a href="dribbble_stream.html">Dribbble Stream</a></li>
                            <li><a href="alt_footer.html">Alt. Footer & Boxes</a></li>
                            <li><a href="pricing_table.html">Pricing Table</a></li>
                            <li><a href="404.html">404 Page</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a href="javascript:{}">Blog</a>
                        <ul class="dropdown-menu">
                            <li><a href="blog.html">Blog</a></li>
                            <li><a href="blog_two.html">Blog II</a></li>
                            <li><a href="blog_post.html">Blog Post</a></li>
                            <li><a href="blog_post2.html">Blog Post II</a></li>
                        </ul>
                    </li>
                    <li><a href="contact.html">Contact</a></li>
                    <?php if ( ! $is_logged_in ){?>
                        <li class="menu-smallish <?=$active=='signin'?'active':''?>"><a href="/acceder">Acceder | Inscribirse</a></li>
                    <?php } else {

                        if ( $is_admin ){ ?>
                            <li class="dropdown menu-smallish <?=$active=='administrar'?'active':''?>"><a href="javascript:{}">Administrar</a>
                                <ul class="dropdown-menu">
                                    <li><a target="_parnassus" href="/parnassus">Sitio Web</a></li>
                                </ul></li>
                        <?php } ?>
                        <li class="menu-smallish <?=$active=='signin'?'active':''?>"><a href="/salir">Bienvenido, <?=$user['first_name']?>&nbsp;|&nbsp;Salir</a></li>
                    <?php }   ?>

                </ul>
            </div>
        </div>
    </nav>
</div>
<!--//header-->

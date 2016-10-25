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

$is_logged_in = $controller->is_logged_in();

$is_admin = $controller->is_admin();

$is_customer = $controller->is_customer();

?>
<header id="masthead" class="navbar navbar-sticky swatch-red-white" role="banner">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".main-navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="<?=$theme_root?>../index.html" class="navbar-brand">
                <img src="<?=$theme_root?>assets/images/logo.png" alt="One of the best themes ever">Angle
            </a>
        </div>
        <nav class="collapse navbar-collapse main-navbar" role="navigation">
            <div class="sidebar-widget widget_search pull-right">
                <form>
                    <div class="input-group">
                        <input class="form-control" type="text" placeholder="Search here....">
                                <span class="input-group-btn">
                            <button class="btn" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown active">
                    <a href="<?=$theme_root?>#" class="dropdown-toggle" data-toggle="dropdown">Home</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?=$theme_root?>index.html">v1. Classic</a>
                        </li>
                        <li><a href="<?=$theme_root?>launch.html">v2. Product Launch</a>
                        </li>
                        <li><a href="<?=$theme_root?>about-us-home.html">v3. The team</a>
                        </li>
                        <li><a href="<?=$theme_root?>one-page.html">v4. One Page Style</a>
                        </li>
                        <li><a href="<?=$theme_root?>flexslider.html">v5. Flexslider</a>
                        </li>
                        <li><a href="<?=$theme_root?>revolution-slider.html">v6. Revolution SLider</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown ">
                    <a href="<?=$theme_root?>#" class="dropdown-toggle" data-toggle="dropdown">Pages</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?=$theme_root?>services-page.html">Services</a>
                        </li>
                        <li><a href="<?=$theme_root?>services-alt-page.html">More Services</a>
                        </li>
                        <li><a href="<?=$theme_root?>single-service.html">Single Service</a>
                        </li>
                        <li><a href="<?=$theme_root?>about-us.html">About Us</a>
                        </li>
                        <li><a href="<?=$theme_root?>about-me.html">About Me</a>
                        </li>
                        <li><a href="<?=$theme_root?>office.html">Our Office</a>
                        </li>
                        <li><a href="<?=$theme_root?>faq.html">FAQ</a>
                        </li>
                        <li><a href="<?=$theme_root?>404.html">404</a>
                        </li>
                        <li><a href="<?=$theme_root?>countdown.html">Coming Soon</a>
                        </li>
                        <li><a href="<?=$theme_root?>pricing.html">Pricing</a>
                        </li>
                        <li><a href="<?=$theme_root?>sidebar-right.html">Right Sidebar</a>
                        </li>
                        <li><a href="<?=$theme_root?>sidebar-left.html">Left Sidebar</a>
                        </li>
                        <li><a href="<?=$theme_root?>header-alt.html">Alt Header</a>
                        </li>
                        <li><a href="<?=$theme_root?>footer-alt.html">Footer Columns</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown ">
                    <a href="<?=$theme_root?>#" class="dropdown-toggle" data-toggle="dropdown">Features</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?=$theme_root?>typography.html">Typography</a>
                        </li>
                        <li><a href="<?=$theme_root?>elements.html">Elements</a>
                        </li>
                        <li><a href="<?=$theme_root?>icons.html">Font Icons</a>
                        </li>
                        <li><a href="<?=$theme_root?>custom-icons.html">Custom Icons</a>
                        </li>
                        <li><a href="<?=$theme_root?>tables.html">Tables</a>
                        </li>
                        <li><a href="<?=$theme_root?>section-decorations.html">Section Decorations</a>
                        </li>
                        <li><a href="<?=$theme_root?>background-videos.html">Background Videos</a>
                        </li>
                        <li><a href="<?=$theme_root?>background-images.html">Background Images</a>
                        </li>
                        <li><a href="<?=$theme_root?>color-swatches.html">Color Swatches</a>
                        </li>
                        <li><a href="<?=$theme_root?>scroll-animation.html">Scroll Animations</a>
                        </li>
                        <li><a href="<?=$theme_root?>header-options.html">Header Options</a>
                        </li>
                        <li><a href="<?=$theme_root?>footer-options.html">Footer Options</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown ">
                    <a href="<?=$theme_root?>#" class="dropdown-toggle" data-toggle="dropdown">Blog</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?=$theme_root?>blog.html">Normal Blog</a>
                        </li>
                        <li><a href="<?=$theme_root?>blog-fullwidth.html">Full Width Blog</a>
                        </li>
                        <li><a href="<?=$theme_root?>grid-blog.html">Grid Blog</a>
                        </li>
                        <li><a href="<?=$theme_root?>post.html">Single Post</a>
                        </li>
                        <li><a href="<?=$theme_root?>blog-styles.html">Blog Styles</a>
                        </li>
                        <li><a href="<?=$theme_root?>results.html">Search Results</a>
                        </li>
                        <li><a href="<?=$theme_root?>author.html">Author&#x27;s Page</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown ">
                    <a href="<?=$theme_root?>#" class="dropdown-toggle" data-toggle="dropdown">Portfolio</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?=$theme_root?>portfolio-2col.html">Two Columns</a>
                        </li>
                        <li><a href="<?=$theme_root?>portfolio-3col.html">Three Columns</a>
                        </li>
                        <li><a href="<?=$theme_root?>portfolio-4col.html">Four Columns</a>
                        </li>
                        <li><a href="<?=$theme_root?>portfolio-3col-circles.html">Circled Portfolio</a>
                        </li>
                        <li><a href="<?=$theme_root?>portfolio-3col-rect.html">Rectangle Portfolio</a>
                        </li>
                        <li><a href="<?=$theme_root?>portfolio-3col-squares.html">Square Portfolio</a>
                        </li>
                        <li role="presentation" class="divider"></li>
                        <li><a href="<?=$theme_root?>portfolio-item-big.html">Single Big</a>
                        </li>
                        <li><a href="<?=$theme_root?>portfolio-item-big-alt.html">Single Big Alt</a>
                        </li>
                        <li><a href="<?=$theme_root?>portfolio-item-small.html">Single Small</a>
                        </li>
                        <li><a href="<?=$theme_root?>portfolio-item-video.html">Single Video</a>
                        </li>
                        <li><a href="<?=$theme_root?>portfolio-item-gallery.html">Single Gallery</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown ">
                    <a href="<?=$theme_root?>#" class="dropdown-toggle" data-toggle="dropdown">Contact</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?=$theme_root?>contact.html">Contact</a>
                        </li>
                    <li><a href="<?=$theme_root?>contact-alt.html">Contact Alt</a>
                        </li>
                    </ul>
                </li>

                <?php if ( ! $is_logged_in ){?>
                    <li class="smallish">
                        <a href="/login">Sign in (Acceder)</a>
                    </li>
                <?php } else {
                    if ( $is_admin ){?>
                        <li class="smallish">
                            <a target="_parnassus_admin" href="/parnassus">Manage</a>
                        </li>
                    <?php }

                    if ( $is_customer ) { ?>
                        <li class="smallish">
                            <a target="_parnassus_portal" href="/parnassus-portal">Portal</a>
                        </li>
                        <?php } ?>
                    <li class="smallish">
                        <a href="/logout">Sign out (Salir)</a>
                    </li>
                <?php  }?>

            </ul>
        </nav>
    </div>
</header>

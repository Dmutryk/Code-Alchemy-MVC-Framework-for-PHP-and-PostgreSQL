<?php

// Get Controller
$controller = get_controller( $this );

// Get State
$state = get_state( $controller );

// Theme root
$theme_root = "/themes/flati/flati/";

// Web root
$webroot = x_objects::instance()->webroot();

$components_directory = $webroot.'/app/views/components/flati/';

$data = $controller->data();

$photo = $data['photo'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once $components_directory.'head.php';?>
</head>

<body>
<?php require_once $components_directory.'header.php';?>
	<!--page-->
	<div id="banner">
		<div class="container intro_wrapper">
			<div class="inner_content">
				<h1><?=$photo['photo_category_name']?></h1>
					<h1 class="title"><?=$photo['caption']?></h1>
						<!--<h1 class="intro">
							We are what we repeatedly do. <span>Excellence</span>, therefore, is not an <span class="hue">act</span>, but a habit. - Aristotle
						</h1>
						-->
						   <div class="portfolio-links pad15">
							<a href="<?=$photo['next_photo_url']?>"><span class="fa-stack fa-lg"><i class="fa fa-angle-left fa-stack-1x fa-inverse"></i></span></a>
							<a href="<?=$photo['previous_photo_url']?>"><span class="fa-stack fa-lg"><i class="fa fa-angle-right fa-stack-1x fa-inverse"></i></span></a>
						</div>
					</div>
				</div>
			</div>
			
			<div class="container wrapper">
			<div class="inner_content">
				<div class="row">
				
				<div class="col-md-8 pad25">
					<div class="hover_colour">
						<a title="<?=$photo['caption']?>" href="<?=$photo['image_filename_url']?>" data-rel="prettyPhoto">
						<img src="<?=$photo['image_filename_url']?>" alt="<?=$photo['caption']?>" /></a>
					</div>
				</div>
		
                    <div class="col-md-4">
                    
                      <h2><span>Photo Description</span></h2>

						<p class="lead">
                            <?=$photo['description']?>
                        </p>

                        <!--
						<p>
							Suspendisse tempor leo at massa laoreet vel tincidunt leo molestie. Proin tristique accumsan nisl, quis sollicitudin urna ullamcorper 
							posuere cubilia curae vel.
						</p>

						<h4><span>Services</span></h4>
						
						<ul class="fa-ul">
							<li><i class="fa-li fa fa-globe colour"></i> Web Design</li>
							<li><i class="fa-li fa fa-pencil colour"></i> Graphic Design</li>
							<li><i class="fa-li fa fa-laptop colour"></i> Web Hosting</li>
						</ul>
						
						 <div class="pad10"></div>
							<a href="#" class="btn btn-info btn-primary btn-custom">view website</a> 
						<div class="pad30"></div>
						-->
					</div>
				</div>
			</div>
		</div>
		
	<!--related projects-->	
	
	<div class="strip2">
		<h1>Related Projects</h1>
			<h3 class="center about_strip">
				Ecilisis venenatis risus, suspendisse ac nec et. Nulla sed mauris, congue duis proin nonummy. Elementum phasellus. Mauris sed nulla sed, 
				egestas feugiat a dictum libero  vivamus purus arcu, commodo cursus egestas et.
			</h3>
			
			<div class="container wrapper">
			<div class="inner_content col_full">
			
			<div id="slider_related">
			<div class="slider-item">
				<div class="slider-image">
				<div class="hover_colour">
				<a href="img/large/s1.jpg" data-rel="prettyPhoto[portfolio1]">
				<img src="<?=$theme_root?>img/small/s1.jpg" alt=""/></a>
				</div> 
				<a class="related_link" href="#">Item Link</a>
				</div>
			</div>
			
			<div class="slider-item">
				<div class="slider-image">
				<div class="hover_colour">
				<a href="img/large/s2.jpg" data-rel="prettyPhoto[portfolio1]">
				<img src="<?=$theme_root?>img/small/s2.jpg" alt=""/></a>
				</div>
				<a class="related_link" href="#">Item Link</a>
				</div>
			</div>
			
			<div class="slider-item">
				<div class="slider-image">
				<div class="hover_colour">
				<a href="img/large/s3.jpg" data-rel="prettyPhoto[portfolio1]">
				<img src="<?=$theme_root?>img/small/s3.jpg" alt=""/></a>
				</div>
				<a class="related_link" href="#">Item Link</a>
				</div>
			</div>
			
			<div class="slider-item">
				<div class="slider-image">
				<div class="hover_colour">
				<a href="img/large/s4.jpg" data-rel="prettyPhoto[portfolio1]">
				<img src="<?=$theme_root?>img/small/s4.jpg" alt=""/></a>
				</div>
				<a class="related_link" href="#">Item Link</a>
				</div>
			</div>
			
			<div class="slider-item">
				<div class="slider-image">
				<div class="hover_colour">
				<a href="img/large/s5.jpg" data-rel="prettyPhoto[portfolio1]">
				<img src="<?=$theme_root?>img/small/s5.jpg" alt=""/></a>
				</div>
				<a class="related_link" href="#">Item Link</a>
				</div>
			</div>
			
			<div class="slider-item">
				<div class="slider-image">
				<div class="hover_colour">
				<a href="img/large/s6.jpg" data-rel="prettyPhoto[portfolio1]">
				<img src="<?=$theme_root?>img/small/s6.jpg" alt=""/></a>
				</div>
				<a class="related_link" href="#">Item Link</a>
				</div>
			</div>
			
			<div class="slider-item">
				<div class="slider-image">
				<div class="hover_colour">
				<a href="img/large/s7.jpg" data-rel="prettyPhoto[portfolio1]">
				<img src="<?=$theme_root?>img/small/s7.jpg" alt=""/></a>
				</div>
				<a class="related_link" href="#">Item Link</a>
				</div>
			</div>
			
			<div class="slider-item">
				<div class="slider-image">
				<div class="hover_colour">
				<a href="img/large/s8.jpg" data-rel="prettyPhoto[portfolio1]">
				<img src="<?=$theme_root?>img/small/s8.jpg" alt=""/></a>
				</div>
				<a class="related_link" href="#">Item Link</a>
				</div>
			</div>
			</div>
				<div id="sl-prev" class="widget-scroll-prev2"><i class="fa fa-chevron-left white"></i></div>
				<div id="sl-next" class="widget-scroll-next2"><i class="fa fa-chevron-right white but_marg"></i></div>
				</div>
			<!--//projects-->
				</div>
			</div>
		<!--//page-->

    <?php require_once $components_directory.'footer-two.php';?>

<!-- up to top -->
				<a href="#"><i class="go-top fa fa-angle-double-up"></i></a>
				<!--//end-->

<script src="<?=$theme_root?>js/jquery.js"></script>
<script src="<?=$theme_root?>js/bootstrap.min.js"></script>
<script src="<?=$theme_root?>js/jquery.touchSwipe.min.js"></script>
<script src="<?=$theme_root?>js/jquery.mousewheel.min.js"></script>	
<script type="text/javascript" src="<?=$theme_root?>js/jquery.prettyPhoto.js"></script>
<script src="<?=$theme_root?>js/retina.js"></script>
<script type="text/javascript" src="<?=$theme_root?>js/scripts.js"></script>

<script type="text/javascript" src="<?=$theme_root?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
<!--carousel--> 
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($) {
	$("#slider_related").carouFredSel({ width : "100%", height : "auto",
	responsive : true, auto : false,
	items : { width : 230, visible: { min: 1, max: 5 }
	},
	swipe : { onTouch : true, onMouse : true },
	scroll: { items: 1, },
	prev : { button : "#sl-prev", key : "left"},
	next : { button : "#sl-next", key : "right" }
	});
});
//]]>
</script>

</body>
</html><?php

function get_controller( \__my_namespace__\controllers\app_controller $controller ){

    return $controller;
}

/**
 * @param \__my_namespace__\controllers\app_controller $controller
 * @return \__my_namespace__\components\state
 */
function get_state( \__my_namespace__\controllers\app_controller $controller ){

    return $controller->state();
}
?>
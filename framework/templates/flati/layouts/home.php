<?php

// Get Controller
$controller = get_controller( $this );

// Get State
$state = get_state( $controller );

// Theme root
$theme_root = $controller->theme_root();

// Web root
$webroot = x_objects::instance()->webroot();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once( $webroot."/app/views/components/head.php");?>
</head>

<body>
<?php require_once( $webroot."/app/views/components/header.php");?>

	<!--page-->
		<!-- REVOLUTION SLIDER -->
        <div class="tp-banner-container">
		<div class="tp-banner">
		<ul>
		<!-- Slide 1 -->
			<li data-transition="slideright">
				<img src="<?=$theme_root?>img/slider/slider1.jpg" alt="" />
				
				<!-- Caption -->
				<div class="tp-caption lfr" data-x="left" data-y="220" data-speed="2400" data-start="800" data-easing="easeOutExpo">
					<img src="<?=$theme_root?>img/slider/robot1.png" alt="" />
				</div>
					
				<!-- Caption -->
				<div class="tp-caption lfb" data-x="870" data-y="150" data-speed="1400" data-start="1800" data-easing="easeOutExpo">
					<img src="<?=$theme_root?>img/slider/rocket.png" alt="" />
				</div>
				
				<!-- Caption -->
				<div class="tp-caption lfb" data-x="825" data-y="340" data-speed="1500" data-start="1900" data-easing="easeOutExpo">
					<img src="<?=$theme_root?>img/slider/flames.png" alt="" />
				</div>
				
				<!-- Caption -->	
				<div class="caption sft stl" data-x="center" data-y="150" data-speed="1000" data-start="700" data-easing="easeOutExpo">
					<h3 class="rev-title bold">FLATI BOOTSTRAP</h3>
				</div>
				
				<!-- Caption -->
				<div class="caption lfl stl rev-title-sub" data-x="center" data-y="260" data-speed="800" data-start="1100" data-easing="easeOutExpo">
					CREATE - DESIGN - CODE
				</div>
				
				<!-- Caption -->
				<div class="caption sfb" data-x="center" data-y="350" data-speed="1100" data-start="1500" data-easing="easeOutExpo">
					<a href="#" class="btn btn-outline btn-mobile">OUR PORTFOLIO</a>                 
				</div>
			</li>
			
			<!-- Slide 2 -->
				<li data-transition="slideleft">
				<img src="<?=$theme_root?>img/slider/slider2.jpg" alt="" />
				
				<!-- Caption -->
				<div class="tp-caption lfl" data-x="right" data-y="55" data-speed="1000" data-start="800" data-easing="easeOutExpo">
					<img src="<?=$theme_root?>img/slider/iMac.png" alt="" />
				</div>
					
				<!-- Caption -->
				<div class="caption lfl stl bg" data-x="20" data-y="60" data-speed="800" data-start="700" data-easing="easeOutExpo">
					<h2 class="rev-title big white">Creative Slider<br>It's a Revolution!</h2>
				</div>
					
				<!-- Caption -->
				<div class="caption lfl stl rev-text rev-left" data-x="left" data-y="210" data-speed="800" data-start="1100" data-easing="easeOutExpo">
					<p class="hidden-xs">Turn simple HTML code into a responsive sliderwith must-see-effects,<br />
					it's Slider Revolution! All elements are included in the download,<br/>
					plus a psd file of the iMac to add your own screenshot to.
					<br/>Get Creative!</p>
				</div>
					
				<!-- Caption -->
				<div class="caption sfb stb rev-left" data-x="left" data-y="430" data-speed="1100" data-start="1500" data-easing="easeOutExpo">
					<a href="#" class="btn btn-outline btn-mobile2 marg-right5">SERVICES</a>
					<a href="#" class="btn btn-outline btn-mobile2">CONTACT</a>                     
				</div>
			</li>
			
			<!-- Slide 3 -->
				<li data-transition="slideleft" >
				<img src="<?=$theme_root?>img/slider/slider3.jpg" alt="" />
				
				<!-- Caption -->	
				<div class="tp-caption lfl" data-x="right" data-y="40" data-speed="1000" data-start="800" data-easing="easeOutExpo">
					<img src="<?=$theme_root?>img/slider/robot2.png" alt="" />
				</div>
				
				<!-- Caption -->
				<div class="caption lfl stl bold bg rev-left" data-x="left" data-y="80" data-speed="800" data-start="1500" data-easing="easeOutExpo">
					<h3 class="rev-title big bold">hire us today</h3>
				</div>

				<!-- Caption -->
				<div class="caption lfl stl rev-text rev-left" data-x="left" data-y="200" data-speed="800" data-start="1700" data-easing="easeOutExpo">
					<p class="white hidden-xs">This plugin features tons of unique transition effects,<br />
					an image preloader, video embedding, autoplay that stops on user interaction<br />
					and lots of easy to set options to create your own effects.</p>
				</div>
				
				<!-- Caption -->	
				<div class="caption sfb rev-left" data-x="left" data-y="370" data-speed="800" data-start="1900" data-easing="easeOutExpo">
					<h3 class="rev-title2 bold">info@flatistudios.com</h3>
				</div>	
			</li>
			
			<!-- SLIDE 4 -->
				<li data-transition="slideleft" data-slotamount="7" data-masterspeed="600">
					<!-- MAIN IMAGE -->
					<img src="<?=$theme_root?>img/slider/slider4.jpg" alt="" data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat">
					<!-- A -->
					<div class="tp-caption sft customout"
							data-x="center" data-hoffset="0"
						data-y="445"
						data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
						data-speed="1000"
						data-start="1500"
						data-easing="Back.easeOut"
						data-endspeed="500"
						style="z-index: 7">
					<img src="<?=$theme_root?>img/slider/videoshadow.png" alt="">
					</div>
					<!-- B -->
					<div class="tp-caption customin customout"
						data-x="center" data-hoffset="0"
						data-y="top" data-voffset="100"
						data-customin="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:5;scaleY:5;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
						data-customout="x:0;y:0;z:0;rotationX:0;rotationY:0;rotationZ:0;scaleX:0.75;scaleY:0.75;skewX:0;skewY:0;opacity:0;transformPerspective:600;transformOrigin:50% 50%;"
						data-speed="600"
						data-start="1000"
						data-easing="Power4.easeOut"
						data-endspeed="500"
						data-endeasing="Power4.easeOut"
					 	data-autoplay="true"
   						data-autoplayonlyfirsttime="true"
						data-nextslideatend="true"
						style="z-index: 8">
						<iframe src='http://www.youtube.com/embed/AkPrczmw9cU?hd=1&amp;wmode=opaque&amp;controls=1&amp;showinfo=0' width='640' height='360'></iframe>
					</div>
				</li>
				<!-- END SLIDE 4 -->
			</ul>
			<div class="tp-bannertimer tp-bottom"></div>
            </div>
        </div>
        <!-- // END REVOLUTION SLIDER -->

	<div id="banner">
	<div class="container intro_wrapper">
	<div class="inner_content">
	
	<!--welcome-->
		<div class="welcome_index">
			I think most <a href="#"><span class="hue_block white normal">programmers</span></a> spend the first 5 years of their career mastering 
			<span class="hue">complexity</span>	and the rest of their lives learning <span>simplicity</span>
			- Buzz Andersen
		</div>
	<!--//welcome-->
		</div>
			</div>
				</div>
				<!--//banner-->
			
	<div class="container wrapper">
	<div class="inner_content">
	
	<!--info boxes-->
	<div class="row pad45">
			<div class="col-sm-6 col-md-3">
				<div class="tile">
					<div class="intro-icon-disc cont-large"><i class="fa fa-pencil intro-icon-large"></i></div>
					<h6>
						<span>DESIGN<br><a href="#">built for &amp; by nerds</a></span>
					</h6>
					<p>
						Like you, we love building awesome products on the web. We love it so much, we decided to help people just like us do it easier, 
						better, and faster.
					</p>
					</div> 
				<div class="pad25"></div>
			</div> 
				
			<div class="col-sm-6 col-md-3">
				<div class="tile">
					<div class="intro-icon-disc cont-large"><i class="fa fa-umbrella intro-icon-large"></i></div>
						<h6>
							<span>CODE<br><a href="#">12-column grid</a></span>
						</h6>
						<p>
							Bootstrap is designed to help people of all skill levels - designer or developer, huge nerd or early beginner. 
							Use it as a complete kit or use to start something.
						</p>
						</div> 
				<div class="pad25"></div>
			</div> 
					
			<div class="col-sm-6 col-md-3">
				<div class="tile">
					<div class="intro-icon-disc cont-large"><i class="fa fa-flask intro-icon-large"></i></div>
						<h6><span>CREATE<br><a href="#">responsive</a></span></h6>
						<p>
							Bootstrap have gone fully responsive. Our components are scaled according to a range of resolutions and devices to provide a 
							consistent experience.
						</p>	
						</div> 
				<div class="pad25"></div>
			</div> 
				
			<div class="col-sm-6 col-md-3">
				<div class="tile tile-hot">
					<div class="intro-icon-disc cont-large"><i class="fa fa-book  intro-icon-large"></i></div>
						<h6>
							<span>SUPPORT<br><a href="#">growing library</a></span></h6>
						<p>
							Despite being only 7kb (gzipped), Bootstrap is one of the most complete front-end toolkits out there with dozens of fully functional components.
						</p>
						</div>
					<div class="pad25"></div>	
			</div> 
				</div> 
				
			<!--//info boxes-->
			
			<div class="pad25 hidden-xs hidden-sm"></div> 
			<div class="row">
			<!--col 1-->
			<div class="col-md-12">
			<div class="row">
			
			<div class="col-sm-12 col-md-4">
			<h1>Recent Work</h1>
			<h4>
				Lorem ipsum dolor sit amet, rebum putant recusabo in ius, pri simul tempor ne, his ei summo virtute.
			</h4>
			<p>
				Nam ea labitur pericula. Meis tamquam pro te, cibo mutat necessitatibus id vim. An his tamquam postulant, pri id mazim nostrud diceret 
				sapientem eloquentiam sea cu, sea ut exerci delicata. Corrumpit vituperata.
			</p>
			
			<a href="#" class="btn btn-primary btn-custom btn-rounded">view portfolio</a>
			<div class="pad30"></div>
			</div>
			
			<!--column 2 slider-->
			<div class="col-xs-12 col-sm-12 col-md-8 pad30">
			
			<div id="slider_home">
            <div class="slider-item">	
				<div class="slider-image">
				<div class="hover_colour">
				<a href="<?=$theme_root?>img/large/s1.jpg" data-rel="prettyPhoto">
					<img src="<?=$theme_root?>img/small/s1.jpg" alt="" /></a>
					</div>
				</div>
				<div class="slider-title">
				<h3><a href="#">catalogue</a></h3>
				<p>An his tamquam postulant, pri id mazim nostrud diceret.</p>
				</div>
			</div>
			
			<div class="slider-item">
				<div class="slider-image">
				<div class="hover_colour">
				<a href="<?=$theme_root?>img/large/s2.jpg" data-rel="prettyPhoto">
					<img src="<?=$theme_root?>img/small/s2.jpg" alt="" /></a>
					</div>
				</div>
				<div class="slider-title">
				<h3><a href="#">loupe</a></h3>
				<p>An his tamquam postulant, pri id mazim nostrud diceret.</p>
				</div>
			</div>
			
			<div class="slider-item">
				<div class="slider-image">
				<div class="hover_colour">
				<a href="<?=$theme_root?>img/large/s3.jpg" data-rel="prettyPhoto">
					<img src="<?=$theme_root?>img/small/s3.jpg" alt="" /></a>
					</div>
				</div>
				<div class="slider-title">
				<h3><a href="#">retro rocket</a></h3>
				<p>An his tamquam postulant, pri id mazim nostrud diceret.</p>
				</div>
			</div>
			
			<div class="slider-item">
				<div class="slider-image">
				<div class="hover_colour">
				<a href="<?=$theme_root?>img/large/s4.jpg" data-rel="prettyPhoto">
					<img src="<?=$theme_root?>img/small/s4.jpg" alt="" /></a>
					</div>
				</div>
				<div class="slider-title">
				<h3><a href="#">infographics</a></h3>
				<p>An his tamquam postulant, pri id mazim nostrud diceret.</p>
				</div>
			</div>
			
			<div class="slider-item">
				<div class="slider-image">
				<div class="hover_colour">
				<a href="<?=$theme_root?>img/large/s5.jpg" data-rel="prettyPhoto">
					<img src="<?=$theme_root?>img/small/s5.jpg" alt="" /></a>
					</div>
				</div>
				<div class="slider-title">
				<h3><a href="#">mock up</a></h3>
					<p>An his tamquam postulant, pri id mazim nostrud diceret.</p>
					</div>
				</div>
			
			<div class="slider-item">
				<div class="slider-image">
				<div class="hover_colour">
				<a href="<?=$theme_root?>img/large/s6.jpg" data-rel="prettyPhoto">
					<img src="<?=$theme_root?>img/small/s6.jpg" alt="" /></a>
					</div>
				</div>
				<div class="slider-title">
				<h3><a href="#">retro badges</a></h3>
					<p>An his tamquam postulant, pri id mazim nostrud diceret.</p>
					</div>
				</div>
			
			<div class="slider-item">
			<div class="slider-image">
				<div class="hover_colour">
				<a href="<?=$theme_root?>img/large/s7.jpg" data-rel="prettyPhoto">
					<img src="<?=$theme_root?>img/small/s7.jpg" alt="" /></a>
					</div>
				</div>
				<div class="slider-title">
				<h3><a href="#">details</a></h3>
					<p>An his tamquam postulant, pri id mazim nostrud diceret.</p>
				</div>
			</div>
			
			<div class="slider-item">
				<div class="slider-image">
				<div class="hover_colour">
				<a href="<?=$theme_root?>img/large/s8.jpg" data-rel="prettyPhoto">
					<img src="<?=$theme_root?>img/small/s8.jpg" alt="" /></a>
					</div>
				</div>
				<div class="slider-title">
				<h3><a href="#">vintage form</a></h3>
					<p>An his tamquam postulant, pri id mazim nostrud diceret.</p>
				</div>
			</div>
				</div>
				<div id="sl-prev" class="widget-scroll-prev"><i class="fa fa-chevron-left white"></i></div>
				<div id="sl-next" class="widget-scroll-next"><i class="fa fa-chevron-right white but_marg"></i></div>
			<div class="pad25"></div> </div>
				</div>
				</div>
			</div>
		</div>
		<!--//page-->
	</div>
	
	<!-- footer -->
	<div id="footer">
		<h1>get in touch</h1>
			<h3 class="center follow">
				We're social and we'd love to hear from you! Feel free to send us an email, find us on Google Plus, follow us on Twitter and join us on Facebook.
			</h3>
	<div class="follow_us">
		<a href="#" class="fa fa-twitter follow_us"></a>
		<a href="#" class="fa fa-facebook follow_us"></a>
		<a href="#" class="fa fa-linkedin follow_us"></a>
		<a href="#" class="fa fa-google-plus follow_us"></a>
		<a href="#" class="fa fa-vimeo-square follow_us"></a>
		</div>
	</div>
	
	<!-- footer 2 -->
	<div id="footer2">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
				<div class="copyright">
							FLATI
							&copy;
							<script type="text/javascript">
							//<![CDATA[
								var d = new Date()
								document.write(d.getFullYear())
								//]]>
								</script>
							 - All Rights Reserved :
							Template by <a href="http://spiralpixel.com">Spiral Pixel</a>
						</div>
						</div>
					</div>
				</div>
					</div>
				<!-- up to top -->
				<a href="#"><i class="go-top fa fa-angle-double-up"></i></a>
				<!--//end-->
				
<!-- SCRIPTS -->
<script src="<?=$theme_root?>js/jquery.js"></script>			
<script src="<?=$theme_root?>js/bootstrap.min.js"></script>
<script src="/js/less.min.js"></script>

<!-- SLIDER REVOLUTION 4.x SCRIPTS  -->
<script type="text/javascript" src="<?=$theme_root?>rs-plugin/js/jquery.themepunch.tools.min.js"></script>   
<script type="text/javascript" src="<?=$theme_root?>rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
<!-- slider settings -->
<script type="text/javascript">
	//<![CDATA[
			jQuery(document).ready(function() {
					jQuery('.tp-banner').show().revolution(
				{
					delay:9000,
					startwidth:1170,
					startheight:600,
					navigationType:"bullet",
					navigationStyle:"square",
					hideBulletsOnMobile:"on",
					hideArrowsOnMobile: "on",
					shadow:0,
					fullWidth:"on",
				});
			});	
		//]]>
	</script>

<script src="<?=$theme_root?>js/jquery.touchSwipe.min.js"></script>
<script src="<?=$theme_root?>js/jquery.mousewheel.min.js"></script>				
<script type="text/javascript" src="<?=$theme_root?>js/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="<?=$theme_root?>js/scripts.js"></script>
<script src="<?=$theme_root?>js/retina.js"></script>
<!-- carousel -->
<script type="text/javascript" src="<?=$theme_root?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($) {
	$("#slider_home").carouFredSel({ 
		width : "100%", 
		height : "auto",
		responsive : true,
		auto : false,
		items : { width : 280, visible: { min: 1, max: 3 }
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
?>
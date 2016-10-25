<?php

// Get Controller
$controller = get_controller( $this );

// Get State
$state = get_state( $controller );

// Theme root
$theme_root = "/themes/flati/";

// Web root
$webroot = x_objects::instance()->webroot();

$components_directory = $webroot.'/app/views/components/flati/';

$data = $controller->data();

$events = $data['events'];

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
				<h1>Calendar</h1>
					<h1 class="title">
						Upcoming Events
					</h1>
				<h1 class="intro">
					Take a gander at our upcoming <span class="hue">school events</span> &nbsp;and see if there's one you'd like to join;
					<span>register </span> or share with friends who may be interested.
				</h1>
			</div>
		</div>
		</div>
		
		<div class="container wrapper">
		<div class="inner_content">
		<div class="row pad30">
		
		<!--col 1-->
		<div class="col-md-9 pad25">
		<div class="row">

            <?php foreach ( $events as $event ){?>

			    <div class="col-md-1 hidden-xs hidden-sm">
			        <div class="btn btn-medium btn-rounded btn-blog1"><?=$event['event_date_day']?><br><?=strtoupper($event['event_date_month'])?><br>
				        <i class="fa fa-calendar fa-2x"></i><br>
				        <a class="com_no" href="<?=$event['event_url']?>"><?=$event['attendees']?></a>
			        </div>
		        </div>

		        <div class="col-md-1 hidden-lg hidden-md">
				    <div class="btn btn-medium btn-rounded btn-blog2">
                        <?=$event['event_date_day']?> <?=$event['event_date_month']?> <i class="fa fa-calendar fa-2x"></i>
                        <a class="com_no" href="<?=$event['event_url']?>"><?=$event['attendees']?></a>
				    </div>
			    </div>

		        <div class="col-md-11">
		            <div class="hover_img">
			            <a href="<?=$event['event_image_filename_url']?>" data-rel="prettyPhoto">
			                <img src="<?=$event['event_image_filename_url']?>" alt="">
                        </a>
		            </div>

		            <h1 class="post_link">
			            <a href="<?=$event['event_url']?>"><?=$event['name']?></a>
		            </h1>

		        <p>
                    <?=$event['description']?>
		        </p>
		        <div class="read_more"><a href="<?=$event['event_url']?>">Attend Event &rarr;</a></div>
		            <div class="pad30"></div>
		        </div>

            <?php } ?>

	<!--post 2-->

			<!--end post-->

		<!--post3-->


		<!--end post-->


			<div class="pad45"></div>
			
			<ul class="pager">
				<li class="previous"><a href="#">&larr; Older</a></li>
				<li class="next"><a href="#">Newer &rarr;</a></li>
			</ul>
			</div>
				</div>
		<!--end post-->
			
		<!--end col1-->
		<!--sidebar-->
			<div class="sidebar col-md-2">
		
			<h3>About Us</h3>
			<h5>Morbi blandit ultricies ultrices ivam us nec lectus sed orci voluptua oportere molestie.
			Enim ultrices, elementum phasellus. Mauris sed nulla sed, egestas feugiat a dictum libero, nunc sapien tri tique facilisis venenatis risus, 
			suspendisse ac nec et.</h5>
			
			<h3 class="pad15">Categories</h3>
			<ul class="fa-ul">
				<li><i class="fa-li fa fa-caret-right grey2"></i><a href="#">Design News</a></li>
				<li><i class="fa-li fa fa-caret-right grey2"></i><a  href="#">Typography</a></li>
				<li><i class="fa-li fa fa-caret-right grey2"></i><a  href="#">Premium Themes</a></li>
				<li><i class="fa-li fa fa-caret-right grey2"></i><a  href="#">Photography</a></li>
				<li><i class="fa-li fa fa-caret-right grey2"></i><a  href="#">Graphics</a></li>
			</ul>
			<div class="pad25"></div>
			
			<!--search-->
			<input type="text" placeholder="search">
		
			<h4 class="pad25">Popular Posts</h4>
				<ul class="media-list">
					<li class="media">
						<img class="pull-left img-rounded" src="img/small/pop_post1.jpg" alt="" />
						<div class="media-body">
						<small class="muted">07/05/13</small><br>
						<a href="#">Aliquam convallis erat a enim dictum gravida nulla</a></div>
					</li>
							
					<li class="media">
						<img class="pull-left img-rounded" src="img/small/pop_post2.jpg" alt="" />
						<div class="media-body">
						<small class="muted">29/04/13</small><br>
						<a href="#">Enim ultrices, elementum phasellus. Mauris sed nulla sed, egestas feugia</a></div>
					</li>
							
					<li class="media">
						<img class="pull-left img-rounded" src="img/small/pop_post3.jpg" alt="" />
						<div class="media-body">
						<small class="muted">23/04/15</small><br>
						<a href="#">Mauris sed nulla sed, egestas feugiat a dictum libero</a></div>
					</li>
				</ul>
					
				<h4 class="pad25">Flickr Feed</h4>
					<!--
                    <div class="flickrs2">
						<div class="FlickrImagesBlog">
							<ul></ul>
						</div>
					</div>
					-->
				</div>
			<div class="pad45"></div>
		</div>
			</div>
				</div>
				<div class="pad45 hidden-md hidden-lg"></div>
				<!--end-->

    <?php require_once $components_directory.'footer-two.php';?>

					<!-- up to top -->
				<a href="#"><i class="go-top fa fa-angle-double-up"></i></a>
				<!--//end--> 
				
<!-- scripts -->
<script src="<?=$theme_root?>js/jquery.js"></script>			
<script src="<?=$theme_root?>js/bootstrap.min.js"></script>
<script src="<?=$theme_root?>js/retina.js"></script>
<script type="text/javascript" src="<?=$theme_root?>js/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="<?=$theme_root?>js/scripts.js"></script>


</body>
</html>
<?php

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
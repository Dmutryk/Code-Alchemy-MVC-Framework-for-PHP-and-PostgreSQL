<?php

// Get Controller
$controller = get_controller( $this );

// Get State
$state = get_state( $controller );

// Theme root
$theme_root = "/themes/flati/";

// Web root
$webroot = x_objects::instance()->webroot();

$components_directory = $webroot.'/app/views/components/';

$data = $controller->data();

$entries = $data['excursions'];

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
				<h1>Online Journal</h1>
					<h1 class="title">
						Creative Bits And Bobs
					</h1>
				<h1 class="intro">
					Web design is the creation of <span class="hue">digital environments</span>, that <span>facilitate</span> and encourage human activity; 
					<span>reflect </span> or adapt to individual voices and content.
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

            <?php foreach( $controller->data_as_object()->excursions as $excursion){?>
	<!--post 1-->
		<!--date-->
			<div class="col-md-1 hidden-xs hidden-sm">	
			<div class="btn btn-medium btn-rounded btn-blog1">
				29<br>MAY<br>
				<i class="fa fa-comments fa-2x"></i><br>
				<a class="com_no" href="#">23</a>
			</div>
		</div>
		<div class="col-md-1 hidden-lg hidden-md">	
				<div class="btn btn-medium btn-rounded btn-blog2">
					29 MAY <i class="fa fa-comments fa-2x"></i> <a class="com_no" href="#">23</a>
				</div>
			</div>
		<!--post entry-->
		<div class="col-md-11">
		<div class="hover_img">
			<a title="<?=$excursion['title']?>" href="<?=$excursion['image_filename_url']?>" data-rel="prettyPhoto">
			<img src="<?=$excursion['image_filename_url']?>" alt="<?=$excursion['caption']?>"></a>
		</div>
		<h1 class="post_link">
			<a href="<?=$excursion['tour_url']?>"><?=$excursion['title']?></a>
		</h1>
		<div class="post-meta muted">
			<ul>
				<li>Posted by <a href="#">admin</a> </li>
				<li>in <a href="#">web</a>, <a href="#">graphics</a> </li>
				<li>tags <a href="#">prints</a>, <a href="#">design</a> </li>
			</ul>
		</div>
                    <?=$excursion['description']?>
                    <div class="read_more"><a href="<?=$excursion['tour_url']?>">READ MORE &rarr;</a></div>
		<div class="pad30"></div>
		</div>
		<!--end post-->
            <?php } ?>

            </div>
			</div>
		<!--end col1-->
		
		<!--sidebar-->
			<div class="sidebar col-md-3">
		
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
					<div class="flickrs2">
						<div class="FlickrImagesBlog">
							<ul></ul>
						</div>
					</div>
				</div>
			<div class="pad45"></div>
		</div>
			</div>
				</div>
				<div class="pad45 hidden-md hidden-lg"></div>
				<!--end-->

    <?php require_once $components_directory.'footer2.php';?>

					<!-- up to top -->
				<a href="#"><i class="go-top fa fa-angle-double-up"></i></a>
				<!--//end--> 
				
<!-- scripts -->
<script src="<?=$theme_root?>js/jquery.js"></script>			
<script src="<?=$theme_root?>js/bootstrap.min.js"></script>
<script src="<?=$theme_root?>js/retina.js"></script>
<script type="text/javascript" src="<?=$theme_root?>js/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="<?=$theme_root?>js/scripts.js"></script>

<!-- FLICKR  - add your id - find it here - http://idgettr.com/-->
<script type="text/javascript">
//<![CDATA[
   $.getJSON("http://api.flickr.com/services/feeds/photos_public.gne?id=60241562@N08&lang=en-us&format=json&jsoncallback=?", function(data){
		$.each(data.items, function(i,item){
			if(i<=8){ // <— change this number to display more or less images
				$("<img/>").attr("src", item.media.m.replace('_m', '_s')).appendTo(".FlickrImagesBlog ul")
				.wrap("<li><a href='" + item.link + "' target='_blank' title='Flickr'></a></li>");
			}
		});			
    });	
//]]>
</script>

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
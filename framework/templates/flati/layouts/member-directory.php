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

$members  = $data['members'];

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <?php require_once $components_directory.'head.php';?>
    </head>

    <body>
    <?php require_once $components_directory.'header.php';?>
    <!--page-->
	
	<!--page-->
	<div id="banner">
		<div class="container intro_wrapper">
			<div class="inner_content">
				<h1>Directory</h1>
					<h1 class="title">School Members</h1>
						<h1 class="intro">
    Showing <?=$data['search_by']?><?=$data['search_by']!= 'all members'?'&nbsp;(<a href="/members">Show me all members instead</a>)':''?>

<?php if ( ! count($members )){ ?>

    <strong>No members match this search</strong>
    <?php } ?>
						</h1>
					</div>
				</div>
			</div>
			
			<div class="container wrapper">
			<div class="inner_content">

                <!--
			<div id="options">                                           
                    <ul id="filters" class="option-set" data-option-key="filter">
                        <li><a href="#filter" data-option-value="*" class=" selected">All</a></li>
                        <li><a href="#filter" data-option-value=".category01">Category 01</a></li>                                            
                        <li><a href="#filter" data-option-value=".category02">Category 02</a></li>
                        <li><a href="#filter" data-option-value=".category03">Category 03</a></li> 
                    </ul>                                           
                    <div class="clear"></div>
                </div>
                -->
                    <!-- portfolio_block -->
					<div class="row">      
                    <div class="projects">
                        <?php foreach ( $members as $member ){?>
                        <div class="col-xs-12 col-sm-6 col-md-3 element " data-category="">
                            <div class="hover_img">
								<a title="<?=$member['full_name']?>" href="<?=$member['profile_image_url']?>" data-rel="prettyPhoto[portfolio1]">
                                <img src="<?=$member['profile_image_url']?>" alt="<?=$member['full_name']?>" /></a>
                            </div>  
                            <div class="item_description">
                               <a href="/members/<?=$member['seo_name']?>"><span><?=$member['full_name']?></span></a><br/>
                                <?=$member['about']?>
                            </div>                                    
                        </div>
                        <?php } ?>
                        <div class="clear"></div>
                    </div>   
                    <!-- //portfolio_block -->   
                </div>
            </div>
        </div>
	<div class="pad25 hidden-md hidden-lg"></div>
	<!--//page-->
    <?php require_once $components_directory.'footer-two.php';?>

    <!-- up to top -->
				<a href="#"><i class="go-top fa fa-angle-double-up"></i></a>
				<!--//end-->
				
<script src="<?=$theme_root?>js/jquery.js"></script>			
<script src="<?=$theme_root?>js/bootstrap.min.js"></script>	
<script src="<?=$theme_root?>js/retina.js"></script>
<script type="text/javascript" src="<?=$theme_root?>js/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="<?=$theme_root?>js/scripts.js"></script>
<script src="<?=$theme_root?>js/jquery.isotope.min.js" type="text/javascript"></script>
<script src="<?=$theme_root?>js/imagesloaded.pkgd.min.js"></script>
	<script type="text/javascript">
//<![CDATA[
	$(document).ready(function(){
	var $container = $('.projects');
	$container.imagesLoaded(function() {
	$('.projects').fadeIn(1000).isotope({
	layoutMode : 'fitRows',
    itemSelector : '.element' });
	});
	$('.element').css('opacity',0);
	$('.element').each(function(i){
	$(this).delay(i*150).animate({'opacity':1},350);
	});
});
//]]>
</script>
</body>
</html>	

<?php

function get_controller( \udssphalumni\controllers\app_controller $controller ){

    return $controller;
}

/**
 * @param \udssphalumni\controllers\app_controller $controller
 * @return \udssphalumni\components\state
 */
function get_state( \udssphalumni\controllers\app_controller $controller ){

    return $controller->state();
}
?>
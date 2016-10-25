<?php

// Get Controller
$controller = get_controller( $this );

// Get State
$state = get_state( $controller );

// Theme root
$theme_root = "/themes/flati/";

// Web root
$webroot = x_objects::instance()->webroot();

$content = $state->get_content();

$data = $controller->data();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once( $webroot."/app/views/components/head.php");?>
</head>

<body>
    <?php
        $navigation_root= '/';
        $active = 'signin';
    require_once( $webroot."/app/views/components/header.php"); ?>

			
	<div class="container wrapper">
	<div class="inner_content">
        <div class="container">
            <div id="loginbox" style="margin-top:50px;display:<?=$controller->uri()->part(1)=='inscribirse'?'none':''?>" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Acceder</div>
                        <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Forgot password?</a></div>
                    </div>

                    <div style="padding-top:30px" class="panel-body" >

                        <div class="row clearfix">
                            <?php if ( isset( $data['signin_error'])){ ?>
                                <div style="" id="login-alert" class="alert alert-danger col-sm-12"><?=$data['signin_error']?></div>
                            <?php } ?>

                        </div>
                        <form id="loginform" class="form-horizontal" role="form" method="POST" action="/acceder">

                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon">
                                    <i class="fa fa-user"></i>
</span>
                                <input id="login-username" type="text" class="form-control" name="email" value="" placeholder="correo electrónico">
                            </div>

                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="fa fa-key"></i>
</span>
                                <input id="login-password" type="password" class="form-control" name="password" placeholder="Contraseña">
                            </div>



                            <div class="input-group">
                                <div class="checkbox">
                                    <label>
                                        <input id="login-remember" type="checkbox" name="remember" value="1"> No cerrar sesión
                                    </label>
                                </div>
                            </div>


                            <div style="margin-top:10px" class="form-group">
                                <!-- Button -->

                                <div class="col-sm-12 controls">
                                    <button type="submit" id="btn-login" class="btn btn-success">Acceder  </button>
                                    <!--
                                    <a id="btn-fblogin" href="#" class="btn btn-primary">Login with Facebook</a>
                                    -->
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-md-12 control">
                                    <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                        ¿No tienes una cuenta?
                                        <a href="#" onClick="$('#loginbox').hide(); $('#signupbox').show()">
                                            Inscríbete aquí
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="signin_submitted" value="yes"/>
                        </form>



                    </div>
                </div>
            </div>
            <div id="signupbox" style="display:<?=$controller->uri()->part(1)=='inscribirse'?'':'none'?>; margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">Inscribirse</div>
                        <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('#signupbox').hide(); $('#loginbox').show()">Sign In</a></div>
                    </div>
                    <div class="panel-body" >
                        <form id="signupform" class="form-horizontal" role="form" method="POST" action="/inscribirse">

                            <?php if ( isset( $data['signup_result']) && $data['signup_result']=='error'){?>
                            <div id="signupalert" style="" class="alert alert-danger">
                                <p>Error:</p>
                                <span><?=$data['signup_error']?></span>
                            </div>
                            <?php } ?>



                            <div class="form-group">
                                <label for="firstname" class="col-md-4 control-label">Nombre(s)</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="first_name" placeholder="Nombre(s)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="lastname" class="col-md-4 control-label">Apellido(s)</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="last_name" placeholder="Apellido(s)">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">Correo Electrónico</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="email" placeholder="Correo Electrónico">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Contraseña</label>
                                <div class="col-md-8">
                                    <input type="password" class="form-control" name="password" placeholder="Contraseña">
                                </div>
                            </div>

                            <div class="form-group">
                                <!-- Button -->
                                <div class="col-md-offset-4 col-md-8">
                                    <button id="btn-signup" type="submit" class="btn btn-info"><i class="icon-hand-right"></i> &nbsp Inscribirse</button>
                                   <!-- <span style="margin-left:8px;">or</span>-->
                                </div>
                            </div>

                            <!--
                            <div style="border-top: 1px solid #999; padding-top:20px"  class="form-group">

                                <div class="col-md-offset-3 col-md-9">
                                    <button id="btn-fbsignup" type="button" class="btn btn-primary"><i class="icon-facebook"></i>   Sign Up with Facebook</button>
                                </div>

                            </div>
                            -->

                            <input type="hidden" name="signup_submitted" value="yes"/>

                        </form>
                    </div>
                </div>




            </div>
        </div>

    </div>
					</div>



    <?php require_once( $webroot."/app/views/components/footer1.php");?>

    <?php require_once( $webroot."/app/views/components/footer2.php");?>

    <!-- up to top -->
				<a href="#"><i class="go-top fa fa-angle-double-up"></i></a>
				<!--//end-->
				
<!-- SCRIPTS -->
<script src="<?=$theme_root?>js/jquery.js"></script>			
<script src="<?=$theme_root?>js/bootstrap.min.js"></script>
<script src="/js/less.min.js"></script>


    <!-- SLIDER REVOLUTION 4.x SCRIPTS  -->
<script type="text/javascript" src="<?=$theme_root?>rs-plugin/js/jquery.themepunch.tools.min.js"></script>
<script src="<?=$theme_root?>js/jquery.touchSwipe.min.js"></script>
<script src="<?=$theme_root?>js/jquery.mousewheel.min.js"></script>				
<script type="text/javascript" src="<?=$theme_root?>js/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="<?=$theme_root?>js/scripts.js"></script>
<script src="<?=$theme_root?>js/retina.js"></script>
</body>
</html>
<?php

function get_controller( \__namespace__\controllers\app_controller $controller ){

    return $controller;
}

/**
 * @param \__namespace__\controllers\app_controller $controller
 * @return \__namespace__\components\state
 */
function get_state( \__namespace__\controllers\app_controller $controller ){

    return $controller->state();
}
?>
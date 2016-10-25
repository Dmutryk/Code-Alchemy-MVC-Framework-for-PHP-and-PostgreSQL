<?php

// Get Controller
$controller = get_controller( $this );

// Get State
$state = get_state( $controller );

// Theme root
$theme_root = "/themes/angle/";

// Web root
$webroot = x_objects::instance()->webroot();

// Get data
$data = $controller->data();

// Get Content
$content = $state->get_content();

$modal = ($controller->uri()->part(1)=='login')?'signin':'signup';

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

        require_once( $webroot."/app/views/components/header.php");

    ?>
    <div id="content" role="main">
        <section style="padding-top: 10px;" class="section <?=$state->swatch(true)?>">
            <div class="container">
                <div class="row">
                    <!-- login box -->
                    <div id="loginbox" style="display:<?=$modal=='signin'?'':'none'?>;margin-top:50px;display:<?=$controller->uri()->part(1)=='inscribirse'?'none':''?>" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                        <div class="panel panel-info" >
                            <!-- header -->
                            <div class="panel-heading">
                                <div class="panel-title">Acceder</div>
                                <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#" onClick="$('#loginbox').hide(); $('#forgotbox').show()">¿Olvidaste tu contraseña?</a></div>
                            </div>

                            <div style="padding-top:30px" class="panel-body" >

                                <?php if ( isset( $_REQUEST['reset'])){ ?>

                                    <div id="resetokalert" style="" class="alert alert-info">
                                        <span>Ya puedes acceder con tu nueva contraseña</span>
                                    </div>
                                <?php } ?>
                                <?php if ( isset( $data['signin_error'])){ ?>
                                    <div style="" id="login-alert" class="alert alert-danger col-sm-12"><?=$data['signin_error']?></div>
                                <?php } ?>

                                <!-- Login form -->
                                <form id="loginform" class="form-horizontal pull-left" role="form" method="POST" action="/acceder">

                                    <!-- Email -->
                                    <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i>
                                        </span>
                                        <input id="login-email" type="email" class="form-control" name="email" value="" placeholder="correo electrónico">
                                    </div>

                                    <!-- password -->
                                    <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user-secret"></i></span>
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
                                            <button type="submit" id="btn-login" class="btn btn-success">Acceder</button>
                                                <!--
                                                <a id="btn-fblogin" href="#" class="btn btn-primary">Login with Facebook</a>
                                                -->
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <div class="col-md-12 control">
                                            <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                                    ¿No tienes ya una cuenta?
                                                <a href="#" onClick="$('#loginbox').hide(); $('#signupbox').show()">
                                                haz clic para inscribirte
                                             </a>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="signin_submitted" value="yes"/>

                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- signup box -->
                    <div id="signupbox" style="display:<?=$modal=='signin'?'none':''?>; margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <div class="panel-title">Inscribirse</div>
                                    <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('#signupbox').hide(); $('#loginbox').show()">Acceder</a></div>
                            </div>
                            <div class="panel-body" >
                                <form id="signupform" class="form-horizontal" role="form" method="POST" action="/inscribirse">
                                    <?php if ( isset( $data['signup_result']) && $data['signup_result']=='error'){?>
                                        <div id="signupalert" style="" class="alert alert-danger">
                                            <p>Error:</p>
                                                <span><?=$data['signup_error']?></span>
                                        </div>
                                    <?php } ?>
                                    <!-- first name -->
                                    <div class="form-group">
                                        <label for="signup-firstname" class="col-md-4 control-label">Nombres</label>
                                        <div class="col-md-8">
                                            <input id="signup-firstname" type="text" class="form-control" name="Nombres" placeholder="First name">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="signup-lastname" class="col-md-4 control-label">Apellidos</label>
                                            <div class="col-md-8">
                                                <input id="signup-lastname" type="text" class="form-control" name="last_name" placeholder="Last name(s)">
                                            </div>
                                    </div>

                                    <!-- email -->
                                    <div class="form-group">
                                        <label for="email" class="col-md-4 control-label">Correo Electrónico</label>
                                        <div class="col-md-8">
                                            <input id="signup-email" type="email" class="form-control" name="email" placeholder="Correo Electrónico">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                            <label for="signup-password" class="col-md-4 control-label">Contraseña</label>
                                            <div class="col-md-8">
                                                <input id="signup-password" type="password" class="form-control" name="password" placeholder="Contraseña">
                                            </div>
                                    </div>

                                    <div class="form-group">
                                        <!-- Button -->
                                        <div class="col-md-offset-4 col-md-8">
                                            <button id="btn-signup" type="submit" class="btn btn-info"><i class="icon-hand-right"></i> &nbsp Inscríbete </button>
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


                        <!-- forgot  box -->
                        <div id="forgotbox" style="display:none; margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <div class="panel-title">Recuperar Contraseña</div>
                                </div>
                                <div class="panel-body" >
                                    <form id="forgotform" class="form-horizontal" role="form" method="POST" action="">

                                        <div style="display:none;" id="resetsuccessalert" class="alert alert-success">
                                            <span>Revisa tu correo para finalizar reiniciar tu contraseña</span>
                                        </div>

                                        <div id="resetinfoalert" style="" class="alert alert-info">
                                            <span>Para recuperar tu contraseña ingresa el correo que usaste para crear la cuenta</span>
                                        </div>

                                        <?php if ( isset( $data['signup_result']) && $data['signup_result']=='error'){?>
                                            <div id="resetalert" style="" class="alert alert-danger">
                                                <p>Error:</p>
                                                <span><?=$data['signup_error']?></span>
                                            </div>
                                        <?php } ?>

                                        <div class="hideable">
                                            <div class="form-group">
                                                <label for="email" class="col-md-4 control-label">Correo Electrónico</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="email" placeholder="">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <!-- Button -->
                                                <div class="col-md-offset-4 col-md-8">
                                                    <button id="btn-signup" type="submit" class="btn btn-info"><i class="icon-hand-right"></i> &nbsp Reiniciar</button>
                                                    <!-- <span style="margin-left:8px;">or</span>-->
                                                </div>
                                            </div>

                                        </div>

                                        <input type="hidden" name="forgot_submitted" value="yes"/>

                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
            </div>
        </section>


        <footer id="footer" role="contentinfo">
                <section class="section <?=$state->theme_swatch()?> has-top">
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
            jQuery('#forgotform').on('submit',function(e){

                e=e?e:window.event;
                e.preventDefault();

                var form = $(this);

                var button = form.find('button[type="submit"]');

                button.attr('disabled','disabled');

                $.ajax({
                    type: 'POST',
                    url: '/parnassus/reset_password',
                    data: 'email='+form.find('input[name="email"]').val(),
                    success: function(json){

                        button.removeAttr('disabled');

                        if ( json.result == 'success'){
                            $('#resetsuccessalert').show();

                            form.find('.hideable').hide();

                        }

                    }
                });
                return false;
            });
        });
        </script>
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

/**
 * @param \__namespace__\components\state $state
 * @return \xobjects\components\page_content
 */
function get_content( \__namespace__\components\state $state ){

    return $state->get_content();

}

?>
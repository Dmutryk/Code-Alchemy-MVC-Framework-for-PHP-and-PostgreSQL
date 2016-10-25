
<!-- Login/Signup Container -->
<div class="container">
    <?php

        // View is used to calculate display settings
        $view = $controller->uri()->part(1);

    ?>

    <!-- Login/Signup Box -->
    <div id="loginbox" style="display:<?=$view=='acceder'?'block':'none'?>;margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">

        <div class="panel panel-info" >
            <div class="panel-heading">
                <div class="panel-title">Acceder</div>
                <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">¿Olvidaste tu contraseña?</a></div>
            </div>

            <form method="POST" action="/<?=$controller->uri()->part(1)?>" id="loginform" class="form-horizontal" role="form">

                <div style="padding-top:30px" class="panel-body" >

                    <?php if ( $controller->data_as_object()->login_error){ ?>
                        <div style="" id="login-alert" class="alert alert-danger col-sm-12"><?=$controller->data_as_object()->login_error?></div>
                    <?php } ?>

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input name="email" id="login-username" type="text" class="form-control" name="username" value="" placeholder="correo electrónico">
                    </div>

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input name="password" id="login-password" type="password" class="form-control" name="password" placeholder="Contraseña">
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
                            <input type="hidden" name="login_submitted" value="1"/>
                            <button type="submit" id="btn-login" href="#" class="btn btn-success">Acceder</button>
                            <!--                                        <a id="btn-fblogin" href="#" class="btn btn-primary">Login with Facebook</a>-->

                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-12 control">
                            <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                ¿No tienes una cuenta?
                                <a href="#" onClick="$('#loginbox').hide(); $('#signupbox').show()">
                                    Inscríbete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- sign up box -->
    <div id="signupbox" style="display:<?=$view=='acceder'?'none':'block'?>; margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">Sign Up</div>
                <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('#signupbox').hide(); $('#loginbox').show()">Sign In</a></div>
            </div>
            <div class="panel-body" >
                <form method="post" action="/inscribirse" id="signupform" class="form-horizontal" role="form">

                    <?php if ( $controller->data_as_object()->signup_error){?>
                        <div id="signupalert" style="" class="alert alert-danger">
                            <p>Error:</p>
                            <span><?=$controller->data_as_object()->signup_error?></span>
                        </div>
                    <?php } ?>


                    <div class="form-group">
                        <label for="firstname" class="col-md-3 control-label">Nombre</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="first_name" placeholder="Nombre">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lastname" class="col-md-3 control-label">Apellido</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="last_name" placeholder="Apellido">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-md-3 control-label">Correo Electrónico</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="email" placeholder="Correo Electrónico">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="password" class="col-md-3 control-label">Contraseña</label>
                        <div class="col-md-9">
                            <input type="password" class="form-control" name="password" placeholder="Contraseña">
                        </div>
                    </div>

                    <!--
                    <div class="form-group">
                        <label for="icode" class="col-md-3 control-label">Invitation Code</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="icode" placeholder="">
                        </div>
                    </div>
-->
                    <div class="form-group">
                        <!-- Button -->
                        <div class="col-md-offset-3 col-md-9">
                            <input type="hidden" name="signup_submitted" value="1"/>
                            <button id="btn-signup" type="submit" class="btn btn-info"><i class="icon-hand-right"></i> &nbsp Inscribirse</button>

                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
    <!--/Page main wrapper -->
</div>
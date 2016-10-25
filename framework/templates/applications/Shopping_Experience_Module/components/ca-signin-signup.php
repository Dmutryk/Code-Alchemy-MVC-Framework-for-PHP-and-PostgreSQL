<!-- Signin Signup Modal-->
<div id="signin-signup" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">
                    <?php if ( $scope->modal_logo ){?>
                        <img class="modal-logo" src="<?=$scope->modal_logo['image_filename_url']?>">
                    <?php } ?>
                    Acceder | Inscribirse</h4>
            </div>
            <div class="modal-body">
                <p>Acceder a tu cuenta para poder realizar el pedido.  ¿No tienes una cuenta?
                    <a onclick="return caShopping.open_signup();" href="#">Haz clic para crearla</a></p>
                <div id="div-forms">



                    <!-- Begin # Login Form -->
                    <form onsubmit="return caShopping.do_login( $(this) );" id="login-form">
                        <div class="modal-body">
                            <div id="div-login-msg">
                                <div id="icon-login-msg" class="fa fa-chevron-right"></div>
                                <span id="text-login-msg">Introduce tu correo electrónico y contraseña</span>
                            </div>
                            <input name="email" id="login_username" class="form-control" type="text" placeholder="Correo Electrónico" required>
                            <input name="password" id="login_password" class="form-control" type="password" placeholder="Contraseña" required>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox">No cerrar sesión
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Acceder</button>
                            </div>
                            <div>

                                <button id="login_lost_btn" type="button" class="btn btn-link">¿Olvidaste tu contraseña?</button>

                                <button onclick="caShopping.open_signup();" id="login_register_btn" type="button" class="btn btn-link">¿No tienes una cuenta?</button>

                            </div>
                        </div>
                    </form>
                    <!-- End # Login Form -->

                    <!-- Begin | Lost Password Form -->
                    <form id="lost-form" style="display:none;">
                        <div class="modal-body">
                            <div id="div-lost-msg">
                                <div id="icon-lost-msg" class="glyphicon glyphicon-chevron-right"></div>
                                <span id="text-lost-msg">Type your e-mail.</span>
                            </div>
                            <input id="lost_email" class="form-control" type="text" placeholder="E-Mail (type ERROR for error effect)" required>
                        </div>
                        <div class="modal-footer">
                            <div>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Send</button>
                            </div>
                            <div>
                                <button id="lost_login_btn" type="button" class="btn btn-link">Log In</button>
                                <button id="lost_register_btn" type="button" class="btn btn-link">Register</button>
                            </div>
                        </div>
                    </form>
                    <!-- End | Lost Password Form -->

                    <!-- Begin | Register Form -->
                    <form onsubmit="return caShopping.register( $(this) );" id="register-form" style="display:none;">
                        <div class="modal-body">
                            <div id="div-register-msg">
                                <div id="icon-register-msg" class="fa fa-chevron-right"></div>
                                <span id="text-register-msg">Crear una Cuenta</span>
                            </div>

                            <input name="first_name" class="form-control" type="text" placeholder="Nombre">
                            <input name="last_name" class="form-control" type="text" placeholder="Apellido" >
                            <input name="email" class="form-control" type="email" placeholder="Correo Electrónico" >
                            <input name="password" class="form-control" type="password" placeholder="Contraseña">
                            <input name="type" class="form-control" type="hidden" value="customer">
                        </div>
                        <div class="modal-footer">
                            <div>
                                <button type="submit" class="btn btn-primary btn-lg btn-block">Crear Cuenta</button>
                            </div>
                            <div>
                                <button id="register_login_btn" type="button" class="btn btn-link">Acceder</button>
                                <button id="register_lost_btn" type="button" class="btn btn-link">¿Olvidaste tu contraseña?</button>
                            </div>
                        </div>
                    </form>
                    <!-- End | Register Form -->

                </div>

            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Cerrar</button>
            </div>
        </div>
    </div>
</div>


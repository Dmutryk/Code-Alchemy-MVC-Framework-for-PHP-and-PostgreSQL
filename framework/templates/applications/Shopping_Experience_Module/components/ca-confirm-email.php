<div id="confirm-email" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header clearfix">
                <?php if ( $scope->modal_logo ){?>
                    <img class="modal-logo" src="<?=$scope->modal_logo['image_filename_url']?>">
                <?php } ?>
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">Confirmar Correo Electrónico</h4>
            </div>
            <div class="modal-body">
                <p>Para mejorar la seguridad y la privacidad de tus datos y nuestros servicios, debes
                confirmar tu correo electrónico antes de realizar pedidos en nuestra tienda.</p>

                <p>Cuando te escribiste al sitio, te enviamos automaticamente un correo electrónico
                con instrucciones cómo confirmar tu correo.</p>

                <p>¿No recibiste este correo?
                <a onclick="return caShopping.resend_email_confirmation();" href="#">Haz Clic Aquí para reenviar el correo</a>
                </p>
                <p>Si ya recibiste el correo, puedes confirmar tu correo de una vez, ingresando
                el código único aquí:</p>

                <form onsubmit="return caShopping.confirm_email( $(this) );">

                    <input name="verification_code" class="form-control">

                    <button type="submit" class="btn btn-primary">¡Confirmar mi correo ya!</button>

                </form>
            </div>
        </div>
    </div>
</div>
<?php

    $data = $controller->data();

if ( isset( $data['login_error'])){ ?>
    <div style="" id="login-alert" class="alert alert-danger col-sm-12"><?=$data['login_error']?></div>
<?php } ?>

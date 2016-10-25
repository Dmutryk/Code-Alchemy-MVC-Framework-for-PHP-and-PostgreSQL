<?php if ( ! $controller->is_logged_in() ){?>
    <li class="smallish">
        <a href="/login">Sign in</a>
    </li>
<?php } else {
    if ( $controller->is_admin() ){ ?>
        <li class="smallish">
            <a target="_code_alchemy_admin" href="/code-alchemy">Manage Site</a>
        </li>
    <?php }
    ?>
    <li class="smallish">
        <a href="/logout">Sign out</a>
    </li>
<?php  }?>

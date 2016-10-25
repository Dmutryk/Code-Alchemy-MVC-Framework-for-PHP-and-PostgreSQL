<?php

$controller = get_controller( $this );

$data = $controller->data();

$services = $data['services'];

$lang = (new \Code_Alchemy\Core\Configuration_File())->find('language');


?>
<div id="preloader">
    <div class="curtain"></div>
    <span class="centerer"></span>
    <img src="/img/preloader.gif">
</div>
<div style="display: none;" class="navbar navbar-default navbar-fixed-top clearfix">
    <div class="container clearfix">
        <div class="navbar-header clearfix">
            <a class="navbar-brand" href="/parnassus">
                <img class="main-logo img-responsive pull-left" src="/img/parnassus_logo.png"/>
                &nbsp;
                <span class="pull-left"><?=$lang=='es'? 'Director Web':'Web Director'?></span>
            </a>
            <button data-target="#navbar-main" data-toggle="collapse" type="button" class="navbar-toggle">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar-main" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li>        <a href="#menu-toggle" class="btn" id="menu-toggle">Toggle Menu</a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a  href="/"><i class="fa fa-reply"></i>
&nbsp;                        <?=$lang=='es'? 'Volver a sitio web':'Back to Website...'?></a></li>
                <li><a  href="/parnassus/logout"><?=$lang=='es'?'Salir':'Logout'?></a></li>
            </ul>
        </div>
    </div>
</div>

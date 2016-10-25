<?php

$controller = get_controller( $this );

$data = $controller->data();

$services = $data['services'];

?>
<div class="navbar navbar-default navbar-fixed-top clearfix">
    <div class="container clearfix">
        <div class="navbar-header clearfix">
            <a class="navbar-brand" href="/parnassus">
                <img class="main-logo img-responsive pull-left" src="/img/parnassus_logo.jpg"/>
            </a>
            <button data-target="#navbar-main" data-toggle="collapse" type="button" class="navbar-toggle">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div id="navbar-main" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a id="themes" href="#" data-toggle="dropdown" class="dropdown-toggle">Manage... <span class="caret"></span></a>
                    <ul aria-labelledby="themes" class="dropdown-menu models-menu">
                        <?php foreach ( $services as $service ){
                            ?>
                            <li><a href="/parnassus/list_of/<?=$service['table_name']?>"><?=$service['service_label']?></a></li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a target="_blank" href="https://github.com/alquemedia/Code_Alchemy-Framework">Built With Code_Alchemy</a></li>
                <li><a target="_blank" href="http://www.alquemedia.com/">An Alquemedia Product</a></li>
                <li><a  href="/"><i class="fa fa-reply"></i>
&nbsp;                        Back to Website...</a></li>
                <li><a  href="/parnassus/logout">Logout</a></li>
            </ul>
        </div>
    </div>
</div>

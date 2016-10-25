<?php

// Get Controller
$controller = get_controller( $this );

$webroot = \Code_Alchemy\Core\Code_Alchemy_Framework::instance()->webroot();

// Get State
$state = $controller->state();

$oData = $controller->data_as_object();

$placeholder = $oData->lang =='es'?'Teclar lo que quieres administrar':'Type what you want to manage';

$classic_label = $oData->lang =='es'?'Vista Clásica':'Classic View';

?>
<!DOCTYPE html>
<html>
<head>
    <?php
        require_once( $webroot."/app/views/components/parnassus-head.php");
    ?>
</head>
<body class="search-home" onload="webDirector.disable_preloader()">

<?php
require_once( $webroot."/app/views/components/parnassus-nav.php");
?>

<div id="wrapper">

    <?php require_once $webroot."/app/views/components/ca-sidebar-wrapper.php";?>

    <div id="page-content-wrapper">

        <div style="margin-top: 50px;" class="container">
            <div class="bs-docs-section clearfix" style="margin-top: 50px;margin-bottom: 100px;">
                <div class="row managed-services">
                    <div class="col-lg-12 ">

                        <div data-toggle="tooltip" title="<?=$placeholder?>" style="width: 396px;" class="center-block clearfix">
                            <input class="typeahead form-control" type="text" placeholder="<?=$placeholder?>">
                        </div>
                        <a href="/parnassus/classic"><?=$classic_label?></a>
                    </div>
                </div>

                <a name="business-services"></a>
            </div>
            <?php

            require_once $webroot."/app/views/components/parnassus-footer.php";
            ?>
        </div>
    </div>

</div>

<script id="business-processes" type="text/x-handlebars-template">
    <?php require_once $webroot."/templates/ca-business-processes.handlebars"; ?>
</script>
</body>
</html>

<?php

function get_controller( $controller ){

    return $controller;
}
?>
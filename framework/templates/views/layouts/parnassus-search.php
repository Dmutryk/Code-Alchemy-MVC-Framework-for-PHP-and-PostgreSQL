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
<body>

<?php
require_once( $webroot."/app/views/components/parnassus-nav.php");
?>

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
    </div>
    <?php

    require_once $webroot."/app/views/components/parnassus-footer.php";
    ?>
</div>

</body>
</html>

<?php

/**
 * Local function to normalize Controller, for code completion
 * @param \_mynamespace_\controllers\app_controller $controller
 * @return \_mynamespace_\controllers\app_controller controller
 */
function get_controller( \_mynamespace_\controllers\app_controller $controller ){

    return $controller;
}
?>
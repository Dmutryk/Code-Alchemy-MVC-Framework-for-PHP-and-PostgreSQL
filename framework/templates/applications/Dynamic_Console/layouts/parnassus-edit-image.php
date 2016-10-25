<?php

$webroot = \Code_Alchemy\Core\Code_Alchemy_Framework::instance()->webroot();

// Get Controller
$controller = get_controller( $this );

// Get State
$state = $controller->state();

$data = $controller->data();

?>
<!DOCTYPE html>
<html>
<head>

    <?php

    require_once( $webroot. "/app/views/components/parnassus-head.php");

    ?>

</head>
<body  onload="webDirector.disable_preloader()">

<?php

require_once( $webroot. "/app/views/components/parnassus-nav.php");

?>

<div style="margin-top: 50px;" class="container">
    <div class="bs-docs-section clearfix" style="margin-top: 50px">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="admin-title"></h2>
            </div>
        </div>
                <div class="row">
                    <div class="col-lg-10">

                        <?php if ( isset($_REQUEST['update_result']) && $_REQUEST['update_result']=='success'){?>
                        <div class="alert alert-dismissable alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>Well done!</strong> Your changes have been saved
                        </div>
                        <?php } ?>
                        <div class="well bs-component">
                        </div>
                    </div>
                </div>
    </div>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script src="/js/bootstrap-clockpicker.js"></script>
    <script src="/js/bootstrap-switch.js"></script>
    <!-- Load widget code -->
    <script type="text/javascript" src="http://feather.aviary.com/imaging/v1/editor.js"></script>
    <!-- Instantiate the widget -->
    <script type="text/javascript">

        var featherEditor = new Aviary.Feather({
            apiKey: '1234567'
            tools: ['draw', 'stickers'],
            onSave: function(imageID, newURL) {
                var img = document.getElementById(imageID);
                img.src = newURL;
            }
        });

        function launchEditor(id, src) {
            featherEditor.launch({
                image: id,
                url: src
            });
            return false;
        }

    </script>
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
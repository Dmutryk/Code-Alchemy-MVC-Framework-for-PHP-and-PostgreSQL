<?php

$webroot = \Code_Alchemy\Core\Code_Alchemy_Framework::instance()->webroot();

// Get Controller
$controller = get_controller( $this );

// Get State
$state = $controller->state();

$data = $controller->data();

$action = $controller->uri()->part(2);

$model = $controller->uri()->part(3);

$model_id = $controller->uri()->part(4);

$commmand = $controller->uri()->part(5);

$services = $data['services'];

$intersections = $data['intersections'];

$language = (string) \Code_Alchemy\Core\Code_Alchemy_Framework::instance()->configuration()->language;

$required_label = $language=='es'?'Rápido':'Faster';

?>
<!DOCTYPE html>
<html>
<head>
    <?php
    // Load head
    require_once( $webroot. "/app/views/components/parnassus-head.php");
    ?>
<!--    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">-->
    <link rel="stylesheet" href="/jquery-ui/jquery-ui.min.css">

    <link rel="stylesheet" href="/css/bootstrap-clockpicker.css">
    <link rel="stylesheet" href="/css/bootstrap-switch.css">
    <script src="//cdn.ckeditor.com/4.4.6/full/ckeditor.js"></script>
</head>
<body>

<?php

// Load head
require_once( $webroot. "/app/views/components/parnassus-nav.php");

?>


<div style="margin-top: 50px;" class="container">
    <div class="bs-docs-section clearfix" style="margin-top: 50px">
                <div class="row">
                    <div class="col-lg-10">

                        <?php if ( isset($_REQUEST['update_result']) && $_REQUEST['update_result']=='success'){?>
                        <div class="alert alert-dismissable alert-success">
                            <button data-dismiss="alert" class="close" type="button">×</button>
                            <strong>Well done!</strong> Your changes have been saved
                        </div>
                        <?php } ?>
                        <div class="well bs-component">
                            <form enctype="multipart/form-data" class="form-horizontal" method="POST">
                                <fieldset>
                                    <div class="form-group">
                                        <label>The following values will be common for all added Models</label>
                                    </div>
                                    <?php if ( isset( $data['common_fields'])) {

                                        // Set fields
                                        $modelFields = $data['common_fields'];

                                        ?>
                                        <input type="hidden" name="new_model_submitted" value="yes"/>

                                <?php require $webroot."/app/views/components/ca-add-model-fields.php";?>

                                    <?php } ?>
                                    <div class="form-group">
                                        <label>The following values will be specific for each added Models</label>
                                    </div>
                                    <?php if ( isset( $data['unique_fields'])) {

                                        // Set fields
                                        $modelFields = $data['unique_fields'];

                                        $is_multi = true;

                                        ?>

                                        <?php

                                            for ( $i = 0; $i< $data['add-qty']; $i++)

                                                require $webroot."/app/views/components/ca-add-model-fields.php";?>

                                    <?php } ?>

                                    <?php if ( isset( $data['error'])){?>
                                    <div class="form-group alerts-here">
                                        <div class="alert alert-dismissable alert-danger">
                                            <button data-dismiss="alert" class="close" type="button">×</button>
                                            <strong>Error!</strong> <?=$data['error']?>.
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if ( isset( $data['new_model_id'])){?>
                                        <div class="form-group alerts-here">
                                            <div class="alert alert-dismissable alert-success">
                                                <button data-dismiss="alert" class="close" type="button">×</button>
                                                <strong>Success!</strong> Your new model was created
                                            </div>
                                        </div>
                                    <?php } ?>

                                    <div class="form-group button-group">
                                        <div class="col-lg-10 col-lg-offset-2">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                            <a href="/parnassus/list_of/<?=$model?>"><button type="button" class="btn btn-default">Cancel</button></a>
                                        </div>
                                    </div>
                                    <input type="hidden" name="update_from_associative" id="update_from_associative" value="yes"/>
                                    <input type="hidden" name="model_id" id="model_id" value="<?=$model_id?>"/>
                                    <input type="hidden" name="model_name" id="model_name" value="<?=$model?>"/>
                                </fieldset>
                            </form>
                            <div class="btn btn-primary btn-xs" id="source-button" style="display: none;">&lt; &gt;</div></div>
                    </div>
                </div>
    </div>
</div>
    <script src="/jquery-ui/jquery-ui.js"></script>
    <script src="/js/bootstrap-clockpicker.js"></script>
    <script src="/js/bootstrap-switch.js"></script>

    <script>
        $(function(){

            $('.faster').on('click',function(e){

                var optional = $('.optional-field');

                if ( optional.is(':hidden')) optional.show();

                else ( optional.hide());

            });

            var edit_modal = $('#edit-field');

            // Trigger event to edit field
            $('i').on('edit-field',function(e){

                $.ajax({
                    url: '/parnassus/fetch_field/<?=$model?>/'+$(this).attr('data-field-name'),
                    success: function(field){

                        console.log(field);

                        edit_modal.find('.field-label').html( field.label );

                        $('a[name="open-edit-field"]').trigger('click');

                    }
                });


            });

                // Add datepicker for required fields
                $('input[data-field-type="date"]').datepicker({
                    dateFormat: 'yy-mm-dd'
                });

                // ... and timepicker fro time fields
                $('.clockpicker').clockpicker({
                    'default': 'now'
                });

                // Bootstrap Switch
            var input = $('.bootstrap-switch');
            input.bootstrapSwitch({
                    onText: 'Yes',
                    offText: 'No',
                    onSwitchChange: function(event, state) {
                        if ( state ) input.attr('checked','checked');
                        else input.removeAttr('checked');
                    }
                });

                $('.remove-field').on('click',function(e){

                    var elem = $(this);

                    $.ajax({
                        url: '/parnassus/hide_field/',
                        type: 'POST',
                        data: 'type=add&model=<?=$model?>&field='+$(this).attr('data-field-name'),
                        success:function(json){

                            if ( json.result == 'success')

                                elem.parent().parent().fadeOut('fast').remove();


                        }
                    });

                });
        });
    </script>
    <?php require_once( $webroot . "/app/views/components/parnassus-edit-field.php");?>
</body>
</html>

<?php

function get_controller( $controller ){

    return $controller;
}
?>
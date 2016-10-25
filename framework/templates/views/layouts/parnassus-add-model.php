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

    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
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
            <div class="col-lg-12">
                <a class="faster btn btn-primary"><?=$required_label?></a>
            </div>
        </div>
        <?php switch ( $action ){

            case '':
?>
        <?php
            break;
            case 'list_of':
        ?>
        <?php
           break;
            case 'models':
            case 'add':
                ?>
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
                                    <legend>Legend</legend>
                                    <?php if ( isset( $data['fields'])) {?>
                                        <input type="hidden" name="new_model_submitted" value="yes"/>
     <?php                                        foreach ( $data['fields']  as $field ) {
                                            if ( ! in_array($field->name,array(
                                                'id','created_date','created_by','last_modified_date','last_modified_by','is_deleted','deleted_date','deleted_by'
                                            ))) {

                                               // FB::log($field);
                                            ?>
                                            <div class="form-group <?=$field->is_required?'required-field':'optional-field'?>">
                                                <div class="label-wrapper float-left relative-wrapper remove-field-parent">
                                                    <label class="col-lg-2 control-label" for="inputEmail"><?=$field->name?><?=$field->is_required?'&nbsp;*':''?></label>
                                                    <i style="left:0;cursor:pointer;" class="fa fa-times fa-2x absolute remove-field" data-field-name="<?=$field->name?>"></i>
                                                    <i style="left:30px;cursor:pointer;" class="fa fa-pencil fa-2x absolute link-field" data-field-name="<?=$field->name?>" onclick="$(this).trigger('edit-field');"></i>
                                                  </div>
                                                    <?php if ( $field->type =='enum'){?>
                                                        <div class="col-lg-10">
                                                            <select data-required-field="<?=$field->is_required?>" class="form-control " name="<?=$field->name?>">
                                                                <?php foreach ( $field->enum_values as $value ){?>
                                                                    <option value="<?=$value?>"><?=$value?></option>
                                                                <?php }?>
                                                            </select>

                                                        </div>
                                                    <?php  } elseif ( $field->type == 'tinyint'){ ?>
                                                    <div class="col-lg-10">
                                                        <input  data-required-field="<?=$field->is_required?>" name="<?=$field->name?>" class="bootstrap-switch" type="checkbox" value="1">
                                                    </div>
                                                    <?php } elseif ( $field->type == 'time'){ ?>
                                                <div class="col-lg-2">
                                                    <div class="input-group clockpicker" data-autoclose="true" data-placement="left" data-align="top" >
                                                        <input data-required-field="<?=$field->is_required?>" name="<?=$field->name?>" type="text" class="form-control" value="">
                                                        <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                        </span>
                                                    </div>
                                                    </div>
                                                    <?php } elseif ( $field->type == 'text'){?>
                                                <div class="col-lg-10">

                                                        <textarea data-required-field="<?=$field->is_required?>" style="height: 200px;" class="form-control" name="<?=$field->name?>" id="<?=$field->name?>"></textarea>
                                                    </div>
                                                        <script>
                                                            // Replace the <textarea id="editor1"> with a CKEditor
                                                            // instance, using default configuration.
                                                            CKEDITOR.replace( '<?=$field->name?>' );
                                                        </script>
                                                    <?php } elseif ($field->is_foreign_key == 1){

                                                        //FB::log($field);

                                                        $fetcher = new \Code_Alchemy\helpers\foreign_key_values_fetcher( $field->foreign_table, $field->reference_column );


                                                        ?>
                                                <div class="col-lg-10">

                                                <select data-required-field="<?=$field->is_required?>" class="form-control" id="<?=$field->name?>" name="<?=$field->name?>">
                                                    <option value="">Please choose...</option>
                                                        <?php foreach ( $fetcher->values() as $id=>$value ){
                                                            $selected = ( isset( $_GET[$field->name]) && $_GET[$field->name] == $id )? 'selected="selected"':'';
                                                            ?>
                                                        <option <?=$selected?> value="<?=$id?>"><?=$value?></option>
                                                        <?php }?>
                                                    </select>
                                                    </div>
                                                    <?php } else { ?>
                                                <div class="col-lg-10">

                                                <input data-required-field="<?=$field->is_required?>" data-field-type="<?=$field->type?>"  <?=in_array('Primary Key',$field->flags)?'disabled="disabled"':''?> type="<?=$field->input_type?>" id="<?=$field->name?>" name="<?=$field->name?>" class="form-control" value="<?=$field->default_value?>">
                                                </div>
                                                    <?php }?>
                                            </div>
                                        <?php }  }
                                    }
                                    ?>
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
                                            <a href="/parnassus/list_of/<?=$model?>"><button type="button" class="btn btn-default">Cancel</button></a>
                                            <button class="btn btn-primary" type="submit">Submit</button>
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
            <?php break; }?>
    </div>
</div>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
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

/**
 * Local function to normalize Controller, for code completion
 * @param \_mynamespace_\controllers\app_controller $controller
 * @return \_mynamespace_\controllers\app_controller controller
 */
function get_controller( \_mynamespace_\controllers\app_controller $controller ){

    return $controller;
}
?>
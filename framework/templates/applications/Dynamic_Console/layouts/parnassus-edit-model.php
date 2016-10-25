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

$object = $data['model'];

$intersections = $data['intersections'];

$referenced_by = $data['referenced_by'];


$language = (string) (new \Code_Alchemy\Core\Configuration_File())->language();

$image_advisory = $lang == 'es'? 'Imagen se actualizará cuando al guardar el registro':'Image will be updated when record is saved';

$edit = $language == 'es'? 'Editar Registro': 'Edit Record';

?>
<!DOCTYPE html>
<html>
<head>

    <?php

    require_once( $webroot. "/app/views/components/parnassus-head.php");

    ?>
    <script src="/ckeditor/ckeditor.js"></script>
    <link rel="stylesheet" href="/css/bootstrap-switch.css">


</head>
<body  onload="webDirector.disable_preloader()">

<?php

require_once( $webroot. "/app/views/components/parnassus-nav.php");

?>

    <div id="wrapper">

        <?php  require_once( $webroot. "/app/views/components/ca-sidebar-wrapper.php");?>

        <div id="page-content-wrapper">

            <div style="margin-top: 50px;" class="container">
                <div class="bs-docs-section clearfix" style="margin-top: 50px">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="admin-title"></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-9">

                            <?php if ( isset($_REQUEST['update_result']) && $_REQUEST['update_result']=='success'){?>
                                <div class="alert alert-dismissable alert-success">
                                    <button data-dismiss="alert" class="close" type="button">×</button>
                                    <strong>Well done!</strong> Your changes have been saved
                                </div>
                            <?php } ?>
                            <div class="well bs-component">
                                <form enctype="multipart/form-data" class="form-horizontal" method="POST">
                                    <fieldset>
                                        <legend><?=$edit?></legend>
                                        <?php if ( isset( $data['fields'])) {?>
                                            <input type="hidden" name="new_model_submitted" value="yes"/>

                                            <?php
                                            // For each field in the Model...
                                            foreach ( $data['fields']  as $field ) {

                                                // Is it read only?
                                                $is_readonly = (new \Code_Alchemy\Models\Characteristics\Is_Readonly_Field($field->name,$model))->bool_value();

                                                // Skip over audit fields and Id field
                                                if ( ! in_array($field->name,array(
                                                    'sortable_id', 'seo_name', 'id','created_date','created_by','last_modified_date','last_modified_by','is_deleted','deleted_date','deleted_by'
                                                ))) {

                                                    $member = $field->name;
                                                    $value = $object[$member];

                                                    if ( in_array($member,array('title','summary')))

                                                        $value = htmlentities( $value );

                                                    // Get Label
                                                    $label = (string) new \Code_Alchemy\Models\Helpers\Model_Field_Label($member);

                                                    ?>
                                                    <div class="form-group">
                                                        <div class="label-wrapper float-left relative-wrapper remove-field-parent">
                                                            <label class="col-lg-3 control-label" for="inputEmail"><?=$label?><?=$field->is_required?'&nbsp;*':''?></label>
                                                            <i style="left:0;cursor:pointer;" class="fa fa-times fa-2x absolute remove-field" data-field-name="<?=$field->name?>"></i>
                                                        </div>

                                                        <?php if ( $field->type ==='enum'){?>
                                                            <div class="col-lg-9">
                                                                <select data-field-type="<?=$field->type?>" class="form-control" id="<?=$field->name?>" name="<?=$field->name?>">
                                                                    <?php foreach ( $field->enum_values as $enum_val ){
                                                                        $selected = $enum_val == $value ? 'selected="selected"':'';
                                                                        ?>
                                                                        <option value="<?=$enum_val?>" <?=$selected?> ><?=$enum_val?></option>
                                                                    <?php } ?>
                                                                </select>

                                                            </div>
                                                        <?php } elseif ( $field->type === 'image'){

                                                            $key = $field->name . "_url";
                                                            $src = isset( $object[$key])?$object[$key]:null;

                                                            ?>
                                                            <div class="col-lg-9">
                                                                <?php if ( $src ){?>
                                                                    <div class="image-grouping text-center center-text clearfix">
                                                                        <img style="max-height: 150px;" src="<?=$src?>"/>
                                                                        <a class="btn btn-primary" href="/parnassus/edit_image/<?=$model?>/<?=$model_id?>/<?=$field->name?>"><i class="pull-left fa fa-file-image-o fa-2x"></i>&nbsp; Edit Image</a>
                                                                    </div>
                                                                <?php } ?>
                                                                <input value="<?=$value?>" data-field-type="<?=$field->type?>"  <?=in_array('Primary Key',$field->flags)?'disabled="disabled"':''?> type="<?=$field->input_type?>" id="<?=$field->name?>" name="<?=$field->name?>" class="form-control">

                                                            </div>
                                                        <?php } elseif ( $field->type === 'tinyint' || $field->data_type === 'boolean'){ ?>
                                                            <div class="col-lg-9">
                                                                <input data-input-name="<?=$field->name?>" name="<?=$field->name?>-switch" class="bootstrap-switch" type="checkbox" value="1" <?=$value?'checked="checked"':''?>>
                                                                <input name="<?=$field->name?>" class="" type="hidden" value="<?=$value?>">
                                                            </div>
                                                        <?php } elseif ( $field->type === 'time'){ ?>
                                                            <div class="col-lg-3">
                                                                <div class="input-group clockpicker" data-autoclose="true" data-placement="left" data-align="top" >
                                                                    <input name="<?=$field->name?>" type="text" class="form-control" value="<?=$value?>">
                                                        <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                        </span>
                                                                </div>
                                                            </div>
                                                        <?php } elseif ( $field->type === 'text' || $field->data_type === 'text'){?>
                                                            <div class="col-lg-9">

                                                                <textarea style="height: 200px;" class="form-control" name="<?=$field->name?>" id="<?=$field->name?>"><?=$value?></textarea>
                                                                <script>
                                                                    // Don't do it if an email template
                                                                    <?php if ( $field->name != 'handlebars_template' &&   ! in_array($model,array('email_template'))) {?>
                                                                    // Replace the <textarea id="editor1"> with a CKEditor
                                                                    // instance, using default configuration.
                                                                    CKEDITOR.replace( '<?=$field->name?>' );
                                                                    <?php } ?>
                                                                </script>

                                                            </div>
                                                        <?php } elseif ($field->is_foreign_key == 1){

                                                            // Is this a website image Id?
                                                            // Do we have the image?
                                                            if ( (new \Code_Alchemy\Database\Table\Fields\Is_Website_Image_Field($field->name))->bool_value() ){

                                                                echo $controller->handlebars('ca-model-website-image',array(

                                                                    'preview_image_display_token' => @$object['image_filename_url']? 'block':'none',

                                                                    'image_filename_url' => @$object['image_filename_url'],

                                                                    'model_name' => $model,

                                                                    'model_id' => $model_id,

                                                                    'field_name' => $field->name,

                                                                    'placeholder' => $language=='es'?'Ingresa el nombre de la imagen':'Type the name of the image',

                                                                    'website_image_id' => $value,

                                                                    'image_advisory' => $image_advisory
                                                                ));
                                                            } else { ?>
                                                                <div class="col-lg-7">

                                                                <select data-selected-value="<?=$value?>" data-lookup-conditions="<?=$field->lookup_conditions?>" data-foreign-table="<?=$field->foreign_table?>" disabled="disabled" class="foreign-key form-control disabled" id="<?=$field->name?>" name="<?=$field->name?>">
                                                                </select>
                                                            <?php } ?>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="col-lg-9">

                                                                <input <?=$is_readonly?'disabled="disabled"':''?>  value="<?=$field->name =='password'? '': $value?>" data-field-type="<?=$field->type?>"  <?=in_array('Primary Key',$field->flags)?'disabled="disabled"':''?> type="<?=$field->input_type?>" id="<?=$field->name?>" name="<?=$field->name?>" class="form-control <?=$is_readonly?'disabled':''?>">
                                                            </div>
                                                        <?php }?>
                                                    </div>
                                                <?php }  }
                                        }
                                        ?>

                                        <?php
                                        // If we have referencing models
                                        if ( count( $referenced_by) > 0 ){
                                            ?>
                                            <!--                                    <h3>Related Items</h3>-->
                                            <?php
                                            foreach( $referenced_by as $ref){
                                                if ( $ref ){
                                                    ?>
                                                    <h3><?=ucfirst(implode(' ',explode('_',$ref)))?></h3>
                                                    <div class="form-group">
                                                        <a href="/parnassus/add/<?=$ref?>?<?=$model?>_id=<?=$model_id?>" class="btn btn-success pull-left">
                                                            <i class="fa fa-plus-circle"></i>
                                                            &nbsp; <?=$language=='es'?'Agregar':'Add'?>
                                                        </a>

                                                    </div>
                                                    <div class="form-group">
                                                        <?php

                                                        $records = @$data["referenced_by_$ref"];

                                                        if ( is_array($records))
                                                            foreach ( $records as $record){
                                                                $col = $record->source()->reference_column();
                                                                ?>
                                                                <a class="btn btn-info btn-xs" href="/parnassus/models/<?=$ref?>/<?=$record->id?>/edit"><?=$record->$col?></a>
                                                            <?php } ?>
                                                    </div>
                                                <?php }
                                                // That's it for referencing models
                                            } }
                                        ?>

                                        <?php foreach( $intersections as $isectname => $isect ){?>
                                            <h3><?=$isect['display_name']?></h3>
                                            <div class="form-group">
                                                <a href="/parnassus/add/<?=$isectname?>?<?=$model?>_id=<?=$model_id?>" class="btn btn-success pull-left">
                                                    <i class="fa fa-plus-circle"></i>
                                                    &nbsp; <?=$language=='es'?'Agregar':'Add'?>
                                                </a>

                                            </div>

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
                                            <div class="col-lg-9 col-lg-offset-2">
                                                <button type="reset" class="btn btn-default cancel-edit">Cancel</button>
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
                </div>
            </div>
        </div>
    </div>


    <script src="/jquery-ui/jquery-ui.min.js"></script>
    <script src="/js/bootstrap-clockpicker.js"></script>
    <script src="/js/bootstrap-switch.js"></script>
    <script>
        $(function(){

            $('.cancel-edit').on('click',function(e){

                window.location.href = '/parnassus/list_of/<?=$model?>/1/25';

            });

            // Bootstrap Switch
            var element = $('.bootstrap-switch');

            element.each(function(){

                var switcher = $(this);

                switcher.bootstrapSwitch({
                    onText: '<?=$language=='es'? 'Si':'Yes'?>',
                    offText: 'No',
                    onSwitchChange: function(event, state) {

                        input2 = $('input[name="'+  switcher.attr('data-input-name')+'"]');

                        if ( state ) {

                            input2.attr('checked','checked');


                            input2.val( 1 );

                        }
                        else {

                            switcher.removeAttr('checked');

                            input2.val( 0 );
                        }

                    }
                });


            });




            // For each foreign key field
            $('select.foreign-key').each(function(){

                var select = $(this);

                console.log('Found Foreign Key selector with ID #'+select.attr('id'));

                var selected_value = select.attr('data-selected-value');

                // Get foreign values
                $.ajax({
                    url: '/parnassus/foreign_values_for/'+select.attr('data-foreign-table'),
                    data: 'conditions='+ select.attr('data-lookup-conditions'),
                    type: 'POST',
                    success: function(values){

                        $.each( values, function(index,value){

                            //var selected = selected_value == value ? 'selected="selected"':'';

                            select.append('<option value="'+index+'">'+value+'</option>');

                        });


                        select.val(selected_value);

                        select.removeClass('disabled').removeAttr('disabled');
                    }
                });


            });

            /**
             *
             * @param json
             * @param label
             * @returns {*}
             */
            function get_value( json,label ){

                return typeof(json[ label ])!='undefined'?json[label]:'';
            }

            /**
             * Get foreign table options
             * @param {string} table
             * @param {function} callback
             */
            function get_foreign_table_options( table, callback ){

                $.ajax({

                    url: '/parnassus/foreign_table_options/'+table,

                    success: function(json){

                        callback(json);

                    }
                });

            }

            /**
             * Place HTML for control
             * @param {string} input
             * @param {object} field
             */
            function place_html( input, field  ){

               $('.button-group').before('<div class="form-group"><label class="col-lg-3 control-label" for="inputEmail">'+field.name+'</label><div class="col-lg-9">'+input +'</div></div>');

                if ( field.type ==='text' || field.data_type == 'text')

                    CKEDITOR.replace( field.name );


    }

        });
    </script>
</body>
</html>

<?php

function get_controller( $controller ){

    return $controller;
}
?>
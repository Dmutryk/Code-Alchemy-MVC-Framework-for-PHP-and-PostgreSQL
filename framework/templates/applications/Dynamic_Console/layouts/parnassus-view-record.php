<?php

$webroot = \Code_Alchemy\Core\Code_Alchemy_Framework::instance()->webroot();

// Get Controller
$controller = get_controller( $this );

// Get State
$state = $controller->state();

$oData = $controller->data_as_object();

$aData = $controller->data();


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

    <div id="wrapper">

        <?php require_once( $webroot. "/app/views/components/ca-sidebar-wrapper.php");?>

        <div id="page-content-wrapper">


            <div style="margin-top: 50px;" class="container">
                <div class="bs-docs-section clearfix" style="margin-top: 1px">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="admin-title"><?=$oData->oRecord->reference_value()?></h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-10">

                            <div class="record-detail-view well bs-component">
                                <form class="form-horizontal">
                                    <fieldset>
                                        <?php foreach ( $oData->aRecord  as $name =>$value) {
                                            $hidden_fields = isset($oData->configuration[$controller->uri()->part(3)]['view_hidden_fields'])?
                                                $oData->configuration[$controller->uri()->part(3)]['view_hidden_fields']:array();
                                            $display = in_array($name, $hidden_fields)?'none':'block';
                                            ?>
                                            <div style="display:<?=$display?>" class="form-group">
                                                <div class="label-wrapper float-left relative-wrapper remove-field-parent">
                                                    <label class="col-lg-4 control-label" for="inputEmail"><?=$name?></label>
                                                    <a data-field-name="<?=$name?>" data-model-id="<?=$controller->uri()->part(4)?>" data-model-name="<?=$controller->uri()->part(3)?>" style="left:10px; top: 5px;" class="absolute hide-field" href="#"><i class="fa fa-trash fa-2x"></i></a>
                                                </div>
                                                <div class="col-lg-8">
                                                    <span style="padding-top: 11px;" class="pull-left"><?=$value?></span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?php
                                        foreach( $oData->oRecord->referenced_by() as $ref){

                                            // Don't shoot blanks
                                            if (! $ref) continue;

                                            $config = $oData->configuration[$ref];

                                            FB::log($config);
                                            ?>
                                            <h3><?=$config['service_label']?></h3>
                                            <table style="float:left; width: 100%;">
                                                <thead>
                                                <tr>
                                                    <?php foreach( $config['columns']['shown'] as $col){?>
                                                        <th><?=$col?></th>
                                                    <?php } ?>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $records = $oData->oRecord->referencing( $ref);

                                                foreach( $records as $record ){?>
                                                    <tr>
                                                        <?php foreach( $config['columns']['shown'] as $col ){?>
                                                            <td><?=$record[$col]?></td>
                                                        <?php }?>
                                                    </tr>

                                                <?php }?>

                                                </tbody>
                                            </table>
                                        <?php } ?>
                                    </fieldset>
                                </form>
                                <div class="btn btn-primary btn-xs" id="source-button" style="display: none;">&lt; &gt;</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script src="/js/bootstrap-clockpicker.js"></script>
    <script src="/js/bootstrap-switch.js"></script>
    <script>
        $(function(){

            $('.cancel-edit').on('click',function(e){

                window.location.href = '/parnassus/list_of/<?=$model?>/1/25';

            });

            // Bootstrap Switch
            var input = $('.bootstrap-switch');
            input.bootstrapSwitch({
                onText: '<?=$language=='es'? 'Si':'Yes'?>',
                offText: 'No',
                onSwitchChange: function(event, state) {

                    input2 = $('input[name="'+input.attr('data-input-name')+'"]');

                    if ( state ) {

                        input.attr('checked','checked');


                        input2.val( 1 );

                    }
                    else {

                        input.removeAttr('checked');

                        input2.val( 0 );
                    }

                }
            });



            // For each foreign key field
            $('select.foreign-key').each(function(){

                var select = $(this);

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

                if ( field.type =='text')

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
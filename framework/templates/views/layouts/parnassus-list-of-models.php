<?php


/**
 * PLEASE DO NOT EDIT THIS FILE.
 * 
 * (Unless you are contributing to improving it, 
 * via Open Source collaboration)
 * 
 * The reason is, if you are the end-user, this file can be 
 * regularly upgraded as part of a published improvement or bug
 * fix to Code_Alchemy Composer and Application Director
 * 
 * If you absolutely feel you need to make a change, contact
 * us instead and make a new feature request, or, if you are a 
 * software developer, feel free to make a contribution.
 * 
 * Contact us for any reason at info@alquemedia.com
 * 
 * This file is part of Code_Alchemy Framework for PHP and jQuery
 * 
 * This application uses PHP, MySQL, JavaScript, jQuery, Handlebars, LESS, 
 *  and Twitter Bootstrap 3.
 *
 * Some of our code is based on licensed templates, such as "Angle", "Flati"
 * and "Toranj."  In every such case, all end-deployed websites, have a 
 * specific purchased and paid license of use, from the respective authors, 
 * and we encourage all such installations to do the same.
 * 
 * Those guys work really hard to save us downstream developers hundreds of 
 * hours of time with their incredible work.  Let's be sure to pay them
 * a small cash value, for their immense use value.
 *
 * PHP version 5.4 or greater is always recommended.
 *
 * @category  Code_Alchemy
 * @package   Composer and Application Director
 * @author    David Greenberg <david@alquemedia.com>
 * @copyright 2015 Authors
 * @license   MIT <http://opensource.org/licenses/MIT>
 * @link      http://www.alquemedia.com
 */


$webroot = \Code_Alchemy\Core\Code_Alchemy_Framework::instance()->webroot();

// Get Controller
$controller = get_controller( $this );

// Get State
$state = $controller->state();

$data = $controller->data();

$data_object = $controller->data_as_object();

$action = $controller->uri()->part(2);

$model = $controller->uri()->part(3);

$model_id = $controller->uri()->part(4);

$commmand = $controller->uri()->part(5);

$services = $data['services'];

$columns = $data['all_columns'];

$shown_columns = $data['shown_columns'];

$models = $data['models'];

$fields = $data['fields'];

$lang = (string) \Code_Alchemy\Core\Code_Alchemy_Framework::instance()->configuration()->language;

$ref = $data['reference_column'];

$is_sortable_class = $data['is_sortable']?'is-sortable':'';

$per_page = $controller->uri()->part(5);

$page_number = $controller->uri()->part(4);

$last_page = $data['last_page'];

$supports_soft_delete = $data['supports_soft_delete'];

$delete_icon = $supports_soft_delete? 'fa-archive':'fa-trash';

$data = $controller->data_as_object();

$select_service_title = $lang =='es'?
    'Salta rapidamente a otro listado cambiando este valor':
    'Quickly jump to another service view by changing this value';

FB::log($controller->data());

?>
<!DOCTYPE html>
<html>
<head>
    <?php
    require_once $webroot ."/app/views/components/parnassus-head.php";
    ?>
</head>
<body>

<?php
    require_once $webroot ."/app/views/components/parnassus-nav.php";
?>
<div style="" class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header">
                        <h1 class="clearfix" id="tables">

                            <select class="pull-left form-control" id="select-service" name="select_service">
                                <?php foreach ( $controller->data_as_object()->services as $service ){

                                    $selected = strtolower($controller->data_as_object()->label) == strtolower($service['service_label'])? 'selected="selected"':'';

                                    ?>
                                <option <?=$selected?> value="<?=$service['table_name']?>"><?=$service['service_label']?></option>
                                <?php } ?>
                            </select>
                            <select class="pull-left form-control" id="page-size" style="margin-left: 20px; width: 200px;" name="page_size">
                                <?php foreach( array(5,10,25,50,100) as $page_size){
                                    $show_label = $data_object->language =='es'?"Mostrar $page_size registros": "Show $page_size records";?>

                                    ?>
                                    <option <?=$page_size==$per_page?'selected="selected"':''?> value="<?=$page_size?>"><?=$show_label?></option>
                                <?php } ?>
                            </select>

                        </h1>

                        <!-- alerts here -->
                        <?php if ( isset($data['new_model_id']) || isset( $data['edited_model_id']) ){?>
                            <div class="alert alert-dismissable alert-success">
                                <button data-dismiss="alert" class="close" type="button">×</button>
                                <strong>Well done!</strong> Your changes have been saved
                            </div>
                        <?php } ?>


                        <p>
                            <a href="/parnassus/add/<?=$data['model_class']?>" class="btn btn-primary">
                                <i class="fa fa-plus-circle"></i>
                                &nbsp; <?=$lang=='es'?'Agregar':'Add'?>
                            </a>
                            <a href="#" class="btn btn-warning edit-view">
                                <i class="fa fa-pencil-square-o"></i>
                                &nbsp; <span class="label">Edit View</span>
                            </a>
                        </p>

                        <select style="display:none;width: 400px;" class="form-control pull-left select-columns" multiple="">
                            <?php foreach ( $columns  as $col) {
                                $selected = in_array($col, $shown_columns)?'selected="selected"':'';

                                ?>
                            <option <?=$selected?> value="<?=$col?>"><?=$col?></option>
                            <?php } ?>
                        </select>

                        <form action="/parnassus/list_of/<?=$model?>">
                            <div class="form-group col-lg-6">
                                <select name="search_column" style="width: 40%;" id="search-col" class="pull-left form-control">
                                    <?php foreach ( $shown_columns as $col){
                                        $selected = $col == $_REQUEST['search_column']?'selected="selected"':'';

                                        ?>
                                        <option <?=$selected?> value="<?=$col?>"><?=$col?></option>
                                    <?php } ?>
                                </select>
                                <input style="width:40%;" name="search_term" id="search-term" class="pull-left form-control" value="<?=$_REQUEST['search_term']?>"/>
                            </div>

                        </form>
                        <a href="/parnassus/list_of/<?=$model?>"><i class="fa fa-times fa-2x"></i></a>


                    </div>

                    <div class="bs-component">
                        <table class="<?=$is_sortable_class?> pull-left table table-striped table-hover ">
                            <thead>
                            <tr>
                                <?php foreach ( $shown_columns as $col){?>
                                <th><?=$col?></th>
                                <?php } ?>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php foreach ( $models as $mod ){?>

                                    <tr data-id="<?=$mod['id']?>">
                                        <?php foreach( $shown_columns as $col ){

                                            $field = $fields[$col];

                                            $val = $mod[ $col ];

                                            // If Boolean Yes/No
                                            if ( $field->type =='tinyint') {

                                                $yes = $lang=='es'?'Sí':'Yes';

                                                $val = $val?$yes:'No';
                                            }

                                            // For an image
                                            if ( preg_match('/image_filename_url/',$col,$hits)) {
                                                ?>
                                                <td><img src="<?=$val?>" style="max-width: 50px;"/></td>
                                                <?php
                                            }  else { ?>
                                            <td><?=$val?></td>
                                        <?php } } ?>
                                        <td>
                                            <a href="/parnassus/view/<?=$controller->uri()->part(3)?>/<?=$mod['id']?>"><i class="fa fa-eye fa-2x"></i></a>
                                            <a href="/parnassus/models/<?=$controller->uri()->part(3)?>/<?=$mod['id']?>/edit">
                                                <i title="<?=$data_object->tooltip_titles['edit']?>" class="fa fa-pencil fa-2x"></i></a>
                                            <i title="<?=$data_object->tooltip_titles['delete']?>" style="cursor:pointer" data-id="<?=$mod['id']?>" class="pointer fa <?=$delete_icon?> delete-item id-<?=$mod['id']?> fa-2x"></i>
                                            <?php if ( $model == 'email_template'){?>
                                            <a data-template-key="<?=$mod['template_key']?>" onclick="$('#test-send').attr('data-template-key',$(this).attr('data-template-key'));" data-toggle="modal" data-target="#test-send" href="#" class="test-send" data-id="<?$mod['id']?>">
                                                <i class="fa fa-paper-plane fa-2x test-send" ></i>
                                            </a>
                                            <?php }?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <div class="row">
                            <ul class="pager">
                                <?php if ( $page_number>1){?>
                                <li><a href="/parnassus/list_of/<?=$model?>/<?=($page_number-1)?>/<?=$per_page?>">Previous</a></li>
                                <?php } ?>
                                <?php if ( $last_page> $page_number ){?>
                                <li><a href="/parnassus/list_of/<?=$model?>/<?=($page_number+1)?>/<?=$per_page?>">Next</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                              </div>
            </div>

    </div>
    <?php

    require_once $webroot."/app/views/components/parnassus-footer.php";
    ?>

<!--</div>-->
    <?php if ( $action == 'add'){ ?>
    <script src="/js/bootstrap-clockpicker.js"></script>
    <script src="/js/bootstrap-switch.js"></script>
    <?php } ?>
<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>

<script src="/js/bloodhound.min.js"></script>
<script src="/js/typeahead.js"></script>
    <script>
        $(function(){

            // Show some tips
            $('.tooltip').tooltip();

            // Select a different service
            $('select#select-service').on('change',function(e){

                window.location.href = '/parnassus/list_of/'+$(this).val()+'/1/25';

            });

            // Change page size
            $('select#page-size').on('change',function(e){

                console.log('ok');

                window.location.href = '/parnassus/list_of/<?=$model?>/<?=$page_number?>/'+$(this).val();

            });



            $('.is-sortable tbody').sortable({
                stop: function(event,ui){

                    var order = [];

                    $('tr').each(function(){

                        var id = $(this).attr('data-id');

                        if ( typeof(id)!='undefined' ) order.push(id);

                    });

                    console.log( order.join(',') );

                    $.ajax({
                        url: '/parnassus/reorder/<?=$model?>',
                        data: 'order='+order.join(','),
                        type: 'POST',
                        success: function(json){
                            console.log(json);
                        }
                    })

                }
            });

            // Edit the view
            $('.edit-view').on('click',function(e){

                var control = $(this);

                var select = $('.select-columns');

                if ( ! select.is(':visible') ){

                    select.show();

                    control.find('.label').html('Save Changes');

                } else {

                    var columns = select.val().join(',');

                    if ( columns.length )

                        $.ajax({

                            url: '/parnassus/set_columns_for/'+'<?=$model?>',

                            type: 'POST',

                            data: 'columns='+columns,

                            success: function(json){

                                if ( json.result == 'success')

                                    window.location.reload();
                            }
                        });
                }

            });
            // Delete an Item
            $('.delete-item').on('click',function(e){

                // Delete using API
                var id = $(this).attr('data-id');
                $.ajax({
                    type: 'DELETE',
                    url: '/api/v1/<?=$model?>/'+ id,
                    success: function(json){

                        if ( json.codeAlchemy_data.operation_result =='success')

                        // remove item
                            $('tr[data-id="'+id+'"]').fadeOut('fast').remove();

                    }
                });

            });

        });
    </script>
    <?php if ( $model == 'email_template'){?>
    <div id="test-send" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                    <h4 class="modal-title">Test Send</h4>
                </div>
                <div class="modal-body">
                    <div style="display:none;" class="alert alert-dismissible alert-danger">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        <strong>Oh snap!</strong> <span class="message"></span>
                    </div>
                    <input class="test-email" placeholder="type email address to send"/>
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                    <button class="btn btn-primary send-email" type="button">Send Email</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function(){

            $('button.send-email').on('click',function(e){

                var modal = $('#test-send');

                $.ajax({
                    type: 'POST',
                    url: '/parnassus/test_email_template/',
                    data: 'template='+modal.attr('data-template-key')+'&email='+modal.find('input').val(),
                    success: function(json){

                        if (json.result == 'success')

                            modal.modal('hide');

                        else {

                            var alert = modal.find('.alert-danger');

                            var message = alert.find('.message');

                            message.html( json.error);

                            alert.show();
                        }
                    }
                });


            });
        });
    </script>
    <?php } ?>
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
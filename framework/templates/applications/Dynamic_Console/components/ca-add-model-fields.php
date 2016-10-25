<?php

    $array_indicator = @$is_multi ? '[]':'';

    foreach ( $modelFields as $field ) {
    if ( ! in_array($field->name,array(
        'id','created_date','created_by','last_modified_date','last_modified_by','is_deleted','deleted_date','deleted_by'
    ))) {

        ?>
        <div class="form-group <?=$field->is_required?'required-field':'optional-field'?>">
            <div class="label-wrapper float-left relative-wrapper remove-field-parent">
                <label class="col-lg-2 control-label" for="inputEmail"><?=$field->name?><?=$field->is_required?'&nbsp;*':''?></label>
                <?php if ( ! @$is_multi ){?>
                <i style="left:0;cursor:pointer;" class="fa fa-times fa-2x absolute remove-field" data-field-name="<?=$field->name?>"></i>
                <i style="left:30px;cursor:pointer;" class="fa fa-pencil fa-2x absolute link-field" data-field-name="<?=$field->name?>" onclick="$(this).trigger('edit-field');"></i>
                <?php }?>
            </div>
            <?php if ( $field->type =='enum'){?>
                <div class="col-lg-10">
                    <select data-required-field="<?=$field->is_required?>" class="form-control " name="<?=$field->name?><?=$array_indicator?>">
                        <?php foreach ( $field->enum_values as $value ){?>
                            <option value="<?=$value?>"><?=$value?></option>
                        <?php }?>
                    </select>

                </div>
            <?php  } elseif ( $field->type == 'tinyint'){ ?>
                <div class="col-lg-10">
                    <input  data-required-field="<?=$field->is_required?>" name="<?=$field->name?><?=$array_indicator?>" class="bootstrap-switch" type="checkbox" value="1">
                </div>
            <?php } elseif ( $field->type == 'time'){ ?>
                <div class="col-lg-2">
                    <div class="input-group clockpicker" data-autoclose="true" data-placement="left" data-align="top" >
                        <input data-required-field="<?=$field->is_required?>" name="<?=$field->name?><?=$array_indicator?>" type="text" class="form-control" value="">
                                                        <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                        </span>
                    </div>
                </div>
            <?php } elseif ( $field->type == 'text'){?>
                <div class="col-lg-10">

                    <textarea data-required-field="<?=$field->is_required?>" style="height: 200px;" class="form-control" name="<?=$field->name?><?=$array_indicator?>" id="<?=$field->name?>"></textarea>
                </div>
                <script>
                    // Replace the <textarea id="editor1"> with a CKEditor
                    // instance, using default configuration.
                    CKEDITOR.replace( '<?=$field->name?>' );
                </script>
            <?php } elseif ($field->is_foreign_key == 1){

                $fetcher = new \Code_Alchemy\Models\Helpers\Foreign_Key_Values_For( $field->foreign_table, $field->reference_column );


                ?>
                <div class="col-lg-10">

                    <select data-required-field="<?=$field->is_required?>" class="form-control" id="<?=$field->name?>" name="<?=$field->name?><?=$array_indicator?>">
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

                    <input data-required-field="<?=$field->is_required?>" data-field-type="<?=$field->type?>"  <?=in_array('Primary Key',$field->flags)?'disabled="disabled"':''?> type="<?=$field->input_type?>" id="<?=$field->name?>" name="<?=$field->name?><?=$array_indicator?>" class="form-control" value="<?=$field->default_value?>">
                </div>
            <?php }?>
        </div>
    <?php } ?>

<?php } ?>
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: David Greenberg
 * Date: 1/06/14
 * Time: 01:34 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy;


class model_auditor {

    private $model_name = '';

    private $audit_data = array(
        'record_id'=> '',
        'user_id'=> 0,
        'audit_type'=>'',
        'audit_fields'=>'',
        'previous_values'=>'',
        'new_values'=>''
    );

    /**
     * @param array $options
     */
    public function __construct( $options = array() ){

        foreach ( $options as $name=>$value)
            if ( $name == 'model_name') $this->model_name = "$value"."_audit";
                else $this->audit_data[$name]=$value;

    }

    public function audit_field($field, $previous, $new){

        $this->audit_data['audit_fields'] .= (strlen($this->audit_data['audit_fields'])>0)?",$field":$field;

        $this->audit_data['previous_values'] .= (strlen($this->audit_data['previous_values'])>0)?",$previous":$previous;

        $this->audit_data['new_values'] .= (strlen($this->audit_data['new_values'])>0)?",$new":$new;

    }

    public function commit(){


            $sql = "INSERT INTO `$this->model_name` (". $this->prepare_fields()
                . ") VALUES(".$this->prepare_values().")";

            $result = \mysql_service::query($sql);

    }

    /**
     * @return string of prepared fields for SQL
     */
    private function prepare_fields(){

        $result = "";

        foreach ( $this->audit_data as $field=>$value)
            $result .= (strlen($result)>0)?",`".$field."`":"`$field`";

        return $result;
    }

    /**
     * @return string of prepared values for SQL
     */
    private function prepare_values(){

        $result = "";

        $result = "";

        foreach ( $this->audit_data as $field=>$value)
            $result .= (strlen($result)>0)?",'".$value."'":"'$value'";

        return $result;
    }

}
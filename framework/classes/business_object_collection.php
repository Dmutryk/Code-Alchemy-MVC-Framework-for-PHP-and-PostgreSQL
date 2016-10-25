<?php
/**
 * Created by JetBrains PhpStorm.
 * User: acer
 * Date: 28/07/13
 * Time: 09:17 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Code_Alchemy;


class business_object_collection {

    /**
     * @var string default direction to sort
     */
    private $sort_direction = 'ASC';

    protected $key_field = '';
    protected $model_name = '';
    protected $id = 0;
    protected $members = array();
    private $exclude_deleted = false;
    private $sort = '';
    public function __construct($options = array()){
        foreach( $options as $name=>$value)
        {
            switch ( $name ){

                case 'sort_direction':
                    $this->sort_direction = (strtoupper($value)==='DESC'?'DESC':'ASC');
                break;
                default:
                    $this->$name = $value;
                break;
            }
        }

        //\FB::log($this->id);
        if ($this->key_field && $this->model_name &&$this->id) {
            $model_name = $this->model_name;
            $model = $model_name::model();
            $search = ($this->sort ? "order by $this->sort $this->sort_direction," : '') .
                ($this->exclude_deleted ? "is_deleted='0'," : '') . "$this->key_field='$this->id'";
            $this->members = $model->find_all($search);
        }
    }

    public function as_array(){
        $array = array();
        foreach ( $this->members as $id=>$member){
            array_push($array,$member->as_array());
        }
        return $array;
    }
}
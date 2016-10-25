<?php

/**
 * This component serves as a bridge between the Controller and the Layout, to serve
 * up the page-specific content view
 *
 * User: "David Owen Greenberg" <code@x-objects.org>
 * Date: 06/02/13
 * Time: 11:59 AM
 */

class xo_page_content extends magic_object {

    /**
     * @var null|xobjects\controllers\controller pointer to controller
     */
    private $controller = null;

    private $key = "home";
    private $vars = array();

    /**
     * @param $key
     * @param array $vars
     * @param xobjects\controllers\controller $controller optional controller pointer
     */
    public function __construct(
        $key,
        $vars = array(),
        xo_controller $controller = null){

        $this->controller = $controller;

        $this->key = $key;
        $this->vars = $vars;
        foreach( $vars as $name =>$value)
            $this->$name = $value;
    }


    /**
     * @return null|\xobjects\controllers\controller the controller
     */
    public function controller(){

        return $this->controller;

    }

    public function __toString(){
        try {
            $xobj =x_object::create($this->key);
            foreach ( $this->vars as $name=>$value)
                $xobj->$name = $value;
            $str = $xobj->html($this);
        } catch (Exception $e){
            $str = "<div style='background-color:#1e1e1e;color: white;width: 800px;height:auto;min-height: 100px;margin: 50px auto 0;padding: 10px;font-size: 25pt'><p>Code_Alchemy says:</p><p>".$e->getMessage()."</p></div>";
        }
        return $str;
    }

    /**
     * @param string $key
     * @return array of variables or a specific variable
     */
    public function variables( $key = '' ){
        return $key ? $this->vars[$key]: $this->vars; }
}

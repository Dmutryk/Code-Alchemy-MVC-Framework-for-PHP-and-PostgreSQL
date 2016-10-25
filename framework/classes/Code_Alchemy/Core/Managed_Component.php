<?php


namespace Code_Alchemy\Core;


use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;

abstract class Managed_Component extends Alchemist{

    /**
     * @var string Error occurring from any operation
     */
    public $error = '';

    /**
     * @var array of User set options
     */
    protected $user_options = array();

    /**
     * Set User Options
     * @param array $options
     * @param string $invoker
     */
    public function set_options( array $options, $invoker = '' ){

        //echo "setting options for ".get_called_class(). " called by $invoker\r\n";

        $this->user_options = array_merge($this->user_options,$options);

        return $this;

    }

    /**
     * @return array of user options
     */
    public function get_options(){

        return $this->user_options;

    }

    /**
     * @return string Root Path of Code_Alchemy
     */
    protected function root(){

        return (string) new Code_Alchemy_Root_Path();

    }

    /**
     * @return bool true if running in verbose mode
     */
    protected function verbose(){

        return !! ( isset($this->user_options['verbose']) && $this->user_options['verbose']=='yes');
    }




}
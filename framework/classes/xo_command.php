<?php
/**
 *
 * Object to encapsulate and run a server Command
 *
 * User: "David Owen Greenberg" <david@reality-magic.com>
 * Date: 31/03/13
 * Time: 01:47 PM
 */

class xo_command {

    /**
     * @var string command to be executed
     */
    private $command = '';

    /**
     * @var string output from the command
     */
    public $output = '';

    /**
     * @var string error, if any
     */
    public $error = '';

    /**
     * @param string $command to execute
     */
    public function __construct($command){

        $this->command = $command;

    }

    /**
     * @return bool true if successfully executed
     */
    public function execute(){

        // by default, not successful
        $result = false;

        $aOutput = array();

        $line = exec($this->command,$aOutput,$result);

        FB::log($line);

        if ( ! $result){

            $this->error = 'The command could not be executed';

        } else {

            $this->output = (string) new xo_array($aOutput);

            $result = true;
        }

        return $result;
    }
}
<?php


namespace Code_Alchemy\Directors;


class Process_Director {

    /**
     * @var string Command, issued by User
     */
    protected $command = '';

    /**
     * @var string Usage Description
     */
    protected $usage_description = "Usage: <command> [args]\r\n";

    /**
     * Show Usage
     */
    public function show_usage(){

        echo $this->usage_description;

    }

}
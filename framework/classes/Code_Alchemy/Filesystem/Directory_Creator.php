<?php


namespace Code_Alchemy\Filesystem;


use Code_Alchemy\Creators\Entity_Creator;

class Directory_Creator extends Entity_Creator{

    /**
     * @var string Full Path
     */
    private $full_path = '';

    /**
     * @var int Permissions
     */
    private $permissions = 0755;

    /**
     * @param $full_path
     * @param int $permissions
     */
    public function __construct( $full_path, $permissions = 0755 ){

        $this->full_path = $full_path;

        $this->permissions = $permissions;

    }

    /**
     * @param bool $verbose
     * @return bool true if created
     */
    public function create( $verbose = false ){

        //if ( $verbose ) echo "Creating directory $this->full_path with permissions $this->permissions\r\n";

        $result = file_exists($this->full_path  ) || mkdir($this->full_path,$this->permissions);


    }



}
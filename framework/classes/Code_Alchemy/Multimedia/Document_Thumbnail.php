<?php

namespace Code_Alchemy\Multimedia;

use Code_Alchemy\Filesystem\Files\File_Extension;
use Code_Alchemy\Security\Officer;

class Document_Thumbnail {

    /**
     * @var mixed|string filename for thumbnail
     */
    private $thumb_filename = '';

    /**
     * @var bool result of thumbnail creation process
     */
    private $success = false;

    /**
     * @var int id of file for thumbnail
     */
    public $file_id = 0;

    /**
     * @var string error that occurred (if any)
     */
    public $error = '';

    /**
     * @param $filename
     * @param array $settings
     */
    public function __construct($filename, array $settings = array()){

        global $webapp_location;

        $mgr = new Officer();

        // Get the user I am "running as"
        $me = $mgr->me();

        $ext = (string) new File_Extension($filename);

        $this->thumb_filename = preg_replace("/\.".$ext."$/", ".jpg", $filename);

        $fixed_name = preg_replace("/\.$ext$/",'.pdf',$filename);

        $full_name = $webapp_location . "/user_images/$me->uuid/$fixed_name";

        $full_thumb = $webapp_location . "/user_images/$me->uuid/$this->thumb_filename";

        // don't create if already exists!
        if ( ! file_exists( $full_thumb)){

            $cmd = " convert $full_name".'[0]'." $full_thumb 2>&1";

            $command = new \xo_shell_command($cmd);

            $command->execute();

        } else {
        }

        $this->success = true;


    }

    /**
     * @return string the thumb filename
     *
     */
    public function thumb_filename(){ return $this->thumb_filename; }
    public function success(){ return $this->success; }
}
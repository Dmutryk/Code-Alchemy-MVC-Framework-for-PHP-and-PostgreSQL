<?php

/**
 * Class file_upload encapsulates the file upload process
 */
class file_upload  {

    /**
     * Constant to allow each type
     */
    const ALLOW_IMAGE = 1;
    const ALLOW_VIDEO = 2;
	const ALLOW_OFFICE = 3;
	const ALLOW_EXCEL = 4;
	const ALLOW_ALL = 5;

    /**
     * @var string name to reference in $_FILES
     */
    private $name = '';

	/**
	 * @var string Original Filename
	 */
	public $original_filename = '';

    /**
     * @var string File extension for uploaded file
     */
    private $file_extension = '';

    /**
     * @var string New name for file, once placed
     */
    public $new_name = '';

    /**
     * @var string Error while uploading, if any
     */
    public $error = '';

    /**
     * @var bool true if the newly uploaded file exists
     */
    public $exists = false;

	/**
	 * @var int Allowed type
	 */
	private $allow = 0;

    /**
     * @var array of acceptable extensions for each type
     */
    private static $extensions = array (
	
		1 => array ( "jpeg", "JPEG" , "jpg", "JPG", "png", "PNG" , "gif", "GIF" ),

		2 => array ( "mpeg", "MPEG" , "mpg", "MPG", "swf", "SWF" , "avi", "AVI" ),

			3 => array(  'xls', 'xlsx', 'XLSX', 'Xlsx','doc','docx','pdf','ppt','pptx','odt','ods','rar' ),

		4 => array( 'xlsx'),


	
	);

	/**
	 * @var array Allowed name for error messages
	 */
	private $allowed_name = array(

		1 => "Images, such as Gif, Jpeg or Png",

		2 => "Movie or video files, such as Mpeg or Avi",

		3 => "Microsoft Office or Open Office Documents (all types)",

		4 => "Microsoft Excel Files (.XLSX) only"
	);

    /**
     * Create the File Uploader
     * @param string $name of $_FILES[] element to reference
     * @param string $new_name to apply to uploaded file
     * @param int $allowed_types bitflags to indicate allowed types
     */
    public function __construct( $name, $new_name = null, $allowed_types = 2 ) {

		$this->allow = $allowed_types;

		// save name and target
		if ( isset( $_FILES[$name]['name'] )) {

            // Save Name
			$this->name = $name;

            // Get file extension
			$filename = $_FILES[$name]['name'];

			$this->original_filename = $filename;

			$this->file_extension = substr(strrchr(is_array($filename)?$filename[0]:$filename,'.'),1);

            // Set new name
			$this->new_name = ( $new_name ) ? $new_name : (string) new \Code_Alchemy\Core\Random_Password( 10 ) . "." . $this->file_extension;
			
		} else {

			$this->error = "$name: No such file uploaded";

		}
		
	}

    /**
     * Move the uploaded file to the given location
     * @param string $directory to move it
     * @return bool true if successfully moved
     */
    public function move_to( $directory ) {

		// Control type
		$allowed = $this->allow == self::ALLOW_ALL ?
				array_merge(self::$extensions[1],self::$extensions[2],self::$extensions[3]):

				self::$extensions[$this->allow];

		if ( ! in_array(strtolower($this->file_extension), $allowed)){

			$this->error = "That file type ($this->file_extension) is not allowed.  Allowed types: "

			.$this->allowed_name[ $this->allow ]
			;

			return false;

		}

        // Set new file path
		$new_file = $directory."/".$this->new_name;

        // If moved
		$filename = is_array($_FILES[$this->name]['tmp_name'])?$_FILES[$this->name]['tmp_name'][0]:$_FILES[$this->name]['tmp_name'];

		if( ! @move_uploaded_file($filename, $new_file)) {

			$this->error = "Unable to create file $new_file from $filename";

			return false;

		} else return true;
	}


}

?>
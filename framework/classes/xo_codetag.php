<?php
/** a codetag is used to track a specific location in code for debugging, events and exceptions
 *
 * @property string $event_format the codetag in a format suitable for event logging
 * @property string $exception_format the codetag in a format suitable to throw an exception
 */
class xo_codetag extends magic_object {

    /**
     * @var string format of tag for Firebug (FirePHP)
     */
    public $firebug_format = '';

    // save the last action timestamp;
    private static $last = 0;
	//! construct given all the usual suspects
	public function __construct(
		$filename,		// the name of file where it happened
		$line,			// the line where it happened
		$class,			// name of class
		$method		// name of method or function
	) {
        //if ( ! self::$last) self::$last = time();
		// save all
		$this->filename = xo_basename($filename);
		$this->line = $line;
		$this->class = $this->set_class($class);
		$this->method = $method;

        $this->set_firebug_format();
		
	}

    /**
     * Set the classname, stripping away namespaces
     * @param $class
     * @return mixed
     */
    private function set_class( $class){

        $arr = explode('\\',$class);

        if ( count($arr)) $class = $arr[(count($arr)-1)];

        return $class;

    }

    /**
     * Set the Firebug format for the Tag
     */
    private function set_firebug_format(){

        $now = time();

        $date = date('H:i:s.u',microtime(true));

        $date2 = new DateTime('now');

        $format = $date2->format('H:i:s');

        $this->firebug_format = "[ $format ][ $this->filename ][ $this->line ][ $this->class{} ][ $this->method" . "() ]";
        $diff = $now - self::$last;
        self::$last = $now;

    }

	//! magic get
	public function __get($what){
        global $container;
        self::$last = time();
		switch( $what ) {
			case 'app_event_format':
				$date = date('Y-m-d H:i:s');
				return "";
			break;
	
			case 'mem_usage': return number_format(((memory_get_usage()/1024)/1024),2)."MB "; break;
			case 'debug_format':
			case 'event_format':
                $now = time();
				$date = date($what == 'event_format'?'M jS, Y H:i:s':'H:i:s',microtime(true));
                $tag = "<span style='font-size: 10pt; float: left; margin-left: 5px;font-family: arial; letter-spacing: 0.5px;'>
                <span style='float:left'>[ $date ] </span>
                <span style='float:left'>[ <span style='color:green;min-width: 200px;display:inline-block'>$this->filename</span> ] </span>
                <span style='float:left'>[ $this->line ] </span>
                <span style='float:left'>[ <span style='color:blue;font-weight:bold;min-width: 220px;display: inline-block'>$this->class{}</span> ] </span>
                <span style='float:left'>[ <span style='color:orange;font-weight:bold'>$this->method" . "()</span> ] </span>
                </span> ";
                $diff = $now - self::$last;
                //if ( $container->performance_tracking )$tag ="<br>\r\n $diff seconds since last action $now - ".self::$last."<br>\r\n$tag";
                self::$last = $now;
                return $tag;
			break;
			case 'exception_format':
				return "$this->filename [ $this->line ] $this->class{}::$this->method"."(): An Application Exception has been thrown: ";
			break;
			default:
				return parent::__get($what);
			break;
		}
	}
	
	/**
	 * create a new codetag.  this method is great for chaining commands.
	 *
	 * @param $filename the name of the file
	 * @param $line the line number
	 * @param $class the name of the class
	 * @param $method the name of the method
	 * @return object returns the new codetag
	 */
	 public static function create( $filename,$line,$class,$method){ return new xo_codetag($filename,$line,$class,$method); }
	
/**
	 * create a new codetag from a JSON string.  this method is best for calls from jquery/javascript.
	 *
	 * @param $json the json string
	 * @return object returns the new codetag
	 */
	public static function from_json( $jsonstr ){
		echo $jsonstr;
		$obj = json_decode( $jsonstr );
		return new xo_codetag($obj->filename,$obj->line,$obj->class,$obj->method);
	}

    /**
     * Format and return a string with the user's custom message
     * @param string $message to show
     * @return string the formatted message
     */
    public function format_message($message){
        return "<p style='float:left;width: 100%;margin: 2px 5px;font-size: 8pt;'>$this->event_format: $message</p>";
    }
	 
}

?>
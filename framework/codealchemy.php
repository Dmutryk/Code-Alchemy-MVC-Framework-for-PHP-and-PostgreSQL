<?php
namespace Code_Alchemy;
date_default_timezone_set('America/New_York');

use Code_Alchemy\Applications\Toolboxes\Command_Line;
use Code_Alchemy\Applications\Toolboxes\Helpers\Text_Colorizer;
use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Helpers\Code_Alchemy_Root_Path;

ob_start();

// bootstrap Code_Alchemy
require_once( "include/bootstrap.codealchemy.php");

// grab container
$container = Code_Alchemy_Framework::instance();

// Always display the banner
echo (string) new Text_Colorizer("Code Alchemy: Web programming transformed.  Open Source under the MIT Open Source License Agreement.
Portions (c) 2009 - 2016 Alquemedia SAS, David Greenberg, all rights reserved.
Third party components may be subject to copyright and/or restrictions in use, as per their published license agreement.
Inquiries and comments: <info@alquemedia.com>.\r\n\r\n",'yellow');

// get the user command
$cmd = @$argv[1];

// Set some colors
$themeize = (string) new Text_Colorizer('themeize','light_red');

$angularjsize = (string) new Text_Colorizer('angularjsize','green');

$ng = (string) new Text_Colorizer('ng','cyan');

$root_path = (string) new Text_Colorizer((string)new Code_Alchemy_Root_Path(),'light_cyan');

echo "Code_Alchemy is located at ". $root_path ." and ";

echo "current working directory is ". (string) new Text_Colorizer(getcwd(),'brown')."\r\n\r\n";

// Usage string
$usage = "usage: codealchemy [command] [args] [options]\r\n
commands:\r\n
\twebapp\t\tcreate a web application
\tcreate\t\tcreate models, views and controllers
\t$themeize\tPrograms an HTML5 theme
\tadd\t\tadd components to your application
\tset\t\tmake settings changes and adjustments
\trefresh\t\tRefresh application, restore missing files
\ttools\t\tAdditional tools for programming your web application
\tinfo\t\tShow info about app and environment

global options:
\t--git=yes\t\tAdd files to the default Git repository
\t--overwrite=yes\t\tOverwrite existing files
\t--directory={path}\tUse application in an alternate directory

";

// die if no command, or not recognized
if ( ! $cmd  ) {
    die(
    $usage
    );
}


//var_dump($argv);

// get the xobjects root
preg_match( '/^(.*)codealchemy\.php$/',$argv[0],$matches);
$root = $matches[1];

$installer = new Command_Line( $argv );

// if this is a valid command
if ( method_exists($installer,$cmd) )

    // Run the command
    $installer->$cmd( @$argv[2], $root, $argv );

else die( $usage );

?>

<?

/**
 * This file sets up global harmoni options, includes important files,
 * and defines a few crucial functions.
 *
 * @version $Id: harmoni.inc.php,v 1.8 2003/06/26 23:36:48 gabeschine Exp $
 * @copyright 2003 
 * @package harmoni
 * @access public
 **/

/**
 * Define the global harmoni directory.
 * 
 * Define the global harmoni directory.
 * @const HARMONI The harmoni directory.
 **/
define("HARMONI",dirname(__FILE__)."/");

/**
 * The name of the services variable.
 * The name of the services variable.
 * @const SERVICES_OBJECT The name of the services variable.
 **/
define("SERVICES_OBJECT","__services__");

// load the services
require_once(HARMONI."services/Services.class.php");

/**
 * The global Services object.
 * The global Services object.
 * @global object Services $__services__ The global Services object.
 **/
$__services__ =& new Services();

// load the Services registration config file
require_once(HARMONI."config/services.cfg.php");
<?php

/**
 * The Context class provides easy access to variables for action scripts and classes. 
 *
 * @package harmoni.architecture
 * @version $Id: Context.class.php,v 1.3 2003/07/22 22:05:46 gabeschine Exp $
 * @copyright 2003 
 **/
class Context {
	/**
	 * @access public
	 * @var string $sid The session name & id: "name=id"
	 **/
	var $sid;
	
	/**
	 * @access public
	 * @var string $myURL The current script's URL
	 **/
	var $myURL;
	
	/**
	 * @access public
	 * @var string $requestModule The module that was requested by the user.
	 **/
	var $requestModule;
	
	/**
	 * @access public
	 * @var string $requestAction The action requested by the user.
	 **/
	var $requestAction;
	
	/**
	 * @access public
	 * @var string $requestModuleDotAction The dotted-pair for module.action requested by the user.
	 **/
	var $requestModuleDotAction;
	
	/**
	 * The constructor
	 * @param string $module
	 * @param string $action
	 * @access public
	 * @return void
	 **/
	function Context($module, $action) {
		$this->sid = session_name() . "=" . session_id();
		$this->myURL = $_SERVER['PHP_SELF'];
		
		$this->requestAction = $action;
		$this->requestModule = $module;
		$this->requestModuleDotAction = "$module.$action";
	}
	
}

?>
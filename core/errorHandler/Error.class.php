<?php

require_once(HARMONI."errorHandler/Error.interface.php");

/**
 * An error class interface provides functionality to create Error objects 
 * to be used by the ErrorHandler
 *
 * @version $Id: Error.class.php,v 1.1 2003/08/14 19:26:30 gabeschine Exp $
 * @package harmoni.errorhandler
 * @copyright 2003 
 **/

class Error extends ErrorInterface {
    
    var $_description;
    var $_type;
    var $_isFatal;

	// the backtrace information (call hierarchy) of the error.
	var $_debugBacktrace;

	/**
	 * The constructor. Create a new error.
	 * @param string $description A description of the error.
	 * @param string $type The type of error. For sorting purposes. ie.: "user", "db", etc.
	 * @param boolean $isFatal True if the error should halt execution.
	 * @access public
	 */

    function Error($description,$type = "",$isFatal = false) {
		$this->_description = $description;
		$this->_type = $type;
		$this->_isFatal = $isFatal;
		$this->_debugBacktrace = getDebugBacktrace;
//		array_shift($this->_debugBacktrace);
    }
    
    /**
     * Gets a string description of the error.
     * @return string Description of the error.
     * @access public
     */

	function getDescription() {
		return $this->_description;
    }

    /**
     * Gets the type of the error.
     * @return string Type of the error.
     * @access public
     */
    function getType() {
		return $this->_type;	
	}	

    /**
     * Whether the execution of the scirpt should be halted after this error has occured.
     * @return boolean True if the execution should be halted.
     * @access public
     */
	function isFatal() {
		return $this->_isFatal;
	}

    /**
     * Gets the debug backtrace information for the error.
     * @return The debug backtrace information the way it is stored by the debug_backtrace() function.
     * @access public
     */
    function getDebugBacktrace() { 
		return $this->_debugBacktrace;
	}    

}

?>
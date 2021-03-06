<?php

/**
 * The purpose of a URLWriter is to generate URLs from contextual data. This
 * data would be the current/target module and action, any contextual name=value
 * pairs specified by the code, and any additional query data.
 * 
 * @package harmoni.architecture.request
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: URLWriter.abstract.php,v 1.10 2008/02/19 14:53:44 adamfranco Exp $
 */

abstract class URLWriter 
	extends SObject
{

	var $_module;
	var $_action;
	var $_vars;
	
	function __construct($base = null) {
		if (is_null($base))
			$this->_base = MYURL;
		else
			$this->_base = $base;
		$this->_vars = array();
		$this->_module = "";
		$this->_action = "";
	}

	/**
	 * Sets the module and action to request in this URL.
	 * @param string $module
	 * @param string $action
	 * @return void
	 * @access public
	 */
	function setModuleAction($module, $action) {
		$this->_module = $module;
		$this->_action = $action;
	}
	
	/**
	 * Answer the module
	 * 
	 * @return string
	 * @access public
	 * @since 12/3/07
	 */
	public function getModule () {
		if (!strlen($this->_module))
			throw new HarmoniException("No Module set.");
		
		return $this->_module;
	}
	
	/**
	 * Answer the action
	 * 
	 * @return string
	 * @access public
	 * @since 12/3/07
	 */
	public function getAction () {
		if (!strlen($this->_action))
			throw new HarmoniException("No Action set.");
		
		return $this->_action;
	}
	
	/**
	 * Takes an associative array of name/value pairs and sets the internal data
	 * to those values. The method is used internally by the {@link RequestContext}
	 * only and should not be called otherwise.
	 * @param array $array
	 * @access private
	 * @return void
	 */
	function batchSetValues($array) {
		$toIgnore =  array(
			"module", "action"
		);
		$toAllow[] = array();
		
		// Ensure that the session id does not get into the url if 
		// configured to use only cookis for sessions.
		$harmoni = Harmoni::instance();
		if ($harmoni->config->get("sessionUseOnlyCookies") === true) {
			// If we have a configuration for Actions which allow the session id
			// to be passed in the URL, then check for those actions.
			if ($harmoni->config->get("sessionInUrlActions") 
				&& is_array($harmoni->config->get("sessionInUrlActions"))
				&& count ($harmoni->config->get("sessionInUrlActions")))
			{
				
				// If our current action is in the allowed list.
				if (in_array($this->getModule().".".$this->getAction(),
						$harmoni->config->get("sessionInUrlActions"))) 
				{
					$toAllow[] = $harmoni->config->get("sessionName");
				} else {
					$toIgnore[] = $harmoni->config->get("sessionName");
				}
			
			} else {
				$toIgnore[] = $harmoni->config->get("sessionName");
			}
		}
		
		// Add any cookie keys to the ignore list to prevent the addition of
		// __utma and __utmz cookies from Google Analytics among others.
		foreach (array_keys($_COOKIE) as $key) {
			if (!in_array($key, $toAllow))
				$toIgnore[] = $key;
		}
		
		foreach ($array as $key=>$val) {
			if (!in_array($key, $toIgnore))
				$this->_vars[$key] = $val;
		}	
	}

	/**
	 * Takes an associative array of name/value pairs and sets the internal
	 * data to those values, replacing any values that already exist.
	 * @param array $array An associative array.
	 * @return void
	 * @access public
	 */
	function setValues($array) {
		foreach ($array as $key=>$val) {
			$this->setValue($key, $val);
		}
	}
	
	/**
	 * Sets a single value in the internal data.
	 * @param string $key
	 * @param string $value
	 * @return void
	 * @access public
	 */
	function setValue($key, $value) {
		$key = RequestContext::name($key);
		if (is_null($value))
			unset($this->_vars[$key]);
		else
			$this->_vars[$key] = $value;
	}
	
	/**
	 * Move a value to the beginning of the values array if it is set.
	 * 
	 * @param string $key
	 * @return void
	 * @access public
	 * @since 8/22/08
	 */
	public function moveValueToBeginning ($key) {
		$key = RequestContext::name($key);
		if (isset($this->_vars[$key])) {
			$newVars = array();
			
			// Add it first.
			$newVars[$key] = $this->_vars[$key];
			
			// Add the rest after it.
			foreach ($this->_vars as $key2 => $val) {
				if ($key2 != $key)
					$newVars[$key2] = $val;
			}
			
			$this->_vars = $newVars;
		}
	}
	
	/**
	 * Calls {@link RequestContext::locationHeader} with this URL.
	 *
	 * @return void
	 **/
	function redirectBrowser()
	{
		$harmoni = Harmoni::instance();
		$harmoni->request->locationHeader($this->write());
	}
	
	/** 
	 * The following function has many forms, and due to PHP's lack of
	 * method overloading they are all contained within the same class
	 * method. Any keys/names for paremeters should be translated into
	 * their contextual equivalents by contacting the {@link RequestContext}
	 * object.
	 * 
	 * write()
	 * write(array $vars)
	 * write(string $key1, string $value1, [string $key2, string $value2, [...]])
	 * 
	 * @access public
	 * @return string The URL. 
	 */
	abstract function write(/* variable-length argument list*/);
	
}

?>
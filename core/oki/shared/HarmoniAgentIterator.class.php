<?
/**
 * @package harmoni.osid_v1.shared
 */

require_once(OKI."/shared.interface.php");

// public static final String NO_MORE_ITERATOR_ELEMENTS = "Iterator has no more elements "
define("NO_MORE_ITERATOR_ELEMENTS","Iterator has no more elements ");

/**
 *
 * @package harmoni.osid_v1.shared
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: HarmoniAgentIterator.class.php,v 1.6 2005/02/04 15:59:07 adamfranco Exp $
 */
class HarmoniAgentIterator
	extends AgentIterator
{ // begin AgentIterator

	/**
	 * @var array $_agents The stored agents.
	 * @access private
	 */
	var $_agents = array();
	 
	/**
	 * @var int $_i The current posititon.
	 * @access private
	 */
	var $_i = -1;
	
	/**
	 * Constructor
	 */
	function HarmoniAgentIterator (& $agentArray) {
		// make sure that we get an array of Agent objects
		ArgumentValidator::validate($agentArray, new ArrayValidatorRuleWithRule(new ExtendsValidatorRule("Agent")));
		
		// load the agents into our private array
		foreach (array_keys($agentArray) as $i => $key) {
			$this->_agents[] =& $agentArray[$key];
		}
	}

	// public boolean hasNext();
	function hasNext() {
		return ($this->_i < count($this->_agents)-1);
	}

	// public Agent & next();
	function &next() {
		if ($this->hasNext()) {
			$this->_i++;
			return $this->_agents[$this->_i];
		} else {
			throwError(new Error(NO_MORE_ITERATOR_ELEMENTS, "AgentIterator", 1));
		}
	}

} // end AgentIterator




?>
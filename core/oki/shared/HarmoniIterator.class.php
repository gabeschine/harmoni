<?
/**
 * @package harmoni.osid_v1.shared
 */

require_once(OKI."/shared.interface.php");

// public static final String NO_MORE_ITERATOR_ELEMENTS = "Iterator has no more elements "
define("NO_MORE_ITERATOR_ELEMENTS","Iterator has no more elements ");

/**
 * A class for passing an arbitrary input array as an iterator.
 *
 * @package harmoni.osid_v1.shared
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: HarmoniIterator.class.php,v 1.7 2005/02/04 15:59:07 adamfranco Exp $
 */

class HarmoniIterator
{ // begin HarmoniIterator

	/**
	 * @var array $_elements The stored elements.
	 * @access private
	 */
	var $_elements = array();
	 
	/**
	 * @var int $_i The current posititon.
	 * @access private
	 */
	var $_i = -1;
	
	/**
	 * Constructor
	 */
	function HarmoniIterator (& $elementArray) {
		// load the elements into our private array
		foreach (array_keys($elementArray) as $i => $key) {
			$this->_elements[] =& $elementArray[$key];
		}
	}

	// public boolean hasNext();
	function hasNext() {
		return ($this->_i < count($this->_elements)-1);
	}

	// public Type & next();
	function &next() {
		if ($this->hasNext()) {
			$this->_i++;
			return $this->_elements[$this->_i];
		} else {
			throwError(new Error(NO_MORE_ITERATOR_ELEMENTS, "HarmoniIterator", 1));
		}
	}

} // end HarmoniIterator

?>
<?

require_once(OKI."/shared.interface.php");

if (!isset($_SESSION[harmoniid])) {
	$_SESSION[harmoniid] = 1;
}

/**
  *
  * @package harmoni.osid_v1.shared
  * 
  * @copyright Copyright &copy; 2005, Middlebury College
  * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
  *
  * @version $Id: HarmoniTestId.class.php,v 1.7 2005/03/29 19:44:21 adamfranco Exp $
  */
class HarmoniTestId
	extends Id

	// extends java.io.Serializable

{ // begin Id

	/**
	 * @var string $_id The id of this id.
	 */
	 var $_id;
	
	/**
	 * Constructor. Creates a HarmoniId with id = $id or a new unique id if $id is NULL.
	 * @param string $id The desired id. If NULL, a new unique id is used.
	 *
	 */
	function HarmoniTestId ( $id = NULL ) {
		if ($id != NULL) {
			// use this id
			ArgumentValidator::validate($id, StringValidatorRule::getRule());
			$this->_id = $id;
		} else {
			// get a new unique id
			// for testing purposes, we are just going to store a counter for the session.
			// this won't work once we start saving things to persistable storage.
			$this->_id = "harmoni.".$_SESSION[harmoniid];
			$_SESSION[harmoniid]++;
		}
	}

	// public String getIdString();
	function getIdString() {
		return $this->_id;
	}

	// public boolean isEqual(osid.shared.Id & $id);
	function isEqual(& $id) {
		return ($id->getIdString() == $this->_id)?true:false;
	}

} // end Id

?>
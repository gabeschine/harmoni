<?

require_once HARMONI."dataManager/search/SearchCriteria.interface.php";
/**
 * An abstract class on which to build Criteria that are made up of multiple other search criteria objects.
 *
 * @package harmoni.datamanager.search
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: MultipleCriteriaSearch.abstract.php,v 1.3 2005/01/19 21:09:43 adamfranco Exp $
 * @abstract
 */
class MultipleCriteriaSearch extends SearchCriteria {
	
	var $_criteria;
	
	function addCriteria (&$criteria) {
		if (!is_array($this->_criteria)) {
			$this->_criteria = array();
		}
		
		$this->_criteria[] =& $criteria;
	}
	
}
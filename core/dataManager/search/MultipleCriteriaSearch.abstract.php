<?

require_once HARMONI."dataManager/search/SearchCriteria.interface.php";
/**
 * An abstract class on which to build Criteria that are made up of multiple other search criteria objects.
 * @package harmoni.datamanager.search
 * @version $Id: MultipleCriteriaSearch.abstract.php,v 1.1 2004/07/27 20:23:43 gabeschine Exp $
 * @copyright 2004, Middlebury College
 * @abstract
 */
class MultipleCriteriaSearch extends SearchCriteria {
	
	var $_criteria;
	
	function addCriteria (&$criteria) {
		if (!is_array($this->_criteria)) {
			$this->_criteria = array();
		}
		
		$this->_criteria[] = $criteria;
	}
	
}
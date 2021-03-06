<?php

require_once(dirname(__FILE__)."/HarmoniAgent.class.php");

/**
 * The AnonymousAgent has Id "0" and can refer to a non-logged in user.
 * 
 * <p>
 * OSID Version: 2.0
 * </p>
 *
 * @package harmoni.osid_v2.agent
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: AnonymousAgent.class.php,v 1.5 2007/09/13 16:04:17 adamfranco Exp $
 */
class AnonymousAgent 
	extends HarmoniAgent
{
	/**
	 * Constructor
	 * 
	 * @param integer dbIndex The database connection as returned by the DBHandler.
	 * @return object
	 * @access public
	 * @since 2/8/05
	 */
	function AnonymousAgent ($dbIndex) {
		$idManager = Services::getService("Id");
		$id =$idManager->getId("edu.middlebury.agents.anonymous");
		
		$type = new Type("Agents", "edu.middlebury.harmoni", "Any/Anonymous", 
			_("Special users that can represent anyone or unknown users."));
		
		$propertiesArray = array();
// 		$propertiesType = new HarmoniType('Agents', 'Harmoni', 'Agent Properties',
// 						'Properties known to the Harmoni Agents System.');
// 		$propertiesArray[0] = new HarmoniProperties($propertiesType);
		
		$this->HarmoniAgent(	_("Anonymous"),
								$id,
								$type,
								$propertiesArray,
								$dbIndex);
	}
}


?>
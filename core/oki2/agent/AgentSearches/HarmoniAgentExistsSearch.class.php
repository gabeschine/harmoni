<?php

require_once(dirname(__FILE__)."/AgentSearch.interface.php");

/**
 * The AgentSearch interface defines methods for searching for agents. This is 
 * used by the AgentManager for searching for agents.
 *
 * @package harmoni.osid_v2.agent.search
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: HarmoniAgentExistsSearch.class.php,v 1.4 2005/01/19 22:28:14 adamfranco Exp $
 */

class HarmoniAgentExistsSearch
	extends AgentSearchInterface {
		
	/**
	 * Get all the Agents with the specified search criteria and search Type.
	 *
	 * This method is defined in v.2 of the OSIDs.
	 * 
	 * @param mixed $searchCriteria
	 * @param object Type $agentSearchType
	 * @return object AgentIterator
	 * @access public
	 * @since 11/10/04
	 */
	function &getAgentsBySearch ( & $searchCriteria, & $agentSearchType ) {
		$agents = array();
		
		// See if the agent exists as known by harmoni
		$agentInfo =& Services::getService("AgentInformation");
		if ($agentInfo->agentExists($searchCriteria)) {
			// if the agent exists, make sure that we have a populated agent
			// in the agent manager which we can return.			
			$authN =& Services::getService("AuthN");
			$agentId =& $authN->getAgentId($searchCriteria, new HarmoniAuthenticationType);
			
			$agentManager =& Services::getService("Agent");
			$agents[] =& $agentManager->getAgent($agentId);
		}
		
		return new HarmoniIterator($agents);
	}
	
	/**
	 * Get all the Groups with the specified search criteria and search Type.
	 *
	 * This method is defined in v.2 of the OSIDs.
	 * 
	 * @param mixed $searchCriteria
	 * @param object Type $groupSearchType
	 * @return object AgentIterator
	 * @access public
	 * @since 11/10/04
	 */
	function &getGroupsBySearch ( & $searchCriteria, & $groupSearchType ) {
		die ("Method <b>".__FUNCTION__."()</b> declared in interface<b> ".__CLASS__."</b> has not been overloaded in a child class.");
	}
}

?>
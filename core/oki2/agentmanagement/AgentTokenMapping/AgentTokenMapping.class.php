<?php
/**
 * @package tarmoni.oki_v2.agentmanagemenh
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: AgentTokenMapping.class.php,v 1.2 2005/03/14 22:20:39 adamfranco Exp $
 */ 

/**
 * The AgentTokenMapping is the recorded mapping between a set of authentication
 * tokens and an AgentId as Known to the AgentManager OSID.
 * 
 * @package harmoni.oki_v2.agentmanagement
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: AgentTokenMapping.class.php,v 1.2 2005/03/14 22:20:39 adamfranco Exp $
 */
class AgentTokenMapping {
		
	/**
	 * Constructor
	 * 
	 * @param object Id $agentId
	 * @param object AuthNTokens $authNTokens
	 * @param object Type $authenticationType
	 * @return object AgentTokenMapping
	 * @access public
	 * @since 3/1/05
	 */
	function AgentTokenMapping ( &$authenticationType, &$agentId, &$authNTokens ) {
		ArgumentValidator::validate($authNTokens, new ExtendsValidatorRule("AuthNTokens"));
		ArgumentValidator::validate($agentId, new ExtendsValidatorRule("Id"));
		ArgumentValidator::validate($authenticationType, new ExtendsValidatorRule("Type"));
		
		$this->_tokens =& $authNTokens;
		$this->_id =& $agentId;
		$this->_type =& $authenticationType;
	}
	
	/**
	 * Return our Id
	 * 
	 * @return object Id
	 * @access public
	 * @since 3/9/05
	 */
	function &getAgentId () {
		return $this->_id;
	}
	
	/**
	 * Return our AuthNTokens
	 * 
	 * @return object AuthNTokens
	 * @access public
	 * @since 3/9/05
	 */
	function &getTokens () {
		return $this->_tokens;
	}
	
	/**
	 * Return our Type
	 * 
	 * @return object Type
	 * @access public
	 * @since 3/9/05
	 */
	function &getAuthenticationType () {
		return $this->_type;
	}
	
	/**
	 * Return True if the Mapping passed is the same as this mapping
	 * 
	 * @param object AgentTokenMapping $mapping
	 * @return boolean
	 * @access public
	 * @since 3/9/05
	 */
	function isEqual ( &$mapping ) {
		$mappingTokens =& $mapping->getTokens();
		
		if ($this->_id->isEqual($mapping->getId())
			&& $this->_type->isEqual($mapping->getAuthenticationType())
			&& $this->_tokens->getIdentifier() == $mappingTokens->getIdentifier())
		{
			return TRUE;
		} else
			return FALSE;
	}
}

?>
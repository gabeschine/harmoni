<?php

require_once(HARMONI."authorizationHandler/HierarchicalAuthorizationContext.interface.php");


/** 
 * An implementation of a hierarchical authorization context.
 * 
 * A hierarchical authorization context describes the 
 * context in which the authorization function will be authorized. A hierarchical 
 * authorization structure resembles a tree structure - a certain
 * unit described by an authorization context can have zero or one parents and 
 * zero or more children. The unit inherits permissions from its parent unit.
 * Similarly, all its children inherit from its permissions. It stores
 * several properties:
 * <br><br>
 * The <b>system</b> narrows the location on a high level. For example,
 * this could be the application name, i.e. 'segue'.
 * <br><br>
 * The <b>subsystem</b> narrows the location within the specific system. For
 * example, in the 'segue' system, we could have two subsystems: 'siteunit' subsystem
 * dealing with user permissions of site, section, page, and story objects, and a
 * 'discussion' subsystem dealing with discussion permissions of users within a specific
 * story.
 * <br><br>
 * The <b>hierarchy depth</b> narrows the location within the subsystem to a specific
 * level in the hierarchy. For example, in the 'segue' system and 'siteunit' subsystem,
 * the hierarchy depth could be either 0, 1, 2, or 3. These would correspond to 'site', 
 * 'section', 'page', and 'story'. The hierarchy depth is always 0 for the topmost
 * level and goes up to <code>h - 1</code>, where <code>h</code> is the height of
 * the tree structure.
 * <br><br>
 * The <b>ancestor system ids</b> is an ordered set of ids for all ancestors (parents) of
 * the context. The first element of the set is the system id of the parent of the
 * context. The second element, is the parent of the parent, and so forth. If the
 * context has no parents, then the set would be empty.
 * <br><br>
 * The <b>system id</b> narrows the location to a specific unit, which we are trying
 * to authorize. For example, this could be a primary key in a database table.
 * <br><br>
 * Thus, in a hierarchical authorization scheme, four things need to be specified
 * in order to define the context in which authorization is to be performed:
 * <br>
 * <b>system -> subsystem -> hierarchy depth -> context id</b>
 * 
 * @access public
 * @version $Id: HierarchicalAuthorizationContext.class.php,v 1.1 2003/06/30 20:41:44 dobomode Exp $
 * @author Middlebury College, ETS
 * @copyright 2003 Middlebury College, ETS
 * @date Created: 6/29/2003
 * @package harmoni.authorizationHandler
 */
class HierarchicalAuthorizationContext extends HierarchicalAuthorizationContextInterface {

	/**
	 * The system of this context.
	 * <br><br>
	 * The <b>system</b> narrows the location on a high level. For example,
	 * this could be the application name, i.e. 'segue'.
	 * @attribute private string _system
	 */
	var $_system;
	
	/**
	 * The subsystem of this context.
	 * <br><br>
	 * The <b>subsystem</b> narrows the location within the specific system. For
	 * example, in the 'segue' system, we could have two subsystems: 'siteunit' subsystem
	 * dealing with user permissions of site, section, page, and story objects, and a
	 * 'discussion' subsystem dealing with discussion permissions of users within a specific
	 * story.
	 * @attribute private string _subsystem
	 */
	var $_subsystem;
	
	
	/**
	 * The hierarchy depth of this context.
	 * <br><br>
	 * The <b>hierarchy depth</b> narrows the location within the subsystem to a specific
	 * level in the hierarchy. For example, in the 'segue' system and 'siteunit' subsystem,
	 * the hierarchy depth could be either 0, 1, 2, or 3. These would correspond to 'site', 
	 * 'section', 'page', and 'story'. The hierarchy depth is always 0 for the topmost
	 * level and goes up to <code>h - 1</code>, where <code>h</code> is the height of
	 * the tree structure.
	 * @attribute private integer _hierarchyDepth
	 */
	var $_hierarchyDepth;
	
	/**
	 * The ancestor systems ids of this context.
	 * <br><br>
	 * The <b>ancestor system ids</b> is an ordered set of ids for all ancestors (parents) of
	 * the context. The first element of the set is the system id of the parent of the
	 * context. The second element, is the parent of the parent, and so forth. If the
	 * context has no parents, then the set would be empty.
	 * @attribute private array _ancestorSystemIds
	 */
	var $_ancestorSystemIds;
	
	
	
	/**
	 * The system id of this context.
	 * <br><br>
	 * The <b>system id</b> narrows the location to a specific unit, which we are trying
	 * to authorize. For example, this could be a primary key in a database table.
	 * @attribute private integer _systemId
	 */
	var $_systemId;
	
	
	/**
	 * The constructor just takes all the properties and returns a new
	 * HierarchicalAuthorizationContext object.
	 * @param string system The system of this context.
	 * @param string subsystem The subsystem of this context.
	 * @param integer hierarchyDepth The hierarchy depth of this context.
	 * @param array ancestorSystemIds The ancestor systems ids of this context.
	 * @param integer systemId The system id of this context.
	 * @access public
	 */
	function HierarchicalAuthorizationContext($system, $subsystem, $hierarchyDepth,
											  $ancestorSystemIds, $systemId) {
		// ** parameter validation
		$stringRule =& new StringValidatorRule();
		$integerRule =& new IntegerValidatorRule();
		$integerArrayRule =& new ArrayValidatorRuleWithRule($integerRule);
		ArgumentValidator::validate($system, $stringRule, true);
		ArgumentValidator::validate($subsystem, $stringRule, true);
		ArgumentValidator::validate($hierarchyDepth, $integerRule, true);
		ArgumentValidator::validate($ancestorSystemIds, $integerArrayRule, true);
		ArgumentValidator::validate($systemId, $integerRule, true);
		// ** end of parameter validation

		$this->_system = $system;
		$this->_subsystem = $subsystem;
		$this->_hierarchyDepth = $hierarchyDepth;
		$this->_ancestorSystemIds = $ancestorSystemIds;
		$this->_systemId = $systemId;
	}


	/**
	 * Returns the system of this authorization context.
	 * <br><br>
	 * The <b>system</b> narrows the location on a high level. For example,
	 * this could be the application name, i.e. 'segue'.
	 * @method public getSystem
	 * @return string The system of this authorization context.
	 */
	function getSystem() {
		return $this->_system;
	}
	

	/**
	 * Returns the subsystem of this authorization context.
	 * <br><br>
	 * The <b>subsystem</b> narrows the location within the specific system. For
	 * example, in the 'segue' system, we could have two subsystems: 'siteunit' subsystem
	 * dealing with user permissions of site, section, page, and story objects, and a
	 * 'discussion' subsystem dealing with discussion permissions of users within a specific
	 * story.
	 * @method public getSubsystem
	 * @return string The subsystem of this authorization context.
	 */
	function getSubsystem() {
		return $this->_subsystem;
	}
	


	/**
	 * Returns the hierarchy depth of this authorization context.
	 * <br><br>
	 * The <b>hierarchy depth</b> narrows the location within the subsystem to a specific
	 * level in the hierarchy. For example, in the 'segue' system and 'siteunit' subsystem,
	 * the hierarchy depth could be either 0, 1, 2, or 3. These would correspond to 'site', 
	 * 'section', 'page', and 'story'. The hierarchy depth is always 0 for the topmost
	 * level and goes up to <code>h - 1</code>, where <code>h</code> is the height of
	 * the tree structure.
	 * @method public getHierarchyDepth
	 * @return string The hierarchy depth of this authorization context.
	 */
	function getHierarchyDepth() {
		return $this->_hierarchyDepth;
	}
	

	
	/**
	 * Returns the system ids of this context's parents.
	 * <br><br>
	 * The <b>ancestor system ids</b> is an ordered set of ids for all ancestors (parents) of
	 * the context. The first element of the set is the system id of the parent of the
	 * context. The second element, is the parent of the parent, and so forth. If the
	 * context has no parents, then the set would be empty.
	 * @method public getAncestorSystemIds
	 * @return array An array storing the ancestor system ids.
	 */
	function getAncestorSystemIds() {
		return $this->_ancestorSystemIds;
	}
	

	/**
	 * Returns the id of this authorization context.
	 * <br><br>
	 * The <b>system id</b> narrows the location to a specific unit, which we are trying
	 * to authorize. For example, this could be a primary key in a database table.
	 * @method public getSystemId
	 * @return string The id of this authorization context.
	 */
	function getSystemId() {
		return $this->_systemId;
	}
	
}


?>
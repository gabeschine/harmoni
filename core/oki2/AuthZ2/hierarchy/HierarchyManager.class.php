<?php

require_once(OKI2."/osid/hierarchy/HierarchyManager.php");
require_once(OKI2."/osid/hierarchy/HierarchyException.php");

require_once(dirname(__FILE__)."/Hierarchy.class.php");
require_once(dirname(__FILE__)."/HierarchyIterator.class.php");
require_once(dirname(__FILE__)."/NodeIterator.class.php");
require_once(dirname(__FILE__)."/TraversalInfoIterator.class.php");

require_once(HARMONI.'/oki2/id/HarmoniIdManager.class.php');

/**
 * <p>
 * HierarchyManager handles creating, deleting, and getting Hierarchies.
 * </p>
 * 
 * <p>
 * All implementations of OsidManager (manager) provide methods for accessing
 * and manipulating the various objects defined in the OSID package. A manager
 * defines an implementation of an OSID. All other OSID objects come either
 * directly or indirectly from the manager. New instances of the OSID objects
 * are created either directly or indirectly by the manager.  Because the OSID
 * objects are defined using interfaces, create methods must be used instead
 * of the new operator to create instances of the OSID objects. Create methods
 * are used both to instantiate and persist OSID objects.  Using the
 * OsidManager class to define an OSID's implementation allows the application
 * to change OSID implementations by changing the OsidManager package name
 * used to load an implementation. Applications developed using managers
 * permit OSID implementation substitution without changing the application
 * source code. As with all managers, use the OsidLoader to load an
 * implementation of this interface.
 * </p>
 * 
 * <p></p>
 * 
 * <p>
 * OSID Version: 2.0
 * </p>
 * 
 *
 * @package harmoni.osid_v2.hierarchy
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: HierarchyManager.class.php,v 1.1 2008/04/24 20:51:43 adamfranco Exp $
 */
class AuthZ2_HierarchyManager 
	implements HierarchyManager 
{


	/**
	 * The database connection as returned by the DBHandler.
	 * @var integer _dbIndex 
	 * @access protected
	 */
	var $_dbIndex;
	
	
	/**
	 * An array that will store all hierarchies and fulfil the function of a
	 * cache.
	 * @var array _hierarchies 
	 * @access private
	 */
	var $_hierarchies;
	
	/**
	 * TRUE if all hiearchies are cached.
	 * @var array _hierarchies 
	 * @access private
	 */
	var $_allHierarchiesCached;
	
	
	/**
	 * Constructor
	 * @param integer dbIndex The database connection as returned by the DBHandler.
	 * @param string hyDB The name of the hierarchy database.
	 * manager.
	 * @access public
	 */
	function __construct () {
		$this->_hierarchies = array();
		$this->_allHierarchiesCached = FALSE;
	}
	
	/**
	 * Assign the configuration of this Manager. Valid configuration options are as
	 * follows:
	 *	database_index			integer
	 *	database_name			string
	 * 
	 * @param object Properties $configuration (original type: java.util.Properties)
	 * 
	 * @throws object OsidException An exception with one of the following
	 *		   messages defined in org.osid.OsidException:	{@link
	 *		   org.osid.OsidException#OPERATION_FAILED OPERATION_FAILED},
	 *		   {@link org.osid.OsidException#PERMISSION_DENIED
	 *		   PERMISSION_DENIED}, {@link
	 *		   org.osid.OsidException#CONFIGURATION_ERROR
	 *		   CONFIGURATION_ERROR}, {@link
	 *		   org.osid.OsidException#UNIMPLEMENTED UNIMPLEMENTED}, {@link
	 *		   org.osid.OsidException#NULL_ARGUMENT NULL_ARGUMENT}
	 * 
	 * @access public
	 */
	function assignConfiguration ( Properties $configuration ) { 
		$this->_configuration =$configuration;
		
		$dbIndex =$configuration->getProperty('database_index');
		$dbName =$configuration->getProperty('database_name');
		
		// ** parameter validation
		ArgumentValidator::validate($dbIndex, IntegerValidatorRule::getRule(), true);
		ArgumentValidator::validate($dbName, StringValidatorRule::getRule(), true);
		// ** end of parameter validation
		
		$this->_dbIndex = $dbIndex;
		
		// Store the Harmoni_Db adapter if it is configured.
        $harmoni_db_name = $this->_configuration->getProperty('harmoni_db_name');
        if (!is_null($harmoni_db_name)) {
			try {
				$this->harmoni_db = Harmoni_Db::getDatabase($harmoni_db_name);
			} catch (UnknownIdException $e) {
			}
        }
        
        // do a test to see if our configuration worked.
        try {
			$dbHandler = Services::getService("DatabaseManager");
			
			$query = new SelectQuery();
			$query->addColumn("id");
			$query->addTable("az2_hierarchy");
			$query->limitNumberOfRows(1);
	
			$queryResult =$dbHandler->query($query, $this->_dbIndex);
	    } catch (QueryDatabaseException $e) {
	    	throw new ConfigurationErrorException("Database is not properly set up for AuthZ2.");
	    }
	}

	/**
	 * Return context of this OsidManager.
	 *	
	 * @return object OsidContext
	 * 
	 * @throws object OsidException 
	 * 
	 * @access public
	 */
	function getOsidContext () { 
		return $this->_osidContext;
	} 

	/**
	 * Assign the context of this OsidManager.
	 * 
	 * @param object OsidContext $context
	 * 
	 * @throws object OsidException An exception with one of the following
	 *		   messages defined in org.osid.OsidException:	{@link
	 *		   org.osid.OsidException#NULL_ARGUMENT NULL_ARGUMENT}
	 * 
	 * @access public
	 */
	function assignOsidContext ( OsidContext $context ) { 
		$this->_osidContext =$context;
	} 

	/**
	 * Create a Hierarchy.
	 * 
	 * @param string $displayName
	 * @param object Type[] $nodeTypes
	 * @param string $description
	 * @param boolean $allowsMultipleParents
	 * @param boolean $allowsRecursion
	 * @param optional object Id $id WARNING: NOT IN OSID
	 *	
	 * @return object Hierarchy
	 * 
	 * @throws object HierarchyException An exception with one of
	 *		   the following messages defined in
	 *		   org.osid.hierarchy.HierarchyException may be thrown:	 {@link
	 *		   org.osid.hierarchy.HierarchyException#OPERATION_FAILED
	 *		   OPERATION_FAILED}, {@link
	 *		   org.osid.hierarchy.HierarchyException#PERMISSION_DENIED
	 *		   PERMISSION_DENIED}, {@link
	 *		   org.osid.hierarchy.HierarchyException#CONFIGURATION_ERROR
	 *		   CONFIGURATION_ERROR}, {@link
	 *		   org.osid.hierarchy.HierarchyException#UNIMPLEMENTED
	 *		   UNIMPLEMENTED}, {@link
	 *		   org.osid.hierarchy.HierarchyException#NULL_ARGUMENT
	 *		   NULL_ARGUMENT}, {@link
	 *		   org.osid.hierarchy.HierarchyException#UNSUPPORTED_CREATION
	 *		   UNSUPPORTED_CREATION}
	 * 
	 * @access public
	 */
	function createHierarchy ( $displayName, array $nodeTypes, $description, $allowsMultipleParents, $allowsRecursion, Id $id = NULL ) { 
		// ** parameter validation
		ArgumentValidator::validate($description, StringValidatorRule::getRule(), true);
		ArgumentValidator::validate($displayName, StringValidatorRule::getRule(), true);
		ArgumentValidator::validate($allowsMultipleParents, BooleanValidatorRule::getRule(), true);
		ArgumentValidator::validate($allowsRecursion, BooleanValidatorRule::getRule(), true);
		ArgumentValidator::validate($id, OptionalRule::getRule(
			ExtendsValidatorRule::getRule("Id")), true);
		
		// ** end of parameter validation

		// check for supported hierarchies
		if ($allowsRecursion)
			throw new OperationFailedException("Unsuported creation. Does not support recursion.");
		
		$dbHandler = Services::getService("DatabaseManager");

		// Create an Id for the Hierarchy
		if (!is_object($id)) {
			$idManager = Services::getService("Id");
			$id =$idManager->createId();
		}
		$idValue = $id->getIdString();
		

		$query = new InsertQuery();
		$query->setTable("az2_hierarchy");
		$query->addValue("id", $idValue);
		$query->addValue("display_name", $displayName);
		$query->addValue("description", $description);
		$query->addValue("multiparent", ($allowsMultipleParents) ? '1' : '0');
		
		$queryResult =$dbHandler->query($query, $this->_dbIndex);
		
		// Create a new hierarchy and insert it into the database
		$cache = new AuthZ2_HierarchyCache($idValue, $allowsMultipleParents, $this->_dbIndex, 
		   		((isset($this->harmoni_db))?$this->harmoni_db:null));
		$cache->setAuthorizationManager($this->getAuthorizationManager());
		$hierarchy = new AuthZ2_Hierarchy($id, $displayName, $description, $cache);
		
		// then cache it
		$this->_hierarchies[$idValue] =$hierarchy;
		
		return $hierarchy;
	}

	
	/**
	 * Get a Hierarchy by unique Id.
	 * 
	 * @param object Id $hierarchyId
	 *	
	 * @return object Hierarchy
	 * 
	 * @throws object HierarchyException An exception with one of
	 *		   the following messages defined in
	 *		   org.osid.hierarchy.HierarchyException may be thrown:	 {@link
	 *		   org.osid.hierarchy.HierarchyException#OPERATION_FAILED
	 *		   OPERATION_FAILED}, {@link
	 *		   org.osid.hierarchy.HierarchyException#PERMISSION_DENIED
	 *		   PERMISSION_DENIED}, {@link
	 *		   org.osid.hierarchy.HierarchyException#CONFIGURATION_ERROR
	 *		   CONFIGURATION_ERROR}, {@link
	 *		   org.osid.hierarchy.HierarchyException#UNIMPLEMENTED
	 *		   UNIMPLEMENTED}, {@link
	 *		   org.osid.hierarchy.HierarchyException#NULL_ARGUMENT
	 *		   NULL_ARGUMENT}, {@link
	 *		   org.osid.hierarchy.HierarchyException#NODE_TYPE_NOT_FOUND
	 *		   NODE_TYPE_NOT_FOUND}
	 * 
	 * @access public
	 */
	function getHierarchy ( Id $hierarchyId ) { 

		$idValue = $hierarchyId->getIdString();
		
		// check the cache
		if (isset($this->_hierarchies[$idValue]))
			return $this->_hierarchies[$idValue];

		$dbHandler = Services::getService("DatabaseManager");
		
		$query = new SelectQuery();
		$query->addColumn("id");
		$query->addColumn("display_name");
		$query->addColumn("description");
		$query->addColumn("multiparent");
		$query->addTable("az2_hierarchy");
		$query->addWhereEqual("id", $hierarchyId->getIdString());

		$queryResult =$dbHandler->query($query, $this->_dbIndex);
		
		if ($queryResult->getNumberOfRows() != 1) {
			$queryResult->free();
			throw new UnknownIdException("No Hierarchy found with Id, '$hierarchyId'");
		}		
		$row = $queryResult->getCurrentrow();
		$queryResult->free();

		$idValue =$row['id'];
		$idManager = Services::getService("Id");
		$id = $idManager->getId($idValue);
		$allowsMultipleParents = ($row['multiparent'] == '1');
		
		if (isset($this->harmoni_db))
			$harmoni_db = $this->harmoni_db;
		else
			$harmoni_db = null;
		$cache = new AuthZ2_HierarchyCache($idValue, $allowsMultipleParents, $this->_dbIndex, $harmoni_db);
		$cache->setAuthorizationManager($this->getAuthorizationManager());
		
		$hierarchy = new AuthZ2_Hierarchy($id, $row['display_name'], $row['description'], $cache);

		// cache it
		$this->_hierarchies[$idValue] =$hierarchy;

		return $hierarchy;		
	}
	

	/**
	 * Get all Hierarchies.
	 *	
	 * @return object HierarchyIterator
	 * 
	 * @throws object HierarchyException An exception with one of
	 *		   the following messages defined in
	 *		   org.osid.hierarchy.HierarchyException may be thrown:	 {@link
	 *		   org.osid.hierarchy.HierarchyException#OPERATION_FAILED
	 *		   OPERATION_FAILED}, {@link
	 *		   org.osid.hierarchy.HierarchyException#PERMISSION_DENIED
	 *		   PERMISSION_DENIED}, {@link
	 *		   org.osid.hierarchy.HierarchyException#CONFIGURATION_ERROR
	 *		   CONFIGURATION_ERROR}, {@link
	 *		   org.osid.hierarchy.HierarchyException#UNIMPLEMENTED
	 *		   UNIMPLEMENTED}
	 * 
	 * @access public
	 */
	function getHierarchies () { 
		if(!$this->_allHierarchiesCached) {
			$dbHandler = Services::getService("DatabaseManager");
			
			$query = new SelectQuery();
			$query->addColumn("id");
			$query->addColumn("display_name");
			$query->addColumn("description");
			$query->addColumn("multiparent");
			$query->addTable("az2_hierarchy");
	
			$queryResult =$dbHandler->query($query, $this->_dbIndex);
			
			$hierarchies = array();
			
			$idManager = Services::getService("Id");
	
			while ($queryResult->hasMoreRows()) {
				$row = $queryResult->getCurrentrow();
		
				$idValue =$row['id'];
				
				// check the cache
				if (!isset($this->_hierarchies[$idValue])) {
					$id =$idManager->getId($idValue);
					$allowsMultipleParents = ($row['multiparent'] == '1');
			
					$cache = new AuthZ2_HierarchyCache($idValue, $allowsMultipleParents, $this->_dbIndex);
					$cache->setAuthorizationManager($this->getAuthorizationManager());
							
					$hierarchy = new AuthZ2_Hierarchy($id, $row['display_name'], $row['description'], $cache);
					$this->_hierarchies[$idValue] =$hierarchy;
				}
		
				$queryResult->advanceRow();
			}
			$queryResult->free();
			$this->_allHierarchiesCached = true;
		}		
		
		$obj = new AuthZ2_HierarchyIterator($this->_hierarchies);
		
		return $obj;
	}

	
	/**
	 * Delete a Hierarchy by unique Id. All Nodes must be removed from the
	 * Hierarchy before this method is called.
	 * 
	 * @param object Id $hierarchyId
	 * 
	 * @throws object HierarchyException An exception with one of
	 *		   the following messages defined in
	 *		   org.osid.hierarchy.HierarchyException may be thrown:	 {@link
	 *		   org.osid.hierarchy.HierarchyException#OPERATION_FAILED
	 *		   OPERATION_FAILED}, {@link
	 *		   org.osid.hierarchy.HierarchyException#PERMISSION_DENIED
	 *		   PERMISSION_DENIED}, {@link
	 *		   org.osid.hierarchy.HierarchyException#CONFIGURATION_ERROR
	 *		   CONFIGURATION_ERROR}, {@link
	 *		   org.osid.hierarchy.HierarchyException#UNIMPLEMENTED
	 *		   UNIMPLEMENTED}, {@link
	 *		   org.osid.hierarchy.HierarchyException#NULL_ARGUMENT
	 *		   NULL_ARGUMENT}, {@link
	 *		   org.osid.hierarchy.HierarchyException#NODE_TYPE_NOT_FOUND
	 *		   NODE_TYPE_NOT_FOUND}, {@link
	 *		   org.osid.hierarchy.HierarchyException#HIERARCHY_NOT_EMPTY
	 *		   HIERARCHY_NOT_EMPTY}
	 * 
	 * @access public
	 */
	function deleteHierarchy ( Id $hierarchyId ) { 
		// ** parameter validation
		ArgumentValidator::validate($hierarchyId, ExtendsValidatorRule::getRule("Id"), true);
		// ** end of parameter validation
		
		$dbHandler = Services::getService("DatabaseManager");
		
		$idValue = $hierarchyId->getIdString();
		
		// see if there are any nodes remaining that have to removed
		$query = new SelectQuery();
		$query->addTable("az2_node");
		$query->addColumn("COUNT(az2_node.id)", "num");
		$query->addWhereEqual("fk_hierarchy", $idValue);

		$queryResult =$dbHandler->query($query, $this->_dbIndex);
		$row = $queryResult->getCurrentRow();
		$queryResult->free();

		// if the hierarchy contains any nodes, cannot delete
		if ($row['num'] > 0) {
			throw new OperationFailedException("Hierarchy not empty.");
			return;
		}
		
		// now delete it
		$query = new DeleteQuery();
		$query->setTable("az2_hierarchy");
		$query->addWhereEqual("id", $idValue);
		$queryResult =$dbHandler->query($query, $this->_dbIndex);
		if ($queryResult->getNumberOfRows() != 1) {
			throw new OperationFailedException("Wrong number of rows deleted. Expecting 1.");
		}
		
		// update the cache
		$this->_hierarchies[$idValue] = null;
		unset($this->_hierarchies[$idValue]);
	}
	
	/**
	 * This method indicates whether this implementation supports
	 * HierarchyManager methods: createHierarchy, deleteHierarchy, updateName,
	 * updateDescription, createRootNode, createNode, deleteNode, addNodeType,
	 * removeNodeType. Note methods: nodeUpdateDescription,
	 * noteUpdateDisplayName, addParent, removeParent, changeParent.
	 *	
	 * @return boolean
	 * 
	 * @throws object HierarchyException An exception with one of
	 *		   the following messages defined in
	 *		   org.osid.hierarchy.HierarchyException may be thrown: {@link
	 *		   org.osid.hierarchy.HierarchyException#OPERATION_FAILED
	 *		   OPERATION_FAILED}, {@link
	 *		   org.osid.hierarchy.HierarchyException#PERMISSION_DENIED
	 *		   PERMISSION_DENIED}, {@link
	 *		   org.osid.hierarchy.HierarchyException#CONFIGURATION_ERROR
	 *		   CONFIGURATION_ERROR}, {@link
	 *		   org.osid.hierarchy.HierarchyException#UNIMPLEMENTED
	 *		   UNIMPLEMENTED}
	 * 
	 * @access public
	 */
	function supportsMaintenance () { 
		return TRUE;
	} 
	
	
	/**
	 * Returns the hierarchy Node with the specified Id.
	 *
	 * WARNING: NOT IN OSID - As of Version 2.0, this method has been removed
	 * from the OSID.
	 *
	 * @access public
	 * @param ref object id The Id object.
	 * @return ref object The Node with the given Id.
	 **/
	function getNode(Id $id) {		
		// Use Harmoni_Db method for greater performance if it is configured
		if (isset($this->harmoni_db))
			return $this->getNode_Harmoni_Db($id);
		
		// Otherwise use original method
		

		$idValue = $id->getIdString();
		
		$dbHandler = Services::getService("DatabaseManager");

		// find the hierarchy id for this node
		$query = new SelectQuery();
		$query->addColumn("fk_hierarchy", "hierarchy_id", "az2_node");
		$query->addTable("az2_node");
		$joinc = "fk_hierarchy = az2_hierarchy.id";
		$query->addTable("az2_hierarchy", INNER_JOIN, $joinc);
		$query->addWhereEqual("az2_node.id", $idValue);
		
		$nodeQueryResult =$dbHandler->query($query, $this->_dbIndex);
		
		if ($nodeQueryResult->getNumberOfRows() != 1) {
			$nodeQueryResult->free();
			throw new UnknownIdException("Could not find node of id, '".$id->getIdString()."'.");
		}
		
		$nodeRow = $nodeQueryResult->getCurrentRow();
		$nodeQueryResult->free();

		$idManager = Services::getService("Id");

		$hierarchyId = $nodeRow['hierarchy_id'];

		// get the hierarchy
		$hierarchy =$this->getHierarchy($idManager->getId($hierarchyId));
		
		$node =$hierarchy->getNode($id);

		return $node;
	}
	
	/**
	 * Answer a hierarchy node with the id specified
	 * 
	 * @param object Id $id
	 * @return object Node
	 * @access private
	 * @since 4/7/08
	 */
	private function getNode_Harmoni_Db (Id $id) {
		$idValue = $id->getIdString();
		
		if (!isset($this->getNode_stmt)) {
		
			$query = $this->harmoni_db->select();
		

			// find the hierarchy id for this node
			$query->addColumn("fk_hierarchy", "hierarchy_id", "az2_node");
			$query->addTable("az2_node");
			$joinc = "fk_hierarchy = "."az2_hierarchy.id";
			$query->addTable("az2_hierarchy", INNER_JOIN, $joinc);
			$query->addWhereEqual("az2_node.id", $idValue);
			
			$this->getNode_stmt = $query->prepare();
		}
		
		$this->getNode_stmt->bindValue(1, $idValue);
		$this->getNode_stmt->execute();
		$result = $this->getNode_stmt->fetchAll();
		$this->getNode_stmt->closeCursor();
		
		if (count($result) != 1) {
			throw new UnknownIdException("Could not find node of id, '".$id->getIdString()."'.");
		}
		
		$nodeRow = $result[0];

		$idManager = Services::getService("Id");

		$hierarchyId = $nodeRow['hierarchy_id'];

		// get the hierarchy
		$hierarchy =$this->getHierarchy($idManager->getId($hierarchyId));
		
		$node =$hierarchy->getNode($id);

		return $node;
	}
	
	/**
	 * Answer TRUE if the a node exists with the given Id
	 *
	 * WARNING: NOT in OSID
	 * 
	 * @param object Id $nodeId
	 *	
	 * @return boolean
	 * 
	 * @access public
	 */
	function nodeExists ( Id $nodeId ) { 
		// ** parameter validation
		ArgumentValidator::validate($nodeId, ExtendsValidatorRule::getRule("Id"), true);
		// ** end of parameter validation
		
		$hierarchies =$this->getHierarchies();
		while ($hierarchies->hasNext()) {
			$hierarchy =$hierarchies->next();
			if ($hierarchy->nodeExists($nodeId))
				return true;
		}
		return false;
	}
	
	/**
	 * Returns the hierarchy to which the given Node belongs. 
	 * 
	 * WARNING: NOT IN OSID - This method is not part of the OKI interface as 
	 * of 07/06/04 but has been scheduled for addition.
	 *
	 * Note: As of version 2.0, the getNode() method has been removed from the
	 * OSID, removing the need for this method.
	 *
	 * @access public
	 * @return ref object The Hierarchy to which the Node belongs.
	 **/
	function getHierarchyForNode(Node $node) {
		$idManager = Services::getService("Id");
		$hierarchyId =$idManager->getId($node->_cache->_hierarchyId);
		$hierarchy =$this->getHierarchy($hierarchyId);
		
		return $hierarchy;
	}
	
	/**
     * Verify to OsidLoader that it is loading
     * 
     * <p>
     * OSID Version: 2.0
     * </p>
     * .
     * 
     * @throws object OsidException 
     * 
     * @access public
     */
    public function osidVersion_2_0 () {}
    
    /**
     * Answer the AuthorizationManager used with this manager.
     * 
     * @return object HierarchyManager
     * @access public
     * @since 4/22/08
     */
    public function getAuthorizationManager () {
    	if (isset($this->authorizationManager))
    		return $this->authorizationManager;
    	else
    		return Services::getService("AuthZ");
    }
    
    /**
     * Set the AuthorizationManager used with this manager.
     * 
     * @param object AuthorizationManager $authorizationManager
     * @return void
     * @access public
     * @since 4/22/08
     */
    public function setAuthorizationManager (AuthorizationManager $authorizationManager) {
    	if (isset($this->_configuration))
    		throw new ConfigurationErrorException("setAuthorizationManager() must be called before configuring the HierarchyManager");
    	$this->authorizationManager = $authorizationManager;
    }
}

?>
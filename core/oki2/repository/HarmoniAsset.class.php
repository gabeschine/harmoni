<?

require_once(HARMONI."oki2/repository/HarmoniAsset.interface.php");
require_once(HARMONI."oki2/repository/HarmoniRecord.class.php");
require_once(HARMONI."oki2/repository/HarmoniRecordIterator.class.php");
require_once(HARMONI."oki2/shared/HarmoniIterator.class.php");

/**
 * Asset manages the Asset itself.  Assets have content as well as Records
 * appropriate to the AssetType and RecordStructures for the Asset.  Assets
 * may also contain other Assets.
 * 
 * 
 * @package harmoni.XXXX.YYYYYY
 * 
 * @copyright Copyright &copy;2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License
 *
 * @version $Id: HarmoniAsset.class.php,v 1.3 2005/01/19 22:28:24 adamfranco Exp $ 
 */

class HarmoniAsset
	extends HarmoniAssetInterface
{ // begin Asset
	
	var $_configuration;
	var $_versionControlAll = FALSE;
	var $_versionControlTypes;
	var $_hierarchy;
	var $_node;
	var $_repository;
	
	var $_recordIDs;
	var $_createdRecords;
	var $_createdRecordStructures;
	
	/**
	 * Constructor
	 */
	function HarmoniAsset (& $hierarchy, & $repository, & $id, & $configuration) {
	 	// Get the node coresponding to our id
		$this->_hierarchy =& $hierarchy;
		$this->_node =& $this->_hierarchy->getNode($id);
		$this->_repository =& $dr;
		
		$this->_recordIDs = array();
		$this->_createdRecords = array();
		$this->_createdRecordStructures = array();
		
		// Store our configuration
		$this->_configuration =& $configuration;
		$this->_versionControlAll = ($configuration['versionControlAll'])?TRUE:FALSE;
		if (is_array($configuration['versionControlTypes'])) {
			ArgumentValidator::validate($configuration['versionControlTypes'], new ArrayValidatorRuleWithRule( new ExtendsValidatorRule("TypeInterface")));
			$this->_versionControlTypes =& $configuration['versionControlTypes'];
		} else {
			$this->_versionControlTypes = array();
		}
		
	 }

	/**
     * Get the display name for this Asset.
     *  
     * @return string
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @public
     */
    function getDisplayName () { 
		return $this->_node->getDisplayName();
	}

	 /**
     * Update the display name for this Asset.
     * 
     * @param string $displayName
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}
     * 
     * @public
     */
    function updateDisplayName ( $displayName ) { 
		$this->_node->updateDisplayName($displayName);
	}

	 /**
     * Get the description for this Asset.
     *  
     * @return string
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @public
     */
    function getDescription () { 
		return $this->_node->getDescription();
	}

	/**
     * Update the description for this Asset.
     * 
     * @param string $description
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}
     * 
     * @public
     */
    function updateDescription ( $description ) { 
		$this->_node->updateDescription($description);
	}

	 /**
     * Get the unique Id for this Asset.
     *  
     * @return object Id
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @public
     */
    function &getId () { 
		return $this->_node->getId();
	}
	
	/**
     * Get the Id of the Repository in which this Asset resides.  This is set
     * by the Repository's createAsset method.
     *  
     * @return object Id
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @public
     */
    function &getRepository () { 

		return $this->_repository;
	}

	/**
     * Get an Asset's content.  This method can be a convenience if one is not
     * interested in all the structure of the Records.
     *  
     * @return object mixed (original type: java.io.Serializable)
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @public
     */
    function &getContent () { 
	
 		$idManager =& Services::getService("Id");
 		$recordMgr =& Services::getService("RecordManager");
 		
 		// Ready our type for comparisson
 		$contentType =& new HarmoniType("Repository", "Harmoni",  "AssetContent");
 		$myId =& $this->_node->getId();
 		
 		// Get the content DataSet.
 		$myRecordSet =& $recordMgr->fetchRecordSet($myId->getIdString());
 		$myRecordSet->loadRecords();
		$contentRecords =& $myRecordSet->getRecordsByType($contentType);
		
		$contentRecord =& $contentRecords[0];
		
 		if (!$contentRecord) {
 			return new Blob;
 		} else {
 			$recordFieldData =& $contentRecord->getCurrentValue("Content");
 			return $recordFieldData->getPrimitive();
 		}
	}

	/**
     * Update an Asset's content.
     * 
     * @param object mixed $content (original type: java.io.Serializable)
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}
     * 
     * @public
     */
    function updateContent ( &$content ) { 
 		ArgumentValidator::validate($content, new ExtendsValidatorRule("Blob"));
 		$idManager =& Services::getService("Id");
 		$recordMgr =& Services::getService("RecordManager");
 		
 		// Ready our type for comparisson
 		$contentType =& new HarmoniType("Repository", "Harmoni",  "AssetContent");
 		$myId =& $this->_node->getId();
 		
 		// Get the content DataSet.
 		$myRecordSet =& $recordMgr->fetchRecordSet($myId->getIdString());
 		$myRecordSet->loadRecords();
		$contentRecords =& $myRecordSet->getRecordsByType($contentType);

 		if (count($contentRecords)) {
 			$contentRecord =& $contentRecords[0];
 			
 			$contentRecord->setValue("Content", $content);
 		
			$contentRecord->commit(TRUE);
 		} else {
			// Set up and create our new record
			$schemaMgr =& Services::getService("SchemaManager");
			$contentSchema =& $schemaMgr->getSchemaByType($contentType);
			$contentSchema->load();
			
			// Decide if we want to version-control this field.
			$versionControl = $this->_versionControlAll;
			if (!$versionControl) {
				foreach ($this->_versionControlTypes as $key => $val) {
					if ($contentType->isEqual($this->_versionControlTypes[$key])) {
						$versionControl = TRUE;
						break;
					}
				}
			}
			
			$contentRecord =& $recordMgr->createRecord($contentType, $versionControl);
			
			$contentRecord->setValue("Content", $content);
 		
			$contentRecord->commit(TRUE);
	
			// Add the record to our group
			$myRecordSet->add($contentRecord);
			$myRecordSet->commit(TRUE);
		}
	}

	/**
     * Get the date at which this Asset is effective.
     *  
     * @return int
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @public
     */
    function getEffectiveDate () { 
	
		if (!$this->_effectiveDate) {
			$this->_loadDates();
		}
		
		return $this->_effectiveDate;
	}

	/**
     * Update the date at which this Asset is effective.
     * 
     * @param int $effectiveDate
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}, {@link
     *         org.osid.repository.RepositoryException#EFFECTIVE_PRECEDE_EXPIRATION}
     * 
     * @public
     */
    function updateEffectiveDate ( $effectiveDate ) { 
		ArgumentValidator::validate($effectiveDate, new ExtendsValidatorRule("Time"));
		
		// Make sure that we have dates from the DB if they exist.
		$this->_loadDates();
		// Update our date in preparation for DB updating
		$this->_effectiveDate->adoptValue($effectiveDate);
		// Store the dates
		$this->_storeDates();
	}

	 /**
     * Get the date at which this Asset expires.
     *  
     * @return int
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @public
     */
    function getExpirationDate () { 
		if (!$this->_expirationDate) {
			$this->_loadDates();
		}
		
		return $this->_expirationDate;
	}

	
    /**
     * Update the date at which this Asset expires.
     * 
     * @param int $expirationDate
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}, {@link
     *         org.osid.repository.RepositoryException#EFFECTIVE_PRECEDE_EXPIRATION}
     * 
     * @public
     */
    function updateExpirationDate ( $expirationDate ) { 
		ArgumentValidator::validate($expirationDate, new ExtendsValidatorRule("Time"));
		
		// Make sure that we have dates from the DB if they exist.
		$this->_loadDates();
		// Update our date in preparation for DB updating
		$this->_expirationDate->adoptValue($expirationDate);
		// Store the dates
		$this->_storeDates();
	}

	/**
     * Add an Asset to this Asset.
     * 
     * @param object Id $assetId
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}, {@link
     *         org.osid.repository.RepositoryException#UNKNOWN_ID UNKNOWN_ID},
     *         {@link org.osid.repository.RepositoryException#ALREADY_ADDED
     *         ALREADY_ADDED}
     * 
     * @public
     */
    function addAsset ( &$assetId ) { 
		$node =& $this->_hierarchy->getNode($assetId);
		$oldParents =& $node->getParents();
		// We are assuming a single-parent hierarchy
		$oldParent =& $oldParents->next();
		$node->changeParent($oldParent->getId(), $this->_node->getId());
		
		$this->save();
	}

	/**
     * Remove an Asset from this Asset.  This method does not delete the Asset
     * from the Repository.
     * 
     * @param object Id $assetId
     * @param boolean $includeChildren
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}, {@link
     *         org.osid.repository.RepositoryException#UNKNOWN_ID UNKNOWN_ID}
     * 
     * @public
     */
    function removeAsset ( &$assetId, $includeChildren ) { 
		$node =& $this->_hierarchy->getNode($assetId);
	
		if (!$includeChildren) {
			// Move the children to the current asset before moving
			// the asset to the dr root
			$children =& $node->getChildren();
			while ($children->hasNext()) {
				$child =& $children->next();
				$child->changeParent($node->getId(), $this->_node->getId());
			}
		}
		
		// Move the asset to the repository root.
		$node->changeParent($this->_node->getId(), $this->_repository->getId());
		
		$this->save();
	}

	 /**
     * Get all the Assets in this Asset.  Iterators return a set, one at a
     * time.
     *  
     * @return object AssetIterator
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @public
     */
    function &getAssets () { 
    	$assets = array();
		$children =& $this->_node->getChildren();
		while ($children->hasNext()) {
			$child =& $children->next();
			$assets[] =& $this->_repository->getAsset($child->getId());
		}
		
		// create an AssetIterator and return it
		$assetIterator =& new HarmoniAssetIterator($assets);
		
		return $assetIterator;
    
    }
	
	 /**
     * Get all the Assets of the specified AssetType in this Repository.
     * Iterators return a set, one at a time.
     * 
     * @param object Type $assetType
     *  
     * @return object AssetIterator
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}, {@link
     *         org.osid.repository.RepositoryException#UNKNOWN_TYPE
     *         UNKNOWN_TYPE}
     * 
     * @public
     */
	
    function &getAssetsByType ( &$assetType ) { 
    	$assets = array();
		$children =& $this->_node->getChildren();
		while ($children->hasNext()) {
			$child =& $children->next();
			if ($assetType->isEqual($child->getType()))
				$assets[] =& $this->_repository->getAsset($child->getId());
		}
		
		return new HarmoniAssetIterator($assets);
	}

	 /**
     * Create a new Asset Record of the specified RecordStructure.   The
     * implementation of this method sets the Id for the new object.
     * 
     * @param object Id $recordStructureId
     *  
     * @return object Record
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}, {@link
     *         org.osid.repository.RepositoryException#UNKNOWN_ID UNKNOWN_ID}
     * 
     * @public
     */
    function &createRecord ( &$recordStructureId ) { 
		ArgumentValidator::validate($recordStructureId, new ExtendsValidatorRule("Id"));
		
		// If this is a schema that is hard coded into our implementation, create
		// a record for that schema.
		if (in_array($recordStructureId->getIdString(), array_keys($this->_repository->_builtInTypes))) 
		{
			// Create an Id for the record;
			$idManager =& Services::getService("Id");
			$newId =& $idManager->createId();
	
			// instantiate the new record.
			$recordClass = $this->_repository->_builtInTypes[$recordStructureId->getIdString()];
			$recordStructure =& $this->_repository->getInfoStructure($recordStructureId);
			$record =& new $recordClass($recordStructure, $newId, $this->_configuration);
			
			// store a relation to the record
			$dbHandler =& Services::getService("DBHandler");
			$query =& new InsertQuery;
			$query->setTable("dr_asset_record");
			$query->setColumns(array("FK_asset", "FK_record", "structure_id"));
			$myId =& $this->getId();
			$query->addRowOfValues(array(
								"'".$myId->getIdString()."'",
								"'".$newId->getIdString()."'",
								"'".$infoStructureId->getIdString()."'"));
			$result =& $dbHandler->query($query, $this->_configuration["dbId"]);
		} 
		
		// Otherwise use the data manager
		else {
			// Get the DataSetGroup for this Asset
			$recordMgr =& Services::getService("RecordManager");
			$myId = $this->_node->getId();
			$myGroup =& $recordMgr->fetchRecordSet($myId->getIdString());
			
			// Get the info Structure needed.
			$recordStructures =& $this->_repository->getRecordStructures();
			while ($recordStructures->hasNext()) {
				$structure =& $recordStructures->next();
				if ($recordStructureId->isEqual($structure->getId()))
					break;
			}
			
			// 	get the type for the new data set.
			$schemaMgr =& Services::getService("SchemaManager");
			$type =& $schemaMgr->getSchemaTypeByID($recordStructureId->getIdString());
			
			// Set up and create our new dataset
			// Decide if we want to version-control this field.
				$versionControl = $this->_versionControlAll;
				if (!$versionControl) {
					foreach ($this->_versionControlTypes as $key => $val) {
						if ($type->isEqual($this->_versionControlTypes[$key])) {
							$versionControl = TRUE;
							break;
						}
					}
				}
				
				$newRecord =& $recordMgr->createRecord($type, $versionControl);
			
			// The ignoreMandatory Allows this record to be created without checking for
			// values on mandatory fields. These constraints should be checked when
			// validateAsset() is called.
			$newRecord->commit(TRUE);
			
			// Add the DataSet to our group
			$myGroup->add($newRecord);
			
			// us the InfoStructure and the dataSet to create a new InfoRecord
			$record =& new HarmoniRecord($structure, $newRecord);
		}
		
		// Add the record to our createdRecords array, so we can pass out references to it.
		$recordId =& $record->getId();
		$this->_createdRecords[$recordId->getIdString()] =& $record;
		
		$this->save();
		
		return $record;
	}

	 /**
     * Add the specified RecordStructure and all the related Records from the
     * specified asset.  The current and future content of the specified
     * Record is synchronized automatically.
     * 
     * @param object Id $assetId
     * @param object Id $recordStructureId
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}, {@link
     *         org.osid.repository.RepositoryException#UNKNOWN_ID UNKNOWN_ID},
     *         {@link
     *         org.osid.repository.RepositoryException#ALREADY_INHERITING_STRUCTURE
     *         ALREADY_INHERITING_STRUCTURE}
     * 
     * @public
     */
    function inheritRecordStructure ( &$assetId, &$recordStructureId ) { 
	
		// Check the arguments
		ArgumentValidator::validate($recordStructureId, new ExtendsValidatorRule("Id"));
		ArgumentValidator::validate($assetId, new ExtendsValidatorRule("Id"));
		
		// If this is a schema that is hard coded into our implementation, create
		// a record for that schema.
		if (in_array($recordStructureId->getIdString(), array_keys($this->_repository->_builtInTypes))) 
		{
			// Create an Id for the record;
			$idManager =& Services::getService("Id");
			$dbHandler =& Services::getService("DBHandler");
	
			// get the record ids that we want to inherit
			$query =& new SelectQuery();
			$query->addTable("dr_asset_record");
			$query->addColumn("FK_record");
			$query->addWhere("FK_asset = '".$assetId->getIdString()."'");
			$query->addWhere("structure_id = '".$recordStructureId->getIdString()."'", _AND);
			
			$result =& $dbHandler->query($query, $this->_configuration["dbId"]);
			
			// store a relation to the record
			$dbHandler =& Services::getService("DBHandler");
			$query =& new InsertQuery;
			$query->setTable("dr_asset_record");
			$query->setColumns(array("FK_asset", "FK_record", "structure_id"));
			
			$myId =& $this->getId();
			
			while ($result->hasMoreRows()) {
				$query->addRowOfValues(array(
									"'".$myId->getIdString()."'",
									"'".$result->field("FK_record")."'",
									"'".$recordStructureId->getIdString()."'"));
				$dbHandler->query($query, $this->_configuration["dbId"]);
				$result->advanceRow();
			}
		} 
		
		// Otherwise use the data manager
		else {
			// Get our managers:
			$recordMgr =& Services::getService("RecordManager");
			$idMgr =& Services::getService("Id");
		
			// Get the DataSetGroup for this Asset
			$myId = $this->_node->getId();
			$mySet =& $recordMgr->fetchRecordSet($myId->getIdString());
			
			// Get the DataSetGroup for the source Asset
			$otherSet =& $recordMgr->fetchRecordSet($assetId->getIdString());
			$otherSet->loadRecords(RECORD_FULL);
			$records =& $otherSet->getRecords();
			
			// Add all of DataSets (Records) of the specified InfoStructure and Asset
			// to our DataSetGroup.
			foreach (array_keys($records) as $key) {
				// Get the ID of the current DataSet's TypeDefinition
				$schema =& $records[$key]->getSchema();
				$schemaId =& $idMgr->getId($schema->getID());
				
				// If the current DataSet's DataSetTypeDefinition's ID is the same as
				// the InfoStructure ID that we are looking for, add that dataSet to our
				// DataSetGroup.
				if ($receordStructureId->isEqual($schemaId)) {
					$mySet->add($records[$key]);
				}
			}
			
			// Save our DataSetGroup
			$mySet->commit(TRUE);
		}
	}

	/**
     * Add the specified RecordStructure and all the related Records from the
     * specified asset.
     * 
     * @param object Id $assetId
     * @param object Id $recordStructureId
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}, {@link
     *         org.osid.repository.RepositoryException#UNKNOWN_ID UNKNOWN_ID},
     *         {@link
     *         org.osid.repository.RepositoryException#CANNOT_COPY_OR_INHERIT_SELF
     *         CANNOT_COPY_OR_INHERIT_SELF}
     * 
     * @public
     */
    function copyRecordStructure ( &$assetId, &$recordStructureId ) { 
	
		// Check the arguments	
		ArgumentValidator::validate($recordStructureId, new ExtendsValidatorRule("Id"));
		ArgumentValidator::validate($assetId, new ExtendsValidatorRule("Id"));
		
		// Get our managers:
		$recordMgr =& Services::getService("RecordManager");
		$idMgr =& Services::getService("Id");
		
		// Get the RecordSet for this Asset
		$myId = $this->_node->getId();
		$set =& $recordMgr->fetchRecordSet($myId->getIdString());
		
		// Get the DataSetGroup for the source Asset
		$otherSet =& $recordMgr->fetchRecordSet($assetId->getIdString());
		$otherSet->loadRecords(RECORD_FULL);
		$records =& $otherSet->getRecords();
		
		// Add all of Records (InfoRecords) of the specified InfoStructure and Asset
		// to our RecordSet.
		foreach (array_keys($records) as $key) {
			// Get the ID of the current DataSet's TypeDefinition
			$schema =& $records[$key]->getSchema();
			$schemaId =& $idMgr->getId($schema->getID());
			
			// If the current Record's Schema ID is the same as
			// the InfoStructure ID that we are looking for, add clones of that Record
			// to our RecordSet.
			if ($recordStructureId->isEqual($schemaId)) {
				$newRecord =& $records[$key]->clone();
				$set->add($newRecord);
			}
		}
		
		// Save our RecordSet
		$set->commit(TRUE);
	}

	/**
     * Delete a Record.  If the specified Record has content that is inherited
     * by other Records, those other Records will not be deleted, but they
     * will no longer have a source from which to inherit value changes.
     * 
     * @param object Id $recordId
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}, {@link
     *         org.osid.repository.RepositoryException#UNKNOWN_ID UNKNOWN_ID}
     * 
     * @public
     */
    function deleteRecord ( &$recordId ) { 
		ArgumentValidator::validate($recordId, new ExtendsValidatorRule("Id"));
		
		$record =& $this->getRecord($recordId);
		$structure =& $record->getRecordStructure();
		$structureId =& $structure->getId();
		
		// If this is a schema that is hard coded into our implementation, create
		// a record for that schema.
		if (in_array($structureId->getIdString(), array_keys($this->_repository->_builtInTypes))) 
		{
			// Delete all of the InfoFields for the record
			$parts =& $record->getParts();
			while ($parts->hasNext()) {
				$part =& $parts->next();
				$record->deletePart($part->getId());
			}
			
			// Delete the relation for the record.
			$dbHandler =& Services::getService("DBHandler");
			$query =& new DeleteQuery;
			$query->setTable("dr_asset_record");
			$myId =& $this->getId();
			$query->addWhere("FK_asset = '".$myId->getIdString()."'");
			$query->addWhere("FK_record = '".$recordId->getIdString()."'");
			
			$result =& $dbHandler->query($query, $this->_configuration["dbId"]);
		}
		// Otherwise use the data manager
		else {
			$recordMgr =& Services::getService("RecordManager");
			$record =& $recordMgr->fetchRecord($recordId->getIdString(),RECORD_FULL);
			
			// Check if the record is part of other record sets (assets via inheretance)
			$myId =& $this->getId();
			$setsContaining = $recordMgr->getRecordSetIDsContaining($record);
			$myRecordSet =& $recordMgr->fetchRecordSet($myId->getIdString());
			
			// If this is the last asset referencing this record, delete it.
			if (count($setsContaining) == 1 && $setsContaining[0] == $myId->getIdString()) {
				$myRecordSet->removeRecord($record);
				$myRecordSet->commit(TRUE);
				$record->delete(TRUE);
				$record->commit(TRUE);
			}
			// If this record is used by other assets, remove the record from this set, 
			// but leave it in the rest.
			else {
				$myRecordSet =& $recordMgr->fetchRecordSet($myId->getIdString());
				$myRecordSet->removeRecord($record);
				$myRecordSet->commit(TRUE);
			}
		}
	}

	 /**
     * Get the Record for this Asset that matches this Record's unique Id.
     * 
     * @param object Id $recordId
     *  
     * @return object Record
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}, {@link
     *         org.osid.repository.RepositoryException#UNKNOWN_ID UNKNOWN_ID}
     * 
     * @public
     */
    function &getRecord ( &$recordId ) { 
		ArgumentValidator::validate($recordId, new ExtendsValidatorRule("Id"));
		
		// Check to see if the info record is in our cache.
		// If so, return it. If not, create it, then return it.
		if (!$this->_createdRecords[$recordId->getIdString()]) {
			
			// Check for the record in our non-datamanager records;
		
			$idManager =& Services::getService("Id");
			$dbHandler =& Services::getService("DBHandler");
			$myId =& $this->getId();
			
			// get the record ids that we want to inherit
			$query =& new SelectQuery();
			$query->addTable("dr_asset_record");
			$query->addColumn("structure_id");
			$query->addWhere("FK_asset = '".$myId->getIdString()."'");
			$query->addWhere("FK_record = '".$recordId->getIdString()."'", _AND);
			
			$result =& $dbHandler->query($query, $this->_configuration["dbId"]);
			
			if ($result->getNumberOfRows()) {
				$structureIdString =& $result->field("structure_id");
				
				$recordClass = $this->_repository->_builtInTypes[$structureIdString];
				$recordStructureId =& $idManager->getId($structureIdString);
				$recordStructure =& $this->_repository->getRecordStructure($recordStructureId);
				
				$this->_createdRecords[$recordId->getIdString()] =& new $recordClass(
												$recordStructure,
												$recordId,
												$this->_configuration
											);
			} 
			
			// Otherwise use the data manager
			else {
				
				// Get the DataSet.
				$recordMgr =& Services::getService("RecordManager");
				// Specifying TRUE for editable because it is unknown whether or not editing will
				// be needed. @todo Change this if we wish to re-fetch the $dataSet when doing 
				// editing functions.
				$record =& $recordMgr->fetchRecord($recordId->getIdString());
	
				// Make sure that we have a valid dataSet
				$rule =& new ExtendsValidatorRule("Record");
				if (!$rule->check($record))
					throwError(new Error(RepositoryException::UNKNOWN_ID(), "Repository :: Asset", TRUE));
				
				// Get the info structure.
				$schema =& $record->getSchema();
				if (!$this->_createdRecordStructures[$schema->getID()]) {
					$this->_createdRecordStructures[$schema->getID()] =& new HarmoniRecordStructure($schema);
				}
				
				// Create the InfoRecord in our cache.
				$this->_createdRecords[$recordId->getIdString()] =& new HarmoniRecord (
								$this->_createdRecordStructures[$schema->getID()], $record);
			}
		}
		
		return $this->_createdRecords[$recordId->getIdString()];
	}

	
    /**
     * Get all the Records for this Asset.  Iterators return a set, one at a
     * time.
     *  
     * @return object RecordIterator
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @public
     */
    function &getRecords () { 
		if ($recordStructureId)
			ArgumentValidator::validate($recordStructureId, new ExtendsValidatorRule("Id"));
		
		$id =& $this->getId();
		$recordMgr =& Services::getService("RecordManager");
		$idManager =& Services::getService("Id");		
		$records = array();
		
		// Get the records from the data manager.
		if ($recordSet =& $recordMgr->fetchRecordSet($id->getIdString())) {
			// fetching as editable since we don't know if it will be edited.
			$recordSet->loadRecords();
			$records =& $recordSet->getRecords();
	
			// create info records for each dataSet as needed.
			foreach (array_keys($records) as $key) {
				$recordIdString = $records[$key]->getID();
				$recordId =& $idManager->getId($recordIdString);
				$record =& $this->getRecord($recordId);
				$structure =& $record->getRecordStructure();
				
				// Add the record to our array
				if (!$recordStructureId || $recordStructureId->isEqual($structure->getId()))
					$records[] =& $record;
			}
		}
		
		// Get our non-datamanager records
		if (!$recordStructureId || in_array($recordStructureId->getIdString(), array_keys($this->_repository->_builtInTypes))) 
		{
			// get the record ids that we want to inherit
			$dbHandler =& Services::getService("DBHandler");
			$myId =& $this->getId();
			
			$query =& new SelectQuery();
			$query->addTable("dr_asset_record");
			$query->addColumn("FK_record");
			$query->addWhere("FK_asset = '".$myId->getIdString()."'");
			if ($infoStructureId)
				$query->addWhere("structure_id = '".$infoStructureId->getIdString()."'", _AND);
			
			$result =& $dbHandler->query($query, $this->_configuration["dbId"]);
			
			while ($result->hasMoreRows()) {
				$recordId =& $sharedManager->getId($result->field("FK_record"));
				
				$records[] =& $this->getRecord($recordId);
				
				$result->advanceRow();
			}
		}
		
		// Create an iterator and return it.
		$recordIterator =& new HarmoniRecordIterator($records);
		
		return $recordIterator;
	}
 	
 	/**
     * Get the AssetType of this Asset.  AssetTypes are used to categorize
     * Assets.
     *  
     * @return object Type
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @public
     */
    function &getAssetType () { 
		return $this->_node->getType();
	}

	/**
     * Get all the RecordStructures for this Asset.  RecordStructures are used
     * to categorize information about Assets.  Iterators return a set, one at
     * a time.
     *  
     * @return object RecordStructureIterator
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @public
     */
    function &getRecordStructures () { 
		// cycle through all our DataSets, get their type and make an InfoStructure for each. 
		$recordStructures = array();
		
		$records =& $this->getRecords();
		
		while ($records->hasNext()) {
			$record =& $records->next();
			$structure =& $record->getRecordStructure();
			$structureId =& $structure->getId();
			if (!$recordStructures[$structureId->getIdString()])
				$recordStructures[$structureId->getIdString()] =& $structure;
		}
		
		return new HarmoniIterator($recordStructures);
	}

	/**
     * Get the RecordStructure associated with this Asset's content.
     *  
     * @return object RecordStructure
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @public
     */
    function &getContentRecordStructure () { 
		$idManager =& Services::getService("Id");
		$schemaMgr =& Services::getService("SchemaManager");
		
		$recordStructures =& $this->_repository->getRecordStructures();

		// Get the id of the Content DataSetTypeDef
		$contentType =& new HarmoniType("Repository", "Harmoni", "AssetContent");
		$contentTypeId =& $idManager->getId($schemaMgr->getIDByType($contentType));
		
		while ($recordStructures->hasNext()) {
			$structure =& $recordStructures->next();
			if ($contentTypeId->isEqual($structure->getId()))
				return $structure;
		}
		throwError(new Error(RepositoryException::OPERATION_FAILED(), "Repository :: Asset", TRUE));
	}
	
	
    /**
     * Get the Part for a Record for this Asset that matches this Part's unique
     * Id.
     * 
     * @param object Id $partId
     *  
     * @return object Part
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}, {@link
     *         org.osid.repository.RepositoryException#UNKNOWN_ID UNKNOWN_ID}
     * 
     * @public
     */
    function &getPart ( &$partId ) { 
	
		$records =& $this->getRecords();
		while ($records->hasNext()) {
			$record =& $records->next();
			$fields =& $record->getParts();
			while ($fields->hasNext()) {
				$field =& $fields->next();
				if ($partId->isEqual($field->getId()))
					return $field;
			}
		}
		// Throw an error if we didn't find the field.
		throwError(new Error(RepositoryException::UNKNOWN_ID(), "Repository :: Asset", TRUE));
	}
	
	/**
     * Get the Value of the Part of the Record for this Asset that matches this
     * Part's unique Id.
     * 
     * @param object Id $partId
     *  
     * @return object mixed (original type: java.io.Serializable)
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}, {@link
     *         org.osid.repository.RepositoryException#UNKNOWN_ID UNKNOWN_ID}
     * 
     * @public
     */
    function &getPartValue ( &$partId ) { 

		throwError(new Error(RepositoryException::UNIMPLEMENTED(), "Repository :: Asset", TRUE));
	}
	
	/**
     * Get the Parts of the Records for this Asset that are based on this
     * RecordStructure PartStructure's unique Id.
     * 
     * @param object Id $partStructureId
     *  
     * @return object PartIterator
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}, {@link
     *         org.osid.repository.RepositoryException#UNKNOWN_ID UNKNOWN_ID}
     * 
     * @public
     */
    function &getPartsByPartStructure ( &$partStructureId ) { 
        die ("Method <b>".__FUNCTION__."()</b> declared in interface<b> ".__CLASS__."</b> has not been overloaded in a child class."); 
    } 
	
	/**
     * Get the Values of the Parts of the Records for this Asset that are based
     * on this RecordStructure PartStructure's unique Id.
     * 
     * @param object Id $partStructureId
     *  
     * @return object ObjectIterator
     * 
     * @throws object RepositoryException An exception with one of
     *         the following messages defined in
     *         org.osid.repository.RepositoryException may be thrown: {@link
     *         org.osid.repository.RepositoryException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.repository.RepositoryException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.repository.RepositoryException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.repository.RepositoryException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.repository.RepositoryException#NULL_ARGUMENT
     *         NULL_ARGUMENT}, {@link
     *         org.osid.repository.RepositoryException#UNKNOWN_ID UNKNOWN_ID}
     * 
     * @public
     */
    function &getPartValuesByPartStructure ( &$partStructureId ) { 
        die ("Method <b>".__FUNCTION__."()</b> declared in interface<b> ".__CLASS__."</b> has not been overloaded in a child class."); 
    } 
	
	/**
	 * Get the InfoFields of the InfoRecords for this Asset that are based 
	 * on this InfoStructure InfoPart Unique Id.
	 *
	 * @param object osid.shared.Id infoPartId
	 *
	 * @return object osid.dr.InfoFieldIterator
	 *
	 * @throws An exception with one of the following messages defined in 
	 * 		osid.dr.DigitalRepositoryException may be thrown: 
	 * 		OPERATION_FAILED, PERMISSION_DENIED, CONFIGURATION_ERROR, 
	 *		UNIMPLEMENTED, NULL_ARGUMENT, UNKNOWN_ID
 	 *
	 * @todo Replace JavaDoc with PHPDoc
	 */
	function &getInfoFieldsByPart(& $infoPartId) {
		throwError(new Error(UNIMPLEMENTED, "Digital Repository :: Asset", TRUE));
	}
	
	/**
	 * Get the Values of the InfoFields of the InfoRecords for this Asset
	 * that are based on this InfoStructure InfoPart Unique Id.
	 *
	 * @param object osid.shared.Id infoPartId
	 *
	 * @return object osid.shared.SerializableObjectIterator
	 *
	 * @throws An exception with one of the following messages defined in 
	 * 		osid.dr.DigitalRepositoryException may be thrown: 
	 * 		OPERATION_FAILED, PERMISSION_DENIED, CONFIGURATION_ERROR, 
	 *		UNIMPLEMENTED, NULL_ARGUMENT, UNKNOWN_ID
 	 *
	 * @todo Replace JavaDoc with PHPDoc
	 */
	function &getInfoFieldValueByPart(& $infoPartId) {
		throwError(new Error(UNIMPLEMENTED, "Digital Repository :: Asset", TRUE));
	}

	/**
	 * Store the effective and expiration Dates. getEffectiveDate or getExpirationDate
	 * should be called first to set the datesInDB flag.
	 * 
	 * @return void
	 * @access public
	 * @since 8/10/04
	 */
	function _storeDates () {
		$dbHandler =& Services::getService("DBHandler");
		$id =& $this->_node->getId();
		
		// If we have stored dates for this asset set them
		if ($this->_datesInDB) {
			$query =& new UpdateQuery;
			$query->setWhere("asset_id='".$id->getIdString()."'");
		} 
		
		// Otherwise, insert Them
		else {
			$query =& new InsertQuery;
		}
		
		$columns = array("asset_id", "effective_date", "expiration_date");
		$values = array($id->getIdString(), 
						$dbHandler->toDBDate($this->_effectiveDate, $this->_configuration["dbId"]), 
						$dbHandler->toDBDate($this->_expirationDate, $this->_configuration["dbId"]));
		$query->setColumns($columns);
		$query->setValues($values);
		$query->setTable("dr_asset_info");
		
		$result =& $dbHandler->query($query, $this->_configuration["dbId"]);
	}
	
	/**
	 * Loads dates from the database and sets the _datesInDB flag
	 * 
	 * @return void
	 * @access public
	 * @since 8/10/04
	 */
	function _loadDates () {
		$dbHandler =& Services::getService("DBHandler");
		// Get the content DataSet.
		$id =& $this->_node->getId();
		
		$query =& new SelectQuery;
		$query->addTable("dr_asset_info");
		$query->addColumn("effective_date");
		$query->addColumn("expiration_date");
		$query->addWhere("asset_id='".$id->getIdString()."'");
		
		$result =& $dbHandler->query($query, $this->_configuration["dbId"]);
		
		// If we have stored dates for this asset set them
		if ($result->getNumberOfRows()) {
			$this->_effectiveDate =& new Time($dbHandler->fromDBDate($result->field("effective_date"), $this->_configuration["dbId"]));
			$this->_expirationDate =& new Time($dbHandler->fromDBDate($result->field("expiration_date"), $this->_configuration["dbId"]));
			$this->_datesInDB = TRUE;
		} 
		
		// Otherwise, just create some zeroed objects to return
		else {
			$this->_effectiveDate =& new Time;
			$this->_expirationDate =& new Time;
			$this->_datesInDB = FALSE;
		}
	}
	
	
	
	/**
	 * Saves this object to persistable storage.
	 * @access protected
	 */
	function save () {		
		// Save the dataManager
		$recordMgr =& Services::getService("RecordManager");
		$nodeId =& $this->_node->getId();
		$group =& $recordMgr->fetchRecordSet($nodeId->getIdString(), true);
		
		// The ignoreMandatory Allows this record to be created without checking for
		// values on mandatory fields. These constraints should be checked when
		// validateAsset() is called.
		if ($group) $group->commit(TRUE);
	}

} // end Asset
?>
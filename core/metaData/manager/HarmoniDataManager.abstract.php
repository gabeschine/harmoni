<?php

require_once(HARMONI."metaData/manager/DataSetTypeManager.class.php");
require_once(HARMONI."metaData/manager/DataTypeManager.class.php");
require_once(HARMONI."metaData/manager/IDManager.class.php");
require_once(HARMONI."metaData/manager/DataType.interface.php");
require_once(HARMONI."metaData/manager/DataSetManager.class.php");
require_once(HARMONI."metaData/manager/DataSetTagManager.class.php");

require_once(HARMONI."metaData/manager/search/include.php");

/**
 * The HarmoniDataManager class is used purely to setup the services required to use the
 * other DataManager classes such as the {@link DataSetTypeManager} or the {@link DataSetManager}.
 * @package harmoni.datamanager
 * @version $Id: HarmoniDataManager.abstract.php,v 1.11 2004/01/14 20:09:42 gabeschine Exp $
 * @author Gabe Schine
 * @copyright 2004
 * @access public
 * @abstract
 **/

class HarmoniDataManager {
	
	/**
	 * Sets up the services required for the DataManager, including: IDManager, DataSetTypeManager,
	 * DataTypeManager, DataSetManager, DataSetTagManager
	 * @return void
	 * @access public
	 * @param int $dbID The DB index from the {@link DBHandler} that we should use to look for our data.
	 * corresponding to DataSetTypes that should be pre-loaded in one DB query.
	 * @abstract
	 */
	function setup( $dbID ) {
		
		// let's setup all our services
		
		// first, make sure they're all stopped and unregistered.
		$services = array("DataTypeManager","DataSetTypeManager","DataSetManager","IDManager","DataSetTagManager");
		foreach ($services as $service) {
			if (Services::serviceAvailable($service)) {
				if (Services::serviceRunning($service))
					Services::stopService($service);
			}
		}
		
		// ok, now on to registering everything
		IDManager::setup($dbID);
		$idManager =& Services::getService("IDManager");
		$dataSetTypeManager =& new DataSetTypeManager($idManager, $dbID, $preloadTypes);
		$dataTypeManager =& new DataTypeManager();
		$dataSetManager =& new DataSetManager( $idManager, $dbID, $dataSetTypeManager );
		$dataSetTagManager =& new DataSetTagManager($idManager, $dbID);

		Services::registerObjectAsService("DataSetTypeManager",$dataSetTypeManager);
		Services::registerObjectAsService("DataTypeManager",$dataTypeManager);
		Services::registerObjectAsService("DataSetManager",$dataSetManager);
		Services::registerObjectAsService("DataSetTagManager",$dataSetTagManager);

		debug::output("Activated Harmoni Data Manager.",DEBUG_SYS1,"HarmoniDataManager");
	}
	
}

?>
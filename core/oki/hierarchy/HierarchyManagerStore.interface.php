<?php

/******************************************************************************
 * A storage class for HierarchyManager[s]. This class provides saving and loading
 * of the HierarchyManager from persistable storage.
 * @author Adam Franco
 * @version $$
 ******************************************************************************/


class HierarchyManagerStore
{

	/**
	 * Adds a hierachy to this managerStore.
	 * @param object HarmoniHierarchy $hierarchy The Hierarchy to add.
	 */
	function addHierarchy (& $hierarchy) {
		die ("Method <b>".__FUNCTION__."()</b> declared in interface <b> ".__CLASS__."</b> has not been overloaded in a child class.");
	}
	
	/**
	 * Returns an array of hierachies known to this managerStore.
	 * @return array The array of hierarchies.
	 */
	function getHierarchyArray () {
		die ("Method <b>".__FUNCTION__."()</b> declared in interface <b> ".__CLASS__."</b> has not been overloaded in a child class.");
	}

	/**
	 * Loads this object from persistable storage.
	 * @access protected
	 */
	function load () {
		die ("Method <b>".__FUNCTION__."()</b> declared in interface <b> ".__CLASS__."</b> has not been overloaded in a child class.");
	}
	
	/**
	 * Saves this object to persistable storage.
	 * @access protected
	 */
	function save () {
		die ("Method <b>".__FUNCTION__."()</b> declared in interface <b> ".__CLASS__."</b> has not been overloaded in a child class.");
	}

}
?>
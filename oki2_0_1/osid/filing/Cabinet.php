<?php 
 
include_once(dirname(__FILE__)."/../filing/CabinetEntry.php");
/**
 * Cabinets contain other Cabinets and ByteStores, and have
 * implementation-dependent properties.
 * 
 * <p>
 * They may manage quotas, that is, if the implementation supports quotas, each
 * Agent may be assigned a quota of space used in the Cabinet.
 * </p>
 * 
 * <p>
 * Cabinets contain CabinetEntries, each of which may be a ByteStore or a
 * Cabinet.  They are known by their IDs and name, where the name is a string
 * which does not include the implementation-dependent separationCharacter,
 * and may represent a filename.
 * </p>
 * 
 * <p>
 * ByteStores and Cabinets are added to Cabinets. Cabinets are created in
 * CabinetFactories or Cabinets, and ByteStores are created in Cabinets.
 * </p>
 * 
 * <p>
 * OSID Version: 2.0
 * </p>
 * 
 * <p>
 * Licensed under the {@link org.osid.SidImplementationLicenseMIT MIT
 * O.K.I&#46; OSID Definition License}.
 * </p>
 * 
 * @package org.osid.filing
 */
interface Cabinet
    extends CabinetEntry
{
    /**
     * Create a new ByteStore and add it to this Cabinet under the given name.
     * The name must not include this Cabinet's separationCharacter.
     * 
     * @param string $displayName
     *  
     * @return object ByteStore
     * 
     * @throws object FilingException An exception with one of the
     *         following messages defined in org.osid.filing.FilingException
     *         may be thrown: {@link
     *         org.osid.filing.FilingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.filing.FilingException#IO_ERROR IO_ERROR}, {@link
     *         org.osid.filing.FilingException#ITEM_ALREADY_EXISTS
     *         ITEM_ALREADY_EXISTS}
     * 
     * @access public
     */
    public function createByteStore ( $displayName ); 

    /**
     * Create a new Cabinet and add it to this Cabinet under the given name.
     * The name must not include this Cabinet's separationCharacter.
     * 
     * @param string $displayName
     *  
     * @return object Cabinet
     * 
     * @throws object FilingException An exception with one of the
     *         following messages defined in org.osid.filing.FilingException
     *         may be thrown: {@link
     *         org.osid.filing.FilingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.filing.FilingException#IO_ERROR IO_ERROR}, {@link
     *         org.osid.filing.FilingException#ITEM_ALREADY_EXISTS
     *         ITEM_ALREADY_EXISTS}, {@link
     *         org.osid.filing.FilingException#NAME_CONTAINS_ILLEGAL_CHARS
     *         NAME_CONTAINS_ILLEGAL_CHARS}
     * 
     * @access public
     */
    public function createCabinet ( $displayName ); 

    /**
     * Copy an existing ByteStore in this Cabinet by copying contents and the
     * appropriate attributes of another ByteStore.
     * 
     * @param string $displayName
     * @param object ByteStore $oldByteStore
     *  
     * @return object ByteStore
     * 
     * @throws object FilingException An exception with one of the
     *         following messages defined in org.osid.filing.FilingException
     *         may be thrown: {@link
     *         org.osid.filing.FilingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.filing.FilingException#IO_ERROR IO_ERROR}, {@link
     *         org.osid.filing.FilingException#ITEM_ALREADY_EXISTS
     *         ITEM_ALREADY_EXISTS}, {@link
     *         org.osid.filing.FilingException#UNIMPLEMENTED UNIMPLEMENTED}
     * 
     * @access public
     */
    public function copyByteStore ( $displayName, ByteStore $oldByteStore ); 

    /**
     * Add a CabinetEntry, it must be from same Manager.
     * 
     * @param object CabinetEntry $entry
     * @param string $displayName
     * 
     * @throws object FilingException An exception with one of the
     *         following messages defined in org.osid.filing.FilingException
     *         may be thrown: {@link
     *         org.osid.filing.FilingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.filing.FilingException#IO_ERROR IO_ERROR}, {@link
     *         org.osid.filing.FilingException#NAME_CONTAINS_ILLEGAL_CHARS
     *         NAME_CONTAINS_ILLEGAL_CHARS}, {@link
     *         org.osid.filing.FilingException#ITEM_ALREADY_EXISTS
     *         ITEM_ALREADY_EXISTS}, {@link
     *         org.osid.filing.FilingException#UNIMPLEMENTED UNIMPLEMENTED}
     * 
     * @access public
     */
    public function add ( CabinetEntry $entry, $displayName ); 

    /**
     * Remove a CabinetEntry. Does not destroy the CabinetEntry.
     * 
     * @param object CabinetEntry $entry
     * 
     * @throws object FilingException An exception with one of the
     *         following messages defined in org.osid.filing.FilingException
     *         may be thrown: {@link
     *         org.osid.filing.FilingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.filing.FilingException#IO_ERROR IO_ERROR}, {@link
     *         org.osid.filing.FilingException#UNIMPLEMENTED UNIMPLEMENTED}
     * 
     * @access public
     */
    public function remove ( CabinetEntry $entry ); 

    /**
     * Get a CabinetEntry from a Cabinet by its ID.
     * 
     * @param object Id $id
     *  
     * @return object CabinetEntry
     * 
     * @throws object FilingException An exception with one of the
     *         following messages defined in org.osid.filing.FilingException
     *         may be thrown: {@link
     *         org.osid.filing.FilingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.filing.FilingException#IO_ERROR IO_ERROR},{@link
     *         org.osid.filing.FilingException#ITEM_DOES_NOT_EXIST
     *         ITEM_DOES_NOT_EXIST}
     * 
     * @access public
     */
    public function getCabinetEntryById ( Id $id ); 

    /**
     * Get a CabinetEntry by name.  Not all CabinetEntrys have names, but if it
     * has a name, the name is unique within a Cabinet.
     * 
     * @param string $displayName
     *  
     * @return object CabinetEntry
     * 
     * @throws object FilingException An exception with one of the
     *         following messages defined in org.osid.filing.FilingException
     *         may be thrown: {@link
     *         org.osid.filing.FilingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.filing.FilingException#IO_ERROR IO_ERROR}, {@link
     *         org.osid.filing.FilingException#DELETE_FAILED DELETE_FAILED},
     *         {@link org.osid.filing.FilingException#ITEM_DOES_NOT_EXIST
     *         ITEM_DOES_NOT_EXIST}, {@link
     *         org.osid.filing.FilingException#UNIMPLEMENTED UNIMPLEMENTED}
     * 
     * @access public
     */
    public function getCabinetEntryByName ( $displayName ); 

    /**
     * Get an Iterator over all CabinetEntries in this Cabinet.
     *  
     * @return object CabinetEntryIterator
     * 
     * @throws object FilingException 
     * 
     * @access public
     */
    public function entries (); 

    /**
     * Return the root Cabinet of this Cabinet.
     *  
     * @return object Cabinet
     * 
     * @throws object FilingException An exception with one of the
     *         following messages defined in org.osid.filing.FilingException
     *         may be thrown: {@link
     *         org.osid.filing.FilingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.filing.FilingException#IO_ERROR IO_ERROR}, {@link
     *         org.osid.filing.FilingException#UNIMPLEMENTED UNIMPLEMENTED}
     * 
     * @access public
     */
    public function getRootCabinet (); 

    /**
     * Return true if this Cabinet is the root Cabinet.
     *  
     * @return boolean
     * 
     * @throws object FilingException 
     * 
     * @access public
     */
    public function isRootCabinet (); 

    /**
     * Return true if this Cabinet allows entries to be added or removed.
     *  
     * @return boolean
     * 
     * @throws object FilingException 
     * 
     * @access public
     */
    public function isManageable (); 

    /**
     * Get number of bytes available in this Cabinet.
     *  
     * @return int
     * 
     * @throws object FilingException An exception with one of the
     *         following messages defined in org.osid.filing.FilingException
     *         may be thrown: {@link org.osid.filing.FilingException#IO_ERROR
     *         IO_ERROR}, {@link org.osid.filing.FilingException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @access public
     */
    public function getAvailableBytes (); 

    /**
     * Get number of bytes used in this Cabinet.
     *  
     * @return int
     * 
     * @throws object FilingException An exception with one of the
     *         following messages defined in org.osid.filing.FilingException
     *         may be thrown: {@link org.osid.filing.FilingException#IO_ERROR
     *         IO_ERROR}, {@link org.osid.filing.FilingException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @access public
     */
    public function getUsedBytes (); 
}

?>
<?php 
 
include_once(dirname(__FILE__)."/../OsidManager.php");
/**
 * <p>
 * LoggingManager handles creating, deleting, and getting logs for reading or
 * writing.  All log Entries have a formatType, a priorityType, and a
 * timestamp.
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
 * <p>
 * Licensed under the {@link org.osid.SidImplementationLicenseMIT MIT
 * O.K.I&#46; OSID Definition License}.
 * </p>
 * 
 * @package org.osid.logging
 */
interface LoggingManager
    extends OsidManager
{
    /**
     * Return the format types available with this implementation.
     *  
     * @return object TypeIterator
     * 
     * @throws object LoggingException An exception with one of the
     *         following messages defined in org.osid.logging.LoggingException
     *         may be thrown:  {@link
     *         org.osid.logging.LoggingException#UNIMPLEMENTED UNIMPLEMENTED},
     *         {@link org.osid.logging.LoggingException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.logging.LoggingException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.logging.LoggingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}
     * 
     * @access public
     */
    public function getFormatTypes (); 

    /**
     * Return the priority types available with this implementation.
     *  
     * @return object TypeIterator
     * 
     * @throws object LoggingException An exception with one of the
     *         following messages defined in org.osid.logging.LoggingException
     *         may be thrown:  {@link
     *         org.osid.logging.LoggingException#UNIMPLEMENTED UNIMPLEMENTED},
     *         {@link org.osid.logging.LoggingException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.logging.LoggingException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.logging.LoggingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}
     * 
     * @access public
     */
    public function getPriorityTypes (); 

    /**
     * Return the names of writable Logs.
     *  
     * @return object StringIterator
     * 
     * @throws object LoggingException An exception with one of the
     *         following messages defined in org.osid.logging.LoggingException
     *         may be thrown:  {@link
     *         org.osid.logging.LoggingException#UNIMPLEMENTED UNIMPLEMENTED},
     *         {@link org.osid.logging.LoggingException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.logging.LoggingException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.logging.LoggingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}
     * 
     * @access public
     */
    public function getLogNamesForWriting (); 

    /**
     * Get an existing log for writing.
     * 
     * @param string $logName
     *  
     * @return object WritableLog
     * 
     * @throws object LoggingException An exception with one of the
     *         following messages defined in org.osid.logging.LoggingException
     *         may be thrown:  {@link
     *         org.osid.logging.LoggingException#UNIMPLEMENTED UNIMPLEMENTED},
     *         {@link org.osid.logging.LoggingException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.logging.LoggingException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.logging.LoggingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.logging.LoggingException#UNKNOWN_NAME UNKNOWN_NAME}
     * 
     * @access public
     */
    public function getLogForWriting ( $logName ); 

    /**
     * Return the names of readable Logs.
     *  
     * @return object StringIterator
     * 
     * @throws object LoggingException An exception with one of the
     *         following messages defined in org.osid.logging.LoggingException
     *         may be thrown:  {@link
     *         org.osid.logging.LoggingException#UNIMPLEMENTED UNIMPLEMENTED},
     *         {@link org.osid.logging.LoggingException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.logging.LoggingException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.logging.LoggingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}
     * 
     * @access public
     */
    public function getLogNamesForReading (); 

    /**
     * Get an existing log for reading.
     * 
     * @param string $logName
     *  
     * @return object ReadableLog
     * 
     * @throws object LoggingException An exception with one of the
     *         following messages defined in org.osid.logging.LoggingException
     *         may be thrown:  {@link
     *         org.osid.logging.LoggingException#UNIMPLEMENTED UNIMPLEMENTED},
     *         {@link org.osid.logging.LoggingException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.logging.LoggingException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.logging.LoggingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.logging.LoggingException#UNKNOWN_NAME UNKNOWN_NAME}
     * 
     * @access public
     */
    public function getLogForReading ( $logName ); 

    /**
     * Create a Writable Log.
     * 
     * @param string $logName
     *  
     * @return object WritableLog
     * 
     * @throws object LoggingException An exception with one of the
     *         following messages defined in org.osid.logging.LoggingException
     *         may be thrown:  {@link
     *         org.osid.logging.LoggingException#UNIMPLEMENTED UNIMPLEMENTED},
     *         {@link org.osid.logging.LoggingException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.logging.LoggingException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.logging.LoggingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.logging.LoggingException#DUPLICATE_NAME
     *         DUPLICATE_NAME}
     * 
     * @access public
     */
    public function createLog ( $logName ); 

    /**
     * Delete the log with the specified name.
     * 
     * @param string $logName
     * 
     * @throws object LoggingException An exception with one of the
     *         following messages defined in org.osid.logging.LoggingException
     *         may be thrown:  {@link
     *         org.osid.logging.LoggingException#UNIMPLEMENTED UNIMPLEMENTED},
     *         {@link org.osid.logging.LoggingException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.logging.LoggingException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.logging.LoggingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.logging.LoggingException#UNKNOWN_NAME UNKNOWN_NAME}
     * 
     * @access public
     */
    public function deleteLog ( $logName ); 

    /**
     * This method indicates whether this implementation supports the
     * ReadableLog interface and the LoggingManager methods: getLogForReading
     * and getLogNamesForReading.
     *  
     * @return boolean
     * 
     * @throws object LoggingException An exception with one of the
     *         following messages defined in org.osid.logging.LoggingException
     *         may be thrown:  {@link
     *         org.osid.logging.LoggingException#UNIMPLEMENTED UNIMPLEMENTED},
     *         {@link org.osid.logging.LoggingException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.logging.LoggingException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.logging.LoggingException#PERMISSION_DENIED
     *         PERMISSION_DENIED}
     * 
     * @access public
     */
    public function supportsReading (); 
}

?>
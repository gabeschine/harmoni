<?php 
 
/**
 * WorkEventIterator provides access to these objects sequentially, one at a
 * time.  The purpose of all Iterators is to to offer a way for OSID methods
 * to return multiple values of a common type and not use an array.  Returning
 * an array may not be appropriate if the number of values returned is large
 * or is fetched remotely.  Iterators do not allow access to values by index,
 * rather you must access values in sequence. Similarly, there is no way to go
 * backwards through the sequence unless you place the values in a data
 * structure, such as an array, that allows for access by index.
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
 * @package org.osid.workflow
 */
class WorkEventIterator
{
    /**
     * Return true if there is an additional  WorkEvent ; false otherwise.
     *  
     * @return boolean
     * 
     * @throws object WorkflowException An exception with one of the
     *         following messages defined in
     *         org.osid.workflow.WorkflowException may be thrown:  {@link
     *         org.osid.workflow.WorkflowException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.workflow.WorkflowException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.workflow.WorkflowException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.workflow.WorkflowException#UNIMPLEMENTED
     *         UNIMPLEMENTED}
     * 
     * @access public
     */
    function hasNextWorkEvent () { 
        die ("Method <b>".__FUNCTION__."()</b> declared in interface<b> ".__CLASS__."</b> has not been overloaded in a child class."); 
    } 

    /**
     * Return the next WorkEvent.
     *  
     * @return object WorkEvent
     * 
     * @throws object WorkflowException An exception with one of the
     *         following messages defined in
     *         org.osid.workflow.WorkflowException may be thrown:  {@link
     *         org.osid.workflow.WorkflowException#OPERATION_FAILED
     *         OPERATION_FAILED}, {@link
     *         org.osid.workflow.WorkflowException#PERMISSION_DENIED
     *         PERMISSION_DENIED}, {@link
     *         org.osid.workflow.WorkflowException#CONFIGURATION_ERROR
     *         CONFIGURATION_ERROR}, {@link
     *         org.osid.workflow.WorkflowException#UNIMPLEMENTED
     *         UNIMPLEMENTED}, {@link
     *         org.osid.workflow.WorkflowException#NO_MORE_ITERATOR_ELEMENTS
     *         NO_MORE_ITERATOR_ELEMENTS}
     * 
     * @access public
     */
    function &nextWorkEvent () { 
        die ("Method <b>".__FUNCTION__."()</b> declared in interface<b> ".__CLASS__."</b> has not been overloaded in a child class."); 
    } 
}

?>
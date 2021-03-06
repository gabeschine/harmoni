<?php 
 
/**
 * The Type class captures the fundamental concept of categorizing an object.
 * Type are designed to be shared among various OSIDs and Managers.  The exact
 * meaning of a particular type is left to the developers who use a given Type
 * subclass.  The form of the Type class enables categorization. There are
 * four Strings that make up the Type class: authority, domain, keyword, and
 * description.  The first three of these Strings are used by the isEqual
 * method to determine if two instance of the Type class are equal.  The
 * fourth String, description, is used to clarify the semantic meaning of the
 * instance.
 * 
 * <p>
 * An example of a FunctionType instance:
 * </p>
 * 
 * <p>
 * <br>  - authority is "higher ed"
 * <br>  - domain is "authorization
 * <br>  - keyword is "writing checks"
 * <br>  - description is "This is the FunctionType for writing checks"
 * </p>
 * 
 * <p>
 * This Type could be used with the authorization OSID.  It could also be used
 * with the dictionary OSID to determine the text to display for a given
 * locale (for example, CANADA_FRENCH).  The dictionary OSID could use the
 * FunctionType instance as a key to find the display text, but it could also
 * use just the keyword string from the FunctionType as a key.  By using the
 * keyword the same display text could then be used for other FunctionTypes
 * such as: 
 * <br>  - authority is "mit"
 * <br>  - domain is "accounting"
 * <br>  - keyword is "writing checks"
 * <br>  - description is "A/P check writing type"
 * <br>An instance of the Type class can be used in a variety of ways to
 * categorize information either as a complete object or as one of its parts
 * (ie authority, domain, keyword).
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
 * @package org.osid.shared
 */
class Type
    extends stdClass
{
    /**
     * 
     * @param object Type $type2
     *  
     * @return boolean
     * 
     * @access public
     */
    public final function isEqual ( Type $type2 )
    {
        if ((null != $type2) && (null != $type2->getDomain()) &&
                (null != $type2->getAuthority()) && (null != $type2->getKeyword()) &&
                (null != $this->getDomain()) && (null != $this->getAuthority()) &&
                (null != $this->getKeyword())) {
            return ($this->getDomain() == $type2->getDomain() 
            		&& $this->getAuthority() == $type2->getAuthority() 
            		&& $this->getKeyword() == $type2->getKeyword()
            	)?TRUE:FALSE;
        }

        return false;
    }

    /**
     *  
     * @return string
     * 
     * @access public
     */
    public final function getAuthority ()
    {
        return $this->authority;
    }

    /**
     *  
     * @return string
     * 
     * @access public
     */
    public final function getDomain ()
    {
        return $this->domain;
    }

    /**
     *  
     * @return string
     * 
     * @access public
     */
    public final function getKeyword ()
    {
        return $this->keyword;
    }

    /**
     *  
     * @return string
     * 
     * @access public
     */
    public final function getDescription ()
    {
        return $this->description;
    }


	/**
	 * Constructor
	 *
	 * @param string $domain
	 * @param string $authority
	 * @param string $keyword
	 * @param optional string $description
	 *
	 * @return object
	 */
	public function __construct ( $domain, $authority, $keyword, $description = "" ) {
        $this->domain = $domain;
        $this->authority = $authority;
        $this->keyword = $keyword;
        $this->description = $description;
    }
    
    /**
     * Return a string version of the type with the delimiter specified.
     * 
     * @param optional string $delimiter
     * @return string
     * @access public
     * @since 2/5/08
     */
    public function asString ($delimiter = '::') {
    	return $this->domain.$delimiter.$this->authority.$delimiter.$this->keyword;
    }
}

?>
<?php

require_once(HARMONI."utilities/FieldSetValidator/rules/ValidatorRule.interface.php");

/**
 * an AlwaysTrueValidatorRule will always return valid for a given value
 *
 * @version $Id: AlwaysTrueValidatorRule.class.php,v 1.1 2003/08/14 19:26:31 gabeschine Exp $
 * @copyright 2003 
 * @package harmoni.utilities.fieldsetvalidator.rules
 **/

class AlwaysTrueValidatorRule
	extends ValidatorRuleInterface
{
	/**
	 * returns true no matter what
	 * @param mixed $val the value to check against the regex
	 * @access public
	 * @return boolean true if the check succeeds, false if it (guess...) fails.
	 **/
	function check( & $val ) {
		return true;
	}
}

?>
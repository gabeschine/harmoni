<?php

require_once(HARMONI."utilities/FieldSetValidator/rules/ValidatorRule.interface.php");

/**
 * the OrValidatorRule takes 2 other rules and validates if at least one of the 2 validates.
 *
 * @package harmoni.utilities.fieldsetvalidator.rules
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: OrValidatorRule.class.php,v 1.3 2005/01/19 21:10:16 adamfranco Exp $
 */ 
class OrValidatorRule extends ValidatorRuleInterface {


	/**
	 * the constructur
	 * 
	 * @param object ValidatorRule $rule1 the first rule
	 * @param object ValidatorRule $rule2 the second rule
	 * @access public
	 * @return void 
	 **/
	function OrValidatorRule( & $rule1, & $rule2 ) {
		$this->_rule1 =& $rule1;
		$this->_rule2 =& $rule2;
	}



	/**
	 * the OrValidatorRule takes 2 other rules and validates if at least one of the 2 validates.
	 * @param mixed $rule1 The first rule.
	 * @access public
	 * @return boolean TRUE, if the value is an integer; FALSE if it is not.
	 **/
	function check( & $val ) {
		return ($this->_rule1->check($val) || $this->_rule2->check($val));
	}
}

?>
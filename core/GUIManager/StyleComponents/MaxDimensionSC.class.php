<?php

require_once(HARMONI."GUIManager/StyleComponent.class.php");
require_once(HARMONI."GUIManager/StyleComponents/LengthSC.class.php");

/**
 * The MaxDimensionSC represents CSS "max-height" and "max-width" values.
 * The allowed values are: 
 * <ul style="font-family: monospace;">
 * 		<li> none </li>	
 * 		<li> [specific length value] - a length value (i.e. units are %, px, in, etc.) </li>
 * </ul>
 * <br /><br />
 * The <code>StyleComponent</code> (SC) is the most basic of the three building pieces
 * of CSS styles. It combines a CSS property value with a ValidatorRule to ensure that
 * the value follows a certain format.<br /><br />@
 *
 * @package  harmoni.gui.scs
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: MaxDimensionSC.class.php,v 1.6 2005/03/29 19:44:10 adamfranco Exp $
 */
class MaxDimensionSC extends StyleComponent {

	/**
	 * The constructor.
	 * @param string value The value to assign to this SC.
	 * @access public
	 **/
	function MaxDimensionSC($value) {
		$options = array("none");

		$errDescription = "Could not validate the MaxDimension StyleComponent value \"%s\".
						   Allowed values are: ".implode(", ", $options)."
  					       or a specific distance value (in length units, i.e. px,
						   in, %, etc).";
		
		
		$rule =& CSSLengthValidatorRule::getRule();
		
		$displayName = "Max Dimension";
		$description = "Specifies the length to use. Allowed values are: ".implode(", ", $options)."
  					    or a specific distance value (in length units, i.e. px,
						in, %, etc).";
		
		$this->StyleComponent($value, $rule, $options, false, $errDescription, $displayName, $description);
	}
}
?>
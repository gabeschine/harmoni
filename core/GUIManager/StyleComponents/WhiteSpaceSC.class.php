<?php

require_once(HARMONI."GUIManager/StyleComponent.class.php");

/**
 * The WhiteSpaceSC represents CSS white-space values. The allowed values are:
 * <ul style="font-family: monospace;">
 * 		<li> normal </li>
 * 		<li> pre </li>
 * 		<li> nowrap </li>
 * </ul>
 * <br /><br />
 * The <code>StyleComponent</code> (SC) is the most basic of the three building pieces
 * of CSS styles. It combines a CSS property value with a ValidatorRule to ensure that
 * the value follows a certain format.<br /><br />
 *
 * @package  harmoni.gui.scs
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: WhiteSpaceSC.class.php,v 1.4 2005/01/19 21:09:33 adamfranco Exp $
 */
class WhiteSpaceSC extends StyleComponent {

	/**
	 * The constructor.
	 * @param string value The value to assign to this SC.
	 * @access public
	 **/
	function WhiteSpaceSC($value) {
		$options = array("normal","pre","nowrap");
	
		$errDescription = "Could not validate the white-space StyleComponent value \"$value\". ";
		$errDescription .= "Allowed values are ".implode(", ", $options).".";
		
		$displayName = "White Space";
		$description = "Specifies the white space property. Allowed values are: ".implode(", ", $options).".";
		
		$this->StyleComponent($value, null, $options, true, $errDescription, $displayName, $description);
	}
}
?>
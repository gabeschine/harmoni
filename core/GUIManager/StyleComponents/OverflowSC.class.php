<?php

require_once(HARMONI."GUIManager/StyleComponent.class.php");

/**
 * The OverflowSC represents CSS overflow values. The allowed values are:
 * <ul style="font-family: monospace;">
 * 		<li> visible </li>
 * 		<li> hidden </li>
 * 		<li> scroll </li>
 * 		<li> auto </li>
 * </ul>
 * <br><br>
 * The <code>StyleComponent</code> (SC) is the most basic of the three building pieces
 * of CSS styles. It combines a CSS property value with a ValidatorRule to ensure that
 * the value follows a certain format.<br><br>
 * The other two CSS styles building pieces are <code>StyleProperties</code> and
 * <code>StyleCollections</code>. To clarify the relationship between these three
 * building pieces, consider the following example:
 * <pre>
 * div {
 *     margin: 20px;
 *     border: 1px solid #000;
 * }
 * </pre>
 * <code>div</code> is a <code>StyleCollection</code> consisting of 2 
 * <code>StyleProperties</code>: <code>margin</code> and <code>border</code>. Each
 * of the latter consists of one or more <code>StyleComponents</code>. In
 * specific, <code>margin</code> consists of one <code>StyleComponent</code>
 * with the value <code>20px</code>, and <code>border</code> has three 
 * <code>StyleComponents</code> with values <code>1px</code>, <code>solid</code>,
 * and <code>#000</code> correspondingly.
 * 
 * @version $Id: OverflowSC.class.php,v 1.1 2004/07/15 21:29:29 tjigmes Exp $
 * @package 
 * @author Middlebury College, ETS
 * @copyright 2004 Middlebury College, ETS
 * @access public
 **/

class OverflowSC extends StyleComponent {

	/**
	 * The constructor.
	 * @param string value The value to assign to this SC.
	 * @access public
	 **/
	function OverflowSC($value) {
		$options = array("visible", "hidden", "scroll", "auto");
	
		$errDescription = "Could not validate the overflow StyleComponent value \"$value\". ";
		$errDescription .= "Allowed values are ".implode(", ", $options).".";
		
		$displayName = "Overflow";
		$description = "Specifies the overflow property value. Allowed values are: ".implode(", ", $options).".";
		
		$this->StyleComponent($value, null, $options, true, $errDescription, $displayName, $description);
	}
}
?>
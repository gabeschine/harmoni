<?php

require_once(HARMONI."layoutHandler/components/Layout.abstract.php");

/**
 * The single content {@link Layout} contains only one content component. Useful for
 * filling the space in a layout that expects another layout as a component with just
 * some content.
 * <br />
 * Content: <br />
 * <ul><li />Index: 0, A Content object.</ul>
 *
 * @package harmoni.layout.components
 * @version $Id: SingleContentLayout.class.php,v 1.1 2003/08/14 19:26:30 gabeschine Exp $
 * @copyright 2003 
 **/

class SingleContentLayout extends Layout {
	/**
	 * The constructor.
	 * @access public
	 * @return void
	 **/
	function SingleContentLayout() {
		$this->addComponent(0,CONTENT);
	}
	
	/**
	 * Prints the component out using the given theme.
	 * @param object $theme The theme object to use.
	 * @access public
	 * @return void
	 **/
	function outputLayout(&$theme) {
		$this->verifyComponents();

		$c =& $this->getComponent(0);
		$c->output($theme);
	}
}

?>
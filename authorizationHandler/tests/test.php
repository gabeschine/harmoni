<?php

/**
 * A group test template using the SimpleTest unit testing package.
 * Just add the UnitTestCase files below using addTestFile().
 *
 * @version $Id: test.php,v 1.1 2003/06/30 14:50:37 adamfranco Exp $
 * @copyright 2003 
 **/

    if (!defined('HARMONI')) {
        require_once("../../harmoni.inc.php");
    }

    if (!defined('SIMPLE_TEST')) {
        define('SIMPLE_TEST', HARMONI.'simple_test/');
    }

    require_once(SIMPLE_TEST . 'simple_unit.php');
    require_once(SIMPLE_TEST . 'dobo_simple_html_test.php');
	
    $test =& new GroupTest('AuthorizationHandler tests');
//    $test->addTestFile(HARMONI.'examplePackage/tests/ExampleClassTestCase.class.php');
    $test->attachObserver(new DoboTestHtmlDisplay());
    $test->run();

?>
<?php
    // $Id: unit_tests.php,v 1.1 2003/08/14 19:26:31 gabeschine Exp $
    
    if (!defined("SIMPLE_TEST")) {
        define("SIMPLE_TEST", "../");
    }
    require_once(SIMPLE_TEST . 'simple_unit.php');
    require_once(SIMPLE_TEST . 'simple_html_test.php');
    require_once(SIMPLE_TEST . 'simple_mock.php');
    
    class UnitTests extends GroupTest {
        function UnitTests() {
            $this->GroupTest("Unit tests");
            $this->addTestFile("simple_mock_test.php");
            $this->addTestFile("web_test_test.php");
            $this->addTestFile("socket_test.php");
            $this->addTestFile("http_test.php");
            $this->addTestFile("browser_test.php");
            $this->addTestFile("parser_test.php");
        }
    }
    
    if (!defined("TEST_RUNNING")) {
        define("TEST_RUNNING", true);
        $test = &new UnitTests();
        $test->attachObserver(new TestHtmlDisplay());
        $test->run();
    }
?>
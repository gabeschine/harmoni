<?php
    // $Id: simple_html_test.php,v 1.2 2005/01/19 16:33:27 adamfranco Exp $
    
    if (!defined("SIMPLE_TEST")) {
        define("SIMPLE_TEST", "./");
    }
    require_once(SIMPLE_TEST . 'simple_test.php');
    
    /**
     *    Sample minimal test displayer. Generates only
     *    failure messages and a pass count.
     */
    class TestHtmlDisplay extends TestDisplay {
        
        /**
         *    Does nothing yet. The first output will
         *    be sent on the first test start.
         */
        function TestHtmlDisplay() {
            $this->TestDisplay();
        }
        
        /**
         *    Paints the top of the web page setting the
         *    title to the name of the starting test.
         *    @param $test_name        Name class of test.
         */
        function paintHeader($test_name) {
            print "<html>\n<head>\n<title>$test_name</title>\n";
            print "<style type=\"text/css\">\n";
            print $this->_getCss() . "\n";
            print "</style>\n";
            print "</head>\n<body>\n";
            print "<h1>$test_name</h1>\n";
        }
        
        /**
         *    Paints the CSS. Add additional styles here.
         *    @access protected
         */
        function _getCss() {
            return ".fail { color: red; }";
        }
        
        /**
         *    Paints the end of the test with a summary of
         *    the passes and failures.
         *    @param $test_name        Name class of test.
         */
        function paintFooter($test_name) {
            $colour = ($this->_fails > 0 ? "red" : "green");
            print "<div style=\"";
            print "padding: 8px; margin-top: 1em; background-color: $colour; color: white;";
            print "\">";
            print $this->getTestCaseProgress() . "/" . $this->getTestCaseCount();
            print " test cases complete:\n";
            print "<strong>" . $this->getPassCount() . "</strong> passes";
            print " and <strong>" . $this->getFailCount() . "</strong> fails.";
            print "</div>\n";
            print "</body>\n</html>\n";
        }
        
        /**
         *    Paints the test failure with a breadcrumbs
         *    trail of the nesting test suites below the
         *    top level test.
         *    @param $message        Failure message displayed in
         *                           the context of the other tests.
         */
        function paintFail($message) {
            parent::paintFail($message);
            print "<span class=\"fail\">Fail</span>: ";
            $breadcrumb = $this->getTestList();
            array_shift($breadcrumb);
            print implode("-&gt;", $breadcrumb);
            print "-&gt;$message<br />\n";
        }
    }
?>

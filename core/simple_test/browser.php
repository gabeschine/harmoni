<?php
    // $Id: browser.php,v 1.3 2007/09/04 20:25:49 adamfranco Exp $
    
    if (!defined("SIMPLE_TEST")) {
        define("SIMPLE_TEST", "./");
    }
    require_once(SIMPLE_TEST . 'http.php');
    require_once(SIMPLE_TEST . 'simple_unit.php');
    
    /**
     *    Repository for cookies. The semantics are a bit
     *    ropy until I can go through the cookie spec with
     *    a fine tooth combe.
     */
    class CookieJar {
        var $_cookies;
        
        /**
         *    Constructor. Jar starts empty.
         *    @access public
         */
        function CookieJar() {
            $this->_cookies = array();
        }
        
        /**
         *    Adds a cookie to the jar. This will overwrite
         *    cookies with matching paths and keys.
         *    @param $cookie        New cookie.
         *    @access public
         */
        function setCookie($cookie) {
            for ($i = 0; $i < count($this->_cookies); $i++) {
                $is_match = $this->_isMatch(
                        $cookie,
                        $this->_cookies[$i]->getHost(),
                        $this->_cookies[$i]->getPath(),
                        $this->_cookies[$i]->getName(),
                        $this->_cookies[$i]->getExpiry());
                if ($is_match) {
                    $this->_cookies[$i] = $cookie;
                    return;
                }
            }
            $this->_cookies[] = $cookie;
        }
        
        /**
         *    Fetches a hash of all valid cookies filtered
         *    by host, path and date and keyed by name
         *    Any cookies with missing categories will not
         *    be filtered out by that category. Empty cookies
         *    not be filtered unless thay are also expired.
         *    Don't blame ne, blame the cookie spec!
         *    @param $host        Host name requirement.
         *    @param $path        Path encompassing cookies.
         *    @param $date        Date to test expiries against,
         *                        either a timestamp or as a
         *                        cookie formatted date string.
         *    @return             Hash of valid cookie objects keyed
         *                        on the cookie name.
         *    @access public
         */
        function getValidCookies($host = false, $path = "/", $date = false) {
            $valid_cookies = array();
            foreach ($this->_cookies as $cookie) {
                if ($this->_isMatch($cookie, $host, $path, $cookie->getName(), $date)) {
                    $valid_cookies[] = $cookie;
                }
            }
            return $valid_cookies;
        }
        
        /**
         *    Tests cookie for matching against search
         *    criteria.
         *    @param $cookie        Cookie to test.
         *    @param $host          Host must match.
         *    @param $path          Cookie path must be shorter than
         *                          this path.
         *    @param $name          Name must match.
         *    @param $date          Cookie must not have expired at
         *                          this time.
         *    @return               True if matched.
         *    @access private
         */
        function _isMatch($cookie, $host, $path, $name, $date) {
            if ($cookie->getName() != $name) {
                return false;
            }
            if ($host && $cookie->getHost() && !$cookie->isValidHost($host)) {
                return false;
            }
            if (!$cookie->isValidPath($path)) {
                return false;
            }
            if ($cookie->isExpired($date)) {
                return false;
            }
            return true;
        }
    }
    
    /**
     *    Fake web browser. Can be set up to automatically
     *    test reponses.
     */
    class TestBrowser {
        var $_test;
        var $_response;
        var $_expect_connection;
        var $_expected_response_codes;
        var $_expected_cookies;
        var $_cookie_jar;
        
        /**
         *    Starts the browser empty.
         *    @param $test     Test case with assertTrue().
         *    @access public
         */
        function TestBrowser($test) {
            $this->_test = $test;
            $this->_response = false;
            $this->_cookie_jar = new CookieJar();
            $this->_clearExpectations();
        }
        
        /**
         *    Resets all expectations.
         *    @access protected
         */
        function _clearExpectations() {
            $this->_expect_connection = null;
            $this->_expected_response_codes = null;
            $this->_expected_mime_types = null;
            $this->_expected_cookies = array();
        }
        
        /**
         *    Fetches a URL performing the standard tests.
         *    @param $url        Target to fetch as string.
         *    @param $request    Test version of SimpleHttpRequest.
         *    @return            Content of page.
         *    @access public
         */
        function fetchUrl($url, $request = false) {
            if (!is_object($request)) {
                $request = new SimpleHttpRequest($url);
            }
            foreach ($this->_cookie_jar->getValidCookies() as $cookie) {
                $request->setCookie($cookie);
            }
            $this->_response = $request->fetch();
            $this->_checkExpectations($url, $this->_response);
            foreach ($this->_response->getNewCookies() as $cookie) {
                $parsed_url = new SimpleUrl($url);
                if ($parsed_url->getHost()) {
                    $cookie->setHost($parsed_url->getHost());
                }
                $this->_cookie_jar->setCookie($cookie);
            }
            return $this->_response->getContent();
        }
        
        /**
         *    Set the next fetch to expect a connection
         *    failure.
         *    @param $is_expected        True if failure wanted.
         *    @access public
         */
        function expectConnection($is_expected = true) {
            $this->_expect_connection = $is_expected;
        }
        
        /**
         *    Sets the allowed response codes.
         *    @param $codes        Array of allowed codes.
         *    @access public
         */
        function expectResponseCodes($codes) {
            $this->_expected_response_codes = $codes;
        }
        
        /**
         *    Sets the allowed mime types and adds the
         *    necessary request headers.
         *    @param $types        Array of allowed types.
         *    @access public
         */
        function expectMimeTypes($types) {
            $this->_expected_mime_types = $types;
        }
        
        /**
         *    Sets an additional cookie. If a cookie has
         *    the same name and path it is replaced.
         *    @param $name            Cookie key.
         *    @param $value           Value of cookie.
         *    @param $host            Host upon which the cookie is valid.
         *    @param $path            Cookie path if not host wide.
         *    @param $expiry          Expiry date as string.
         *    @access public
         */
        function setCookie($name, $value, $host = false, $path = "/", $expiry = false) {
            $cookie = new SimpleCookie($name, $value, $path, $expiry);
            if ($host) {
                $cookie->setHost($host);
            }
            $this->_cookie_jar->setCookie($cookie);
        }
        
        /**
         *    Sets an expectation for a cookie.
         *    @param $name        Cookie key.
         *    @param $value       Expected value of incoming cookie.
         *    @access public
         */
        function expectCookie($name, $value = false) {
            $this->_expected_cookies[] = array("name" => $name, "value" => $value);
        }
        
        /**
         *    Reads a cookie value from the browser cookies.
         *    @param $host        Host to search.
         *    @param $path        Applicable path.
         *    @param $name        Name of cookie to read.
         *    @param $date        Time when cookie is to be tested.
         *    @return             Null if not present, else the
         *                        value as a string.
         *    @access public
         */
        function getCookieValues($host, $path, $name, $date = false) {
            $values = array();
            foreach ($this->_cookie_jar->getValidCookies($host, $path, $date) as $cookie) {
                if ($name == $cookie->getName()) {
                    $values[] = $cookie->getValue();
                }
            }
            return $values;
        }
        
        /**
         *    Checks that the headers are as expected.
         *    Each expectation sends a test event.
         *    @param $url         Target URL.
         *    @param $reponse     HTTP response from the fetch.
         *    @access private
         */
        function _checkExpectations($url, $response) {
            if (isset($this->_expect_connection)) {
                $this->_assertTrue(
                        $response->isError() != $this->_expect_connection,
                        "Fetching $url with error [" . $response->getError() . "]");
            }
            if (isset($this->_expected_response_codes)) {
                $this->_assertTrue(
                        in_array($response->getResponseCode(), $this->_expected_response_codes),
                        "Fetching $url with response code [" . $response->getResponseCode() . "]");
            }
            if (isset($this->_expected_mime_types)) {
                $this->_assertTrue(
                        in_array($response->getMimeType(), $this->_expected_mime_types),
                        "Fetching $url with mime type [" . $response->getMimeType() . "]");
            }
            $cookies = $response->getNewCookies();
            foreach($this->_expected_cookies as $expectation) {
                $this->_checkExpectedCookie($expectation, $cookies);
            }
        }
        
        /**
         *    Checks that an expected cookie was present
         *    in the incoming cookie list. The cookie
         *    should appear only once.
         *    @param $expected    Expected cookie.
         *    @param $cookies     Incoming.
         *    @return             True if expectation met.
         *    @access private
         */
        function _checkExpectedCookie($expected, $cookies) {
            $is_match = false;
            $message = "Expected cookie " . $expected["name"] . " not found";
            foreach ($cookies as $cookie) {
                if ($cookie->getName() == $expected["name"]) {
                    $is_match = ($cookie->getValue() == $expected["value"]);
                    $message = "Expected cookie " . $expected["name"] .
                            " value " . $expected["value"] .
                            " should be [" . $cookie->getValue() . "]";
                }
            }
            $this->_assertTrue($is_match, $message);
        }
        
        /**
         *    Sends an assertion to the held test case.
         *    @param $result        True on success.
         *    @param $message       Message to send to test.
         *    @access protected
         */
        function _assertTrue($result, $message) {
            $this->_test->assertTrue($result, $message);
        }
    }
?>
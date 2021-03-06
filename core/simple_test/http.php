<?php
    // $Id: http.php,v 1.4 2007/09/04 20:25:50 adamfranco Exp $

    if (!defined("SIMPLE_TEST")) {
        define("SIMPLE_TEST", "./");
    }
    require_once(SIMPLE_TEST . 'socket.php');
    
    /**
     *    URL parser to replace parse_url() PHP function.
     */
    class SimpleUrl {
        var $_scheme;
        var $_host;
        var $_path;
        var $_request;
        
        /**
         *    Constructor. Parses URL into sections.
         *    @param $url            URL as string.
         *    @access public
         */
        function SimpleUrl($url) {
            $this->_scheme = "http";
            $this->_host = "localhost";
            $this->_path = "/";
            $this->_request = array();
            if (preg_match('/(.*?):\/\/(.*)/', $url, $matches)) {
                $this->_scheme = $matches[1];
                $url = $matches[2];
            }
            if (preg_match('/(.*?)(\/|\?)(.*)/', $url, $matches)) {
                $this->_host = $matches[1];
                $this->_path = ($matches[2] == "/" ? "/" : "/?") . $matches[3];
            }
            if (preg_match('/(.*?)\?(.*)/', $this->_path, $matches)) {
                $this->_path = $matches[1];
                $this->_request = $this->_parseRequest($matches[2]);
            }
        }
        
        /**
         *    Breaks the request down into a hash.
         *    @param $raw        Raw request string.
         *    @return            Hash of GET data.
         *    @access private
         */
        function _parseRequest($raw) {
            $request = array();
            foreach (split("&", $raw) as $pair) {
                if (preg_match('/(.*?)=(.*)/', $pair, $matches)) {
                    $request[$matches[1]] = urldecode($matches[2]);
                }
            }
            return $request;
        }
        
        /**
         *    Accessor for protocol part.
         *    @return        Scheme name, e.g "http".
         *    @access public
         */
        function getScheme() {
            return $this->_scheme;
        }
        
        /**
         *    Accessor for hostname and port.
         *    @return        Hostname only.
         *    @access public
         */
        function getHost() {
            return $this->_host;
        }
        
        /**
         *    Accessor for path.
         *    @return     Full path including leading slash.
         *    @access public
         */
        function getPath() {
            return $this->_path;
        }
        
        /**
         *    Accessor for current request parameters
         *    in URL string form
         *    @return    Form is string "a=1&b=2", etc.
         *    @access public
         */
        function getEncodedRequest() {
            $parameters = array();
            foreach ($this->getRequest() as $key => $value) {
                $parameters[] = $key . "=" . urlencode($value);
            }
            return (count($parameters) > 0 ? "?" : "") . implode("&", $parameters);
        }
        
        /**
         *    Accessor for current request parameters
         *    as a hash.
         *    @return    Hash of name value pairs.
         *    @access public
         */
        function getRequest() {
            return $this->_request;
        }
        
        /**
         *    Adds an additional parameter to the request.
         *    @param $key            Name of parameter.
         *    @param $value          Value as string.
         *    @access public
         */
        function addRequestParameter($key, $value) {
            $this->_request[$key] = $value;
        }
    }

    /**
     *    Cookie data holder. Cookie rules are full of pretty
     *    arbitary stuff. I have used...
     *    http://wp.netscape.com/newsref/std/cookie_spec.html
     *    http://www.cookiecentral.com/faq/
     */
    class SimpleCookie {
        var $_host;
        var $_name;
        var $_value;
        var $_path;
        var $_expiry;
        var $_is_secure;
        
        /**
         *    Constructor. Sets the stored values.
         *    @param $name            Cookie key.
         *    @param $value           Value of cookie.
         *    @param $path            Cookie path if not host wide.
         *    @param $expiry          Expiry date as string.
         *    @param $is_secure       True if SSL is demanded.
         */
        function SimpleCookie($name, $value = false, $path = false, $expiry = false, $is_secure = false) {
            $this->_host = false;
            $this->_name = $name;
            $this->_value = $value;
            $this->_path = ($path ? $this->_fixPath($path) : "/");
            $this->_expiry = false;
            if (is_string($expiry)) {
                $this->_expiry = strtotime($expiry);
            } elseif (is_integer($expiry)) {
                $this->_expiry = $expiry;
            }
            $this->_is_secure = $is_secure;
        }
        
        /**
         *    Sets the host. The cookie rules determine
         *    that the first two parts are taken for
         *    certain TLDs and three for others. If the
         *    new host does not match these rules then the
         *    call will fail.
         *    @param $host       New hostname.
         *    @return            True if hostname is valid.
         *    @access public
         */
        function setHost($host) {
            if ($host = $this->_truncateHost($host)) {
                $this->_host = $host;
                return true;
            }
            return false;
        }
        
        /**
         *    Accessor for the truncated host to which this
         *    cookie applies.
         *    @return        Truncated hostname as string.
         *    @access public
         */
        function getHost() {
            return $this->_host;
        }
        
        /**
         *    Test for a cookie being valid for a host name.
         *    @param $host    Host to test against.
         *    @return         True if the cookie would be valid
         *                    here.
         */
        function isValidHost($host) {
            return $this->_truncateHost($host) === $this->getHost();
        }
        
        /**
         *    Extracts just the domain part that determines a
         *    cookie's host validity.
         *    @param $host    Host name to truncate.
         *    @return         Domain as string or false on a
         *                    bad host.
         *    @access private
         */
        function _truncateHost($host) {
            if (preg_match('/[a-z\-]+\.(com|edu|net|org|gov|mil|int)$/i', $host, $matches)) {
                return $matches[0];
            } elseif (preg_match('/[a-z\-]+\.[a-z\-]+\.[a-z\-]+$/i', $host, $matches)) {
                return $matches[0];
            }
            return false;
        }
        
        /**
         *    Accessor for name.
         *    @return        Cookie key.
         *    @access public
         */
        function getName() {
            return $this->_name;
        }
        
        /**
         *    Accessor for value. A deleted cookie will
         *    have an empty string for this.
         *    @return        Cookie value.
         *    @access public
         */
        function getValue() {
            return $this->_value;
        }
        
        /**
         *    Accessor for path.
         *    @return        Valid cookie path.
         *    @access public
         */
        function getPath() {
            return $this->_path;
        }
        
        /**
         *    Tests a path to see if the cookie applies
         *    there. The test path must be longer or
         *    equal to the cookie path.
         *    @param $path       Path to test against.
         *    @return            True if cookie valid here.
         *    @access public
         */
        function isValidPath($path) {
            if (substr($path, -1) != '/') {
                $path .= '/';
            }
            return (strncmp($path, $this->getPath(), strlen($this->getPath())) == 0);
        }
        
        /**
         *    Accessor for expiry.
         *    @return        Expiry string.
         *    @access public
         */
        function getExpiry() {
            if (!$this->_expiry) {
                return false;
            }
            return gmdate("D, d M Y H:i:s", $this->_expiry) . " GMT";
        }
        
        /**
         *    Test to see if cookie is expired against
         *    the cookie format time or timestamp.
         *    Will give true if no cookie expiry.
         *    @param $now     Time to test against. Result
         *                    will be false if this time
         *                    is later than the cookie expiry.
         *                    Can be either a timestamp integer
         *                    or a cookie format date.
         *    @access public
         */
        function isExpired($now) {
            if (!$this->_expiry) {
                return false;
            }
            if (is_string($now)) {
                $now = strtotime($now);
            }
            return ($this->_expiry < $now);
        }
        
        /**
         *    Accessor for the secure flag.
         *    @return        True if cookie needs SSL.
         *    @access public
         */
        function isSecure() {
            return $this->_is_secure;
        }
        
        /**
         *    Adds a trailing slash to the path if missing.
         *    @param $path            Path to fix.
         *    @access private
         */
        function _fixPath($path) {
            return (substr($path, -1) == '/' ? $path : $path . '/');
        }
    }

    /**
     *    HTTP request for a web page. Factory for
     *    HttpResponse object.
     */
    class SimpleHttpRequest {
        var $_user_headers;
        var $_url;
        var $_cookies;
        
        /**
         *    Saves the URL ready for fetching.
         *    @param $url        URL as string.
         *    @access public
         */
        function SimpleHttpRequest($url) {
            $this->_url = new SimpleUrl($url);
            $this->_user_headers = array();
            $this->_cookies = array();
        }
        
        /**
         *    Fetches the content and parses the headers.
         *    @param $socket        Test override.
         *    @return               Either false or a HttpResponse.
         *    @access public
         */
        function fetch($socket = false) {
            if (!is_object($socket)) {
                $socket = new SimpleSocket($this->_url->getHost());
            }
            if ($socket->isError()) {
                return false;
            }
            $socket->write("GET " . $this->_url->getPath() . $this->_url->getEncodedRequest() . " HTTP/1.0\r\n");
            $socket->write("Host: " . $this->_url->getHost() . "\r\n");
            foreach ($this->_user_headers as $header_line) {
                $socket->write($header_line . "\r\n");
            }
            if (count($this->_cookies) > 0) {
                $socket->write("Cookie: " . $this->_marshallCookies($this->_cookies) . "\r\n");
            }
            $socket->write("Connection: close\r\n");
            $socket->write("\r\n");
            return $this->_createResponse($socket);
        }
        
        /**
         *    Adds a header line to the request.
         *    @param $header_line        Text of header line.
         *    @access public
         */
        function addHeaderLine($header_line) {
            $this->_user_headers[] = $header_line;
        }
        
        /**
         *    Adds a cookie to the request.
         *    @param $cookie     New SimpleCookie object.
         *    @access public
         */
        function setCookie($cookie) {
            $this->_cookies[] = $cookie;
        }
        
        /**
         *    Serialises the cookie hash ready for
         *    transmission.
         *    @param $cookies     Cookies as hash.
         *    @return             Cookies in header form.
         *    @access private
         */
        function _marshallCookies($cookies) {
            $cookie_pairs = array();
            foreach ($cookies as $cookie) {
                $cookie_pairs[] = $cookie->getName() . "=" . $cookie->getValue();
            }
            return implode(";", $cookie_pairs);
        }
        
        /**
         *    Wraps the socket in a response parser.
         *    @param $socket        Responding socket.
         *    @return               Parsed response object.
         *    @access protected
         */
        function _createResponse($socket) {
            $obj = new SimpleHttpResponse($socket);
            return $obj;
        }
    }
    
    /**
     *    Basic HTTP response.
     */
    class SimpleHttpResponse extends StickyError {
        var $_content;
        var $_response_code;
        var $_http_version;
        var $_mime_type;
        var $_cookies;
        
        /**
         *    Constructor. Reads and parses the incoming
         *    content and headers.
         *    @param $socket        Network connection to fetch
         *                          response text from.
         *    @access public
         */
        function SimpleHttpResponse($socket) {
            $this->StickyError();
            $this->_content = false;
            $this->_response_code = 0;
            $this->_http_version = 0;
            $this->_mime_type = "";
            $this->_cookies = array();
            $raw = $this->_readAll($socket);
            if ($socket->isError()) {
                $this->_setError("Error reading socket [" . $socket->getError() . "]");
                return;
            }
            if (!strstr($raw, "\r\n\r\n")) {
                $this->_setError("Could not parse headers");
                return;
            }
            list($headers, $this->_content) = split("\r\n\r\n", $raw, 2);
            foreach (split("\r\n", $headers) as $header_line) {
                $this->_parseHeaderLine($header_line);
            }
        }
        
        /**
         *    Accessor for the content after the last
         *    header line.
         *    @return            All content as string.
         *    @access public
         */
        function getContent() {
            return $this->_content;
        }
        
        /**
         *    Accessor for parsed HTTP protocol version.
         *    @return            HTTP error code integer.
         *    @access public
         */
        function getHttpVersion() {
            return $this->_http_version;            
        }
        
        /**
         *    Accessor for parsed HTTP error code.
         *    @return            HTTP error code integer.
         *    @access public
         */
        function getResponseCode() {
            return (integer)$this->_response_code;            
        }
        
        /**
         *    Accessor for MIME type header information.
         *    @return            MIME type as string.
         *    @access public
         */
        function getMimeType() {
            return $this->_mime_type;            
        }
        
        /**
         *    Accessor for any new cookies.
         *    @return        List of new cookies.
         *    @access public
         */
        function getNewCookies() {
            return $this->_cookies;
        }
        
        /**
         *    Called on each header line. Subclasses should
         *    add behaviour to this method for their
         *    particular header types.
         *    @param $header_line        One line of header.
         *    @access protected
         */
        function _parseHeaderLine($header_line) {
            if (preg_match('/HTTP\/(\d+\.\d+)\s+(.*?)\s/i', $header_line, $matches)) {
                $this->_http_version = $matches[1];
                $this->_response_code = $matches[2];
            }
            if (preg_match('/Content-type:\s*(.*)/i', $header_line, $matches)) {
                $this->_mime_type = trim($matches[1]);
            }
            if (preg_match('/Set-cookie:(.*)/i', $header_line, $matches)) {
                $this->_cookies[] = $this->_parseCookie($matches[1]);
            }
        }
        
        /**
         *    Parse the Set-cookie content.
         *    @param $cookie_line    Text after "Set-cookie:"
         *    @return                New cookie object.
         *    @access private
         */
        function _parseCookie($cookie_line) {
            $parts = split(";", $cookie_line);
            $cookie = array();
            preg_match('/\s*(.*?)\s*=(.*)/', array_shift($parts), $cookie);
            foreach ($parts as $part) {
                if (preg_match('/\s*(.*?)\s*=(.*)/', $part, $matches)) {
                    $cookie[$matches[1]] = trim($matches[2]);
                }
            }
            return new SimpleCookie(
                    $cookie[1],
                    trim($cookie[2]),
                    isset($cookie["path"]) ? $cookie["path"] : "",
                    isset($cookie["expires"]) ? $cookie["expires"] : false);
        }
        
        /**
         *    Reads the whole of the socket output into a
         *    single string.
         *    @param $socket        Unread socket.
         *    @return               String if successful else
         *                          false.
         *    @access private
         */
        function _readAll($socket) {
            $all = "";
            while ($next = $socket->read()) {
                $all .= $next;
            }
            return $all;
        }
    }
?>
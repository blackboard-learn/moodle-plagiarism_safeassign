<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Convenient wrappers and helper for using the SafeAssign web service API.
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace plagiarism_safeassign\api;

defined('MOODLE_INTERNAL') || die();

use plagiarism_safeassign\local;

/**
 * A helper class to access REST api
 *
 * @package   plagiarism_safeassign
 * @copyright Copyright (c) 2017 Blackboard Inc. (http://www.blackboard.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class rest_provider {

    /**
     * @var string HTTP_GET
     */
    const HTTP_GET     = 'GET';

    /**
     * @var string HTTP_POST
     */
    const HTTP_POST    = 'POST';

    /**
     * @var string HTTP_PUT
     */
    const HTTP_PUT     = 'PUT';

    /**
     * @var string HTTP_HEAD
     */
    const HTTP_HEAD    = 'HEAD';

    /**
     * @var string HTTP_DELETE
     */
    const HTTP_DELETE  = 'DELETE';

    /**
     * @var int ERR_UNKNOWN
     */
    const ERR_UNKNOWN  = 1010;

    /**
     * @var string ERR_JSON
     */
    const ERR_JSON     = 1020;

    /**
     * @var string PLUGIN
     */
    const PLUGIN       = 'plagiarism_safeassign';

    /**
     * @var string TOKEN
     */
    const TOKEN        = 'plagiarism_safeassign_token';

    /**
     * @var string STR_HTTP
     */
    const STR_HTTP     = 'HTTP'; // Raw HTTP response parsing.

    /**
     * @var string HEADER_KEY_CONTENT_TYPE
     */
    const HEADER_KEY_CONTENT_TYPE = "Content-Type";

    /**
     * @var string HEADER_KEY_ACCEPT
     */
    const HEADER_KEY_ACCEPT = "Accept";

    /**
     * @var string HEADER_KEY_AUTHORIZATION
     */
    const HEADER_KEY_AUTHORIZATION = "Authorization";

    /**
     * @var string HEADER_KEY_CONTENT_LENGTH
     */
    const HEADER_KEY_CONTENT_LENGTH = "Content-length";

    /**
     * @var string HEADER_VAL_MULTIPART_FORM
     */
    const HEADER_VAL_MULTIPART_FORM = "multipart/form-data";

    /**
     * @var string HEADER_VAL_APP_JSON
     */
    const HEADER_VAL_APP_JSON = "Application/json";

    /**
     * @var null|rest_provider
     */
    private static $instance = null;
    /**
     * @var null|string
     */
    private $token = null;

    /**
     * @var null|string
     */
    private $error = null;
    /**
     * @var int
     */
    private $errorno = 0;

    /**
     * @var null|string
     */
    private $errorstring = null;

    /**
     * @var null|memfile
     */
    private $memfile = null;

    /**
     * @var null|string
     */
    private $rawresponse = null;

    /**
     * @var null|string
     */
    private $respheaders = null;

    /**
     * @var null|int
     */
    private $lasthttpcode = null;

    /**
     * @var null|array
     */
    private $curlinfo = null;

    /**
     * @var null|cache
     */
    private $cache = null;

    /**
     * @var null|string|int
     */
    private $currentuserid = null;

    /**
     * rest_provider constructor.
     * @throws nocurl_exception
     */
    private function __construct() {
        // Something.
        if (!function_exists('curl_init')) {
            throw new nocurl_exception();
        }

        $this->memfile = new memfile();
        $this->cache = new cache();
    }

    /**
     * Empty clone function.
     */
    private function __clone() {
        // Prevent cloning.
    }

    /**
     * Returns singleton instance.
     * @return rest_provider|null
     * @throws nocurl_exception
     */
    public static function instance() {
        if (self::$instance === null) {
            $c = __CLASS__;
            self::$instance = new $c();
        }
        return self::$instance;
    }

    /**
     * Reset cURL cache for current user.
     */
    public function reset_cache() {
        $this->cache->refresh();
    }

    /**
     * Gets default options.
     * @param array $custopts
     * @return array
     */
    public function getopts(array $custopts = array()) {
        $this->memfile->reset();
        $this->rawresponse  = null;
        $this->lasthttpcode = null;
        $this->respheaders  = null;
        $this->error        = null;
        $this->errorno      = 0;
        $this->errorstring  = null;
        $this->curlinfo     = null;

        $standard = array(
            'CURLOPT_FOLLOWLOCATION' => false,
            'CURLOPT_MAXREDIRS'      => 0,
            'CURLOPT_PROTOCOLS'      => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            'CURLOPT_RETURNTRANSFER' => false,
            'CURLOPT_FILE'           => $this->memfile->get(),
            'CURLOPT_SSL_VERIFYPEER' => false,
            'CURLOPT_CONNECTTIMEOUT' => 1,
            'CURLOPT_USERAGENT'      => 'MoodleSafeAssignClient/1.0',
            'CURLOPT_ENCODING'       => '',
            'CURLOPT_HEADER'         => true,
            'CURLOPT_NOPROGRESS'     => true,
            'CURLOPT_FAILONERROR'    => false,
        );

        // Set $CFG->forced_plugin_settings['plagiarism_safeassign'][connecttimeout] to custom connect timeout.
        // For more details on this option take a look at
        // CURLOPT_CONNECTTIMEOUT on http://php.net/manual/en/function.curl-setopt.php .
        $connecttimeout = get_config('plagiarism_safeassign', 'connecttimeout');
        if ($connecttimeout !== false) {
            $standard['CURLOPT_CONNECTTIMEOUT'] = $connecttimeout;
        }

        // Set $CFG->forced_plugin_settings['plagiarism_safeassign'][timeout] to custom timeout.
        // For more details on this option take a look at
        // CURLOPT_TIMEOUT on http://php.net/manual/en/function.curl-setopt.php .
        $timeout = get_config('plagiarism_safeassign', 'timeout');
        if ($timeout !== false) {
            $standard['CURLOPT_TIMEOUT'] = $timeout;
        }

        $options = $standard + $custopts;

        return $options;
    }

    /**
     * Request to webservice.
     *
     * If you are running behat test, this simulates a call to a webservice loading the response from a local json file.
     *
     * @param string $url
     * @param string $method
     * @param array $custheaders
     * @param array $options
     * @return mixed
     * @throws \Exception
     * @throws norequestmethod_exception
     * @throws nourl_exception
     */
    public function request($url, $method, array $custheaders = array(), array $options = array()) {
        if (empty($url)) {
            throw new nourl_exception();
        }
        if (empty($method)) {
            throw new norequestmethod_exception();
        }

        // Default content type.
        $defaultctype = 'application/json; charset=UTF-8';

        // Review custom headers to see if Accept and Content-Type are already present.
        $hasaccept = false;
        $hasctype = false;
        foreach ($custheaders as $header) {
            if (strpos($header, 'Accept:') !== false) {
                $hasaccept = true;
            }

            if (strpos($header, 'Content-Type:') !== false) {
                $hasctype = true;
            }
        }

        // Add Accept or Content-Type if missing.
        if (!$hasaccept) {
            $custheaders[] = "Content-Type: {$defaultctype}";
        }

        if (!$hasctype) {
            $custheaders[] = "Accept: {$defaultctype}";
        }

        $useopts = array(
            'CURLOPT_CUSTOMREQUEST' => $method,
            'CURLOPT_URL'           => $url,
            'CURLOPT_HTTPHEADER'    => $custheaders,
        );
        $fullopts = $options + $useopts;
        $curlopts = $this->getopts($fullopts);
        if (!defined('SAFEASSIGN_OMIT_CACHE') && $ret = $this->cache->get($url)) {
            $this->rawresponse = $ret;
            if (local::duringtesting()) {
                $this->lasthttpcode = testhelper::get_code_data($url);
                if ($this->lasthttpcode >= 400) {
                    return false;
                }
            }
            return true;
        } else if (local::duringtesting()) {
            $this->lasthttpcode = testhelper::get_code_data($url);
            $this->rawresponse = testhelper::get_fixture_data($url);
            if ($this->lasthttpcode >= 400) {
                return false;
            } else {
                return true;
            }
        }

        $response = null;
        $curl = new safeassign_curl();
        switch($method) {
            case self::HTTP_GET:
                $response = $curl->get($url, null, $curlopts);
                break;
            case self::HTTP_POST:
                $params = !empty($curlopts['CURLOPT_POSTFIELDS']) ? $curlopts['CURLOPT_POSTFIELDS'] : '';
                $response = $curl->post($url, $params, $curlopts);
                break;
            case self::HTTP_PUT:
                $params = !empty($curlopts['CURLOPT_POSTFIELDS']) ? $curlopts['CURLOPT_POSTFIELDS'] : '';
                $response = $curl->put($url, null, $curlopts);
                break;
            case self::HTTP_DELETE:
                $response = $curl->delete($url, null, $curlopts);
                break;
        }

        $this->error = $response; // If there was an error, it may be contained in the response.
        $this->errorno = $curl->get_errno();
        $this->curlinfo = $curl->get_info();
        $rawresponse = $this->memfile->get_content();
        $this->memfile->close();
        if (!empty($rawresponse)) {
            $responselist = explode("\r\n\r\n", $rawresponse);
            // Get last header and response.
            foreach ($responselist as $responseval) {
                if (strpos($responseval, self::STR_HTTP) === 0) {
                    $this->respheaders = $responseval;
                } else {
                    $this->rawresponse = $responseval;
                }
            }
        }
        if ($response) {
            $httpcode = isset($this->curlinfo['http_code']) ? $this->curlinfo['http_code'] : false;
            if ($httpcode !== false) {
                $this->lasthttpcode = $httpcode;
                if ($httpcode >= 400) {
                    $response = false;
                    $contentype = isset($this->curlinfo['content_type']) ? $this->curlinfo['content_type'] : false;
                    if ($contentype && (stripos($contentype, 'application/json') !== false)) {
                        $decode = json_decode($this->rawresponse);
                        if ($decode === false) {
                            $this->errorno     = json_last_error();
                            $this->error       = json_last_error_msg();
                            $this->errorstring = 'error_generic';
                        } else if (property_exists($decode, 'error')) {
                            $this->errorno     = self::ERR_UNKNOWN;
                            $this->error       = $decode->error;
                            $this->errorstring = 'rest_error_server';
                        }
                    }
                }
            }
        } else {
            if ($this->errorno) {
                $this->errorstring = 'rest_error_curl';
            }
        }

        if ($response && !defined('SAFEASSIGN_OMIT_CACHE')) {
            $this->cache->set($url, $this->rawresponse);
        }

        return $response;
    }

    /**
     * Simulated request handling for behat tests.
     *
     * @param string $url
     * @return bool|string
     */
    private function request_behat($url) {
        global $CFG;
        $result = ""; // Return json from file or empty.
        $parse = parse_url($url);
        $params = explode("/", $parse["path"]);

        if (isset($params[1]) && $params[1] == "error") {
            // With this name of instance, we simulate a error in connection to SafeAssign.
            $this->errorno     = self::ERR_UNKNOWN;
            $this->errorstring = 'error_generic';
            $this->error       = get_string("error_behat_instancefail", self::PLUGIN);

        } else {

            // Get filename of json to return.
            $filename = $this->behat_getjsonfile($params);
            if (!empty($filename)) {
                // Get json file.
                $result = file_get_contents($CFG->dirroot."/plagiarism/safeassign/tests/fixtures/$filename");
                if ($result) {
                    // Call to user/login, set cookie required.
                    if (isset($params[1]) && isset($params[2]) && ($params[1] == "user") && ($params[2] == "login")) {
                        $this->settoken(0, "behat_test");
                    }
                }
            }

            if (empty($result)) {
                // Json file dont found. Set error for debug.
                $this->errorno     = self::ERR_UNKNOWN;
                $this->error       = get_string("error_behat_getjson", "plagiarism_safeassign", $filename);
                $this->errorstring = 'error_generic';
            }
        }
        // Set rawresponse , this will be used in generic_call.
        $this->rawresponse = $result;
        return $result;
    }

    /**
     * Function used for behat tests.
     * Get filename to return in call of webservice when behat is running.
     *
     * @param array $params
     * @return string
     */
    private function behat_getjsonfile(array $params) {
        $filename = "";

        // Call to user/login or user/accesstoken.
        if (isset($params[1]) && isset($params[2]) && ($params[1] == "user") &&
            ($params[2] == "accesstoken" || $params[2] == "login")) {
            $filename = sprintf('%s-%s-final.json', $params[1], $params[2]);
        }

        return $filename;
    }

    /**
     * Gets API last response.
     * @return null|string
     */
    public function lastresponse() {
        return $this->rawresponse;
    }

    /**
     * Gets last response headers.
     * @return null|string
     */
    public function response_headers() {
        return $this->respheaders;
    }

    /**
     * Gets request headers.
     * @return string
     */
    public function request_headers() {
        return isset($this->curlinfo['request_header']) ? $this->curlinfo['request_header'] : '';
    }

    /**
     * Method should be used for printing Exception based error status.
     * @param bool $extrainfoondebug
     * @return string
     */
    public function errorinfo($extrainfoondebug = true) {
        $lasterrormsg = $this->geterrormsg();
        // In case debug mode is on show extra debug information.
        if ($extrainfoondebug) {
            $lasterrorcode = sprintf("Error code: %s", $this->geterrorcode());
            if (get_config('core', 'debug') == DEBUG_DEVELOPER) {
                if (CLI_SCRIPT) {
                    $lasterrormsg .= $lasterrorcode. "\n";
                    $lasterrormsg .= "Web Service request time: ";
                    $lasterrormsg .= $this->curlinfo['total_time']." s \n";
                    $requestheaders = $this->request_headers();
                    if (!empty($requestheaders)) {
                        $lasterrormsg .= "Request headers:\n";
                        $lasterrormsg .= $this->request_headers();
                        $lasterrormsg .= "\n\n";
                        $lasterrormsg .= "Response headers:\n";
                        $lasterrormsg .= $this->response_headers();
                        $lasterrormsg .= "\n\n";
                        $lasterrormsg .= "Response body:\n";
                        $lasterrormsg .= $this->lastresponse();
                        $lasterrormsg .= "\n";
                    }
                } else {
                    $lasterrormsg  = \html_writer::tag('h3', $this->geterrormsg()) . \html_writer::empty_tag('br');
                    $lasterrormsg .= \html_writer::tag('h3', $lasterrorcode) . \html_writer::empty_tag('br');
                    $calltitle = \html_writer::tag('h4', 'Web Service request time:');
                    $calltime = \html_writer::span(" ".$this->curlinfo['total_time']." s");
                    $lasterrormsg .= \html_writer::div($calltitle . $calltime);
                    $requestheaders = $this->request_headers();
                    if (!empty($requestheaders)) {
                        $lasterrormsg .= \html_writer::empty_tag('br');
                        $rtitle = \html_writer::tag('h4', 'Request headers:');
                        $request = \html_writer::tag('pre', s($this->request_headers()), array('title' => 'Request headers'));
                        $lasterrormsg .= \html_writer::div($rtitle . $request);
                        $lasterrormsg .= \html_writer::empty_tag('br');
                        $rstitle = \html_writer::tag('h4', 'Response headers:');
                        $response = \html_writer::tag('pre', s($this->response_headers()), array('title' => 'Response headers'));
                        $lasterrormsg .= \html_writer::div($rstitle . $response);
                        $lasterrormsg .= \html_writer::empty_tag('br');
                        $responsebody = \html_writer::tag('pre', s($this->lastresponse()), array('title' => 'Response body'));
                        $rsbodytitle = \html_writer::tag('h4', 'Response body:');
                        $lasterrormsg .= \html_writer::div($rsbodytitle . $responsebody);
                    }
                }
            }
        }
        return $lasterrormsg;
    }

    /**
     * Throws an exception with all data
     * @throws \moodle_exception
     */
    public function print_error() {
        print_error($this->errorstring, self::PLUGIN, '', $this->errorinfo());
    }


    /**
     * Does a POST request.
     * @param string $url
     * @param null $data
     * @param array $custheaders
     * @param array $options
     * @return mixed
     * @throws curlerror_exception
     * @throws jsonerror_exception
     * @throws norequestmethod_exception
     * @throws nourl_exception
     */
    public function post($url, $data = null, array $custheaders = array(), array $options = array()) {
        if (!empty($data)) {
            $jdata = json_encode($data);
            if ($jdata === false) {
                throw new jsonerror_exception();
            }
            $custheaders[] = 'Content-Length: ' . strlen($jdata);
            $options['CURLOPT_POSTFIELDS'] = $jdata;
        }
        return $this->request($url, self::HTTP_POST, $custheaders, $options);
    }

    /**
     * Does a GET request.
     * @param string $url
     * @param array $custheaders
     * @param array $options
     * @return mixed
     * @throws curlerror_exception
     * @throws norequestmethod_exception
     * @throws nourl_exception
     */
    public function get($url, array $custheaders = array(), array $options = array()) {
        return $this->request($url, self::HTTP_GET, $custheaders, $options);
    }

    /**
     * Does a PUT request.
     * @param string $url
     * @param array $custheaders
     * @param array $options
     * @return mixed
     * @throws curlerror_exception
     * @throws norequestmethod_exception
     * @throws nourl_exception
     */
    public function put($url, array $custheaders = array(), array $options = array()) {
        return $this->request($url, self::HTTP_PUT, $custheaders, $options);
    }

    /**
     * Does a DELETE request.
     * @param string $url
     * @param array $custheaders
     * @param array $options
     * @return mixed
     * @throws curlerror_exception
     * @throws norequestmethod_exception
     * @throws nourl_exception
     */
    public function delete($url, array $custheaders = array(), array $options = array()) {
        return $this->request($url, self::HTTP_DELETE, $custheaders, $options);
    }

    /**
     * Does a request with token.
     * @param string $url
     * @param int $userid
     * @param string $method
     * @param array $custheaders
     * @param array $options
     * @return mixed
     * @throws curlerror_exception
     * @throws norequestmethod_exception
     * @throws nourl_exception
     */
    public function request_withtoken($url, $userid, $method, array $custheaders = array(), array $options = array()) {
        if ($this->hastoken($userid)) {
            if (!isset($custheaders)) {
                $custheaders = array();
            }
            $custheaders[] = 'Authorization: Bearer '.$this->token;
        }
        return $this->request($url, $method, $custheaders, $options);
    }

    /**
     * Does a request with auth.
     * @param string $url
     * @param string $username
     * @param string $password
     * @param string $method
     * @param array $custheaders
     * @param array $options
     * @return mixed
     * @throws curlerror_exception
     * @throws norequestmethod_exception
     * @throws nourl_exception
     */
    public function request_withauth($url, $username, $password, $method, array $custheaders = array(), array $options = array()) {
        if (!isset($custheaders)) {
            $custheaders = array();
        }
        $custheaders[] = 'Authorization: Basic '.base64_encode($username.':'.$password);
        return $this->request($url, $method, $custheaders, $options);
    }

    /**
     * Does a GET request with token.
     * @param string $url
     * @param int $userid
     * @param array $custheaders
     * @param array $options
     * @return mixed
     */
    public function get_withtoken($url, $userid,  array $custheaders = array(), array $options = array()) {
        return $this->request_withtoken($url, $userid, self::HTTP_GET, $custheaders, $options);
    }

    /**
     * Does a POST request with token.
     * @param string $url
     * @param int $userid
     * @param array $custheaders
     * @param array $options
     * @param string $postdata
     * @return mixed
     */
    public function post_withtoken($url, $userid, array $custheaders = array(), array $options = array(), $postdata = null) {
        if (isset($postdata)) {
            $options['CURLOPT_POSTFIELDS'] = $postdata;
        }

        return $this->request_withtoken($url, $userid, self::HTTP_POST, $custheaders, $options);
    }

    /**
     * Does a POST request with token.
     * @param string $url
     * @param int $userid
     * @param array $custheaders
     * @param array $options
     * @return mixed
     */
    public function put_withtoken($url, $userid,  array $custheaders = array(), array $options = array()) {
        return $this->request_withtoken($url, $userid, self::HTTP_PUT, $custheaders, $options);
    }

    /**
     * Does a DELETE request with token.
     * @param string $url
     * @param int $userid
     * @param array $custheaders
     * @param array $options
     * @return mixed
     */
    public function delete_withtoken($url, $userid,  array $custheaders = array(), array $options = array()) {
        return $this->request_withtoken($url, $userid, self::HTTP_DELETE, $custheaders, $options);
    }

    /**
     * Does a POST request with auth.
     * @param string $url
     * @param string $username
     * @param string $password
     * @param array $custheaders
     * @param array $options
     * @param string $postdata
     * @return mixed
     */
    public function post_withauth($url, $username, $password, array $custheaders = array(),
                                  array $options = array(), $postdata = null) {
        if (isset($postdata)) {
            $options['CURLOPT_POSTFIELDS'] = $postdata;
        }

        return $this->request_withauth($url, $username, $password, self::HTTP_POST, $custheaders, $options);
    }

    /**
     * Gets the HTML error code.
     * @return int
     */
    public function geterrorcode() {
        return $this->errorno;
    }

    /**
     * Gets the error message.
     * @return null|string
     */
    public function geterrormsg() {
        return $this->error;
    }

    /**
     * Gets the user token.
     * @param int $userid
     * @return null|string
     */
    public function gettoken($userid = null) {
        if (empty($this->token) || (!empty($userid) && $userid != $this->currentuserid)) {
            $this->cleartoken();
            $this->currentuserid = $userid;

            if (!empty($this->currentuserid)) {
                $value = $this->cache->get(self::TOKEN.'_'.$userid);
                if (!empty($value)) {
                    $this->token = $value;
                }
            }
        }

        return $this->token;
    }

    /**
     * Resets the token for a user.
     * @param int $userid
     */
    public function resettoken($userid) {
        $this->cache->delete(self::TOKEN.'_'.$userid);
        $this->currentuserid = null;
        $this->token = null;
    }

    /**
     * Clears the current token if there is one.
     */
    public function cleartoken() {
        $this->cache->delete(self::TOKEN.'_'.$this->currentuserid);
        $this->currentuserid = null;
        $this->token = null;
    }

    /**
     * Sets the token in cache for an user.
     * @param int $userid
     * @param string $value
     * @param int $timeout
     */
    public function settoken($userid, $value, $timeout = false) {
        if (!empty($value)) {
            $this->cache->set(self::TOKEN.'_'.$userid, $value, $timeout);
            $this->currentuserid = $userid;
            $this->token = $value;
        }
    }

    /**
     * Gets the value of an option.
     * @param string $opt
     * @return mixed
     */
    public function getinfo($opt = null) {
        $result = ($opt === null) ? $this->curlinfo : $this->curlinfo[$opt];
        return $result;
    }

    /**
     * Checks if the user has a token.
     * @param int $userid
     * @return bool
     */
    public function hastoken($userid) {
        $value = $this->gettoken($userid);
        return !empty($value);
    }

    /**
     * Gets last http response code.
     * @return int|null
     */
    public function lasthttpcode() {
        return $this->lasthttpcode;
    }

    /**
     * Post a submission to SafeAssign using curl
     * @param int $userid
     * @param string $url
     * @param \stored_file[] $files
     * @param bool $globalcheck
     * @param bool $groupsubmission
     * @return bool|mixed
     */
    public function post_submission_to_safeassign($userid, $url, array $files, $globalcheck = false, $groupsubmission = false) {

        if (local::duringtesting()) {
            $this->lasthttpcode = testhelper::get_code_data($url);
            $this->rawresponse = testhelper::get_fixture_data($url);
            if ($this->lasthttpcode >= 400) {
                return false;
            } else {
                return true;
            }
        }

        $fields = array(
            "attributes" => json_encode(
                array(
                    "global_check" => ($globalcheck == "" ? "false" : "true"),
                    "group_submission" => ($groupsubmission == "" ? "false" : "true")
                )
            )
        );
        $boundary = uniqid();
        $delimiter = '-------------' . $boundary;
        $postdata = $this->build_data_files($boundary, $fields, $files);
        $postlength = strlen($postdata);
        $headers[self::HEADER_KEY_CONTENT_TYPE] = self::HEADER_VAL_MULTIPART_FORM . '; boundary=' . $delimiter;
        $headers[self::HEADER_KEY_ACCEPT] = self::HEADER_VAL_APP_JSON;
        $headers[self::HEADER_KEY_AUTHORIZATION] = "Bearer " . $this->gettoken($userid);
        $headers[self::HEADER_KEY_CONTENT_LENGTH] = $postlength;

        $headersstring = array();
        foreach ($headers as $k => $v) {
            array_push($headersstring, $k . ": " . $v);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLINFO_HEADER_OUT, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headersstring);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_FILETIME, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

        $serveroutput = curl_exec ($ch);
        $this->lasthttpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        $this->rawresponse = $serveroutput;

        return !($this->lasthttpcode() >= 400);
    }

    /**
     * Transforms an array of files and fields into text to send through curl.
     * @param string $boundary
     * @param array $fields
     * @param array $files
     * @return string
     */
    private function build_data_files($boundary, array $fields, array $files) {
        $data = '';
        $eol = "\r\n";
        $delimiter = '-------------' . $boundary;

        foreach ($fields as $name => $content) {
            $data .= "--" . $delimiter . $eol
                . 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
                . $content . $eol;
        }

        foreach ($files as $file) {
            $data .= "--" . $delimiter . $eol
                . 'Content-Disposition: form-data; name="files"; filename="' . $file->get_filename() . '"' . $eol
                . 'Content-Type: ' . $file->get_mimetype() . $eol
                . 'Content-Transfer-Encoding: binary'.$eol;
            $data .= $eol;
            $data .= $file->get_content() . $eol;
        }
        $data .= "--" . $delimiter . "--".$eol;

        return $data;
    }

}

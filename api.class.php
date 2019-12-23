<?php

    /**
     * Api wrapper class.
     */
    class Api
    {
        protected $_api_key = '';
        protected $_url = '';
        protected $_version = '';

        protected $_error_code = false;
        protected $_error_message = false;
        protected $_request_id = 1;

        protected $_timeout = 300;
        protected $_headers = [];
        protected $_proxy = '';

        protected $_debug = false;


        /**
         * Constructor
         *
         * The hostname used to log into the API is the same hostname which appears in your browsers address bar
         * after you log into the system.
         *
         * Please refer to the documentation for instructions on how to generate an API key.
         *
         * @param   string $url     API endpoint URL. Usually "https://<your_login_hostname>/api/jsonrpcserver".
         * @param   string $api_key Unique key which identifies you to the system. Keep this secret.
         * @param   string $version The version of the API to use for all requests.
         */
        public function __construct($url, $api_key, $version = '3.3')
        {
            $this->setUrl($url);
            $this->setAPIKey($api_key);
            $this->setVersion($version);
        }


        /**
         * Customisable error handler called whenever the API returns an error.
         *
         * @param   string $method_name   The name of the API method.
         * @param   int    $error_code    The error code.
         * @param   string $error_message The error message.
         */
        public function handleError($method_name, $error_code, $error_message)
        {
            //
            // :TODO: Add error handling code here...
            //
            // Change this to return false to handle errors in the calling code with Api::getErrorMessage() and Api::getErrorCode().
            //


            die($method_name . ': Error ' . $error_code . ': ' . $error_message . PHP_EOL);
        }


        /**
         * Customisable request debug handler called when debug mode is enabled.
         *
         * @param   string $method_name The name of the API method.
         * @param   int    $data        The serialized request/response data.
         */
        public function handleDebug($method_name, $data)
        {
            //
            // :TODO: Add request debug handling code here...
            //


            print 'Method: ' . $method_name . '    ';

            if (php_sapi_name() == "cli") {
                print $data . PHP_EOL;
            } else {
                print '<br>' . nl2br($data) . PHP_EOL;
            }
        }


        /**
         * Call an API method.
         *
         * @param  string $method_name Name of the API method to call.
         * @param  mixed  $param1,...  Variable list of parameters to pass to the method.
         *
         * @return mixed    Method result.
         */
        public function invokeMethod($method_name /*, parameters ...*/)
        {
            $parameters = func_get_args();

            return $this->_invokeMethod($this->_api_key, array_shift($parameters), $parameters);
        }


        /**
         * Set the API endpoint URL.
         *
         * @param   string $url The endpoint URL. Usually "https://<your_login_hostname>/api/jsonrpcserver".
         */
        public function setUrl($url)
        {
            $this->_url = $url;
        }


        /**
         * Get the API endpoint URL.
         *
         * @return  string
         */
        public function getUrl()
        {
            return $this->_url;
        }


        /**
         * Set the API key to use when making requests.
         *
         * @param   string $key The API key. Keep this secret.
         */
        public function setAPIKey($key)
        {
            $this->_api_key = $key;
        }


        /**
         * Get the API key used when making requests.
         *
         * @return  string
         */
        public function getAPIKey()
        {
            return $this->_api_key;
        }


        /**
         * Set the API version to use when making requests.
         *
         * @param   string
         */
        public function setVersion($version)
        {
            $this->_version = $version;
        }


        /**
         * Get the API version used when making requests.
         *
         * @return  string
         */
        public function getVersion()
        {
            return $this->_version;
        }


        /**
         * Set the connection timeout used when making requests.
         *
         * @param   int $seconds Timout in seconds.
         */
        public function setTimeout($seconds)
        {
            $this->_timeout = $seconds;
        }


        /**
         * Get the connection timeout used when making requests.
         *
         * @return  int
         */
        public function getTimeout()
        {
            return $this->_timeout;
        }


        /**
         * Set the proxy connection string used when making requests.
         *
         * @param   string $proxy
         */
        public function setProxy($proxy)
        {
            $this->_proxy = $proxy;
        }


        /**
         * Get the proxy connection string used when making requests.
         *
         * @return string
         */
        public function getProxy()
        {
            return $this->_proxy;
        }


        /**
         * Set additional HTTP headers to send when making requests.
         *
         * @param   array $headers_array An array of HTTP headers.
         */
        public function setHeaders($headers_array)
        {
            $this->_headers = $headers_array;
        }


        /**
         * Get the array of additional HTTP headers.
         *
         * @return  array
         */
        public function getHeaders()
        {
            return $this->_headers;
        }


        /**
         * Get the error code from the last method call.
         *
         * @return  int
         */
        public function getErrorCode()
        {
            return $this->_error_code;
        }


        /**
         * Get the error message from the last method call.
         *
         * @return  string
         */
        public function getErrorMessage()
        {
            return $this->_error_message;
        }


        /**
         * Enable or disable request debugging.
         *
         * @param   bool $enabled Set to true to enable request debug output.
         */
        public function setDebug($enabled)
        {
            $this->_debug = $enabled;
        }


        /**
         * Determine if request debugging is enabled.
         *
         * @return  bool
         */
        public function getDebug()
        {
            return $this->_debug;
        }


        /**
         * Perform an API method request.
         *
         * @param   string $api_key     The API key to use for the request.
         * @param   string $method_name The name of the API method.
         * @param   array  $parameters  An array of parameters to pass to the method.
         *
         * @return  mixed   Returns the method result on success.
         */
        protected function _invokeMethod($api_key, $method_name, $parameters)
        {
            $this->_error_code    = false;
            $this->_error_message = false;

            if ($api_key) {
                // API key is the first method parameter
                array_unshift($parameters, $api_key);
            }


            // Encode the request
            $request_id = $this->_request_id++;
            $request    = [
                'id'     => $request_id,
                'method' => $method_name,
                'params' => $parameters,
            ];


            // Send the request and read the response
            $encoded_response = $this->_postRequest($method_name, $request);
            $this->_debug($method_name, $encoded_response);


            // Decode response
            $response = json_decode($encoded_response, true);
            if ($response) {
                if (isset($response['result'])) {
                    if ($method_name == 'login') { // deprecated, use API keys instead
                        $this->setUrl($response['result']);
                    }

                    return $response['result'];
                } else if (isset($response['error'])) {
                    $error_code    = (isset($response['error']['code']) ? $response['error']['code'] : '?');
                    $error_message = (isset($response['error']['message']) ? $response['error']['message'] : 'Unknown Error');
                    if (is_string($response['error'])) {
                        if (preg_match('/^(\d+)\-(.*)/', $response['error'], $matches)) {
                            $error_code    = intval($matches[1]);
                            $error_message = trim($matches[2]);
                        }
                    }
                    $this->_error($method_name, $error_code, $error_message);

                    return false;
                }
            }

            $this->_error($method_name, 6, 'Invalid server response');

            return false;
        }


        /**
         * Post an API method request.
         *
         * @param   string $method_name The name of the API method.
         * @param   array  $post_data   The serialized method request.
         *
         * @return  mixed   Returns the serialized response on success.
         */
        protected function _postRequest($method_name, $post_data)
        {
            $post_data = json_encode($post_data);
            $this->_debug($method_name, $post_data);


            // Add API version to url
            $url = '';
            $parts = @parse_url($this->getUrl());
            if ($parts) {
                $url = (isset($parts['scheme']) ? $parts['scheme'] : 'http') . '://';
                if (isset($parts['host'])) {
                    $url .= $parts['host'];
                }
                if (isset($parts['port'])) {
                    $url .= ':' . $parts['port'];
                }
                $url .= (isset($parts['path']) ? $parts['path'] : '/');
                if (isset($parts['query'])) {
                    parse_str($parts['query'], $query);
                }
                $query['version'] = $this->_version;
                $url              .= '?' . http_build_query($query);
            }

            $url = trim($url);
            if (!$url) {
                $this->_error($method_name, 2, 'Url not valid: ' . $url);

                return false;
            }


            // Build request headers
            $headers = [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($post_data),
            ];
            $headers = array_merge($headers, $this->_headers);


            // Create stream context
            $context_options = [
                'http' => [
                    'method'     => 'POST',
                    'user_agent' => 'JSON-RPC PHP Wrapper',
                    'header'     => $headers,
                    'content'    => $post_data,
                    'timeout'    => $this->getTimeout(),
                ],
            ];

            if ($this->getProxy()) {
                $context_options['http']['proxy'] = $this->getProxy();
            }

            $context = stream_context_create($context_options);


            // Connect and send the request
            $fp = @fopen($url, 'rb', false, $context);
            if (!$fp) {
                $this->_error($method_name, 2, 'Unable to connect to ' . $url);

                return false;
            }

            // Read the response
            $response  = stream_get_contents($fp);
            $meta_data = stream_get_meta_data($fp);
            fclose($fp);

            if ($meta_data['timed_out']) {
                $this->_error($method_name, 3, 'Connection timed out');

                return false;
            }

            if ($response === false) {
                $this->_error($method_name, 4, 'Error occurred while reading from socket');
            }

            $last_status = false;
            foreach ($meta_data['wrapper_data'] as $line) {
                if (substr($line, 0, 5) == 'HTTP/') {
                    $last_status = explode(' ', $line, 3);
                }
            }

            if (!$last_status || count($last_status) != 3) {
                $this->_error($method_name, 5, 'Invalid server response');

                return false;
            } else if ($last_status[1] != 200) {
                $this->_error($method_name, 5, $last_status[1] . ' ' . substr($last_status[2], 0, strpos($last_status[2], "\r\n")));

                return false;
            }

            return $response;
        }


        /**
         * Invokes the error handler.
         *
         * @param   string $method_name   The name of the API method.
         * @param   int    $error_code    The error code.
         * @param   string $error_message The error message.
         */
        protected function _error($method_name, $error_code, $error_message)
        {
            $this->_error_code    = $error_code;
            $this->_error_message = $error_message;
            $this->handleError($method_name, $error_code, $error_message);
        }


        /**
         * Invokes the debug handler.
         *
         * @param   string $method_name The name of the API method.
         * @param   mixed  $data        The serialized request/response data.
         */
        protected function _debug($method_name, $data)
        {
            if ($this->getDebug()) {
                $this->handleDebug($method_name, $data);
            }
        }
    }


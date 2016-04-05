<?php
/**
 * Created by PhpStorm.
 * User: denyskazka
 * Date: 02.09.15
 * Time: 17:41
 */

namespace app\helpers;

use app\App;
use app\exceptions\CurlException;

class Http {

    protected
        $headers,
        $statusCode,
        $timeout = 60,
        $verifySSL = false,
        $ignoreErrors = 1,
        $defaultHeaders = [];

    /**
     * @param string $url
     * @param array  $headers
     *
     * @return string
     */
    public function get( $url, $headers = [] )
    {
        return $this->send( $url, 'GET', null, $headers );
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $data
     * @param array  $headers
     *
     * @return string
     */
    public function send( $url, $method = 'GET', $data = null, $headers = [] )
    {
        $contextData = [
            'http' => [
                'method'        => strtoupper( $method ),
                'header'        => $this->makeHeaders( array_merge( $this->defaultHeaders, $headers ) ),
                'timeout'       => $this->timeout,
                'ignore_errors' => $this->ignoreErrors
            ]
        ];

        if (!$this->verifySSL) {
            $contextData['ssl'] = [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ];
        }

        if ($data) {
            $contextData['http']['content'] = $data;
        }

        $response = file_get_contents( $url, false, stream_context_create( $contextData ) );
//        $responseHeaders = $http_response_header;
        $this->parseHeaders( $http_response_header );

        return $response;
    }


    /**
     * @param string $url
     * @param array  $postData
     * @param array  $headers
     *
     * @return string
     */
    public function post( $url, $postData, $headers = [] )
    {
        $postData = http_build_query( $postData );

        $defaultHeaders = [
            'Content-Type'   => 'application/x-www-form-urlencoded',
            'Content-Length' => strlen( $postData )
        ];

        $headers = array_merge( $defaultHeaders, $headers );

        return $this->send( $url, 'POST', $postData, $headers );
    }


    /**
     * @param $url
     * @param $data
     *
     * @return string
     */
    public function apiRequest( $url, $data )
    {
        $data = json_encode( $data );

        $headers = [
            'Accept'         => 'application/json',
            'Content-Type'   => 'application/json',
            'Content-Length' => strlen( $data )
        ];

        return $this->send( $url, 'POST', $data, $headers );

    }


    protected function parseHeaders( $responseHeaders )
    {
        $this->headers = $responseHeaders;

        $this->statusCode = function_exists('http_response_code')
            ? http_response_code() : (int)preg_replace( '/.*?\s(\d+)\s.*/', "\\1", $responseHeaders[0] );

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return string
     */
    protected function makeHeaders( $headers )
    {
        $headersString = '';
        foreach ($headers as $key => $value) {
            $headersString .= "{$key}: {$value}\r\n";
        }

        return $headersString;
    }


    public function getHeaders()
    {
        return $this->headers;
    }


    public function getStatusCode()
    {
        return $this->statusCode;
    }


    public function getCurl($url)
    {
        $curl = curl_init($url);

        curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $curl_response = curl_exec($curl);

        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            throw new CurlException(print_r($info, true));
        }

        curl_close($curl);

        return $curl_response;
    }


    /**
     * @param $pathUrl
     * @param $lang
     * @return null
     * @throws CurlException
     */
    public static function getFromApi($pathUrl, $lang)
    {
        $params = App::getParams('auth');

        $url = $params['url'] . '/' . $pathUrl . "?" . http_build_query([
                'auth' => [
                    'id' => $params['id'],
                    'token' => $params['token'],
                ],
                'lang' => $lang
            ]);
        $url = preg_replace('/(?<!:)\/(\/)*/', '/', $url);
        $http = new self();
        return json_decode($http->getCurl($url), 1);
    }


}

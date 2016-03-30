<?php
/**
 * Copyright 2016 Xenofon Spafaridis
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Phramework\JSONAPI\Client;

use Phramework\JSONAPI\Client\Exceptions\Exception;
use Phramework\JSONAPI\Client\Response\Collection;
use Phramework\JSONAPI\Client\Response\Resource;

/**
 * Class API
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 * @todo handle errors
 */
abstract class API
{
    /**
     * @var object|null
     */
    protected static $globalHeaders = null;
    /**
     * @var string|null
     */
    protected static $globalAPI     = null;

    const METHOD_GET    = 'GET';
    const METHOD_HEAD   = 'HEAD';
    const METHOD_POST   = 'POST';
    const METHOD_PATCH  = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PUT    = 'PUT';

    const REQUEST_EMPTY_FLAG = 0;
    const REQUEST_BINARY = 1;
    const REQUEST_NOT_URL_ENCODED = 2;

    /**
     * @var string
     * @todo
     */
    protected static $endpoint = null;

    /**
     * @var string
     * @todo
     */
    protected static $type     = null;

    /**
     * Overrides global API
     * @var string|null
     */
    protected static $API      =  null;

    /**
     * MAY be overridden
     * @return \stdClass
     */
    protected static function getHeaders()
    {
        return new \stdClass();
    }

    /**
     * Get API url
     * @return string
     */
    protected function prepareAPI()
    {
        if (static::$API !== null) {
            return static::$API;
        }

        return static::getGlobalAPI() ?? '';
    }

    /**
     * Prepare headers
     * @param \stdClass|null $additional Additional headers
     * @return \stdClass
     */
    protected function prepareHeaders(\stdClass $additional = null)
    {
        if ($additional === null) {
            $additional = new \stdClass();
        }

        $headers = static::getGlobalHeaders();

        foreach (static::getHeaders() as $key => $value) {
            $headers->{$key} = $value;
        }

        foreach ($additional as $key => $value) {
            $headers->{$key} = $value;
        }

        return $headers;
    }

    /**
     * Get collection of resources
     * @param Page|null                $page    Page directive
     * @param Filter|null              $filter  Filter directive
     * @param Sort|null                $sort    Sort directive
     * @param Fields|null              $fields  Fields directive
     * @param IncludeRelationship|null $include Include directive
     * @param \stdClass|null           $additionalHeaders Will override global
     *     headers
     * @param \string[]                ...$additional Additional url
     * @return Collection
     * @example
     * ```php
     * $users = Users::get(
     *     new Page(10),
     *     new Filter(
     *         null,
     *         (object) [
     *             'group' => [1, 2,3]
     *         ],
     *         [
     *             new FilterAttribute(
     *                 'language',
     *                 Operator::OPERATOR_EQUAL,
     *                 'en'
     *             )
     *         ]
     *     )
     * );
     * ```
     * @todo provide examples for all arguments
     */
    public static function get(
        Page $page = null,
        Filter $filter = null,
        Sort $sort = null,
        Fields $fields = null,
        IncludeRelationship $include = null,
        \stdClass $additionalHeaders = null,
        string ...$additional
    ) {
        $API = self::getGlobalAPI();

        $url = $API . static::$endpoint;

        //prepare parts

        $pagePart    = ($page    ? $page->toURL()                : '');
        $filterPart  = ($filter  ? $filter->toURL(static::$type) : '');
        $sortPart    = ($sort    ? $sort->toURL()                : '');
        $fieldsPart  = ($fields  ? $fields->toURL()              : '');
        $includePart = ($include ? $include->toURL()             : '');

        $questionMark = false;

        //append parts

        if (!empty($pagePart)) {
            $url = $url . ($questionMark ? '&' : '?') . $pagePart;
            $questionMark = true;
        }

        if (!empty($filterPart)) {
            $url = $url . ($questionMark ? '&' : '?') . $filterPart;
            $questionMark = true;
        }

        if (!empty($sortPart)) {
            $url = $url . ($questionMark ? '&' : '?') . $sortPart;
            $questionMark = true;
        }

        if (!empty($fieldsPart)) {
            $url = $url . ($questionMark ? '&' : '?') . $fieldsPart;
            $questionMark = true;
        }

        if (!empty($includePart)) {
            $url = $url . ($questionMark ? '&' : '?') . $includePart;
            $questionMark = true;
        }

        //Append additional
        $url = $url . implode('', $additional);

        $headers = static::prepareHeaders($additionalHeaders);

        /*var_dump($url);*/

        list(
            $responseStatusCode,
            $responseHeaders,
            $responseBody
        ) = static::request(
            $url,
            self::METHOD_GET,
            $headers
        );
/*
        var_dump(
            $responseStatusCode,
            $responseHeaders,
            $responseBody
        );*/

        $collection = (new Collection())->parse(
            $responseBody
        );

        $resource = (new Resource())->parse(
            $responseBody
        );

        return $collection;

        //data
        //include
        //links
        //meta
    }

    public static function getById(
        $id,
        Fields $fields = null,
        IncludeRelationship $include = null,
        \stdClass $additionalHeaders = null,
        string ...$additional
    ) {

    }

    public static function post(
        \stdClass $attributes = null,
        \stdClass $relationships = null
    ) {

    }

    public static function patch(
        string $id,
        \stdClass $attributes = null,
        \stdClass $relationships = null)
    {

    }

    public static function delete(
        string $id
    ) {

    }

    /**
     * Perform an cURL request
     */
    public static function request(
        string $url,
        string $method = self::METHOD_GET,
        \stdClass $headers = null,
        $data = NULL,
        $flags = self::REQUEST_EMPTY_FLAG,
        //$accept = 'application/json',
        $encoding = NULL

    ) {
        //Extract flags
        //Is the request binary
        $binary = ($flags & self::REQUEST_BINARY) != 0;
        //If the request parameters form encoded
        $form_encoded = !(($flags & self::REQUEST_NOT_URL_ENCODED) != 0);
        //Initialize headers

        /*$headers = array(
            'Accept: ' . $accept,
            /* $this->auth_header */
        //)*/

        //If request's data is encoded provide the Content type Header
        /*if ($form_encoded) {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
        }
        //If request has a special Content-Encoding
        if ($encoding) {
            $headers[] = 'Content-Encoding: ' . $encoding;
        }*/

        $headersArray = [];

        foreach ($headers as $key => $value) {
            $headersArray[] = "$key: $value";
        }

        //Initialize curl
        $handle = curl_init();

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headersArray);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_HEADER, true);

        //Set timeout values ( in seconds )
        //curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, $this->settings[self::SETTING_CURLOPT_CONNECTTIMEOUT]);
        //curl_setopt($handle, CURLOPT_TIMEOUT, $this->settings[self::SETTING_CURLOPT_TIMEOUT]);
        curl_setopt($handle, CURLOPT_NOSIGNAL, 1);
        //Security options
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);

        /*//On binary transfers
        if ($binary) {
            curl_setopt($handle, CURLOPT_BINARYTRANSFER, TRUE);
        }*/

        //Switch on HTTP Request method
        switch ($method) {
            case self::METHOD_GET: //On METHOD_GET
            case self::METHOD_HEAD: //On METHOD_HEAD
                break;
            case self::METHOD_POST: //On METHOD_POST
                curl_setopt($handle, CURLOPT_POST, true);
                if ($data && $form_encoded) { //Encode fields if required ( URL ENCODED )
                    curl_setopt(
                        $handle, CURLOPT_POSTFIELDS, http_build_query($data));
                } else if ($data) {
                    curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                }
                break;
            case self::METHOD_PUT: //On METHOD_PUT
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, self::METHOD_PUT);
                if ($data) {
                    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;
            case self::METHOD_DELETE: //On METHOD_DELETE
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, self::METHOD_DELETE);
                break;
            default:
                throw new Exception('Unsupported method');
        }
        
        //Get response
        $response = curl_exec($handle);
        //Get response code
        $responseStatusCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($handle, CURLINFO_HEADER_SIZE);

        $responseHeadersTemp = str_replace("\r", '', substr($response, 0, $headerSize));
        $responseHeaders = new \stdClass;

        foreach (explode("\n", $responseHeadersTemp) as $i => $line) {
            if ($i !== 0 && !empty($line)) {
                if (count($parts = explode(': ', $line)) === 2) {
                    list($key, $value) = explode(': ', $line);
                    $responseHeaders->{$key} = $value;
                }
            }
        }

        $responseBody = substr($response, $headerSize);

        curl_close($handle);

        return [
           $responseStatusCode,
           $responseHeaders,
           json_decode($responseBody)
       ];

       /* if (!$response) {
            throw new Exception('Error: ' . curl_error($handle));
        }*/

       /* //Throw exception on response failure
        if (!in_array($code, array(200, 201, 202))) { // OK, Created, Accepted
            $decoded = json_decode($response, true);
            throw new Exception($decoded['error'], $code);
        }*/

        curl_close($handle);
        //Return the data of response

        return $response;

        /*return (

            $accept == 'application/json' ? json_decode($response, true) : $response );*/
    }

    /**
     * @param string|null $API
     */
    public static function setGlobalAPI(string $API = null)
    {

        static::$globalAPI = $API;
    }

    /**
     * @return string|null
     */
    public static function getGlobalAPI()
    {

        return static::$globalAPI;
    }

    /**
     * @param string $key
     * @param string $header
     */
    public static function setGlobalHeader(string $key, string $header)
    {
        if (static::$globalHeaders === null) {
            static::$globalHeaders = new \stdClass();
        }

        static::$globalHeaders->{$key} = $header;
    }

    /**
     * @return null
     */
    public static function getGlobalHeaders()
    {
        if (static::$globalHeaders === null) {
            static::$globalHeaders = new \stdClass();
        }

        return static::$globalHeaders;
    }
}

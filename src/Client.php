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

use Phramework\JSONAPI\Client\Exceptions\ResponseException;
use Phramework\JSONAPI\Client\Response\Collection;
use Phramework\JSONAPI\Client\Response\Errors;
use Phramework\JSONAPI\Client\Response\Resource;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 * @todo handle errors
 * @todo add post batch
 */
abstract class Client
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
    protected static function prepareAPI()
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
    protected static function prepareHeaders(\stdClass $additional = null)
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
     * @param \string[]                ...$additional Additional url parts
     * @return Collection
     * @throws ResponseException When response code is not on of 200, 201, 202, 203 or 204
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
     * @example
     * ```php
     * $users = Users::get(
     *    null,
     *    null,
     *    new Sort(null, 'created', false),
     *    new Fields((object) [
     *        'user' => ['name', 'email']
     *    ]),
     *    new IncludeRelationship('project', 'group'),
     *    (object) [
     *        'Accept' => 'application/vnd.api+json'
     *    ],
     *    '&token=1234'
     * );
     * ```
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
        $API = self::prepareAPI();

        $url = $API . static::$endpoint . '/';

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

        list(
            $responseStatusCode,
            $responseHeaders,
            $responseBody
        ) = static::request(
            $url,
            self::METHOD_GET,
            $headers
        );

        $collection = (new Collection())->parse(
            $responseBody
        );

        $collection->setStatusCode($responseStatusCode);
        $collection->setHeaders($responseHeaders);

        return $collection;
    }

    /**
     * @param string                   $id      Resource id
     * @param Fields|null              $fields  Fields directive
     * @param IncludeRelationship|null $include Include directive
     * @param \stdClass|null           $additionalHeaders Will override global
     *     headers
     * @param \string[]                ...$additional Additional url
     * @return Resource
     * @throws ResponseException When response code is not on of 200, 201, 202, 203 or 204
     * @example
     * ```php
     * $user = User::getById(
     *     '10',
     *     null,
     *     new IncludeRelationship('project', 'group')
     * )
     * ```
     */
    public static function getById(
        string $id,
        Fields $fields = null,
        IncludeRelationship $include = null,
        \stdClass $additionalHeaders = null,
        string ...$additional
    ) {
        $API = self::prepareAPI();

        $url = $API . static::$endpoint . '/' . $id . '/';

        //prepare parts
        $fieldsPart  = ($fields  ? $fields->toURL()  : '');
        $includePart = ($include ? $include->toURL() : '');

        $questionMark = false;

        //append parts
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

        list(
            $responseStatusCode,
            $responseHeaders,
            $responseBody
        ) = static::request(
            $url,
            self::METHOD_GET,
            $headers
        );

        $resource = (new Resource())->parse(
            $responseBody
        );

        $resource->setStatusCode($responseStatusCode);
        $resource->setHeaders($responseHeaders);

        return $resource;
    }

    /**
     * @param \stdClass|null         $attributes
     * @param RelationshipsData|null $relationships
     * @param \stdClass|null         $additionalHeaders
     * @param \string[]              ...$additional
     * @return $this
     * @throws ResponseException
     * @throws \Exception
     */
    public static function post(
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        \stdClass $additionalHeaders = null,
        string ...$additional
    ) {
        $API = self::prepareAPI();

        $url = $API . static::$endpoint . '/';

        //Append additional
        $url = $url . implode('', $additional);

        $headers = static::prepareHeaders($additionalHeaders);

        $body = (object) [
            'data' => (object) [
                'type' => static::$type
            ]
        ];

        if ($attributes !== null) {
            $body->data->attributes = $attributes;
        }

        if ($relationships !== null) {
            $body->data->relationships = $relationships->getRelationships();
        }

        list(
            $responseStatusCode,
            $responseHeaders,
            $responseBody
        ) = static::request(
            $url,
            self::METHOD_POST,
            $headers,
            $body
        );

        $resource = (new Resource())->parse(
            $responseBody
        );

        $resource->setStatusCode($responseStatusCode);
        $resource->setHeaders($responseHeaders);

        return $resource;
    }

    public static function patch(
        string $id,
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        \stdClass $additionalHeaders = null,
        string ...$additional
    ) {
        $API = self::prepareAPI();

        $url = $API . static::$endpoint . '/' . $id . '/';

        //Append additional
        $url = $url . implode('', $additional);

        $headers = static::prepareHeaders($additionalHeaders);

        $body = (object) [
            'data' => (object) [
                'id'   => $id,
                'type' => static::$type
            ]
        ];

        if ($attributes !== null) {
            $body->data->attributes = $attributes;
        }

        if ($relationships !== null) {
            $body->data->relationships = $relationships->getRelationships();
        }

        list(
            $responseStatusCode,
            $responseHeaders,
            $responseBody
        ) = static::request(
            $url,
            self::METHOD_PATCH,
            $headers,
            $body
        );

        $resource = (new Resource())->parse(
            $responseBody
        );

        $resource->setStatusCode($responseStatusCode);
        $resource->setHeaders($responseHeaders);

        return $resource;
    }

    public static function put(
        string $id,
        \stdClass $attributes = null,
        RelationshipsData  $relationships = null,
        \stdClass $additionalHeaders = null,
        string ...$additional
    ) {
        $API = self::prepareAPI();

        $url = $API . static::$endpoint . '/' . $id . '/';

        //Append additional
        $url = $url . implode('', $additional);

        $headers = static::prepareHeaders($additionalHeaders);

        $body = (object) [
            'data' => (object) [
                'type' => static::$type
            ]
        ];

        if ($attributes !== null) {
            $body->data->attributes = $attributes;
        }

        if ($relationships !== null) {
            $body->data->relationships = $relationships->getRelationships();
        }

        list(
            $responseStatusCode,
            $responseHeaders,
            $responseBody
        ) = static::request(
            $url,
            self::METHOD_PUT,
            $headers,
            $body
        );

        $resource = (new Resource())->parse(
            $responseBody
        );

        $resource->setStatusCode($responseStatusCode);
        $resource->setHeaders($responseHeaders);

        return $resource;
    }

    public static function delete(
        string $id,
        \stdClass $additionalHeaders = null,
        string ...$additional
    ) {
        $API = self::prepareAPI();

        $url = $API . static::$endpoint . '/' . $id . '/';

        //Append additional
        $url = $url . implode('', $additional);

        $headers = static::prepareHeaders($additionalHeaders);

        list(
            $responseStatusCode,
            $responseHeaders,
            $responseBody
        ) = static::request(
            $url,
            self::METHOD_DELETE,
            $headers
        );

        $resource = (new Resource())->parse(
            $responseBody
        );

        $resource->setStatusCode($responseStatusCode);
        $resource->setHeaders($responseHeaders);

        return $resource;
    }

    /**
     * Perform an cURL request to a JSON API web service over HTTP
     * @param string         $url Request url
     * @param string         $method Request HTTP method
     * @param \stdClass|null $headers Request headers
     * @param \stdClass|null $data Request body
     * @return array which contains
     * - $responseStatusCode
     * - $responseHeaders
     * - $responseBody (JSON encoded)
     * @throws ResponseException When response code is not on of 200, 201, 202, 203 or 204
     * @throws \Exception
     * @example
     * ```php
     * list(
     *     $responseStatusCode,
     *     $responseHeaders,
     *     $responseBody
     * ) = Client::request(
     *     'http://myapi.com/user/',
     *     Client::METHOD_GET,
     *     (object) [
     *         'Content-Type' => 'application/vnd.api+json',
     *         'Accept' => 'application/vnd.api+json'
     *     ]
     * );
     * ```
     */
    public static function request(
        string $url,
        string $method = self::METHOD_GET,
        \stdClass $headers = null,
        \stdClass $data = null,
        $flags = self::REQUEST_EMPTY_FLAG,
        //$accept = 'application/json',
        $encoding = null
    ) {
        //Extract flags
        //Is the request binary
        $binary = ($flags & self::REQUEST_BINARY) != 0;
        //If the request parameters form encoded
        $form_encoded = false;// !(($flags & self::REQUEST_NOT_URL_ENCODED) != 0);
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
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_HEADER, true);

        //Set timeout values ( in seconds )
        //curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, $this->settings[self::SETTING_CURLOPT_CONNECTTIMEOUT]);
        //curl_setopt($handle, CURLOPT_TIMEOUT, $this->settings[self::SETTING_CURLOPT_TIMEOUT]);
        curl_setopt($handle, CURLOPT_NOSIGNAL, 1);
        //Security options
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

        /*//On binary transfers
        if ($binary) {
            curl_setopt($handle, CURLOPT_BINARYTRANSFER, true);
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
                        $handle,
                        CURLOPT_POSTFIELDS,
                        http_build_query($data)
                    );
                } elseif ($data) {
                    curl_setopt(
                        $handle,
                        CURLOPT_POSTFIELDS,
                        json_encode($data)
                    );
                }
                break;
            case self::METHOD_PATCH: //On METHOD_PUT
            case self::METHOD_PUT: //On METHOD_PUT
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, self::METHOD_PATCH);
                //todo only if json
                if ($data) {
                    curl_setopt(
                        $handle,
                        CURLOPT_POSTFIELDS,
                        json_encode($data)
                    );
                }
                break;
            case self::METHOD_DELETE: //On METHOD_DELETE
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, self::METHOD_DELETE);
                break;
            default:
                throw new \Exception(sprintf(
                    'Unsupported method "%s"',
                    $method
                ));
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

        //Throw exception on response failure
        if (!in_array($responseStatusCode, [200, 201, 202, 203, 204])) {
            var_dump($responseBody);
            throw new ResponseException(
                (new Errors())
                    ->parse(json_decode($responseBody) ?? new \stdClass)
                    ->setHeaders($responseHeaders)
                    ->setStatusCode($responseStatusCode)
            );
        }

        //Return the data of response
        return [
            $responseStatusCode,
            $responseHeaders,
            json_decode($responseBody)
        ];

        /*return (

            $accept == 'application/json' ? json_decode($response, true) : $response );*/
    }

    /**
     * @param string|null $API
     * @deprecated
     * wont work correctly 
     */
    public static function setAPI(string $API = null)
    {
        static::$API = $API;
    }

    /**
     * @param string|null $API
     */
    public static function setGlobalAPI(string $API = null)
    {
        self::$globalAPI = $API;
    }

    /**
     * @return string|null
     */
    public static function getGlobalAPI()
    {

        return self::$globalAPI;
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

<?php

namespace Phramework\APISDK;
use Phramework\APISDK\Exceptions\Exception;

/**
 * Class API
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
class API
{
    /**
     * @var object|null
     */
    protected static $globalHeaders = null;

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
    protected static $endpoint = 'user';

    /**
     * @var string
     * @todo
     */
    protected static $type     = 'user';

    /**
     * @var string|null
     */
    protected static $api      =  null;

    /**
     * Get collection of resources
     * @param Page|null                $page    Page directive
     * @param Filter|null              $filter  Filter directive
     * @param Sort|null                $sort    Sort directive
     * @param Fields|null              $fields  Fields directive
     * @param IncludeRelationship|null $include Include directive
     * @param \stdClass|null           $additionalHeaders Will override global
     *     headers
     * @param \string[]                ...$additional
     * @return string
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
     */
    public static function get(
        Page $page = null,
        Filter $filter = null,
        Sort $sort = null,
        Fields $fields = null,
        IncludeRelationship $include = null,
        \stdClass $additionalHeaders = null,
        string ...$additional //todo
    ) {
        $API = 'https://translate.nohponex.gr/';
        $url = $API . self::$endpoint;

        $pagePart =  ($page ? $page->toURL() : '');
        $filterPart = ($filter ? $filter->toURL(self::$type) : '');
        $sortPart = ($sort ? $sort->toURL() : '');
        $fieldsPart = ($fields ? $fields->toURL() : '');
        $includePart = ($include ? $include->toURL() : '');

        $questionMark = false;

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

        $url = $url . implode('', $additional);

        return $url;
    }

    public static function getById(
        $id,
        Fields $fields = null,
        IncludeRelationship $include = null,
        \stdClass $headers = null,
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

    ){

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

        //Initialize curl
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        //Set timeout values ( in seconds )
        //curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, $this->settings[self::SETTING_CURLOPT_CONNECTTIMEOUT]);
        //curl_setopt($handle, CURLOPT_TIMEOUT, $this->settings[self::SETTING_CURLOPT_TIMEOUT]);
        curl_setopt($handle, CURLOPT_NOSIGNAL, 1);
        //Security options
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        //On binary transfers
        if ($binary) {
            curl_setopt($handle, CURLOPT_BINARYTRANSFER, TRUE);
        }
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
        //Get response code
        $responseStatusCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($handle, CURLINFO_HEADER_SIZE);

        $responseHeadersTemp = str_replace("\r", '', substr($response, 0, $headerSize));
        $responseHeaders = [];

        foreach (explode("\n", $responseHeadersTemp) as $i => $line) {
            if ($i !== 0 && !empty($line)) {
                if (count($parts = explode(': ', $line)) === 2) {
                    list($key, $value) = explode(': ', $line);
                    $responseHeaders[$key] = $value;
                }
            }
        }

        $responseBody = substr($response, $headerSize);

        curl_close($handle);

        return [
           $responseStatusCode,
           $responseHeaders,
           $responseBody
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
    public static function getGlobalHeader()
    {
        if (static::$globalHeaders === null) {
            static::$globalHeaders = new \stdClass();
        }

        return static::$globalHeaders;
    }
}

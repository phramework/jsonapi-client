<?php
declare(strict_types=1);
/*
 * Copyright 2016-2017 Xenofon Spafaridis
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
namespace Phramework\JSONAPI\Client\Response;

use Psr\Http\Message\ResponseInterface;

/**
 * @author Xenofon Spafaridis <nohponex@gmail.com>
 * @since 0.0.0
 */
abstract class Response
{
    /**
     * @var \stdClass
     */
    protected $meta;
    
    /**
     * @var \stdClass
     */
    protected $links;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Parse errors object, will copy any top members available at this
     * @param ResponseInterface $response
     * @throws \JsonException
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        
        if ($response->getBody()->isSeekable()) {
            $response->getBody()->rewind();
        }

        $body = $this->decodeValidJson($response->getBody()->getContents());

        if ($body) {
            $members = array_keys(get_object_vars($this));

            foreach ($members as $member) {
                if (isset($body->{$member})) {
                    $this->{$member} = $body->{$member};
                }
            }
        }
    }

    /**
     * @throws \JsonException
     */
    protected function decodeValidJson(string $json): string
    {
        $body = json_decode($json, false, 512, JSON_THROW_ON_ERROR);

        // Check and throw exception in case of unhandled decode error
        $this->throwOnJsonLastError($body);

        return $body;
    }

    /**
     * @throws \JsonException
     */
    private function throwOnJsonLastError($decoded): void
    {
        $generalErrorMessage = 'Could not decode JSON!';

        //Backwards compatability.
        if (!function_exists('json_last_error')) {
            if ($decoded === false || $decoded === null) {
                throw new \JsonException($generalErrorMessage);
            }
        } else {
            //Get the last JSON error.
            $jsonError = json_last_error();

            //In some cases, this will happen.
            if (is_null($decoded) && $jsonError == JSON_ERROR_NONE) {
                throw new \JsonException($generalErrorMessage);
            }

            //If an error exists.
            if ($jsonError != JSON_ERROR_NONE) {
                //Use a switch statement to figure out the exact error.
                switch ($jsonError) {
                    case JSON_ERROR_DEPTH:
                        $error = 'Maximum depth exceeded!';
                        break;
                    case JSON_ERROR_STATE_MISMATCH:
                        $error = 'Underflow or the modes mismatch!';
                        break;
                    case JSON_ERROR_CTRL_CHAR:
                        $error = 'Unexpected control character found';
                        break;
                    case JSON_ERROR_SYNTAX:
                        $error = 'Malformed JSON';
                        break;
                    case JSON_ERROR_UTF8:
                        $error = 'Malformed UTF-8 characters found!';
                        break;
                    default:
                        $error = 'Unknown error!';
                        break;
                }
                
                throw new \JsonException(sprintf('%s %s', $generalErrorMessage, $error));
            }
        }
    }

    /**
     * @return int
     */
    public function getStatusCode() : int
    {
        return $this->response->getStatusCode();
    }

    /**
     * @return string[][]
     */
    public function getHeaders() : array
    {
        return $this->response->getHeaders();
    }

    /**
     * @return \stdClass
     */
    public function getMeta(): \stdClass
    {
        return $this->meta;
    }
    /**
     * @return \stdClass
     */
    public function getLinks(): \stdClass
    {
        return $this->links;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}

<?php

/**
 * Copyright Shopgate Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author    Shopgate Inc, 804 Congress Ave, Austin, Texas 78701 <interfaces@shopgate.com>
 * @copyright Shopgate Inc
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 */

namespace Shopgate\ConnectSdk\Service\BulkImport;

use GuzzleHttp\Psr7\Utils;
use Shopgate\ConnectSdk\Exception\InvalidDataTypeException;
use Psr\Http\Message\StreamInterface;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\NotFoundException;
use Shopgate\ConnectSdk\Http\ClientInterface;
use Shopgate\ConnectSdk\Http\Persistence\TokenPersistenceException;
use Shopgate\ConnectSdk\Service\BulkImport\Handler\File;
use Shopgate\ConnectSdk\Service\BulkImport\Handler\Stream;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;

class Feed
{
    /** @var  ClientInterface */
    protected $client;

    /** @var  string */
    protected $importReference;

    /** @var  string */
    private $url;

    /** @var StreamInterface | resource */
    protected $stream;

    /** @var string */
    protected $handlerType;

    /** @var array */
    protected $requestBodyOptions;

    /** @var bool */
    protected $isFirstItem = true;

    /**
     * @param ClientInterface $client
     * @param string          $importReference
     * @param string          $handlerType
     * @param array           $requestBodyOptions
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function __construct(
        ClientInterface $client,
        $importReference,
        $handlerType,
        $requestBodyOptions = []
    ) {
        $this->client = $client;
        $this->importReference = $importReference;
        $this->handlerType = $handlerType;
        $this->requestBodyOptions = $requestBodyOptions;

        $this->url = $this->getUrl();

        switch ($this->handlerType) {
            case Stream::HANDLER_TYPE:
                $this->stream = Utils::streamFor();
                $this->stream->write('[');
                break;
            case File::HANDLER_TYPE:
                $this->stream = tmpfile();
                fwrite($this->stream, '[');
                break;
        }
    }

    /**
     * @return string
     *
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws TokenPersistenceException
     * @throws UnknownException
     */
    protected function getUrl()
    {
        $response = $this->client->request([
            'method' => 'post',
            'service' => 'import',
            'path' => 'imports/' . $this->importReference . '/urls',
            'body' => $this->requestBodyOptions
        ]);

        return $response['url'];
    }

    /**
     * @return string
     */
    protected function getItemDivider()
    {
        return $this->isFirstItem
            ? ''
            : ',';
    }

    /**
     * @throws AuthenticationInvalidException
     * @throws InvalidDataTypeException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws TokenPersistenceException
     */
    public function end()
    {
        $requestOption = [
            'method' => 'put',
            'url' => $this->url
        ];

        switch ($this->handlerType) {
            case Stream::HANDLER_TYPE:
                $this->stream->write(']');
                $this->client->request($requestOption + ['body' => (string)$this->stream]);
                break;
            case File::HANDLER_TYPE:
                fwrite($this->stream, ']');
                fseek($this->stream, 0);
                $requestOption['body'] = fread($this->stream, filesize(stream_get_meta_data($this->stream)['uri']));
                fclose($this->stream);
                $this->client->request($requestOption);
                break;
        }
    }
}

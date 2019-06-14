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

namespace Shopgate\ConnectSdk\Service\BulkImport\Feed;

use Shopgate\ConnectSdk\Dto\Catalog\Product\Create;
use Shopgate\ConnectSdk\Service\BulkImport\Feed;

class Product extends Feed
{
    /**
     * @return mixed
     */
    protected function getUrl()
    {
        $response = $this->client->doRequest(
            [
                // general
                'method'      => 'post',
                'body'        => ['entity' => 'product', 'catalogCode' => '123'],
                'requestType' => 'direct',
                'service'     => 'import',
                'path'        => 'imports/' . $this->importReference . '/' . 'urls',
            ]
        );

        $response = json_decode($response->getBody(), true);

        return $response['url'];
    }

    public function add(Create $product)
    {
        $this->stream->write($product->toJson() . ',');
        /*
        $stream = fopen($this->url, 'x');
        fwrite($stream, 'Hello!');
        fwrite($stream, 'World!');
        fclose($stream);
        */
    }
}

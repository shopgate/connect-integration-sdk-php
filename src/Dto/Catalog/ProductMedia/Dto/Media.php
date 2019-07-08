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

namespace Shopgate\ConnectSdk\Dto\Catalog\ProductMedia\Dto;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method Media setCode(string $code)
 * @method Media setUrl(string $url)
 * @method Media setType(string $type)
 * @method Media setAltText(string $altText)
 * @method Media setTitle(string $title)
 * @method Media setSequenceId(int $sequenceId)
 *
 * @method string getCode()
 * @method string getUrl()
 * @method string getType()
 * @method string getAltText()
 * @method string getTitle()
 * @method int getSequenceId()
 *
 * @codeCoverageIgnore
 */
class Media extends Base
{
    /**
     * @var array
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'code'       => ['type' => 'string'],
            'url'        => ['type' => 'string'],
            'type'       => ['type' => 'string'],
            'altText'    => ['type' => 'string'],
            'title'      => ['type' => 'string'],
            'sequenceId' => ['type' => 'integer']
        ],
        'additionalProperties' => true
    ];
}

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

namespace Shopgate\ConnectSdk\Dto\Order\Order\Dto;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method FulfillmentGroup setId(string $id)
 * @method FulfillmentGroup setFulfillmentMethod(string $fulfillmentMethod)
 * @method FulfillmentGroup setFulfillmentLocationCode(string $fulfillmentLocationCode)
 * @method FulfillmentGroup setShipToAddressSequenceIndex(int $shipToAddressSequenceIndex)
 * @method FulfillmentGroup setFulFillments(FulfillmentGroup\Fulfillment[] $fulfillments)
 * @method string getId()
 * @method string getFulfillmentMethod()
 * @method string getFulfillmentLocationCode()
 * @method int getShipToAddressSequenceIndex()
 * @method FulfillmentGroup\Fulfillment[] getFulfillments()
 *
 * @codeCoverageIgnore
 */
class FulfillmentGroup extends Base
{
    /**
     * @var array
     */
    protected $schema = [
        'type' => 'object',
        'properties' => [
            'id' => ['type' => 'string'],
            'fulfillmentMethod' => ['type' => 'string'],
            'fulfillmentLocationCode' => ['type' => 'string'],
            'shipToAddressSequenceIndex' => ['type' => 'number'],
            'fulfillments' => [
                'type' => 'array',
                'items' => ['$ref' => FulfillmentGroup\Fulfillment::class]
            ]
        ],
        'additionalProperties' => true,
    ];
}

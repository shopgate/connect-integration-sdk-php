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

namespace Shopgate\ConnectSdk\Dto\Order;

use Dto\RegulatorInterface;
use Shopgate\ConnectSdk\Dto\Order\Dto\Fulfillment;
use Shopgate\ConnectSdk\Dto\Order\FulfillmentOrder\Dto;

/**
 * @method Dto\Channel getChannel()
 * @method Dto\FulfillmentOrderAddress getFulfillmentOrderAddress()
 * @method Fulfillment[] getFulfillments()
 * @method Dto\LineItem[] getLineItems()
 * @method Dto\HistoryItem[] getHistory()
 *
 * @codeCoverageIgnore
 */
class FulfillmentOrder extends SimpleFulfillmentOrder
{
    /**
     * Rewritten to provide inheritance of payload structure
     *
     * @inheritDoc
     */
    public function __construct($input = null, $schema = null, RegulatorInterface $regulator = null)
    {
        $this->schema['properties']['channel'] = ['$ref' => Dto\Channel::class];
        $this->schema['properties']['fulfillmentOrderAddress'] = ['$ref' => Dto\FulfillmentOrderAddress::class];
        $this->schema['properties']['fulfillments'] = [
            'type' => 'array',
            'items' => ['$ref' => Fulfillment::class],
        ];
        $this->schema['properties']['lineItems'] = [
            'type' => 'array',
            'items' => ['$ref' => Dto\LineItem::class],
        ];
        $this->schema['properties']['history'] = [
            'type' => 'array',
            'items' => ['$ref' => Dto\HistoryItem::class],
        ];

        parent::__construct($input, $schema, $regulator);
    }
}

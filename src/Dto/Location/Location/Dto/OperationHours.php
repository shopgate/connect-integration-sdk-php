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

namespace Shopgate\ConnectSdk\Dto\Location\Location\Dto;

use Shopgate\ConnectSdk\Dto\Base;

/**
 * @method OperationHours setSun(string $sun)
 * @method OperationHours setMon(string $mon)
 * @method OperationHours setTue(string $tue)
 * @method OperationHours setWed(string $wed)
 * @method OperationHours setThu(string $thu)
 * @method OperationHours setFri(string $fri)
 * @method OperationHours setSat(string $sat)
 * @method string getSun()
 * @method string getMon()
 * @method string getTue()
 * @method string getWed()
 * @method string getThu()
 * @method string getFri()
 * @method string getSat()
 *
 * @codeCoverageIgnore
 */
class OperationHours extends Base
{
    /**
     * @var array
     */
    protected $schema
        = [
            'type'                 => 'object',
            'properties'           => [
                'sun' => ['type' => 'string'],
                'mon' => ['type' => 'string'],
                'tue' => ['type' => 'string'],
                'wed' => ['type' => 'string'],
                'thu' => ['type' => 'string'],
                'fri' => ['type' => 'string'],
                'sat' => ['type' => 'string'],
            ],
            'additionalProperties' => true,
        ];

    /**
     * @param string $day
     * @param string $hours
     *
     * @return $this
     */
    public function add($day, $hours)
    {
        $this->set((string)$day, (string)$hours);

        return $this;
    }
}

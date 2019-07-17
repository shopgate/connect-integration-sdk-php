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
 * @method Address setCode(string $code)
 * @method Address setName(string $name)
 * @method Address setStreet(string $street)
 * @method Address setStreet2(string $street2)
 * @method Address setStreet3(string $street3)
 * @method Address setStreet4(string $street4)
 * @method Address setPostalCode(string $postalCode)
 * @method Address setCity(string $city)
 * @method Address setRegion(string $region)
 * @method Address setCountry(string $country)
 * @method Address setPhoneNumber(string $phoneNumber)
 * @method Address setFaxNumber(string $faxNumber)
 * @method Address setEmailAddress(string $emailAddress)
 * @method Address setIsPrimary(bool $isPrimary)
 * @method string getCode()
 * @method string getName()
 * @method string getStreet()
 * @method string getStreet2()
 * @method string getStreet3()
 * @method string getStreet4()
 * @method string getPostalCode()
 * @method string getCity()
 * @method string getRegion()
 * @method string getCountry()
 * @method string getPhoneNumber()
 * @method string getFaxNumber()
 * @method string getEmailAddress()
 * @method string getIsPrimary()
 *
 * @codeCoverageIgnore
 */
class Address extends Base
{
    /**
     * @var array
     */
    protected $schema = [
            'type'                 => 'object',
            'properties'           => [
                'code'         => ['type' => 'string'],
                'name'         => ['type' => 'string'],
                'street'       => ['type' => 'string'],
                'street1'      => ['type' => 'string'],
                'street2'      => ['type' => 'string'],
                'street3'      => ['type' => 'string'],
                'street4'      => ['type' => 'string'],
                'postalCode'   => ['type' => 'string'],
                'city'         => ['type' => 'string'],
                'region'       => ['type' => 'string'],
                'country'      => ['type' => 'string'],
                'phoneNumber'  => ['type' => 'string'],
                'faxNumber'    => ['type' => 'string'],
                'emailAddress' => ['type' => 'string'],
                'isPrimary'    => ['type' => 'bool']
            ],
            'additionalProperties' => true,
        ];

}

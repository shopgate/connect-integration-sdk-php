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

namespace Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Properties;

use Dto\RegulatorInterface;
use Shopgate\ConnectSdk\Dto\Base;
use \Exception;

class LocalizationType extends Base
{
    protected $schema = [
        'anyOf' => [
            [
                'type' => 'string',
                'additionalProperties' => true,
            ],
            [
                'type' => 'object',
                'additionalProperties' => true,
            ]
        ]
    ];

    /**
     * @inheritDoc
     */
    public function __construct($input = [], $schema = null, RegulatorInterface $regulator = null)
    {
        parent::__construct($input, null === $schema ? $this->getDefaultSchema() : $schema, $regulator);
        if ($input === []) {
            $this->storage_type = 'object';
        }
    }

    /**
     * @param string $locale
     * @param string $string
     *
     * @return $this
     */
    public function add($locale, $string)
    {
        if ($this->getStorageType() == self::STORAGE_TYPE_SCALAR) {
            try {
                $this->hydrate([$locale => $string]);
            } catch (Exception $ex) {
            }
            return $this;
        }

        $this->set((string)$locale, (string)$string);

        return $this;
    }
}

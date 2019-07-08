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

namespace Shopgate\ConnectSdk\Dto\Catalog\ProductMedia;

use Shopgate\ConnectSdk\Dto\Catalog\ProductMedia;
use Shopgate\ConnectSdk\Dto\Catalog\ProductMedia\Dto\Media;

/**
 * @method Create setMedia(ProductMedia\Dto\MediaList[] $media)
 */
class Create extends ProductMedia
{
    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'media' => [
                'type'  => 'array',
                'items' => [
                    'type' => 'object'
                ]
            ]
        ],
        'additionalProperties' => true
    ];

    /**
     * @param string  $locale
     * @param Media[] $media
     *
     * @return Create
     */
    public function add($locale, array $media)
    {
        $mediaList   = $this->get('media');
        $mediaList[] = new ProductMedia\Dto\MediaList([(string) $locale => $media]);
        $this->set('media', $mediaList);

        return $this;
    }
}

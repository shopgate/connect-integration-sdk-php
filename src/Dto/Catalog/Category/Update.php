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

namespace Shopgate\ConnectSdk\Dto\Catalog\Category;

use Shopgate\ConnectSdk\Dto\Catalog\Category;
use Shopgate\ConnectSdk\Dto\Catalog\Category\Dto\Description;
use Shopgate\ConnectSdk\Dto\Catalog\Category\Dto\Image;
use Shopgate\ConnectSdk\Dto\Catalog\Category\Dto\Name;
use Shopgate\ConnectSdk\Dto\Catalog\Category\Dto\Url;

/**
 * @method Update setImage(Image $image)
 * @method Update setName(Name $name)
 * @method Update setParentCategoryCode(string $parentCategoryCode)
 * @method Update setCatalogCode(string $catalogCode)
 * @method Update setUrl(Url $url)
 * @method Update setDescription(Description $description)
 * @method Update setExternalUpdateDate(string $date)
 * @method Update setStatus(string $status)
 * @method Update setSequenceId(int $sequenceId)
 *
 * @inheritdoc
 */
class Update extends Category
{
    /**
     * @var array
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'image'              => ['$ref' => Dto\Image::class],
            'name'               => ['$ref' => Dto\Name::class],
            'parentCategoryCode' => ['type' => 'string'],
            'catalogCode'        => ['type' => 'string'],
            'url'                => ['$ref' => Dto\Url::class],
            'description'        => ['$ref' => Dto\Description::class],
            'externalUpdateDate' => ['type' => 'string'],
            'status'             => ['type' => 'string'],
            'sequenceId'         => ['type' => 'integer']
        ],
        'additionalProperties' => true
    ];
}

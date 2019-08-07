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
 * @method Create setCode(string $code)
 * @method Create setParentCategoryCode(string $parentCategoryCode)
 * @method Create setImage(Image $image)
 * @method Create setName(Name $name)
 * @method Create setUrl(Url $url)
 * @method Create setSequenceId(int $sequenceId)
 * @method Create setDescription(Description $description)
 * @method Create setExternalUpdateDate(string $externalUpdateDate)
 * @method Create setStatus(string $status)
 * @method Create setCatalogCode(string $catalogCode)
 *
 * @inheritdoc
 */
class Create extends Category
{
    /**
     * @var array
     */
    protected $schema = [
        'type'                 => 'object',
        'properties'           => [
            'code'               => ['type' => 'string'],
            'parentCategoryCode' => ['type' => 'string'],
            'image'              => ['$ref' => Dto\Image::class],
            'name'               => ['$ref' => Dto\Name::class],
            'url'                => ['$ref' => Dto\Url::class],
            'sequenceId'         => ['type' => 'integer'],
            'description'        => ['$ref' => Dto\Description::class],
            'externalUpdateDate' => ['type' => 'string'],
            'status'             => ['type' => 'string'],
            'catalogCode'        => ['type' => 'string']
        ],
        'additionalProperties' => true
    ];
}

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

namespace Shopgate\ConnectSdk\Connector_\Entities;

use Psr\Http\Message\ResponseInterface;
use Shopgate\ConnectSdk\DTO\Base as DTOBase;

/**
 * @method ResponseInterface createCategory(DTOBase $payload, array $meta = [])
 * @method ResponseInterface updateCategory(string $categoryCode, DTOBase $payload, array $meta = [])
 * @method ResponseInterface deleteCategory(string $categoryCode, array $meta = [])
 * @method ResponseInterface getCategory(array $meta)
 * @method ResponseInterface createProduct(DTOBase $payload, array $meta = [])
 * @method ResponseInterface updateProduct(string $productCode, DTOBase $payload, array $meta = [])
 * @method ResponseInterface deleteProduct(string $productCode, array $meta = [])
 * @method ResponseInterface getProduct(array $meta)
 *
 * @see \Shopgate\ConnectSdk\Entities\Catalog\Category\Async
 * @see \Shopgate\ConnectSdk\Entities\Catalog\Category\Direct
 * @see \Shopgate\ConnectSdk\Entities\Catalog\Product\Async
 * @see \Shopgate\ConnectSdk\Entities\Catalog\Product\Direct
 */
class Catalog extends Base
{
}

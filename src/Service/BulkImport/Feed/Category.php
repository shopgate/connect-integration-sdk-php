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

namespace Shopgate\ConnectSdk\Service\BulkImport\Feed;

use Shopgate\ConnectSdk\Service\BulkImport\Feed;
use Shopgate\ConnectSdk\Service\BulkImport\Handler\Stream;
use Shopgate\ConnectSdk\Service\BulkImport\Handler\File;

class Category extends Feed
{
    /**
     * @param array $category
     */
    public function add(array $category)
    {
        switch ($this->handlerType) {
            case Stream::HANDLER_TYPE:
                $this->stream->write($this->getItemDivider() . json_encode($category));
                break;
            case File::HANDLER_TYPE:
                fwrite($this->stream, $this->getItemDivider() . json_encode($category));
                break;
        }

        $this->isFirstItem = false;
    }
}

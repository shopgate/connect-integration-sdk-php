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

namespace Shopgate\CloudIntegrationSdk\ValueObject\Route;

abstract class AbstractRoute
{
    /** @var string */
    protected $pattern;

    /** @var string */
    protected $identifier;

    /** @var string[] */
    protected $paramNameList;

    /**
     * @return string
     */
    public function getIdentifier() {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getPattern() {
        return $this->pattern;
    }

    /**
     * @return string[]
     */
    public function getParamNameList() {
        return $this->paramNameList;
    }

    /**
     * @param int $paramPosition
     *
     * @return string | null
     */
    public function getParamName($paramPosition) {
        return array_key_exists((int) $paramPosition, $this->paramNameList)
            ? $this->paramNameList[$paramPosition]
            : null
        ;
    }
}

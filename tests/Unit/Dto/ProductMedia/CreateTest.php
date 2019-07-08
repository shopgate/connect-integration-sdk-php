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

namespace Shopgate\ConnectSdk\Tests\Unit\Dto\ProductMedia;

use PHPUnit\Framework\TestCase;
use Shopgate\ConnectSdk\Dto\Catalog\ProductMedia;
use Shopgate\ConnectSdk\Dto\Catalog\ProductMedia\Create;
use Shopgate\ConnectSdk\Dto\Catalog\ProductMedia\Dto\Media;

/**
 * @coversDefaultClass \Shopgate\ConnectSdk\Dto\Catalog\ProductMedia\Create
 */
class CreateTest extends TestCase
{
    /**
     * Regular way of setting the DTO
     */
    public function testDtoStructure()
    {
        $media = (new Media())
            ->setCode('test1')
            ->setUrl('https://myAwesomeShop.com/image01.jpg')
            ->setType(ProductMedia::TYPE_PDF)
            ->setAltText('a translated string')
            ->setTitle('a translated string2')
            ->setSequenceId(1);

        $result   = (new Create())->add('en-us', [$media])->toJson();
        $expected = '{"media":[{"en-us":[' .
            '{"code":"test1","url":"https:\/\/myAwesomeShop.com\/image01.jpg","type":"pdf",' .
            '"altText":"a translated string","title":"a translated string2","sequenceId":1}]}]}';
        $this->assertEquals($expected, $result);
    }

    /**
     * Multiple DTOs set
     */
    public function testMultipleMediaDTOs()
    {
        $media  = (new Media())->setCode('test1');
        $media2 = (new Media())->setCode('test2');
        $dto    = (new Create())->add('en-us', [$media, $media2])->add('en-gb', [$media]);

        $expected = '{"media":[{"en-us":[{"code":"test1"},{"code":"test2"}]},{"en-gb":[{"code":"test1"}]}]}';
        $this->assertEquals($expected, $dto->toJson());
    }

    /**
     * Alternative way of setting up the DTO object
     */
    public function testAltDtoSetting()
    {
        $media     = (new Media())
            ->setCode('test1')
            ->setUrl('https://myAwesomeShop.com/image01.jpg')
            ->setType(ProductMedia::TYPE_PDF)
            ->setAltText('a translated string')
            ->setTitle('a translated string2')
            ->setSequenceId(1);
        $localized = new ProductMedia\Dto\MediaList(['en-us' => [$media]]);
        $dto       = (new Create())->setMedia([$localized]);
        $expected  = '{"media":[{"en-us":[' .
            '{"code":"test1","url":"https:\/\/myAwesomeShop.com\/image01.jpg","type":"pdf",' .
            '"altText":"a translated string","title":"a translated string2","sequenceId":1}]}]}';
        $this->assertEquals($expected, $dto->toJson());
    }
}

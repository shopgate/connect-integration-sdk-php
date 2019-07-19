<?php

/**
 * Created by PhpStorm.
 * User: shopgate
 * Date: 2019-07-19
 * Time: 12:45
 */

namespace Shopgate\ConnectSdk\Dto\Customer\Wishlist;

use Shopgate\ConnectSdk\Dto\Customer\Wishlist;

/**
 * @method Create setCode(string $code)
 * @method Create setName(string $name)
 * @method Create setItems(Dto\Item[] $items)
 */
class Create extends Wishlist
{
    /**
     * @var array
     * @codeCoverageIgnore
     */
    protected $schema = [
            'type'                 => 'object',
            'properties'           => [
                'code'  => ['type' => 'string'],
                'name'  => ['type' => 'string'],
                'items' => [
                    'type'  => 'array',
                    'items' => ['$ref' => Dto\Item::class]
                ]
            ],
            'additionalProperties' => true,
        ];
}

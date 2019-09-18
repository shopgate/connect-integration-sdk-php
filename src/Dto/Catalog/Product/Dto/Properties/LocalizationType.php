<?php


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
     * @param array                   $input
     * @param null                    $schema
     * @param RegulatorInterface|null $regulator
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

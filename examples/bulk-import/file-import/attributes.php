<?php

require_once('../../bootstrap.php');

use Shopgate\ConnectSdk\Dto\Catalog\Attribute\Dto\Name;
use \Shopgate\ConnectSdk\Dto\Catalog\Attribute;
use \Shopgate\ConnectSdk\Dto\Catalog\AttributeValue;

$attributes = provideSampleAttributes();

try {
    $handler = $sdk->getBulkImportService()->createFileImport();
    $attributeHandler = $handler->createAttributeFeed(CATALOG_CODE);
    $attributeHandler->add($attributes[0]);
    $attributeHandler->add($attributes[1]);
    $attributeHandler->end();
    $handler->trigger();
} catch (Exception $exception) {
    echo $exception->getMessage();
}


/**
 * @return Attribute\Create[]
 */
function provideSampleAttributes()
{
    $attributes = [];

    $extra = new Attribute\Create();
    $extra->setCode(EXTRA_VALUE_CODE)
        ->setType(Attribute\Create::TYPE_TEXT)
        ->setUse(Attribute\Create::USE_EXTRA)
        ->setExternalUpdateDate('2018-12-15T00:00:23.114Z');
    $extraName = new Name();
    $extraName->add('de-de', 'Extra 1 de');
    $extraName->add('en-us', 'Extra 1 en');
    $extra->setName($extraName);
    $extraValue = new AttributeValue\Create();
    $extraValue->setCode(EXTRA_VALUE_CODE);
    $extraValue->setSequenceId(1);

    $extraValueName = new AttributeValue\Dto\Name();
    $extraValueName->add('de-de', 'Extra 1 Attribute de');
    $extraValueName->add('en-us', 'Extra 1 Attribute en');
    $extraValue->setName($extraValueName);

    $extra->setValues([$extraValue]);

    $attributes[] = $extra;

    $extraSecond = new Attribute\Create;
    $extraSecond->setCode(EXTRA_CODE_SECOND)
                ->setType(Attribute\Create::TYPE_TEXT)
                ->setUse(Attribute\Create::USE_EXTRA)
                ->setExternalUpdateDate('2018-12-15T00:00:23.114Z');
    $extraSecondName = new Name();
    $extraSecondName->add('de-de', 'Extra 2 de');
    $extraSecondName->add('en-us', 'Extra 2 en');
    $extraSecond->setName($extraSecondName);

    $extraSecondValue = new AttributeValue\Create();
    $extraSecondValue->setCode(EXTRA_VALUE_CODE_SECOND);
    $extraSecondValue->setSequenceId(1);
    $extraSecondValueName = new AttributeValue\Dto\Name();
    $extraSecondValueName->add('de-de', 'Extra 2 Attribute de');
    $extraSecondValueName->add('en-us', 'Extra 2 Attribute en');
    $extraSecondValue->setName($extraSecondValueName);

    $extraSecond->setValues([$extraSecondValue]);

    $attributes[] = $extra;

    return $attributes;
}

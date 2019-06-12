<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */
require '../vendor/autoload.php';

require_once('./inc/config.php');

$client = new \Shopgate\ConnectSdk\ShopgateSdk($config);

$product = new Shopgate\ConnectSdk\DTO\Catalog\Product\Create();

$name = new \Shopgate\ConnectSdk\DTO\Catalog\Product\Name();
$name->add('de-de', 'Product Name Beispiel');
$name->add('en-us', 'Product Name example');
$product->setName($name);
$product->setCode('wbg_test_3');
$product->setModelType('standard');
$product->setIsInventoryManaged(false);

$price = new \Shopgate\ConnectSdk\DTO\Catalog\Product\Price();
$price->setPrice(55.12);
$price->setCurrencyCode('EUR');
$product->setPrice($price);

$longName = new \Shopgate\ConnectSdk\DTO\Catalog\Product\LongName();
$longName->add('de-de', 'Product langer name Beispiel');
$longName->add('en-us', 'Product long name example');
$product->setLongName($longName);

$shortDescription = new \Shopgate\ConnectSdk\DTO\Catalog\Product\ShortDescription();
$shortDescription->add('de-de', 'Product kurze Beschreibung Beispiel');
$shortDescription->add('en-us', 'Product short description example');
$product->setShortDescription($shortDescription);

$logDescription = new \Shopgate\ConnectSdk\DTO\Catalog\Product\LongDescription();
$logDescription->add('de-de', 'Product lange Beschreibung Beispiel');
$logDescription->add('en-us', 'Product long description example');
$product->setLongDescription($logDescription);

$categoryMapping = new \Shopgate\ConnectSdk\DTO\Catalog\Product\CategoryMapping();
$categoryMapping->setCategoryCode(23);
$categoryMapping->setIsPrimary(true);
$product->setCategories([$categoryMapping]);

$propertyOneAttribute = new Shopgate\ConnectSdk\DTO\Catalog\Product\Property\Attribute();
$propertyOneAttribute->setCode('attribute_1');
$propertyOneAttribute->setValue(['attributeValueCode1', 'attributeValueCode2']);
$propertyOneAttribute->setDisplayGroup('properties');
$propertyOneAttributeSubDisplayGroup = new \Shopgate\ConnectSdk\DTO\Catalog\Product\LocalizationSubDisplayGroup();
$propertyOneAttributeSubDisplayGroup->add('en-us', 'Appearance');
$propertyOneAttributeSubDisplayGroup->add('de-de', 'Aussehen' );
$propertyOneAttribute->setSubDisplayGroup($propertyOneAttributeSubDisplayGroup);

$propertyTwoSimple = new \Shopgate\ConnectSdk\DTO\Catalog\Product\Property\Simple();
$propertyTwoSimple->setCode('simple_2');
$propertyTwoSimpleName = new \Shopgate\ConnectSdk\DTO\Catalog\Product\LocalizationPropertyName();
$propertyTwoSimpleName->add('en-us', 'Color');
$propertyTwoSimpleName->add('de-de', 'Farbe');
$propertyTwoSimple->setName($propertyTwoSimpleName);
$propertyTwoSimpleValue = new \Shopgate\ConnectSdk\DTO\Catalog\Product\LocalizationPropertyValue();
$propertyTwoSimpleValue->add('en-us', ['red', 'blue', 'black']);
$propertyTwoSimpleValue->add('de-de', ['rot', 'blau', 'schwarz']);
$propertyTwoSimple->setValue($propertyTwoSimpleValue);
$propertyTwoSimple->setDisplayGroup('properties');
$propertyTwoSimpleSubDisplayGroup = new \Shopgate\ConnectSdk\DTO\Catalog\Product\LocalizationSubDisplayGroup();
$propertyTwoSimpleSubDisplayGroup->add('en-us', 'Appearance');
$propertyTwoSimpleSubDisplayGroup->add('de-de', 'Aussehen' );
$propertyTwoSimple->setSubDisplayGroup($propertyTwoSimpleSubDisplayGroup);
$propertyTwoSimple->setIsPriced(true);
$propertyTwoSimple->setAttributePrice(88.55);
$propertyTwoSimple->setUnit('number');
$product->setProperties([$propertyOneAttribute, $propertyTwoSimple]);




//print_r($propertyOneAttribute->toJson(1));
print_r($propertyTwoSimple->toJson(1));

//$res = $client->catalog->addProducts([$product], ['requestType' => 'direct']);

//print_r($res->getStatusCode());
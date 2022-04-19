# Migrating From 1.x to 2.x
## DTOs Removed
All DTO classes have been removed from the service class functions. Instead, associative arrays matching the API
specifications are expected.

### Example: Creating a product in 1.x:

```php
<?php
// before
$name = new Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\Name(['en-us' => 'Blue Jeans regular']);

$price = new Shopgate\ConnectSdk\Dto\Catalog\Product\Dto\PriceDto();
$price->setPrice(90)->setSalePrice(84.99)->setCurrencyCode(PriceDto::CURRENCY_CODE_EUR);

$productPayload = new Shopgate\ConnectSdk\Dto\Catalog\Product\Create();
$productPayload->setCode('42')
               ->setCatalogCode('my_catalog')
               ->setName($name)
               ->setStatus(Shopgate\ConnectSdk\Dto\Catalog\Product\Create::STATUS_ACTIVE)
               ->setModelType(Shopgate\ConnectSdk\Dto\Catalog\Product\Create::MODEL_TYPE_STANDARD)
               ->setIsInventoryManaged(true)
               ->setPrice($price);

$sgSdk->getCatalogService()->addProducts([$productPayload]);
```

### Example: Creating a product in 2.x:
```php
<?php
// before
$productPayload = [
  'code' => '42',
  'catalogCode' => 'my_catalog',
  'name' => ['en-us' => 'Blue Jeans Regular'],
  'status' => 'active',
  'modelType' => 'standard',
  'isInventoryManaged' => true,
  'price' => ['price' => 90, 'salePrice' => 84.99, 'currencyCode' => 'EUR']
];

$sgSdk->getCatalogService()->addProducts([$productPayload]);
```

## Replaced `requestType` Property With Separate Parameter
The property `requestType` that was previously passed within the `$query` parameter of some functions has been replaced
with a separate parameter named `$async`. The change is supposed to help separation of SDK functionality from actual API
query parameters. Also, it is a more clear indicator of whether events are supported for an entity and operation.

As previously, for operations that support events, this is the default.

Newly added support for events will reflect in
the `$async` parameter being added to the end of the method's argument list and defaulting to `true`. Versions having
such changes will be **minor** updates.

### Example: Sending a direct request in 1.x:
```php
<?php
$sgSdk->getCatalogService()->addProducts([[/* ... */]], ['requestType' => Shopgate\ConnectSdk\ShopgateSdk::REQUEST_TYPE_DIRECT]);
```

### Example: Sending a direct request in 2.x:
```php
<?php
$sgSdk->getCatalogService()->addProducts([[/* ... */]], [], /* $async => */ false);
```

## Return Values On Successful Requests
In 1.x, the return values of the "Service" class functions were `Psr\Http\Message\ResponseInterface` objects. In 2.x the
response will be the JSON-decoded response of the API, represented as an associative array.

This also includes bulk actions such as `addProducts`, where entity-related errors are in an `errors` property of the
response. In 1.x an exception would be thrown if the `errors` property had at least one element, containing the code
and error message of the first error in that list. In 2.x the list of errors will be returned as part of the API
response, JSON-decoded as an associative array.

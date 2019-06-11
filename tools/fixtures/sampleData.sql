SET FOREIGN_KEY_CHECKS=1;

INSERT IGNORE INTO merchant.`Merchant` (`MerchantID`, `OwnerUserID`, `MerchantName`, `MerchantCode`, `Region`, `AppLogo`, `CreateBy`)
VALUES
('1', '4b4b51ce-a4de-4e48-9cf4-ade08de2cc02', 'Ponder Nerd', '1', 'US', 'https://scontent-ber1-1.xx.fbcdn.net/v/t1.0-1/p200x200/28471572_10156169825948781_8970975354537639936_n.jpg?_nc_cat=106&_nc_ht=scontent-ber1-1.xx&oh=b7c659809d68e285aca5fcfab13dec91&oe=5C6E1AD0', 'Mike Haze'),
('6395bd1f-c2df-4d20-9ada-374def0ab516', '4b4b51ce-a4de-4e48-9cf4-ade08de2cc02', 'Ponder Nerd', 'newMerchant', 'US', 'https://scontent-ber1-1.xx.fbcdn.net/v/t1.0-1/p200x200/28471572_10156169825948781_8970975354537639936_n.jpg?_nc_cat=106&_nc_ht=scontent-ber1-1.xx&oh=b7c659809d68e285aca5fcfab13dec91&oe=5C6E1AD0', 'Mike Haze');

INSERT IGNORE INTO merchant.`MerchantSetting` (`MerchantSettingID`,`MerchantID`,`Key`,`Value`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
('1','1','DefaultTimezone','America/Chicago','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL),
('2','1','DefaultCurrency','USD','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL),
('3','1','DefaultLocale','en-us','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL);

INSERT IGNORE INTO location.`LocationType` (`LocationTypeID`, `LocationTypeCode`, `TypeDesc`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
('1', 'WHS', 'Warehouse Location', 'Mike Haze', '2018-11-11 16:50:18', NULL, NULL, NULL, NULL);


INSERT IGNORE INTO location.`Location` (`LocationID`, `MerchantID`, `LocationTypeID`, `LocationCode`, `LocationName`, `LocationStatus`, `Latitude`, `Longitude`, `CreateBy`, `IsDefault`)
VALUES
('d581bf8e-acab-4273-b6a8-48aea3ecb38b', '1', '1', '1', 'Ponder Nerd Warehouse', 'active', '50.117330', '9.681810', 'Mike Haze', 1),
('0890dc14-5888-48a6-a29a-b57df677e5ef', '1', '1', 'DEMO 2', 'Ponder Nerd Warehouse 3', 'active', '45.117330', '19.681810', 'Mike Haze', 0);


INSERT IGNORE INTO catalog.`ParentCatalog` (`ParentCatalogID`, `MerchantID`, `ParentCatalogCode`, `ParentCatalogName`, `DefaultLocaleCode`, `DefaultCurrencyCode`, `Status`, `isDefault`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
('71abdc28-17b2-49d6-ae11-bbfef9dbb868', '1', 'SGC', 'Shopgate Global Catalog', 'en-us', 'USD', 'Active', '1', 'Mike Haze', '2018-12-14 20:03:42', NULL, NULL, NULL, NULL);

INSERT IGNORE INTO catalog.`Catalog` (`CatalogID`, `CatalogCode`, `ParentCatalogID`, `CatalogName`, `DefaultLocaleCode`, `DefaultCurrencyCode`, `isDefault`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
('2d8ff9ab-0992-4e73-ae1f-046dd6e768a2', 'NA Wholesale', '71abdc28-17b2-49d6-ae11-bbfef9dbb868', 'North American Wholesale', 'en-us', 'USD', 0, 'Mike Haze', '2018-12-14 20:06:31', NULL, NULL, NULL, NULL),
('ad14a8e9-6dac-4789-a593-5d263952557c', 'NA Retail', '71abdc28-17b2-49d6-ae11-bbfef9dbb868', 'North American Retail', 'en-us', 'USD', 1, 'Mike Haze', '2018-12-14 20:06:31', NULL, NULL, NULL, NULL);

INSERT INTO catalog.`Category` (`CategoryId`, `CatalogId`, `ParentId`, `CategoryCode`, `ImgUrl`,`CategoryURL`, `SequenceId`, `CreateBy`)
VALUES
('445da2b3-e8f8-47b4-a8f5-9adff092e751', 'ad14a8e9-6dac-4789-a593-5d263952557c', NULL, '41', NULL, NULL, 2, 'Pascal Vomhoff'),
('6ae2310c-e6b5-46c5-a45b-1a03f6933b50', '2d8ff9ab-0992-4e73-ae1f-046dd6e768a2', NULL, 'wh-41', NULL, NULL, 1, 'Pascal Vomhoff'),
('0e873427-d6a8-4965-99db-fcd247d6898b', '2d8ff9ab-0992-4e73-ae1f-046dd6e768a2', NULL, 'wh-42', NULL, NULL, 2, 'Pascal Vomhoff'),
('ea8888fc-1b84-4417-a41b-3b140decafe4', '2d8ff9ab-0992-4e73-ae1f-046dd6e768a2', '0e873427-d6a8-4965-99db-fcd247d6898b', 'wh-43', 'https://www.shopgate.com/1.jpg', NULL, 3, 'Pascal Vomhoff'),
('ca2f3023-4c08-4b22-8638-06ebb300bead', 'ad14a8e9-6dac-4789-a593-5d263952557c', NULL, '42', NULL, 'https://someurl/cat/42', 1, 'Pascal Vomhoff');

INSERT IGNORE INTO catalog.`CategoryContent` (`CategoryContentId`, `CategoryId`, `LocaleCode`, `Name`, `Description`, `CreateBy`)
VALUES
('de46e680-1005-43cf-b212-771e14c8eb36', '6ae2310c-e6b5-46c5-a45b-1a03f6933b50', 'en-us', 'Mens', 'Some description', ''),
('a0dbdab0-9df1-4c7f-a490-493e151002da', '0e873427-d6a8-4965-99db-fcd247d6898b', 'en-us', 'Another test category', 'Some description', ''),
('1d69aafa-9573-40a2-8527-44cb9dcac4b6', 'ea8888fc-1b84-4417-a41b-3b140decafe4', 'en-us', 'Super test category', 'Some description', ''),
('406ac168-6057-4be3-b5db-385e7d99cf45', '445da2b3-e8f8-47b4-a8f5-9adff092e751', 'en-us', 'Some test category', 'Some description', ''),
('dbd768ba-330a-4482-8e9e-d8c8f23b4ed9', '445da2b3-e8f8-47b4-a8f5-9adff092e751', 'de-de', 'Test Kategorie', 'Eine Beschreibung', ''),
('4dc10756-ced4-4789-b476-70ac30300ca6', 'ca2f3023-4c08-4b22-8638-06ebb300bead', 'en-us', 'Category #2', 'Some description', '');


INSERT IGNORE INTO catalog.`Product` (`ProductID`, `CatalogID`, `ProductCode`, `ParentProductCode`, `ProductModelType`, `MFGPartNum`, `DistiPartNum`, `UPC`, `ProductUnit`, `EAN`, `ISBN`, `Status`, `StartDate`, `EndDate`, `EOLDate`, `isInventoryManaged`, `InventoryTreatment`, `isShippedAlone`, `PackageHeight`, `PackageHeightUnit`, `PackageWidth`, `PackageWidthUnit`, `PackageLength`, `PackageLengthUnit`, `PackageWeight`, `PackageWeightUnit`, `Rating`, `ProductURL`, `FirstAvailableDate`, `isTaxed`, `TaxClass`, `ProductAttribute`, `fulfillmentMethods`, `isSerialized`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
('c37e0586-8a9a-42c6-bb9f-87941299d078', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'sg0004-1', NULL, 'standard', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 1, 'allowBackOrders', 0, NULL, NULL, NULL, NULL, NULL, NULL, 5.0000, 'lbs', NULL, 'https://magento-omnichannel2.shopgatedev.com/testproduct-5.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Unknown', '2019-02-05 12:34:04', 'Unknown', '2019-02-11 17:39:38', NULL, NULL),
('89f7ef9b-2a99-41e1-bd4a-854d07c33bd3', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'sg0007', NULL, 'standard', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 1, 'allowBackOrders', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://magento-omnichannel2.shopgatedev.com/testproduct-7.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Unknown', '2019-02-06 11:16:20', 'Unknown', '2019-02-06 11:18:07', NULL, NULL),
('8431fc89-3f67-4267-a722-e942efa03798', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'MH', NULL, 'configurable', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 1, 'allowBackOrders', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://magento-omnichannel2.shopgatedev.com/testproduct-7.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Unknown', '2019-02-06 11:16:20', 'Unknown', '2019-02-06 11:18:07', NULL, NULL),
('40844157-4d80-443c-90d4-7c7b3153f4e2', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'MH-512GB-2GB', 'MH', 'variant', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 1, 'allowBackOrders', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://magento-omnichannel2.shopgatedev.com/testproduct-7.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Unknown', '2019-02-06 11:16:20', 'Unknown', '2019-02-06 11:18:07', NULL, NULL),
('2aaa2f9c-2f96-43c1-89a4-8d77c679582e', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'MH-512GB-16GB', 'MH', 'variant', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 1, 'allowBackOrders', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://magento-omnichannel2.shopgatedev.com/testproduct-7.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Unknown', '2019-02-06 11:16:20', 'Unknown', '2019-02-06 11:18:07', NULL, NULL),
('8f130a6b-d401-4903-b9cf-212a6fa7ad7a', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'MH-256GB-8GB', 'MH', 'variant', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 1, 'allowBackOrders', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://magento-omnichannel2.shopgatedev.com/testproduct-7.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Unknown', '2019-02-06 11:16:20', 'Unknown', '2019-02-06 11:18:07', NULL, NULL),
('65acc3c2-2e3f-4760-8900-73dfbd2a7c77', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'MH-1TB-8GB', 'MH', 'variant', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 1, 'allowBackOrders', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://magento-omnichannel2.shopgatedev.com/testproduct-7.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Unknown', '2019-02-06 11:16:20', 'Unknown', '2019-02-06 11:18:07', NULL, NULL),
('6fa326df-a24c-487f-b218-98c4711e0c81', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'MH-2TB-4GB', 'MH', 'variant', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 1, 'allowBackOrders', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://magento-omnichannel2.shopgatedev.com/testproduct-7.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Unknown', '2019-02-06 11:16:20', 'Unknown', '2019-02-06 11:18:07', NULL, NULL),
('71d22746-4d70-486a-92bf-c435f15fc889', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'MH-2TB-2GB', 'MH', 'variant', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 1, 'allowBackOrders', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://magento-omnichannel2.shopgatedev.com/testproduct-7.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Unknown', '2019-02-06 11:16:20', 'Unknown', '2019-02-06 11:18:07', NULL, NULL),
('b6d8ddab-880b-45f4-b558-2e69507cdb00', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'MH-4TB-16GB', 'MH', 'variant', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 0, 'allowBackOrders', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://magento-omnichannel2.shopgatedev.com/testproduct-7.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Unknown', '2019-02-06 11:16:20', 'Unknown', '2019-02-06 11:18:07', NULL, NULL),
('1b824405-aebc-45fc-b1af-eec59084b786', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'MH-4TB-8GB', 'MH', 'variant', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 1, 'allowBackOrders', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://magento-omnichannel2.shopgatedev.com/testproduct-7.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Unknown', '2019-02-06 11:16:20', 'Unknown', '2019-02-06 11:18:07', NULL, NULL),

('0c1bda6e-56c2-4950-9455-d5c93c3dc634', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'TShirt', NULL, 'configurable', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 1, 'allowBackOrders', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://magento-omnichannel2.shopgatedev.com/gabrielle-micro-sleeve-top.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', 'Konstantin Tsabolov', '2019-03-26 16:18:00', NULL, NULL),
('63507a9d-39a0-4269-83cd-c3f343d84470', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'TShirt-Red', 'TShirt', 'variant', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 1, 'allowBackOrders', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://magento-omnichannel2.shopgatedev.com/gabrielle-micro-sleeve-top.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', 'Konstantin Tsabolov', '2019-03-26 16:18:00', NULL, NULL),
('171b2d2a-bf92-4b6c-98a8-0683046c2c98', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'TShirt-Green', 'TShirt', 'variant', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 1, 'allowBackOrders', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://magento-omnichannel2.shopgatedev.com/gabrielle-micro-sleeve-top.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', 'Konstantin Tsabolov', '2019-03-26 16:18:00', NULL, NULL),
('991782a7-1ce4-494e-8a69-b8ac1791cae8', 'ad14a8e9-6dac-4789-a593-5d263952557c', 'TShirt-Blue', 'TShirt', 'variant', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, NULL, 1, 'allowBackOrders', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'https://magento-omnichannel2.shopgatedev.com/gabrielle-micro-sleeve-top.html', NULL, 1, '2', NULL, 'directShip,inStorePickup', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', 'Konstantin Tsabolov', '2019-03-26 16:18:00', NULL, NULL);

INSERT IGNORE INTO catalog.`ProductContent` (`ProductContentID`, `ProductID`, `LocaleCode`, `Name`, `ShortName`, `LongName`, `ShortDesc`, `LongDesc`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
('c15f5c35-5602-46d6-afcb-d6b63a9b1d13', 'c37e0586-8a9a-42c6-bb9f-87941299d078', 'en-us', 'Testproduct1', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Unknown', '2019-02-04 16:48:56', '2019-02-04 18:46:01', '2019-02-04 18:46:01', NULL, NULL),
('15f16537-0f99-4322-b4fb-b1139410d364', '89f7ef9b-2a99-41e1-bd4a-854d07c33bd3', 'en-us', 'Testproduct2', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Unknown', '2019-02-04 16:48:56', '2019-02-04 18:46:01', '2019-02-04 18:46:01', NULL, NULL),
('23accb0b-5cb4-4ef3-a4f2-bfaae5177183', '8431fc89-3f67-4267-a722-e942efa03798', 'en-us', 'MH product', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Unknown', '2019-02-04 16:48:56', '2019-02-04 18:46:01', '2019-02-04 18:46:01', NULL, NULL),
('23accb0b-5cb4-4ef3-a4f2-aaaaaaaaaaaa', '40844157-4d80-443c-90d4-7c7b3153f4e2', 'en-us', 'MH product', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Unknown', '2019-02-04 16:48:56', '2019-02-04 18:46:01', '2019-02-04 18:46:01', NULL, NULL),
('23accb0b-5cb4-4ef3-a4f2-bbbbbbbbbbbb', '2aaa2f9c-2f96-43c1-89a4-8d77c679582e', 'en-us', 'MH product', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Unknown', '2019-02-04 16:48:56', '2019-02-04 18:46:01', '2019-02-04 18:46:01', NULL, NULL),
('23accb0b-5cb4-4ef3-a4f2-cccccccccccc', '8f130a6b-d401-4903-b9cf-212a6fa7ad7a', 'en-us', 'MH product', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Unknown', '2019-02-04 16:48:56', '2019-02-04 18:46:01', '2019-02-04 18:46:01', NULL, NULL),
('23accb0b-5cb4-4ef3-a4f2-dffffdddsdfs', '65acc3c2-2e3f-4760-8900-73dfbd2a7c77', 'en-us', 'MH product', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Unknown', '2019-02-04 16:48:56', '2019-02-04 18:46:01', '2019-02-04 18:46:01', NULL, NULL),
('23accb0b-5cb4-4ef3-a4f2-wer43rte55gf', '6fa326df-a24c-487f-b218-98c4711e0c81', 'en-us', 'MH product', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Unknown', '2019-02-04 16:48:56', '2019-02-04 18:46:01', '2019-02-04 18:46:01', NULL, NULL),
('23accb0b-5cb4-4ef3-a4f2-234r454r5tt5', '71d22746-4d70-486a-92bf-c435f15fc889', 'en-us', 'MH product', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Unknown', '2019-02-04 16:48:56', '2019-02-04 18:46:01', '2019-02-04 18:46:01', NULL, NULL),
('23accb0b-5cb4-4ef3-a4f2-23423d43f34f', 'b6d8ddab-880b-45f4-b558-2e69507cdb00', 'en-us', 'MH product', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Unknown', '2019-02-04 16:48:56', '2019-02-04 18:46:01', '2019-02-04 18:46:01', NULL, NULL),
('23accb0b-5cb4-4ef3-a4f2-5d5f4f4r5f4v', '1b824405-aebc-45fc-b1af-eec59084b786', 'en-us', 'MH product', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Unknown', '2019-02-04 16:48:56', '2019-02-04 18:46:01', '2019-02-04 18:46:01', NULL, NULL),

('c4dea1c7-465d-40df-b5ae-3aa32ef662ee', '0c1bda6e-56c2-4950-9455-d5c93c3dc634', 'en-us', 'T-shirt', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('153d8965-5df5-4869-a7c1-12c929ada0c1', '63507a9d-39a0-4269-83cd-c3f343d84470', 'en-us', 'Red t-shirt', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('9b6b28bb-915a-4872-a04b-67ee3cd90a09', '171b2d2a-bf92-4b6c-98a8-0683046c2c98', 'en-us', 'Green t-shirt', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('ed1b3920-5359-4b26-9715-f14b1105e6fd', '991782a7-1ce4-494e-8a69-b8ac1791cae8', 'en-us', 'Blue t-shirt', NULL, NULL, '<p>Short description</p>', '<p>Description</p>', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL);


INSERT IGNORE INTO catalog.`ProductInventory` (`ProductInventoryID`, `ProductID`, `LocationID`, `SKU`, `OnHandQTY`, `SafetyStockQTY`, `BIN`, `BINLocation`, `CreateBy`)
VALUES
('152e5a0f-c76c-4d59-9a29-1dd0bca27bca', 'c37e0586-8a9a-42c6-bb9f-87941299d078', 'd581bf8e-acab-4273-b6a8-48aea3ecb38b', '123', 12, 1, NULL, NULL, ''),
('152e5a0f-c76c-4d59-9a29-5k65k563nesc', '8f130a6b-d401-4903-b9cf-212a6fa7ad7a', 'd581bf8e-acab-4273-b6a8-48aea3ecb38b', '123', 12, 1, NULL, NULL, ''),
('152e5a0f-c76c-4d59-9a29-45b5b54kj543', '65acc3c2-2e3f-4760-8900-73dfbd2a7c77', 'd581bf8e-acab-4273-b6a8-48aea3ecb38b', '123', 12, 1, NULL, NULL, ''),
('152e5a0f-c76c-4d59-9a29-324kj324jk34', '71d22746-4d70-486a-92bf-c435f15fc889', 'd581bf8e-acab-4273-b6a8-48aea3ecb38b', '123', 12, 1, NULL, NULL, ''),
('152e5a0f-c76c-4d59-9a29-oijh4j5hjk4j', '1b824405-aebc-45fc-b1af-eec59084b786', 'd581bf8e-acab-4273-b6a8-48aea3ecb38b', '123', 12, 1, NULL, NULL, ''),
('124b687c-a00f-41d6-aa1c-9bd7751e67d3', 'c37e0586-8a9a-42c6-bb9f-87941299d078', '0890dc14-5888-48a6-a29a-b57df677e5ef', '456', 30, 1, NULL, NULL, ''),
('66d3cc91-d46c-4718-9541-768509d20e46', '89f7ef9b-2a99-41e1-bd4a-854d07c33bd3', '0890dc14-5888-48a6-a29a-b57df677e5ef', '789', 50, 1, NULL, NULL, '');

INSERT IGNORE INTO catalog.`InventoryReservation` (`InventoryReservationID`,`ProductInventoryID`,`FulfillmentOrderID`,`SalesOrderID`, `QTY`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
('2cb17e52-5aa9-4101-87df-c0cbdcbc0bb5','152e5a0f-c76c-4d59-9a29-5k65k563nesc','92b63a49-ceb4-42ba-8ea6-42bfa4b13e1e','4321', 5,'Pascal Vomhoff','1970-01-01 00:00:00',NULL,'2019-02-07 19:32:09',NULL,NULL),
('2cb17e52-5aa9-4101-87df-34j3k3k45jj5','152e5a0f-c76c-4d59-9a29-oijh4j5hjk4j','1306363b-583b-42b6-857b-1f228737477d','4321', 12,'Pascal Vomhoff','1970-01-01 00:00:00',NULL,'2019-02-07 19:32:09',NULL,NULL);

INSERT IGNORE INTO catalog.`Attribute` (`AttributeID`,`MerchantID`,`AttributeCode`,`AttributeType`,`AttributeUse`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
('56166d66-3c34-11e9-a392-07a413d253ed','1','warranty','Text','Option','Unkown','2019-03-01 15:15:12',NULL,NULL,NULL,NULL),
('08cb912c-5083-4ac2-a1f2-2445a775d00d','1','disk','Text','Option','Unkown','2019-03-01 15:15:12',NULL,NULL,NULL,NULL),
('fedd3eab-b506-423e-a28e-b3540517b353','1','ram','Text','Option','Unkown','2019-03-01 15:15:12',NULL,NULL,NULL,NULL),

('b9216b07-9187-4c49-aa10-92ab9787f0fe', '1', 'player-number', 'Text', 'Extra', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('0a24780a-fba6-4db4-a5ee-d81a41607165', '1', 'player-name', 'Text', 'Extra', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('09d4ee4d-73ab-4a7d-b005-875f4821adfe', '1', 'custom-player-name', 'Input', 'Extra', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL);

INSERT IGNORE INTO catalog.`AttributeContent` (`AttributeContentID`,`AttributeID`,`LocaleCode`,`AttributeName`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
('f2a95832-3c34-11e9-8f00-fbc1ff9c5296','56166d66-3c34-11e9-a392-07a413d253ed','en-us','Add Warranty?','Unknown','2019-03-01 15:16:48',NULL,NULL,NULL,NULL),
('99b68080-a9f5-4bcd-9a5f-1a9c27ccc311','fedd3eab-b506-423e-a28e-b3540517b353','en-us','RAM','Unknown','2019-03-01 15:16:48',NULL,NULL,NULL,NULL),
('1e680a58-9218-477d-90f5-24a36a46eaef','08cb912c-5083-4ac2-a1f2-2445a775d00d','en-us','Disk size','Unknown','2019-03-01 15:16:48',NULL,NULL,NULL,NULL),

('361fa5d4-bb55-4481-ac9f-88ff82feb1ba', 'b9216b07-9187-4c49-aa10-92ab9787f0fe', 'en-us', 'Player number', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('bb88305e-bc5a-40aa-8845-707535023697', '0a24780a-fba6-4db4-a5ee-d81a41607165', 'en-us', 'Player name', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('a33dbfe2-428c-4f77-8ae4-ca3119849fff', '09d4ee4d-73ab-4a7d-b005-875f4821adfe', 'en-us', 'Custom player name', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL);

INSERT IGNORE INTO catalog.`AttributeValue` (`AttributeValueID`,`AttributeID`,`AttributeValue`,`SequenceId`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
('5fbbf584-3c34-11e9-acb5-f796e8c4def3','56166d66-3c34-11e9-a392-07a413d253ed','3yrs',0,'Unknown','2019-03-01 15:21:35',NULL,NULL,NULL,NULL),
('b57cc55a-e313-4af7-b345-901e92b374f3','fedd3eab-b506-423e-a28e-b3540517b353','2GB', 1,'Unknown','2019-03-01 15:21:35',NULL,NULL,NULL,NULL),
('eeca80d2-a0fc-4cf0-9a69-2f477b51ab25','fedd3eab-b506-423e-a28e-b3540517b353','4GB', 2,'Unknown','2019-03-01 15:21:35',NULL,NULL,NULL,NULL),
('a06b8211-6706-498f-9f17-0211c5b21115','fedd3eab-b506-423e-a28e-b3540517b353','8GB', 3,'Unknown','2019-03-01 15:21:35',NULL,NULL,NULL,NULL),
('c1fd3d91-75d6-4b2c-a4a1-a94fb43f1004','fedd3eab-b506-423e-a28e-b3540517b353','16GB',4,'Unknown','2019-03-01 15:21:35',NULL,NULL,NULL,NULL),
('30ee8fbf-c7eb-4949-9160-3ffd07de3ddd','08cb912c-5083-4ac2-a1f2-2445a775d00d','256GB',1,'Unknown','2019-03-01 15:21:35',NULL,NULL,NULL,NULL),
('53bca4e3-b349-4305-a56e-dbd50cb91639','08cb912c-5083-4ac2-a1f2-2445a775d00d','512GB',2,'Unknown','2019-03-01 15:21:35',NULL,NULL,NULL,NULL),
('5543a301-e6bc-4cd3-a0de-955dccd96b5c','08cb912c-5083-4ac2-a1f2-2445a775d00d','1TB',3,'Unknown','2019-03-01 15:21:35',NULL,NULL,NULL,NULL),
('99b68080-a9f5-4bcd-9a5f-1a9c27ccc311','08cb912c-5083-4ac2-a1f2-2445a775d00d','2TB',4,'Unknown','2019-03-01 15:21:35',NULL,NULL,NULL,NULL),
('c1fd3d91-75d6-4b2c-a4a1-aaaaaaaaaaaa','08cb912c-5083-4ac2-a1f2-2445a775d00d','4TB',5,'Unknown','2019-03-01 15:21:35',NULL,NULL,NULL,NULL),

-- Player numbers
('b31e2086-49ef-4a61-a8f9-0aba7763c2be', 'b9216b07-9187-4c49-aa10-92ab9787f0fe', '1', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('d0dba55e-e385-4a43-a54a-b3610907293d', 'b9216b07-9187-4c49-aa10-92ab9787f0fe', '10', 1, 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('667477a8-965b-4ba4-9653-704c7980271a', 'b9216b07-9187-4c49-aa10-92ab9787f0fe', '33', 2, 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),

-- Player names
('76f43a4b-bd3b-4fd3-932a-d7dfb9fe877a', '0a24780a-fba6-4db4-a5ee-d81a41607165', 'Ibrahimović', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('dabbe084-062d-457b-9251-41f1f95a9574', '0a24780a-fba6-4db4-a5ee-d81a41607165', 'Ronaldo', 1, 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('532fd039-c576-4a7b-9b93-f45513e5c3e3', '0a24780a-fba6-4db4-a5ee-d81a41607165', 'Messi', 2, 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),

-- Dummy value for an 'input' attribute
('0ecde677-8937-4a31-a68f-eec0a8545555', '09d4ee4d-73ab-4a7d-b005-875f4821adfe', '', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL);

INSERT IGNORE INTO catalog.`AttributeValueContent` (`AttributeValueContentID`,`AttributeValueID`,`LocaleCode`,`AttributeValueName`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
('f993256e-3c35-11e9-ba8f-0f61e335233f','5fbbf584-3c34-11e9-acb5-f796e8c4def3','en-us','3 Years','Unknown','2019-03-01 15:23:55',NULL,NULL,NULL,NULL),
('002d3e7b-ba52-4bbc-9a4d-e094498b5674','b57cc55a-e313-4af7-b345-901e92b374f3','en-us','2GB','Unknown','2019-03-01 15:23:55',NULL,NULL,NULL,NULL),
('c729a986-c11f-4988-b89f-fc2b4828841e','eeca80d2-a0fc-4cf0-9a69-2f477b51ab25','en-us','4GB','Unknown','2019-03-01 15:23:55',NULL,NULL,NULL,NULL),
('993edf7b-3460-47ae-a486-06aaa6e19f3f','a06b8211-6706-498f-9f17-0211c5b21115','en-us','8GB','Unknown','2019-03-01 15:23:55',NULL,NULL,NULL,NULL),
('2eb0d1f8-202f-461d-b846-21e209aba887','c1fd3d91-75d6-4b2c-a4a1-a94fb43f1004','en-us','16GB','Unknown','2019-03-01 15:23:55',NULL,NULL,NULL,NULL),
('be0beab2-484a-4c4f-9ba5-7e46453a1530','30ee8fbf-c7eb-4949-9160-3ffd07de3ddd','en-us','256GB','Unknown','2019-03-01 15:23:55',NULL,NULL,NULL,NULL),
('13a224a7-d5e9-45d6-905b-309a900234af','53bca4e3-b349-4305-a56e-dbd50cb91639','en-us','512GB','Unknown','2019-03-01 15:23:55',NULL,NULL,NULL,NULL),
('f0f7235a-a209-4685-9679-04c89fc5ca28','5543a301-e6bc-4cd3-a0de-955dccd96b5c','en-us','1TB','Unknown','2019-03-01 15:23:55',NULL,NULL,NULL,NULL),
('842e8800-26b5-490a-9f12-0eda787a6294','99b68080-a9f5-4bcd-9a5f-1a9c27ccc311','en-us','2TB','Unknown','2019-03-01 15:23:55',NULL,NULL,NULL,NULL),
('811c5355-1a94-4c47-bfd4-b6886ba51e3c','c1fd3d91-75d6-4b2c-a4a1-aaaaaaaaaaaa','en-us','4TB','Unknown','2019-03-01 15:23:55',NULL,NULL,NULL,NULL),

('6401b057-4b95-4bcc-98f2-3dd49fb2b634', 'b31e2086-49ef-4a61-a8f9-0aba7763c2be', 'en-us', '1', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('17337bc4-14e9-422e-90ff-34242d2ba16b', 'd0dba55e-e385-4a43-a54a-b3610907293d', 'en-us', '10', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('7967628f-eea3-4bdd-a338-61c00747f832', '667477a8-965b-4ba4-9653-704c7980271a', 'en-us', '33', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('94f89503-0b30-4dbd-bf23-7127af1c66ac', '76f43a4b-bd3b-4fd3-932a-d7dfb9fe877a', 'en-us', 'Ibrahimović', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('8d73d606-8dce-43a5-95c3-429ad913117b', 'dabbe084-062d-457b-9251-41f1f95a9574', 'en-us', 'Ronaldo', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL),
('450fe40c-b485-4a71-97c6-a74fdde48425', '532fd039-c576-4a7b-9b93-f45513e5c3e3', 'en-us', 'Messi', 'Konstantin Tsabolov', '2019-03-26 16:15:00', NULL, NULL, NULL, NULL);

INSERT IGNORE INTO catalog.`ProductOption` (`ProductOptionID`,`ProductID`,`AttributeID`,`AttributeValueID`,`AdditionalPrice`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
('fbbb66be-3c33-11e9-9c62-c752d1b46358','c37e0586-8a9a-42c6-bb9f-87941299d078','56166d66-3c34-11e9-a392-07a413d253ed','5fbbf584-3c34-11e9-acb5-f796e8c4def3',5,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),

('35af11ef-04a6-44b2-8042-bbbbbbbbbbbb','40844157-4d80-443c-90d4-7c7b3153f4e2','08cb912c-5083-4ac2-a1f2-2445a775d00d','53bca4e3-b349-4305-a56e-dbd50cb91639',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),
('35af11ef-04a6-44b2-8042-b09b920e139a','40844157-4d80-443c-90d4-7c7b3153f4e2','fedd3eab-b506-423e-a28e-b3540517b353','b57cc55a-e313-4af7-b345-901e92b374f3',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),

('2ce661ad-1b74-4e6a-8069-cccccccccccc','2aaa2f9c-2f96-43c1-89a4-8d77c679582e','08cb912c-5083-4ac2-a1f2-2445a775d00d','53bca4e3-b349-4305-a56e-dbd50cb91639',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),
('2ce661ad-1b74-4e6a-8069-588305fd208d','2aaa2f9c-2f96-43c1-89a4-8d77c679582e','fedd3eab-b506-423e-a28e-b3540517b353','c1fd3d91-75d6-4b2c-a4a1-a94fb43f1004',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),

('4d2cf675-844b-43b1-82fa-dddddddddddd','8f130a6b-d401-4903-b9cf-212a6fa7ad7a','08cb912c-5083-4ac2-a1f2-2445a775d00d','30ee8fbf-c7eb-4949-9160-3ffd07de3ddd',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),
('4d2cf675-844b-43b1-82fa-3aa5dfce62ba','8f130a6b-d401-4903-b9cf-212a6fa7ad7a','fedd3eab-b506-423e-a28e-b3540517b353','a06b8211-6706-498f-9f17-0211c5b21115',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),

('e34e47b3-3072-4ddb-b5f0-aaaaaaaaaaaa','65acc3c2-2e3f-4760-8900-73dfbd2a7c77','08cb912c-5083-4ac2-a1f2-2445a775d00d','5543a301-e6bc-4cd3-a0de-955dccd96b5c',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),
('e34e47b3-3072-4ddb-b5f0-17d0ef563a36','65acc3c2-2e3f-4760-8900-73dfbd2a7c77','fedd3eab-b506-423e-a28e-b3540517b353','a06b8211-6706-498f-9f17-0211c5b21115',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),

('dc7a6e4b-67ca-44aa-860d-qqqqqqqqqqqq','6fa326df-a24c-487f-b218-98c4711e0c81','08cb912c-5083-4ac2-a1f2-2445a775d00d','99b68080-a9f5-4bcd-9a5f-1a9c27ccc311',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),
('dc7a6e4b-67ca-44aa-860d-0836e72c4cf8','6fa326df-a24c-487f-b218-98c4711e0c81','fedd3eab-b506-423e-a28e-b3540517b353','eeca80d2-a0fc-4cf0-9a69-2f477b51ab25',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),

('0f88eff8-c0c2-4456-85eb-wwwweqweqeeq','71d22746-4d70-486a-92bf-c435f15fc889','08cb912c-5083-4ac2-a1f2-2445a775d00d','99b68080-a9f5-4bcd-9a5f-1a9c27ccc311',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),
('0f88eff8-c0c2-4456-85eb-9c376ed8100b','71d22746-4d70-486a-92bf-c435f15fc889','fedd3eab-b506-423e-a28e-b3540517b353','b57cc55a-e313-4af7-b345-901e92b374f3',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),

('17693f33-ec59-4e32-b790-ewr43rtrg6y6','b6d8ddab-880b-45f4-b558-2e69507cdb00','08cb912c-5083-4ac2-a1f2-2445a775d00d','c1fd3d91-75d6-4b2c-a4a1-aaaaaaaaaaaa',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),
('17693f33-ec59-4e32-b790-f6d846255e03','b6d8ddab-880b-45f4-b558-2e69507cdb00','fedd3eab-b506-423e-a28e-b3540517b353','c1fd3d91-75d6-4b2c-a4a1-a94fb43f1004',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),

('1e4a6c00-ebe2-4507-b886-34r5th5thjk5','1b824405-aebc-45fc-b1af-eec59084b786','08cb912c-5083-4ac2-a1f2-2445a775d00d','c1fd3d91-75d6-4b2c-a4a1-aaaaaaaaaaaa',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL),
('1e4a6c00-ebe2-4507-b886-0adf8eec0de7','1b824405-aebc-45fc-b1af-eec59084b786','fedd3eab-b506-423e-a28e-b3540517b353','a06b8211-6706-498f-9f17-0211c5b21115',0,'Unknown','2019-03-01 15:25:16','',NULL,'',NULL);

INSERT INTO catalog.`ProductExtra` (`ProductExtraID`,`ProductID`,`AttributeID`,`AttributeValueID`,`AdditionalPrice`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
    -- Red t-shirt
    -- -- Player numbers
    ('9484d2fb-5d8d-4ab7-af57-4b5da9cf4d08', '63507a9d-39a0-4269-83cd-c3f343d84470', 'b9216b07-9187-4c49-aa10-92ab9787f0fe', 'b31e2086-49ef-4a61-a8f9-0aba7763c2be', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    ('57325a2f-673f-423f-96aa-c222bc9bcfa7', '63507a9d-39a0-4269-83cd-c3f343d84470', 'b9216b07-9187-4c49-aa10-92ab9787f0fe', 'd0dba55e-e385-4a43-a54a-b3610907293d', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    ('18bfe5a6-0a86-4598-abaa-1d3ecc74a52b', '63507a9d-39a0-4269-83cd-c3f343d84470', 'b9216b07-9187-4c49-aa10-92ab9787f0fe', '667477a8-965b-4ba4-9653-704c7980271a', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    -- -- Player names
    ('ea90b767-5647-4945-b0ff-b5a4518aadb2', '63507a9d-39a0-4269-83cd-c3f343d84470', '0a24780a-fba6-4db4-a5ee-d81a41607165', '76f43a4b-bd3b-4fd3-932a-d7dfb9fe877a', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    ('caa573aa-0342-463e-84a5-b3920e7cb1fa', '63507a9d-39a0-4269-83cd-c3f343d84470', '0a24780a-fba6-4db4-a5ee-d81a41607165', 'dabbe084-062d-457b-9251-41f1f95a9574', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    ('cb4aa7e3-6be0-4949-9912-39e787b70a11', '63507a9d-39a0-4269-83cd-c3f343d84470', '0a24780a-fba6-4db4-a5ee-d81a41607165', '532fd039-c576-4a7b-9b93-f45513e5c3e3', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    -- -- Custom player name
    ('faf088c1-8253-48d1-9152-b63b95e150d0', '991782a7-1ce4-494e-8a69-b8ac1791cae8', '09d4ee4d-73ab-4a7d-b005-875f4821adfe', '0ecde677-8937-4a31-a68f-eec0a8545555', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),

    -- Green t-shirt
    -- -- Player numbers
    ('81e4df8a-b9e9-439f-a752-2a779a10d4a5', '171b2d2a-bf92-4b6c-98a8-0683046c2c98', 'b9216b07-9187-4c49-aa10-92ab9787f0fe', 'b31e2086-49ef-4a61-a8f9-0aba7763c2be', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    ('655533df-4741-4358-b127-9ad1d4e628ac', '171b2d2a-bf92-4b6c-98a8-0683046c2c98', 'b9216b07-9187-4c49-aa10-92ab9787f0fe', 'd0dba55e-e385-4a43-a54a-b3610907293d', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    ('7713ad5c-737a-4109-81a2-edc9d4112594', '171b2d2a-bf92-4b6c-98a8-0683046c2c98', 'b9216b07-9187-4c49-aa10-92ab9787f0fe', '667477a8-965b-4ba4-9653-704c7980271a', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    -- -- Player names
    ('2986ff01-4173-41b2-8677-bb2fc7a97743', '171b2d2a-bf92-4b6c-98a8-0683046c2c98', '0a24780a-fba6-4db4-a5ee-d81a41607165', '76f43a4b-bd3b-4fd3-932a-d7dfb9fe877a', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    ('16386794-52fb-48b4-a8e8-c8b05ad023fa', '171b2d2a-bf92-4b6c-98a8-0683046c2c98', '0a24780a-fba6-4db4-a5ee-d81a41607165', 'dabbe084-062d-457b-9251-41f1f95a9574', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    ('2eae5988-97eb-4e4a-a659-851dd16a83d8', '171b2d2a-bf92-4b6c-98a8-0683046c2c98', '0a24780a-fba6-4db4-a5ee-d81a41607165', '532fd039-c576-4a7b-9b93-f45513e5c3e3', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    -- -- Custom player name
    ('8c7a775a-5c5d-4000-9a47-6ff704a30902', '991782a7-1ce4-494e-8a69-b8ac1791cae8', '09d4ee4d-73ab-4a7d-b005-875f4821adfe', '0ecde677-8937-4a31-a68f-eec0a8545555', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),

    -- Blue t-shirt
    -- -- Player numbers
    ('caaef3c9-2ff2-4395-b2e6-07eb391efff9', '991782a7-1ce4-494e-8a69-b8ac1791cae8', 'b9216b07-9187-4c49-aa10-92ab9787f0fe', 'b31e2086-49ef-4a61-a8f9-0aba7763c2be', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    ('c0a092bc-0dad-49cb-940f-193c5fb01974', '991782a7-1ce4-494e-8a69-b8ac1791cae8', 'b9216b07-9187-4c49-aa10-92ab9787f0fe', 'd0dba55e-e385-4a43-a54a-b3610907293d', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    ('53d5b51f-960f-4f5a-a6a8-65f23488d895', '991782a7-1ce4-494e-8a69-b8ac1791cae8', 'b9216b07-9187-4c49-aa10-92ab9787f0fe', '667477a8-965b-4ba4-9653-704c7980271a', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    -- -- Player names
    ('5963379f-d72b-4953-992e-a65c6be3f509', '991782a7-1ce4-494e-8a69-b8ac1791cae8', '0a24780a-fba6-4db4-a5ee-d81a41607165', '76f43a4b-bd3b-4fd3-932a-d7dfb9fe877a', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    ('0b5754b4-f378-410b-968f-69aa578cffe3', '991782a7-1ce4-494e-8a69-b8ac1791cae8', '0a24780a-fba6-4db4-a5ee-d81a41607165', 'dabbe084-062d-457b-9251-41f1f95a9574', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    ('2830d541-86b6-4eb2-a101-74324b6f7515', '991782a7-1ce4-494e-8a69-b8ac1791cae8', '0a24780a-fba6-4db4-a5ee-d81a41607165', '532fd039-c576-4a7b-9b93-f45513e5c3e3', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL),
    -- -- Custom player name
    ('d2c804e1-f75a-416f-81dc-710075302428', '991782a7-1ce4-494e-8a69-b8ac1791cae8', '09d4ee4d-73ab-4a7d-b005-875f4821adfe', '0ecde677-8937-4a31-a68f-eec0a8545555', 0, 'Konstantin Tsabolov', '2019-03-26 16:15:00', '', NULL, '', NULL)
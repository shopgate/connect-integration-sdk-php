SET FOREIGN_KEY_CHECKS=1;

INSERT IGNORE INTO authservice.`clients` (`id`,`name`,`secret`,`grantTypes`,`userId`,`accessTokenLifetime`,`refreshTokenLifetime`)
VALUES
(1, 'integration-tests', 'integration-tests', 'client_credentials,refresh_token',1,3600,7776000);

INSERT IGNORE INTO authservice.`users` (`id`,`name`,`type`,`password`,`scopes`,`parentId`)
VALUES
(1, 'tester', 'system', '', 'shop.*:rw', null);

INSERT IGNORE INTO merchant.`Merchant` (`MerchantID`, `OwnerUserID`, `MerchantName`, `MerchantCode`, `Region`, `AppLogo`, `CreateBy`)
VALUES
('1', '4b4b51ce-a4de-4e48-9cf4-ade08de2cc02', 'Test Merchant 1', 'TM1', 'US', 'https://scontent-ber1-1.xx.fbcdn.net/v/t1.0-1/p200x200/28471572_10156169825948781_8970975354537639936_n.jpg?_nc_cat=106&_nc_ht=scontent-ber1-1.xx&oh=b7c659809d68e285aca5fcfab13dec91&oe=5C6E1AD0', 'Johnny Bravo'),
('2', '4b4b51ce-a4de-4e48-9cf4-ade08de2cc02', 'Test Merchant 2', 'TM2', 'US', 'https://scontent-ber1-1.xx.fbcdn.net/v/t1.0-1/p200x200/28471572_10156169825948781_8970975354537639936_n.jpg?_nc_cat=106&_nc_ht=scontent-ber1-1.xx&oh=b7c659809d68e285aca5fcfab13dec91&oe=5C6E1AD0', 'Scooby Doo');

INSERT IGNORE INTO merchant.`MerchantSetting` (`MerchantSettingID`,`MerchantID`,`Key`,`Value`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
('1','1','DefaultTimezone','America/Chicago','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL),
('2','1','DefaultCurrency','USD','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL),
('3','1','DefaultLocale','en-us','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL),
('4','2','DefaultTimezone','America/Chicago','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL),
('5','2','DefaultCurrency','USD','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL),
('6','2','DefaultLocale','en-us','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL);

INSERT IGNORE INTO location.`LocationType` (`LocationTypeID`, `LocationTypeCode`, `TypeDesc`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
('1', 'warehouse', 'Warehouse Location', 'Johnny Bravo', '2018-11-11 16:50:18', NULL, NULL, NULL, NULL);

INSERT IGNORE INTO catalog.`ParentCatalog` (`ParentCatalogID`, `MerchantID`, `ParentCatalogCode`, `ParentCatalogName`, `DefaultLocaleCode`, `DefaultCurrencyCode`, `Status`, `isDefault`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
(1, '1', 'TM1C', 'Test Merchant 1 Global Catalog', 'en-us', 'USD', 'Active', '1', 'Johnny', '2018-12-14 20:03:42', NULL, NULL, NULL, NULL),
(2, '2', 'TM2C', 'Test Merchant 2 Global Catalog', 'en-us', 'USD', 'Active', '1', 'Shaggy', '2018-12-14 20:03:42', NULL, NULL, NULL, NULL);

INSERT IGNORE INTO catalog.`Catalog` (`CatalogID`, `CatalogCode`, `ParentCatalogID`, `CatalogName`, `DefaultLocaleCode`, `DefaultCurrencyCode`, `isDefault`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
(1, 'NA Wholesale', 1, 'North American Wholesale', 'en-us', 'USD', 0, 'Johnny', '2018-12-14 20:06:31', NULL, NULL, NULL, NULL),
(2, 'NA Retail', 1, 'North American Retail', 'en-us', 'USD', 1, 'Johnny', '2018-12-14 20:06:31', NULL, NULL, NULL, NULL),
(3, 'NA Wholesale', 2, 'North American Wholesale', 'en-us', 'USD', 0, 'Scooby', '2018-12-14 20:06:31', NULL, NULL, NULL, NULL),
(4, 'NA Retail', 2, 'North American Retail', 'en-us', 'USD', 1, 'Scooby', '2018-12-14 20:06:31', NULL, NULL, NULL, NULL);

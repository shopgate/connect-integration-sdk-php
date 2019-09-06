SET FOREIGN_KEY_CHECKS=1;

INSERT INTO omnichannel_auth.`Client` (`ClientId`, `Name`, `Secret`, `GrantTypes`, `UserId`, `AccessTokenLifetime`, `RefreshTokenLifetime`, `ApplicationType`)
VALUES
(19,'integration-tests','integration-tests','client_credentials,refresh_token',13,3600,7776000,NULL),
(20,'bananas','bananas','password,refresh_token',NULL,3600,7776000,NULL);

INSERT INTO omnichannel.`User` (`UserID`, `UserEmail`, `FirstName`, `LastName`, `ProfileImage`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`, `UserCode`)
VALUES
('4b4b51ce-a4de-4e48-9cf4-ade08de2cc02', 'test@test.com', 'test', 'test', NULL, '', '2018-11-13 18:57:40', NULL, NULL, NULL, NULL, '64981c63-e909-47aa-95ab-a7e6ee2a6e50');

INSERT IGNORE INTO omnichannel.`Merchant` (`MerchantID`, `OwnerUserID`, `MerchantName`, `MerchantCode`, `Region`, `AppLogo`, `CreateBy`)
VALUES
('1', '4b4b51ce-a4de-4e48-9cf4-ade08de2cc02', 'Test Merchant 1', 'TM1', 'US', 'https://scontent-ber1-1.xx.fbcdn.net/v/t1.0-1/p200x200/28471572_10156169825948781_8970975354537639936_n.jpg?_nc_cat=106&_nc_ht=scontent-ber1-1.xx&oh=b7c659809d68e285aca5fcfab13dec91&oe=5C6E1AD0', 'Johnny Bravo'),
('2', '4b4b51ce-a4de-4e48-9cf4-ade08de2cc02', 'Test Merchant 2', 'TM2', 'US', 'https://scontent-ber1-1.xx.fbcdn.net/v/t1.0-1/p200x200/28471572_10156169825948781_8970975354537639936_n.jpg?_nc_cat=106&_nc_ht=scontent-ber1-1.xx&oh=b7c659809d68e285aca5fcfab13dec91&oe=5C6E1AD0', 'Scooby Doo');

INSERT INTO omnichannel.`MerchantEngageApp` (`MerchantEngageAppId`, `MerchantId`, `ShopNumber`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
('cce23639-73c8-4ba0-b43d-2fa7f59d', '1', '31371', 'Me', '2018-12-18 13:03:07', NULL, NULL, NULL, NULL);

INSERT INTO omnichannel.`MerchantPartner` (`MerchantPartnerID`, `MerchantID`, `PartnerName`, `PartnerURL`, `PartnerLogo`, `PartnerPhone`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
('689ca1ae-866d-4ee8-ac83-7c893eab4a20', '1', 'Ernie Consulting', 'https://shopgate.com', 'https://s3.eu-central-1.amazonaws.com/shopgatedevcloud-bigapi/exampleData/omni/bild.jpg', '+17471234567', 'Me', '1970-01-01 00:00:00', NULL, NULL, NULL, NULL);

INSERT IGNORE INTO omnichannel.`MerchantSetting` (`MerchantSettingID`,`MerchantID`,`Key`,`Value`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
('1','1','DefaultTimezone','America/Chicago','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL),
('2','1','DefaultCurrency','USD','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL),
('3','1','DefaultLocale','en-us','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL),
('4','2','DefaultTimezone','America/Chicago','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL),
('5','2','DefaultCurrency','USD','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL),
('6','2','DefaultLocale','en-us','','1970-01-01 00:00:00',NULL,NULL,NULL,NULL);

INSERT IGNORE INTO location.`LocationType` (`LocationTypeID`, `LocationTypeCode`, `TypeDesc`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
('1', 'warehouse', 'Warehouse Location', 'Johnny Bravo', '2018-11-11 16:50:18', NULL, NULL, NULL, NULL),
('2', 'store', 'Store Location', 'Johnny Bravo', '2018-11-11 16:51:23', NULL, NULL, NULL, NULL),
('3', 'dropShipping', 'Drop Shipping Location', 'Johnny Bravo', '2018-11-11 16:52:03', NULL, NULL, NULL, NULL),
('4', '3rdPartyFulfillment', '3rd Party Fulfillment Location', 'Johnny Bravo', '2018-11-11 16:54:12', NULL, NULL, NULL, NULL);

INSERT IGNORE INTO catalog.`ParentCatalog` (`ParentCatalogID`, `MerchantID`, `ParentCatalogCode`, `ParentCatalogName`, `DefaultLocaleCode`, `DefaultCurrencyCode`, `Status`, `isDefault`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
(1, '1', 'TM1C', 'Test Merchant 1 Global Catalog', 'en-us', 'USD', 'Active', '1', 'Johnny', '2018-12-14 20:03:42', NULL, NULL, NULL, NULL),
(2, '2', 'TM2C', 'Test Merchant 2 Global Catalog', 'en-us', 'USD', 'Active', '1', 'Shaggy', '2018-12-14 20:03:42', NULL, NULL, NULL, NULL);

INSERT IGNORE INTO catalog.`Catalog` (`CatalogID`, `CatalogCode`, `ParentCatalogID`, `CatalogName`, `DefaultLocaleCode`, `DefaultCurrencyCode`, `isDefault`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
(1, 'NAWholesale', 1, 'North American Wholesale', 'en-us', 'USD', 0, 'Johnny', '2018-12-14 20:06:31', NULL, NULL, NULL, NULL),
(2, 'NARetail', 1, 'North American Retail', 'en-us', 'USD', 1, 'Johnny', '2018-12-14 20:06:31', NULL, NULL, NULL, NULL),
(3, 'NAWholesale', 2, 'North American Wholesale', 'en-us', 'USD', 0, 'Scooby', '2018-12-14 20:06:31', NULL, NULL, NULL, NULL),
(4, 'NARetail', 2, 'North American Retail', 'en-us', 'USD', 1, 'Scooby', '2018-12-14 20:06:31', NULL, NULL, NULL, NULL);

INSERT INTO omnichannel_order.`OrderType` (`OrderTypeID`, `MerchantID`, `OrderType`, `RouteTypeID`, `CreateBy`)
VALUES
  (1, '1', 'directShip', 7, 'Me'),
  (2, '1', 'BOPIS', 8, 'Me'),
  (3, '1', 'ROPIS', 9, 'Me');

INSERT INTO omnichannel_order.`Channel` (`ChannelID`, `MerchantID`, `Region`, `ChannelCode`, `ChannelName`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
	(1, '1', NULL, 'USRTL', 'US Retail', 'Me', '2018-11-12 10:48:44', NULL, NULL, NULL, NULL);

INSERT INTO omnichannel_order.`FulfillmentOrder` (`FulfillmentOrderID`, `SalesOrderID`, `MerchantID`, `CustomerID`, `CustomerNumber`, `ChannelID`, `LocationID`, `RouteTypeID`, `OrderTypeID`, `Status`, `CancellationReason`, `OrderNumber`, `ExternalCode`, `POSTransactionId`, `SequenceID`, `AcceptedDate`, `ReadyDate`, `CompletedDate`, `OrderSubmittedDate`, `PricelistCode`, `Expedited`, `OrderSubTotal`, `OrderTaxTotal`, `OrderTax2Total`, `OrderShippingTotal`, `OrderTotal`, `LocaleCode`, `CurrencyCode`, `OrderNotes`, `OrderSpecialInstructions`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`)
VALUES
	(1, 207, '2', '50ed505b-1d7e-4240-af9d-f5f8d6316cf6', NULL, 1, '1', 8, 3, 'fulfilled', NULL, '10138-0001', NULL, NULL, 0, '2018-12-21 12:28:55', '2018-12-21 12:29:38', NULL, '2018-12-20 19:19:50', NULL, 0, 59, 0, NULL, 0, 59, 'en-US', 'USD', X'7B226D657373616765223A2022227D', '{}', '', '2018-12-20 19:22:21', 'pascal.vomhoff+testadmin@shopgate.com', '2018-12-21 12:29:39', NULL);


INSERT INTO omnichannel_user.`Permission` (`PermissionID`,`PermissionStatus`,`PermissionCode`,`Application`,`Module`,`Submodule`,`Function`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUE
('08ccdd99-63ea-4047-86b5-6b23af4ad533','active','SOV','api',NULL,NULL,NULL,'','2019-06-03 09:10:57',NULL,NULL,NULL,NULL),
('0e006e85-6d74-4d06-a6a2-76014fb71755','active','CCAE','api','catalog','category','edit','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('1','active','FOV','api',NULL,NULL,NULL,'','2019-06-04 11:57:34',NULL,NULL,NULL,NULL),
('100b2d42-219e-443b-90a4-11eb28dacbc3','active','CPD','api','catalog','product','delete','Me','2019-07-22 05:32:53',NULL,NULL,NULL,NULL),
('106c5b18-e8b9-41a3-93cf-b8b34c796219','active','CCD','api','catalog','catalog','delete','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('124c62af-4da8-4ccb-a35b-5cd7f8d1b539','active','CAD','api','catalog','attributes','delete','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('1f9eec9a-810e-44bb-965f-c5acc9eb16ff','active','CCAV','api','catalog','category','view','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('24d52d94-b1ab-4872-aae9-4f578578a4c8','active','FOC','api',NULL,NULL,'create','Me','2019-07-08 08:25:25',NULL,NULL,NULL,NULL),
('2725c142-7d1a-4281-b792-10498d0f71ff','active','CAV','api','catalog','attributes','view','Me','2019-07-22 05:32:51',NULL,NULL,NULL,NULL),
('2caa765e-2b5f-4079-86fc-8e5f1b5a5955','active','URV','api',NULL,NULL,'view','Me','2019-06-10 16:36:46',NULL,NULL,NULL,NULL),
('3335abb9-ccb3-4fe3-9524-695246911ab2','active','LLC','api','location','location','create','Me','2019-08-26 12:20:45',NULL,NULL,NULL,NULL),
('4042b334-1d9d-497f-affd-9a53b650e615','active','CPV','api','catalog','product','view','Me','2019-07-22 05:32:53',NULL,NULL,NULL,NULL),
('4196e4a5-d875-42c8-81e1-658b80180607','active','UMD','api',NULL,NULL,'delete','Me','2019-06-10 16:36:46',NULL,NULL,NULL,NULL),
('4388a62e-a85a-410f-8889-a700b75873fb','active','UMC','api',NULL,NULL,'create','Me','2019-06-10 16:36:46',NULL,NULL,NULL,NULL),
('454d9bf9-a762-45d8-9e57-1dbcb3987a38','active','CCV','api','catalog','catalog','view','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('46657198-b4e1-4f66-8a0f-2d6a112a6c57','active','LLV','api',NULL,NULL,'delete','Me','2019-06-25 08:58:07',NULL,NULL,NULL,NULL),
('516deae0-0d5a-4214-8bb3-6c322294aaf8','active','UMV','api',NULL,NULL,'view','Me','2019-06-10 16:36:46',NULL,NULL,NULL,NULL),
('52a695df-ba4b-4426-be50-50eea288cea9','active','UME','api',NULL,NULL,'edit','Me','2019-06-10 16:36:46',NULL,NULL,NULL,NULL),
('561e6aa6-f2cb-4f36-aa5d-d77160d57ca0','active','CAC','api','catalog','attributes','create','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('629365a6-f69d-4bb4-bafb-a113c68ce2e6','active','LLE','api','location','location','','Me','2019-08-26 12:20:45',NULL,NULL,NULL,NULL),
('70c975cd-a202-4071-93cc-0b5e76fc97d7','active','SOC','api',NULL,NULL,NULL,'','2019-05-26 19:28:00',NULL,NULL,NULL,NULL),
('76ccbf00-5c94-43c5-9f1f-67f3a062b6af','active','CIE','api','catalog','inventory','edit','Me','2019-07-22 05:32:53',NULL,NULL,NULL,NULL),
('796bb4a5-b3dc-4e64-aef5-89fc156da1bb','active','CPC','api','catalog','product','create','Me','2019-07-22 05:32:53',NULL,NULL,NULL,NULL),
('85fd2890-aac6-48a0-a1ef-a5ae66b4b8d2','active','CCAD','api','catalog','category','delete','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('911a6650-4a9e-4b27-9a9a-584c237c7293','active','CPE','api','catalog','product','edit','Me','2019-07-22 05:32:53',NULL,NULL,NULL,NULL),
('930fbe82-2a5d-4f40-bd4b-87fe29124a6c','active','URE','api',NULL,NULL,'edit','Me','2019-06-10 16:36:46',NULL,NULL,NULL,NULL),
('9d6bc3cd-707e-42bc-905a-edb8663c0317','active','CAE','api','catalog','attributes','edit','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('a1ed6229-0ab5-4725-9fbb-3f26de78d8ed','active','URD','api',NULL,NULL,'delete','Me','2019-06-10 16:36:46',NULL,NULL,NULL,NULL),
('bab2b536-a411-4da8-b653-b30dc01000b5','active','LLD','api','location','location','delete','Me','2019-08-26 12:20:45',NULL,NULL,NULL,NULL),
('bd29cd68-ae5b-415d-b8c3-5f35e4023ddf','active','CIV','api','catalog','inventory','view','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('d126acc7-46e3-43aa-aec2-e98c4a2dc32d','active','URC','api',NULL,NULL,'create','Me','2019-06-10 16:36:46',NULL,NULL,NULL,NULL),
('db752f3e-3318-49d7-a34c-fee5952c9233','active','CCE','api','catalog','catalog','edit','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('e6b7be84-55c2-4f08-b966-b1ecd5ec465e','active','CCC','api','catalog','catalog','create','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('f3b032fa-2b67-41f5-b46d-ca6ce0631ac9','active','CCAC','api','catalog','category','create','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('f99912e7-0e1d-4828-b3cf-f6299b952698','active','FOE','api',NULL,NULL,'create','Me','2019-07-08 08:30:06',NULL,NULL,NULL,NULL),
('fe6b4100-57f2-413a-948b-6b3c4089ee25','active','CID','api','catalog','inventory','delete','Me','2019-07-22 05:32:53',NULL,NULL,NULL,NULL),
('2b2d9f1d-5fef-48d1-9770-1dd8f169569b','active','APIALL','api','api','api','edit','Me','2019-08-28 06:57:21',NULL,NULL,NULL,NULL);



INSERT INTO omnichannel_user.`PermissionMapping` (`PermissionMappingId`,`PermissionId`,`DependingPermissionId`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
('0816075d-7b01-4fcb-aadc-c0705190a2cf','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','d126acc7-46e3-43aa-aec2-e98c4a2dc32d','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('08802a6c-77e7-4d83-a749-9b79e0e7f65d','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','24d52d94-b1ab-4872-aae9-4f578578a4c8','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('0f9373a8-eb95-4321-9636-ea0faadd35d9','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','bab2b536-a411-4da8-b653-b30dc01000b5','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('1195a626-31c4-42ad-8a53-e13ba38d81ae','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','516deae0-0d5a-4214-8bb3-6c322294aaf8','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('1400dbec-6353-47b0-bebc-78717b023b99','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','f3b032fa-2b67-41f5-b46d-ca6ce0631ac9','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('1409ca11-b21a-4499-87bc-213cdf3c0619','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','454d9bf9-a762-45d8-9e57-1dbcb3987a38','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('159e1380-e243-4929-a02f-7cdfcc147d54','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','a1ed6229-0ab5-4725-9fbb-3f26de78d8ed','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('1804628e-1356-4949-904b-a4906f01e5fb','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','70c975cd-a202-4071-93cc-0b5e76fc97d7','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('19e13794-8875-4564-b703-cb3d59b4fafa','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','100b2d42-219e-443b-90a4-11eb28dacbc3','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('1cf4f8b9-afdc-4078-ae5c-519e7727da7c','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','db752f3e-3318-49d7-a34c-fee5952c9233','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('1d8a69f8-0604-4d19-8567-fec2d0a5c9a9','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','46657198-b4e1-4f66-8a0f-2d6a112a6c57','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('218b17ed-b50c-443a-838b-fc8d82342f0e','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','124c62af-4da8-4ccb-a35b-5cd7f8d1b539','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('38af4a43-40f3-47fd-a6e9-8c5ccd984d58','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','629365a6-f69d-4bb4-bafb-a113c68ce2e6','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('38f7d379-59d5-4922-9874-c73552ce2f2b','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','796bb4a5-b3dc-4e64-aef5-89fc156da1bb','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('3fc3a05e-d322-4349-813f-6a83b533da70','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','85fd2890-aac6-48a0-a1ef-a5ae66b4b8d2','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('49e01ac5-84ba-4ccd-830b-e6ded42f6b86','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','76ccbf00-5c94-43c5-9f1f-67f3a062b6af','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('7061187c-c751-40ec-8762-b74f11be2e2e','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','911a6650-4a9e-4b27-9a9a-584c237c7293','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('72899b12-f5b5-47a5-938b-710b98bb3ad5','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','2725c142-7d1a-4281-b792-10498d0f71ff','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('7ec4747b-6954-44f1-8dbf-ce9765164206','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','08ccdd99-63ea-4047-86b5-6b23af4ad533','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('8270c1c1-7f87-4716-b99f-1144cd59e622','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','106c5b18-e8b9-41a3-93cf-b8b34c796219','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('83a81c2f-2917-47c4-92a5-012ae74c8c17','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','e6b7be84-55c2-4f08-b966-b1ecd5ec465e','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('86b47dea-6d05-4cca-8f48-5fae4fdb85a8','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','930fbe82-2a5d-4f40-bd4b-87fe29124a6c','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('9f5d3647-ff9e-485b-b0d4-9489377de39d','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','4388a62e-a85a-410f-8889-a700b75873fb','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('ab5eafea-876d-4f0e-9acb-55245ff3a822','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','561e6aa6-f2cb-4f36-aa5d-d77160d57ca0','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('b35495bf-7bfe-4012-a557-d2a1444cf592','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','4196e4a5-d875-42c8-81e1-658b80180607','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('be420fd6-7d0c-41d4-bebf-49bb0d857e8e','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','3335abb9-ccb3-4fe3-9524-695246911ab2','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('c0b23a3f-5b50-4e2a-b18d-99064929ce3d','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','52a695df-ba4b-4426-be50-50eea288cea9','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('cd99ff01-18d4-4e12-9599-337b0320835d','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','0e006e85-6d74-4d06-a6a2-76014fb71755','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('d1b36487-e63a-4f13-94f7-c6580ae79b9b','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','1f9eec9a-810e-44bb-965f-c5acc9eb16ff','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('d7153abd-3503-45b1-85a6-1ff0bbc2a843','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','bd29cd68-ae5b-415d-b8c3-5f35e4023ddf','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('e146a6ce-6755-48b5-b0e4-bb266292c741','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','2caa765e-2b5f-4079-86fc-8e5f1b5a5955','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('ec3fd72f-0b0f-47e8-8d8f-467ec51774f6','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','9d6bc3cd-707e-42bc-905a-edb8663c0317','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('f4570d47-72b5-4fdb-b909-fd0df504cfb1','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','1','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('f4fc315d-7ef1-49db-ab90-87af8c48b5ce','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','f99912e7-0e1d-4828-b3cf-f6299b952698','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('f7b4a6ce-445e-416d-8fdc-9a263edaec77','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','4042b334-1d9d-497f-affd-9a53b650e615','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL),
('fa577d01-46e2-42c8-9460-aa1d1c99b311','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','fe6b4100-57f2-413a-948b-6b3c4089ee25','Me','2019-08-28 06:59:41',NULL,NULL,'',NULL);


INSERT INTO omnichannel_user.`Role` (`RoleID`,`MerchantID`,`RoleCode`,`RoleName`,`RoleStatus`,`ApplicationType`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
('563cb082-633a-4e0d-bc4f-4d76f2e74a1f',NULL,'administrator','Administrator','active','admin','Me','2019-07-03 05:56:33',NULL,NULL,NULL,NULL);

INSERT INTO omnichannel_user.`RolePermission` (`RolePermissionID`,`RoleID`,`PermissionID`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
('33ce06c2-729c-4223-ac5a-624c083ff4e4', '563cb082-633a-4e0d-bc4f-4d76f2e74a1f', '2b2d9f1d-5fef-48d1-9770-1dd8f169569b', 'Me', '2019-05-10 12:19:33', NULL, NULL, NULL, NULL);

INSERT INTO omnichannel_user.`User` (`UserID`, `UserEmail`, `UserPassword`, `FirstName`, `LastName`, `ProfileImage`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
('0f6e3169-0e28-4e17-a68e-0c5fb77e7267','team-bananas@shopgate.com','$2b$10$WDVzNx8xZpRkaJbxq5ZZi.Fvy6cWRJX9etmugDiPhjjtWaASlxmi6','Gonzo','Gonzo','https://media.licdn.com/dms/image/C4D03AQG1duOkQi-TYA/profile-displayphoto-shrink_800_800/0?e=1547683200&v=beta&t=qTTgaFihiM2S_HNaXds_rei8D37GwJ_ux8Eal0FOiAw','Me','2019-04-29 09:49:54',NULL,NULL,NULL,NULL);


INSERT INTO omnichannel_user.`UserRole` (`UserRoleID`, `UserID`, `MerchantID`, `RoleID`, `ContextType`, `ContextId`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
('727698a3-e321-421c-934b-8e9c818042a9','0f6e3169-0e28-4e17-a68e-0c5fb77e7267','2','563cb082-633a-4e0d-bc4f-4d76f2e74a1f',NULL,NULL,'','2019-05-10 12:19:33',NULL,NULL,NULL,NULL),
('er34t4rc-e321-421c-934b-eewereeetert','0f6e3169-0e28-4e17-a68e-0c5fb77e7267','1','563cb082-633a-4e0d-bc4f-4d76f2e74a1f',NULL,NULL,'','2019-05-10 12:19:33',NULL,NULL,NULL,NULL);

INSERT INTO omnichannel_user.`UserMerchant` (`UserMerchantID`, `UserID`, `MerchantID`, `Status`, `LastLoginDate`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
('727698a3-e321-421c-934b-8e9c818042a9','0f6e3169-0e28-4e17-a68e-0c5fb77e7267','1','active',NULL,'','2019-05-10 12:19:33',NULL,NULL,NULL,NULL),
('f1ba3640-cb1c-424e-aa78-e98a7777ba3d','0f6e3169-0e28-4e17-a68e-0c5fb77e7267','2','active',NULL,'','2019-05-10 12:19:33',NULL,NULL,NULL,NULL);


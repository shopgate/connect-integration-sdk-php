SET FOREIGN_KEY_CHECKS=1;

INSERT INTO omnichannel_auth.`Client` (`ClientId`, `Name`, `Secret`, `GrantTypes`, `UserId`, `AccessTokenLifetime`, `RefreshTokenLifetime`, `ApplicationType`)
VALUES
(19,'integration-tests','integration-tests','client_credentials,refresh_token',13,3600,7776000,NULL),
(20,'bananas','bananas','password,refresh_token',NULL,3600,7776000,NULL);

INSERT INTO omnichannel_user.`User` (`UserID`, `UserEmail`, `FirstName`, `LastName`, `ProfileImage`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
('4b4b51ce-a4de-4e48-9cf4-ade08de2cc02', 'test@test.com', 'test', 'test', NULL, '', '2018-11-13 18:57:40', NULL, NULL, NULL, NULL);

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
VALUES
('08ccdd99-63ea-4047-86b5-6b23af4ad533','active','SOV','api',NULL,NULL,NULL,'','2019-06-03 09:10:57',NULL,NULL,NULL,NULL),
('0e006e85-6d74-4d06-a6a2-76014fb71755','active','CCAE','api','catalog','category','edit','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('1','active','FOV','api',NULL,NULL,NULL,'','2019-06-04 11:57:34',NULL,NULL,NULL,NULL),
('100b2d42-219e-443b-90a4-11eb28dacbc3','active','CPD','api','catalog','product','delete','Me','2019-07-22 05:32:53',NULL,NULL,NULL,NULL),
('106c5b18-e8b9-41a3-93cf-b8b34c796219','active','CCD','api','catalog','catalog','delete','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('124c62af-4da8-4ccb-a35b-5cd7f8d1b539','active','CAD','api','catalog','attributes','delete','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('1f9eec9a-810e-44bb-965f-c5acc9eb16ff','active','CCAV','api','catalog','category','view','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('22eabc08-49a3-40af-90af-a96dbac2f7d2','active','CUCE','api','customer','customer','edit','Me','2019-09-12 10:02:33',NULL,NULL,NULL,NULL),
('24d52d94-b1ab-4872-aae9-4f578578a4c8','active','FOC','api',NULL,NULL,'create','Me','2019-07-08 08:25:25',NULL,NULL,NULL,NULL),
('2725c142-7d1a-4281-b792-10498d0f71ff','active','CAV','api','catalog','attributes','view','Me','2019-07-22 05:32:51',NULL,NULL,NULL,NULL),
('2b2d9f1d-5fef-48d1-9770-1dd8f169569b','active','APIALL','api','api','api','edit','Me','2019-08-28 06:57:21',NULL,NULL,NULL,NULL),
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
('9ba08e15-8dd6-4451-ab70-8d2257ccf261','active','CUAE','api','customer','attribute','edit','Me','2019-09-12 10:02:33',NULL,NULL,NULL,NULL),
('9d6bc3cd-707e-42bc-905a-edb8663c0317','active','CAE','api','catalog','attributes','edit','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('a1ed6229-0ab5-4725-9fbb-3f26de78d8ed','active','URD','api',NULL,NULL,'delete','Me','2019-06-10 16:36:46',NULL,NULL,NULL,NULL),
('a6958c2d-4446-4915-a8db-3269d7dd6b6d','active','CUAC','api','customer','attribute','create','Me','2019-09-12 10:02:33',NULL,NULL,NULL,NULL),
('b138f75a-dc44-444a-abe9-cf01118dfa5e','active','CUCD','api','customer','customer','delete','Me','2019-09-12 10:02:33',NULL,NULL,NULL,NULL),
('b972f76d-5dfe-4b06-a34e-43f0fd6c3784','active','CUAV','api','customer','attribute','view','Me','2019-09-12 10:02:33',NULL,NULL,NULL,NULL),
('bab2b536-a411-4da8-b653-b30dc01000b5','active','LLD','api','location','location','delete','Me','2019-08-26 12:20:45',NULL,NULL,NULL,NULL),
('bd29cd68-ae5b-415d-b8c3-5f35e4023ddf','active','CIV','api','catalog','inventory','view','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('cb06a5a4-567d-4d03-94d0-2095aa499610','active','CUCV','api','customer','customer','view','Me','2019-09-12 10:02:33',NULL,NULL,NULL,NULL),
('d126acc7-46e3-43aa-aec2-e98c4a2dc32d','active','URC','api',NULL,NULL,'create','Me','2019-06-10 16:36:46',NULL,NULL,NULL,NULL),
('db752f3e-3318-49d7-a34c-fee5952c9233','active','CCE','api','catalog','catalog','edit','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('e6b7be84-55c2-4f08-b966-b1ecd5ec465e','active','CCC','api','catalog','catalog','create','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('ef4b4d75-5c7c-4619-8fff-b69eb0a339de','active','CUCC','api','customer','customer','create','Me','2019-09-12 10:02:33',NULL,NULL,NULL,NULL),
('f3b032fa-2b67-41f5-b46d-ca6ce0631ac9','active','CCAC','api','catalog','category','create','Me','2019-07-22 05:32:52',NULL,NULL,NULL,NULL),
('f50589e1-1189-4b23-b87b-950bc30e9aa6','active','CUAD','api','customer','attribute','delete','Me','2019-09-12 10:02:33',NULL,NULL,NULL,NULL),
('f99912e7-0e1d-4828-b3cf-f6299b952698','active','FOE','api',NULL,NULL,'create','Me','2019-07-08 08:30:06',NULL,NULL,NULL,NULL),
('fe6b4100-57f2-413a-948b-6b3c4089ee25','active','CID','api','catalog','inventory','delete','Me','2019-07-22 05:32:53',NULL,NULL,NULL,NULL);



INSERT INTO omnichannel_user.`PermissionMapping` (`PermissionMappingId`,`PermissionId`,`DependingPermissionId`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
('0197f39c-c57c-4b40-aa81-cb50cb8b0ad4','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','516deae0-0d5a-4214-8bb3-6c322294aaf8','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('05f9a681-e7d9-422d-95d7-8132f8960091','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','3335abb9-ccb3-4fe3-9524-695246911ab2','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('06fe812d-23d1-4317-a69c-2b110f3c3482','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','9d6bc3cd-707e-42bc-905a-edb8663c0317','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('0da4a07d-9815-4734-9653-daf043b53508','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','b138f75a-dc44-444a-abe9-cf01118dfa5e','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('10f93dd1-dae6-498a-9191-afe676a5e125','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','f3b032fa-2b67-41f5-b46d-ca6ce0631ac9','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('156003ee-27d7-4057-80c2-6c5ef1b09121','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','1f9eec9a-810e-44bb-965f-c5acc9eb16ff','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('1d2cad46-79b6-42d6-8771-2d4fed26ef76','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','2caa765e-2b5f-4079-86fc-8e5f1b5a5955','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('2dc4e525-e097-4fc0-8aa6-7c6cc7d790a7','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','a1ed6229-0ab5-4725-9fbb-3f26de78d8ed','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('2ecb007b-53bb-4c1d-a67d-d9b086a46d99','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','22eabc08-49a3-40af-90af-a96dbac2f7d2','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('31ff3e82-3e34-4d5a-b216-bf52c852a89a','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','db752f3e-3318-49d7-a34c-fee5952c9233','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('3a8f1446-99cd-45d2-b424-55f1196a29a7','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','9ba08e15-8dd6-4451-ab70-8d2257ccf261','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('468f8efd-509c-453b-8cbb-07e932bf9a9b','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','ef4b4d75-5c7c-4619-8fff-b69eb0a339de','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('4e118d7f-f52a-4b7d-a001-7e3c0858274e','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','4196e4a5-d875-42c8-81e1-658b80180607','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('4eb7187d-a898-4071-91e0-5eaddc56c0b7','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','930fbe82-2a5d-4f40-bd4b-87fe29124a6c','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('6494aab4-6214-4f7b-a1ba-1d23006ab5f7','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','a6958c2d-4446-4915-a8db-3269d7dd6b6d','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('662e1207-4135-4767-bdf9-65913baeb27a','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','70c975cd-a202-4071-93cc-0b5e76fc97d7','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('66d6fb73-4693-4f76-92fe-f38d0d867f10','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','629365a6-f69d-4bb4-bafb-a113c68ce2e6','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('6a4df901-821b-4763-a1a0-313bd400ad15','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','e6b7be84-55c2-4f08-b966-b1ecd5ec465e','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('6cab7c60-2cf0-426d-9948-3a7e0d6732ac','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','cb06a5a4-567d-4d03-94d0-2095aa499610','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('6f92fb86-bea9-4c58-8f1b-1a3620ab4ddb','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','106c5b18-e8b9-41a3-93cf-b8b34c796219','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('742b3697-23bd-45fd-b843-02c41b4fa7be','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','124c62af-4da8-4ccb-a35b-5cd7f8d1b539','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('76057fab-126b-48f5-9e94-2faadb388511','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','454d9bf9-a762-45d8-9e57-1dbcb3987a38','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('76ebb43f-ae9e-42ec-9008-47327d8c8930','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','796bb4a5-b3dc-4e64-aef5-89fc156da1bb','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('79924090-1f32-4d2c-86d7-816379237fa9','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','fe6b4100-57f2-413a-948b-6b3c4089ee25','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('851cc9ad-8bd4-4bc9-b7ff-81caeb5fca66','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','4042b334-1d9d-497f-affd-9a53b650e615','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('8dd3bfeb-2e50-4a02-a833-63a6b2e87fd3','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','911a6650-4a9e-4b27-9a9a-584c237c7293','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('a29d7cc2-72d4-439f-9a23-8188d382e4a8','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','1','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('a7b93909-ab6a-475d-a082-fccb167dd122','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','d126acc7-46e3-43aa-aec2-e98c4a2dc32d','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('aacd330f-8259-4fbd-99db-70f585eebdbc','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','52a695df-ba4b-4426-be50-50eea288cea9','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('ac8d078f-a42c-44b6-8225-a34475de1514','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','f50589e1-1189-4b23-b87b-950bc30e9aa6','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('b14f2025-61d4-468d-9545-c0c76636a9d7','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','4388a62e-a85a-410f-8889-a700b75873fb','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('b5146651-d62f-4588-a762-021b80f66f3c','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','561e6aa6-f2cb-4f36-aa5d-d77160d57ca0','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('bce2effa-51dd-4812-9460-4254155288f0','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','85fd2890-aac6-48a0-a1ef-a5ae66b4b8d2','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('bdedcb0f-10d2-4ee5-be3a-ee5490127099','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','0e006e85-6d74-4d06-a6a2-76014fb71755','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('bf12187b-e782-4f90-b9b9-61cec2f7c398','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','2725c142-7d1a-4281-b792-10498d0f71ff','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('c3612da6-3046-4be9-8d70-bf47124d038a','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','08ccdd99-63ea-4047-86b5-6b23af4ad533','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('d2b5c8e9-3035-4b0b-ab9e-c4746d3b39a2','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','bd29cd68-ae5b-415d-b8c3-5f35e4023ddf','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('d62bb779-7b7b-4b96-8a35-f273646077d1','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','100b2d42-219e-443b-90a4-11eb28dacbc3','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('d808dba0-08a7-4874-ac24-3c96ff63f33d','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','f99912e7-0e1d-4828-b3cf-f6299b952698','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('e98e3e53-a606-42ff-b65c-2acd51007964','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','24d52d94-b1ab-4872-aae9-4f578578a4c8','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('eda5fa2b-6df8-4677-9bee-67d4e75b2657','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','b972f76d-5dfe-4b06-a34e-43f0fd6c3784','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('f6b13037-1fde-46f0-9483-45c617be3cc3','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','bab2b536-a411-4da8-b653-b30dc01000b5','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('f9909044-9ba4-49c8-964a-ebd5522de429','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','76ccbf00-5c94-43c5-9f1f-67f3a062b6af','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL),
('ff795d75-e0e1-4424-92e5-d51f48e23097','2b2d9f1d-5fef-48d1-9770-1dd8f169569b','46657198-b4e1-4f66-8a0f-2d6a112a6c57','Me','2019-09-12 10:12:11',NULL,NULL,'',NULL);


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

INSERT INTO omnichannel_order.`FOFulfillment` (`FulfillmentOrderID`,`MerchantID`,`Status`,`Carrier`,`ServiceLevel`,`Tracking`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
(1,'1','open',NULL,NULL,NULL,'','2018-12-04 10:44:21',NULL,NULL,NULL,NULL);

INSERT INTO omnichannel_order.`FOFulfillmentPackage` (`FulfillmentOrderID`,`FOFulfillmentID`,`Status`,`ServiceLevel`,`FulfilledFromLocationCode`,`Weight`,`Dimensions`,`Tracking`,`PickUpBy`,`LabelURL`,`FulfilledDate`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
(1,1,'open',NULL,'DERetail001',NULL,NULL,NULL,NULL,NULL,NULL,'','2018-12-04 10:44:21',NULL,NULL,NULL,NULL);

INSERT INTO omnichannel_order.`SalesOrder` (`MerchantID`, `CustomerID`, `CustomerNumber`, `OrderType`, `ChannelID`, `Status`, `OrderNumber`, `ExternalCode`, `Expedited`, `OrderDate`, `OrderSubmitDate`, `OrderCompleteDate`, `PriceListCode`, `PrimaryBillToAddressSequenceIndex`, `PrimaryShipToAddressSequenceIndex`, `OrderSubTotal`, `OrderDiscountAmount`, `OrderPromoAmount`, `OrderTaxAmount`, `OrderTax2Amount`, `OrderTotal`, `ShippingSubTotal`, `ShippingDiscountAmount`, `ShippingPromoAmount`, `ShippingTotal`, `LocaleCode`, `CurrencyCode`, `TaxExempt`, `Notes`, `SpecialInstructions`, `OrderData`, `Platform`, `Domain`, `UserAgent`, `SourceIP`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
('2', 'fcae5c41-b256-4f9d-848a-f02a104f6a38', NULL, 'standard', NULL, 'open', '100000', 'a90742e401b6265c5eefbb6be1f6f046', 0, '2019-10-23 12:10:05', '2019-10-23 12:10:04', NULL, NULL, 0, NULL, 180, 0, 0, 0, 0, 180, 0, 0, 0, 0, 'en-us', 'USD', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0f6e3169-0e28-4e17-a68e-0c5fb77e7267', '2019-10-23 12:10:04', NULL, NULL, NULL, NULL);

INSERT INTO omnichannel_order.`SalesOrderLineItem` (`SalesOrderID`, `LineItemCode`, `Quantity`, `CurrencyCode`, `Price`, `Cost`, `ShippingAmount`, `TaxAmount`, `Tax2Amount`, `TaxExempt`, `DiscountAmount`, `PromoAmount`, `OverrideAmount`, `FulfillmentMethod`, `FulfillmentLocationId`, `ShipToAddressSequenceIndex`, `ExtendedPrice`, `Product`, `CreateBy`, `CreateDate`, `UpdateBy`, `UpdateDate`, `DeleteBy`, `DeleteDate`)
VALUES
(1, 'lineItem-321', 1, 'USD', 90, NULL, 0, 0, 0, 0, 0, 0, 0, 'directShip', 'f431e8a2-ebd7-49da-acd6-d3dbf7b979a0', 0, 0, '{"code": "321", "name": "product name 321", "image": "https://myawesomeshop.com/images/img1.jpg", "price": 90, "currencyCode": "USD"}', '0f6e3169-0e28-4e17-a68e-0c5fb77e7267', '2019-10-23 12:10:04', NULL, NULL, NULL, NULL);

INSERT INTO omnichannel_order.`FulfillmentOrderLineItem` (`FulfillmentOrderID`,`SOLineItemID`,`SKU`,`Status`,`Quantity`,`CurrencyCode`,`Price`,`Cost`,`ShippingAmount`,`TaxAmount`,`Tax2Amount`,`TaxExempt`,`DiscountAmount`,`PromoAmount`,`OverrideAmount`,`ExtendedPrice`,`Product`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
(1,1,'','picked',1,'USD',59,NULL,0,0,0,0,0,0,NULL,0,'{\"sku\": \"24-MB02\", \"name\": \"Fusion Backpack\", \"price\": 59, \"productCode\": 6, \"currencyCode\": \"USD\", \"productImage\": \"https://magento-omnichannel2.shopgatedev.com/pub/media/catalog/product/m/b/mb02-gray-0.jpg\"}','','2018-12-04 10:44:21','pascal.vomhoff+testadmin@shopgate.com','2018-12-07 07:58:59',NULL,NULL);

INSERT INTO omnichannel_order.`FOFulfillmentPackageLI` (`FOFulfillmentPackageID`,`FulfillmentOrderLineItemID`,`Quantity`,`CreateBy`,`CreateDate`,`UpdateBy`,`UpdateDate`,`DeleteBy`,`DeleteDate`)
VALUES
(1,1,1,'','2018-12-04 10:44:21',NULL,NULL,NULL,NULL);


SET GLOBAL sql_mode = 'NO_ENGINE_SUBSTITUTION';
SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS authservice;
DROP DATABASE IF EXISTS catalog;
DROP DATABASE IF EXISTS location;
DROP DATABASE IF EXISTS merchant;
DROP DATABASE IF EXISTS customer;
DROP DATABASE IF EXISTS import;
DROP DATABASE IF EXISTS omnichannel;

CREATE DATABASE authservice;
CREATE DATABASE catalog;
CREATE DATABASE location;
CREATE DATABASE merchant;
CREATE DATABASE import;
CREATE DATABASE omnichannel;

CREATE TABLE authservice.`access_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `expires` datetime NOT NULL,
  `clientId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `expires` (`expires`),
  KEY `clientId` (`clientId`),
  KEY `userId` (`userId`),
  CONSTRAINT `access_tokens_ibfk_1` FOREIGN KEY (`clientId`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `access_tokens_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=488622 DEFAULT CHARSET=utf8;

CREATE TABLE authservice.`clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `secret` varchar(100) NOT NULL,
  `grantTypes` varchar(100) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `accessTokenLifetime` int(10) NOT NULL,
  `refreshTokenLifetime` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

CREATE TABLE authservice.`refresh_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(50) NOT NULL,
  `expires` datetime NOT NULL,
  `clientId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `expires` (`expires`),
  KEY `clientId` (`clientId`),
  KEY `userId` (`userId`),
  CONSTRAINT `refresh_tokens_ibfk_1` FOREIGN KEY (`clientId`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `refresh_tokens_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=140691 DEFAULT CHARSET=utf8;

CREATE TABLE authservice.`users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(15) NOT NULL DEFAULT 'system',
  `password` varchar(100) DEFAULT NULL,
  `scopes` text,
  `parentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`type`),
  KEY `parentId` (`parentId`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`parentId`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30989 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS location.`Location`;

CREATE TABLE location.`Location` (
  `LocationID` char(36) NOT NULL DEFAULT '',
  `MerchantID` char(36) NOT NULL DEFAULT '',
  `LocationTypeID` char(36) NOT NULL,
  `LocationCode` varchar(45) CHARACTER SET utf8 NOT NULL,
  `LocationName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `LocationStatus` enum('active','inactive','deleted','onhold') DEFAULT 'active',
  `Latitude` float(10,6) DEFAULT NULL,
  `Longitude` float(10,6) DEFAULT NULL,
  `TimeZone` varchar(40) DEFAULT NULL,
  `LocaleCode` varchar(5) DEFAULT NULL,
  `IsDefault` tinyint(1) DEFAULT '0',
  `FulfillmentMethods` varchar(255) DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`LocationID`),
  KEY `LocMerchant` (`MerchantID`),
  KEY `LocationTypeID_idx` (`LocationTypeID`),
  CONSTRAINT `LocationTypeID` FOREIGN KEY (`LocationTypeID`) REFERENCES `LocationType` (`LocationTypeID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS location.`LocationAddress`;

CREATE TABLE location.`LocationAddress` (
  `LocationAddressId` bigint(21) unsigned NOT NULL AUTO_INCREMENT,
  `LocationID` char(36) NOT NULL DEFAULT '',
  `AddressName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `AddressCode` varchar(50) CHARACTER SET utf8 NOT NULL,
  `Address1` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Address2` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Address3` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Address4` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `City` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Region` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `PostalCode` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `CountryCode` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `PhoneNumber` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `FaxNumber` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `EmailAddress` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `IsPrimary` tinyint(1) DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`LocationAddressID`),
  KEY `LocationID_idx` (`LocationID`),
  CONSTRAINT `FKLocAddrLoc_idx` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS location.`LocationDetail`;

CREATE TABLE location.`LocationDetail` (
  `LocationDetailID` char(36) NOT NULL,
  `LocationID` char(36) NOT NULL,
  `Manager` varchar(255) DEFAULT NULL,
  `LocationImage` varchar(255) DEFAULT NULL,
  `LocationDepartments` json DEFAULT NULL,
  `LocationServices` json DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`LocationDetailID`),
  KEY `FKLocDtlLoc_idx` (`LocationID`),
  CONSTRAINT `FKLocDtlLoc` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS location.`LocationSetting`;

CREATE TABLE location.`LocationSetting` (
  `LocationSettingID` char(36) NOT NULL,
  `LocationID` char(36) NOT NULL,
  `SettingKey` varchar(255) DEFAULT NULL,
  `SettingValue` varchar(255) DEFAULT NULL,
  `SettingType` enum('string', 'boolean', 'number') DEFAULT 'string',
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`LocationSettingID`),
  UNIQUE KEY `idx_LocSet_Location_SettingKey` (`LocationID`, `SettingKey`),
  CONSTRAINT `FKLocSetLoc` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS location.`LocationInventorySetting`;

CREATE TABLE location.`LocationInventorySetting` (
  `LocationInventorySettingID` char(36) NOT NULL,
  `LocationID` char(36) NOT NULL,
  `IsManaged` tinyint(1) DEFAULT 0,
  `Mode` enum('blind', 'integrated') DEFAULT 'blind',
  `SafetyStockMode` enum('enabled', 'disabled') DEFAULT 'disabled',
  `SafetyStock` int(11) DEFAULT 0,
  `SafetyStockType` enum('percentage', 'count') DEFAULT 'count',
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`LocationInventorySettingID`),
  CONSTRAINT `FKLocInvLoc` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS location.`LocationGroup`;

CREATE TABLE location.`LocationGroup` (
  `LocationGroupID` char(36) NOT NULL,
  `MerchantID` char(36) NOT NULL,
  `Priority` int(11) NOT NULL,
  `GroupName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `StatusID` char(36) NOT NULL,
  `CreatedBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`LocationGroupID`),
  KEY `FK_LG_Merc` (`MerchantID`),
  KEY `FK_LG_LS` (`StatusID`),
  CONSTRAINT `FK_LG_LS` FOREIGN KEY (`StatusID`) REFERENCES `LocationGroupStatus` (`StatusID`),
  CONSTRAINT `FK_LG_Merc` FOREIGN KEY (`MerchantID`) REFERENCES `Merchant` (`MerchantID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS location.`LocationGroupMember`;

CREATE TABLE location.`LocationGroupMember` (
  `LocationGroupMemberID` char(36) NOT NULL,
  `LocationGroupID` char(36) NOT NULL,
  `LocationID` char(36) NOT NULL,
  `Priority` int(11) NOT NULL,
  `CreatedBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`LocationGroupMemberID`),
  UNIQUE KEY `UKLGM_Priority` (`LocationGroupID`,`LocationID`,`Priority`),
  KEY `FK_LOGM_LOC` (`LocationID`),
  CONSTRAINT `FK_LG_LOGM` FOREIGN KEY (`LocationGroupID`) REFERENCES `LocationGroup` (`LocationGroupID`),
  CONSTRAINT `FK_LOGM_LOC` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS location.`LocationGroupStatus`;

CREATE TABLE location.`LocationGroupStatus` (
  `StatusID` char(36) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `CreatedBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`StatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS location.`LocationHour`;

CREATE TABLE location.`LocationHour` (
  `LocationHourID` char(36) NOT NULL DEFAULT '',
  `LocationID` char(36) NOT NULL DEFAULT '',
  `SundayHours` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `MondayHours` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `TuesdayHours` varchar(100) DEFAULT NULL,
  `WednesdayHours` varchar(100) DEFAULT NULL,
  `ThursdayHours` varchar(100) DEFAULT NULL,
  `FridayHours` varchar(100) DEFAULT NULL,
  `SaturdayHours` varchar(100) DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`LocationHourID`),
  UNIQUE KEY `LocationID` (`LocationID`),
  CONSTRAINT `LocationHourID` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS location.`LocationType`;

CREATE TABLE location.`LocationType` (
  `LocationTypeID` char(36) NOT NULL DEFAULT '',
  `LocationTypeCode` varchar(120) CHARACTER SET utf8 NOT NULL,
  `TypeDesc` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`LocationTypeID`),
  UNIQUE KEY `Unique` (`LocationTypeCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS merchant.`Merchant`;

CREATE TABLE merchant.`Merchant` (
  `MerchantID` char(36) NOT NULL DEFAULT '',
  `OwnerUserID` char(36) NOT NULL,
  `MerchantName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `MerchantCode` varchar(64) CHARACTER SET utf8 NOT NULL,
  `Region` varchar(100) CHARACTER SET utf8 NOT NULL,
  `AppLogo` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`MerchantID`),
  UNIQUE KEY `UKMerchantCode` (`MerchantCode`),
  KEY `FKOwnerID_UserID` (`OwnerUserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS merchant.`MerchantSetting`;

CREATE TABLE merchant.`MerchantSetting` (
  `MerchantSettingID` char(36) NOT NULL,
  `MerchantID` char(36) NOT NULL,
  `Key` varchar(255) DEFAULT 'NULL',
  `Value` longtext NOT NULL,
  `CreateBy` varchar(255) NOT NULL,
  `CreateDate` datetime NOT NULL,
  `UpdateBy` varchar(255) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`MerchantSettingID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`FOFulfillment`;

CREATE TABLE omnichannel.`FOFulfillment` (
  `FOFulfillmentID` char(36) NOT NULL DEFAULT '',
  `FulfillmentOrderID` char(36) NOT NULL DEFAULT '',
  `MerchantID` char(36) NOT NULL DEFAULT '',
  `Status` enum('open','canceled','inProgress','fulfilled') NOT NULL DEFAULT 'open',
  `Carrier` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `ServiceLevel` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Tracking` json DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`FOFulfillmentID`),
  KEY `FKFOF_Merchant` (`MerchantID`),
  CONSTRAINT `FKFOF_Merchant` FOREIGN KEY (`MerchantID`) REFERENCES `Merchant` (`MerchantID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`FOFulfillmentPackage`;

CREATE TABLE omnichannel.`FOFulfillmentPackage` (
  `FOFulfillmentPackageID` char(36) NOT NULL DEFAULT '',
  `FulfillmentOrderID` char(36) NOT NULL DEFAULT '',
  `FOFulfillmentID` char(36) NOT NULL DEFAULT '',
  `Status` enum('open','canceled','inProgress','fulfilled') NOT NULL DEFAULT 'open',
  `ServiceLevel` varchar(255) DEFAULT NULL,
  `FulfilledFromLocationCode` varchar(45) DEFAULT NULL,
  `Weight` float DEFAULT NULL,
  `Dimensions` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Tracking` json DEFAULT NULL,
  `PickUpBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `LabelURL` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `FulfilledDate` datetime DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`FOFulfillmentPackageID`),
  KEY `FK_FOFP_FOF` (`FOFulfillmentID`),
  CONSTRAINT `FK_FOFP_FOF` FOREIGN KEY (`FOFulfillmentID`) REFERENCES `FOFulfillment` (`FOFulfillmentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`FOFulfillmentPackageLI`;

CREATE TABLE omnichannel.`FOFulfillmentPackageLI` (
  `FOFulfillmentPackageLIID` char(36) NOT NULL DEFAULT '',
  `FOFulfillmentPackageID` char(36) NOT NULL DEFAULT '',
  `FulfillmentOrderLineItemID` char(36) NOT NULL DEFAULT '',
  `Quantity` int(11) NOT NULL,
  `CreateBy` varchar(20) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`FOFulfillmentPackageLIID`),
  KEY `FK_FOFPLI_FOFP` (`FOFulfillmentPackageID`),
  KEY `FulfillmentOrderLineItemID` (`FulfillmentOrderLineItemID`),
  CONSTRAINT `FK_FOFPLI_FOFP` FOREIGN KEY (`FOFulfillmentPackageID`) REFERENCES `FOFulfillmentPackage` (`FOFulfillmentPackageID`),
  CONSTRAINT `FOFulfillmentPackageLI_ibfk_1` FOREIGN KEY (`FulfillmentOrderLineItemID`) REFERENCES `FulfillmentOrderLineItem` (`FOLineItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`FulfillmentOrder`;

CREATE TABLE omnichannel.`FulfillmentOrder` (
  `FulfillmentOrderID` char(36) NOT NULL,
  `SalesOrderID` char(36) NOT NULL,
  `MerchantID` char(36) NOT NULL,
  `CustomerID` char(36) NOT NULL,
  `CustomerNumber` varchar(100) DEFAULT NULL,
  `ChannelID` char(36) NOT NULL,
  `LocationID` char(36) NOT NULL DEFAULT '',
  `RouteTypeID` char(36) NOT NULL,
  `OrderTypeID` char(36) NOT NULL,
  `Status` enum('new','requested','accepted','rejected','canceled','inProgress','picked','packed','ready','hold','fulfilled') NOT NULL DEFAULT 'new',
  `OrderNumber` varchar(50) NOT NULL DEFAULT '',
  `ExternalCode` varchar(50) DEFAULT NULL,
  `SequenceID` int(11) NOT NULL,
  `AcceptedDate` datetime DEFAULT NULL,
  `ReadyDate` datetime DEFAULT NULL,
  `CompletedDate` datetime DEFAULT NULL,
  `OrderSubmittedDate` datetime NOT NULL,
  `PricelistCode` varchar(50) DEFAULT NULL,
  `Expedited` tinyint(1) NOT NULL DEFAULT '0',
  `OrderSubTotal` float NOT NULL,
  `OrderTaxTotal` float NOT NULL,
  `OrderShippingTotal` float NOT NULL,
  `OrderTotal` float NOT NULL,
  `LocaleCode` varchar(5) NOT NULL DEFAULT 'en-US',
  `CurrencyCode` varchar(3) NOT NULL DEFAULT 'USD',
  `OrderNotes` json DEFAULT NULL,
  `OrderSpecialInstructions` json DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`FulfillmentOrderID`),
  UNIQUE KEY `SalesOrderID` (`SalesOrderID`,`SequenceID`),
  KEY `FK_FO_Merc` (`MerchantID`),
  KEY `FK_FO_RT` (`RouteTypeID`),
  KEY `FK_FO_OT` (`OrderTypeID`),
  KEY `FK_FO_Channel` (`ChannelID`),
  KEY `LocationID` (`LocationID`),
  KEY `OrderNumberLocationID` (`OrderNumber`,`LocationID`,`ExternalCode`),
  KEY `UpdateDate` (`UpdateDate`,`CreateDate`),
  CONSTRAINT `FK_FO_Channel` FOREIGN KEY (`ChannelID`) REFERENCES `Channel` (`ChannelID`),
  CONSTRAINT `FK_FO_Merc` FOREIGN KEY (`MerchantID`) REFERENCES `Merchant` (`MerchantID`),
  CONSTRAINT `FK_FO_OT` FOREIGN KEY (`OrderTypeID`) REFERENCES `OrderType` (`OrderTypeID`),
  CONSTRAINT `FK_FO_RT` FOREIGN KEY (`RouteTypeID`) REFERENCES `RouteType` (`RouteTypeID`),
  CONSTRAINT `FK_FO_SO` FOREIGN KEY (`SalesOrderID`) REFERENCES `SalesOrder` (`SalesOrderID`),
  CONSTRAINT `FulfillmentOrder_ibfk_1` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`FulfillmentOrderAddress`;

CREATE TABLE omnichannel.`FulfillmentOrderAddress` (
  `FOAddressID` char(36) NOT NULL DEFAULT '',
  `FulfillmentOrderID` char(36) NOT NULL DEFAULT '',
  `OrderIndex` int(11) NOT NULL DEFAULT '0' COMMENT 'sort by OrderIndex ASC',
  `ContactTypeID` char(36) NOT NULL DEFAULT '',
  `FirstName` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `MiddleName` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `LastName` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `CompanyName` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `Address1` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `Address2` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `Address3` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `Address4` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `City` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `Region` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `PostalCode` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `Country` varchar(2) CHARACTER SET utf8 DEFAULT '',
  `PhoneNumber` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `FaxNumber` varchar(50) CHARACTER SET utf8 DEFAULT '',
  `MobileNumber` varchar(50) CHARACTER SET utf8 DEFAULT '',
  `EmailAddress` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`FOAddressID`),
  UNIQUE KEY `FulfillmentOrderID` (`FulfillmentOrderID`,`OrderIndex`),
  CONSTRAINT `FK_FOA_FO` FOREIGN KEY (`FulfillmentOrderID`) REFERENCES `FulfillmentOrder` (`FulfillmentOrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`FulfillmentOrderHistory`;

CREATE TABLE omnichannel.`FulfillmentOrderHistory` (
  `FulfillmentOrderHistoryID` char(36) NOT NULL DEFAULT '',
  `FulfillmentOrderID` char(36) NOT NULL DEFAULT '',
  `SequenceID` int(11) NOT NULL DEFAULT '0',
  `EventName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `EventDate` datetime NOT NULL,
  `EventDetails` json DEFAULT NULL,
  `EventNewValue` json DEFAULT NULL,
  `EventOldValue` json DEFAULT NULL,
  `UserID` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`FulfillmentOrderHistoryID`),
  KEY `FK_FOH_FO` (`FulfillmentOrderID`),
  CONSTRAINT `FK_FOH_FO` FOREIGN KEY (`FulfillmentOrderID`) REFERENCES `FulfillmentOrder` (`FulfillmentOrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`FulfillmentOrderLineItem`;

CREATE TABLE omnichannel.`FulfillmentOrderLineItem` (
  `FOLineItemID` char(36) NOT NULL DEFAULT '',
  `FulfillmentOrderID` char(36) NOT NULL DEFAULT '',
  `SOLineItemID` char(36) NOT NULL DEFAULT '',
  `Status` enum('new','requested','accepted','rejected','canceled','picked','packed','ready','hold','fulfilled') NOT NULL DEFAULT 'new',
  `Quantity` int(11) NOT NULL,
  `CurrencyCode` varchar(5) CHARACTER SET utf8 NOT NULL,
  `LocaleCode` varchar(5) CHARACTER SET utf8 NOT NULL,
  `Price` float NOT NULL,
  `Cost` float DEFAULT NULL,
  `ShippingAmount` float DEFAULT NULL,
  `TaxAmount` float DEFAULT NULL,
  `Tax2Amount` float DEFAULT NULL,
  `TaxExempt` tinyint(1) NOT NULL,
  `DiscountAmount` float DEFAULT NULL,
  `PromoAmount` float DEFAULT NULL,
  `OverrideAmount` float DEFAULT NULL,
  `ExtendedPrice` float DEFAULT NULL,
  `Product` json DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`FOLineItemID`),
  KEY `FK_FOLI_FO` (`FulfillmentOrderID`),
  KEY `SOLineItemID` (`SOLineItemID`),
  CONSTRAINT `FK_FOLI_FO` FOREIGN KEY (`FulfillmentOrderID`) REFERENCES `FulfillmentOrder` (`FulfillmentOrderID`),
  CONSTRAINT `FulfillmentOrderLineItem_ibfk_1` FOREIGN KEY (`SOLineItemID`) REFERENCES `SalesOrderLineItem` (`SOLineItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`RouteType`;

CREATE TABLE omnichannel.`RouteType` (
  `RouteTypeID` char(36) NOT NULL DEFAULT '',
  `RouteType` varchar(100) NOT NULL,
  `CreateBy` varchar(255) NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`RouteTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`SalesOrder`;

CREATE TABLE omnichannel.`SalesOrder` (
  `SalesOrderID` char(36) NOT NULL DEFAULT '',
  `MerchantID` char(36) NOT NULL DEFAULT '',
  `CustomerID` char(36) NOT NULL,
  `CustomerNumber` varchar(100) DEFAULT NULL,
  `OrderTypeID` char(36) NOT NULL,
  `ChannelID` char(36) NOT NULL,
  `Status` enum('new','open','rejected','canceled','fulfilled','completed','ready') DEFAULT 'open',
  `OrderNumber` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ExternalCode` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `Expedated` tinyint(1) NOT NULL,
  `OrderDate` datetime NOT NULL,
  `OrderSubmitDate` datetime DEFAULT NULL,
  `OrderAcceptDate` datetime DEFAULT NULL,
  `OrderCompleteDate` datetime DEFAULT NULL,
  `PriceListCode` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `PrimaryBillToAddressSequenceIndex` int(11) NOT NULL,
  `PrimaryShipToAddressSequenceIndex` int(11) NOT NULL,
  `OrderSubTotal` float NOT NULL,
  `OrderDiscountAmount` float NOT NULL,
  `OrderPromoAmount` float NOT NULL,
  `OrderTaxAmount` float NOT NULL,
  `OrderTax2Amount` float NOT NULL,
  `OrderTotal` float NOT NULL,
  `ShippingSubTotal` float NOT NULL,
  `ShippingDiscountAmount` float NOT NULL DEFAULT '0',
  `ShippingPromoAmount` float NOT NULL DEFAULT '0',
  `ShippingTotal` float NOT NULL DEFAULT '0',
  `LocaleCode` varchar(5) NOT NULL DEFAULT 'en-US',
  `CurrencyCode` varchar(5) NOT NULL DEFAULT 'USD',
  `TaxExempt` tinyint(1) DEFAULT NULL,
  `Notes` json DEFAULT NULL,
  `SpecialInstructions` json DEFAULT NULL,
  `OrderData` json DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`SalesOrderID`),
  UNIQUE KEY `MerchantID` (`MerchantID`,`OrderNumber`),
  KEY `FK_SO_OT` (`OrderTypeID`),
  CONSTRAINT `FK_SO_Merch` FOREIGN KEY (`MerchantID`) REFERENCES `Merchant` (`MerchantID`),
  CONSTRAINT `FK_SO_OT` FOREIGN KEY (`OrderTypeID`) REFERENCES `OrderType` (`OrderTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`SalesOrderAddress`;

CREATE TABLE omnichannel.`SalesOrderAddress` (
  `SOAddressID` char(36) NOT NULL DEFAULT '',
  `SalesOrderID` char(36) NOT NULL DEFAULT '',
  `ContactTypeID` char(36) NOT NULL,
  `OrderIndex` int(11) NOT NULL DEFAULT '0' COMMENT 'sort by OrderIndex ASC',
  `FirstName` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `MiddleName` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `LastName` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `CompanyName` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `Address1` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `Address2` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `Address3` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `Address4` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `City` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `Region` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `PostalCode` varchar(255) CHARACTER SET utf8 DEFAULT '',
  `Country` varchar(2) CHARACTER SET utf8 DEFAULT '',
  `PhoneNumber` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `FaxNumber` varchar(50) CHARACTER SET utf8 DEFAULT '',
  `MobileNumber` varchar(50) CHARACTER SET utf8 DEFAULT '',
  `EmailAddress` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`SOAddressID`),
  UNIQUE KEY `SalesOrderID` (`SalesOrderID`,`OrderIndex`),
  CONSTRAINT `FK_SOA_SO` FOREIGN KEY (`SalesOrderID`) REFERENCES `SalesOrder` (`SalesOrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`SalesOrderHistory`;

CREATE TABLE omnichannel.`SalesOrderHistory` (
  `SalesOrderHistoryID` char(36) NOT NULL DEFAULT '',
  `SalesOrderID` char(36) NOT NULL DEFAULT '',
  `EventName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `EventDate` datetime NOT NULL,
  `EventDetails` json DEFAULT NULL,
  `EventNewValue` json DEFAULT NULL,
  `EventOldValue` json DEFAULT NULL,
  `UserID` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`SalesOrderHistoryID`),
  KEY `FK_SOH_SO` (`SalesOrderID`),
  CONSTRAINT `FK_SOH_SO` FOREIGN KEY (`SalesOrderID`) REFERENCES `SalesOrder` (`SalesOrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`SalesOrderLineItem`;

CREATE TABLE omnichannel.`SalesOrderLineItem` (
  `SOLineItemID` char(36) NOT NULL DEFAULT '',
  `SalesOrderID` char(36) NOT NULL DEFAULT '',
  `LineItemCode` varchar(45) DEFAULT NULL,
  `Quantity` int(11) NOT NULL,
  `CurrencyCode` varchar(5) CHARACTER SET utf8 NOT NULL,
  `LocaleCode` varchar(5) CHARACTER SET utf8 NOT NULL,
  `Price` float NOT NULL,
  `Cost` float NOT NULL,
  `ShippingAmount` float NOT NULL,
  `TaxAmount` float NOT NULL,
  `Tax2Amount` float NOT NULL,
  `TaxExempt` tinyint(1) NOT NULL,
  `DiscountAmount` float NOT NULL,
  `PromoAmount` float NOT NULL,
  `OverrideAmount` float NOT NULL,
  `FulfillmentMethodID` int(11) NOT NULL,
  `FulfillmentLocationCode` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `ShipToAddressSequenceIndex` int(11) DEFAULT NULL,
  `ExtendedPrice` float NOT NULL,
  `Product` json DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`SOLineItemID`),
  KEY `FK_SOLI_SO` (`SalesOrderID`),
  CONSTRAINT `FK_SOLI_SO` FOREIGN KEY (`SalesOrderID`) REFERENCES `SalesOrder` (`SalesOrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`SOFulfillment`;

CREATE TABLE omnichannel.`SOFulfillment` (
  `SOFulfillmentID` char(36) NOT NULL DEFAULT '',
  `FulfillmentGroupID` char(36) NOT NULL DEFAULT '',
  `SalesOrderID` char(36) NOT NULL DEFAULT '',
  `MerchantID` char(36) NOT NULL DEFAULT '',
  `Status` enum('new','open','rejected','canceled','partially_fulfilled','fulfilled','completed') NOT NULL DEFAULT 'new',
  `Carrier` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `ServiceLevel` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Tracking` json DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`SOFulfillmentID`),
  KEY `FK_SOF_SOFG` (`FulfillmentGroupID`),
  KEY `FKSOF_Merchant` (`MerchantID`),
  CONSTRAINT `FKSOF_Merchant` FOREIGN KEY (`MerchantID`) REFERENCES `Merchant` (`MerchantID`),
  CONSTRAINT `FK_SOF_SOFG` FOREIGN KEY (`FulfillmentGroupID`) REFERENCES `SOFulfillmentGroup` (`SOFulfillmentGroupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`SOFulfillmentGroup`;

CREATE TABLE omnichannel.`SOFulfillmentGroup` (
  `SOFulfillmentGroupID` char(36) NOT NULL DEFAULT '',
  `SalesOrderID` char(36) NOT NULL DEFAULT '',
  `FulfillmentMethodID` int(11) NOT NULL COMMENT 'currently mapped to RouteTypeID',
  `FulfillmentLocationCode` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `ShipToAddressSequenceIndex` int(11) DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`SOFulfillmentGroupID`),
  KEY `FK_SOFG_SO` (`SalesOrderID`),
  CONSTRAINT `FK_SOFG_SO` FOREIGN KEY (`SalesOrderID`) REFERENCES `SalesOrder` (`SalesOrderID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`SOFulfillmentPackage`;

CREATE TABLE omnichannel.`SOFulfillmentPackage` (
  `SOFulfillmentPackageID` char(36) NOT NULL DEFAULT '',
  `SalesOrderID` char(36) NOT NULL DEFAULT '',
  `SOFulfillmentID` char(36) NOT NULL DEFAULT '',
  `Status` enum('open','canceled','in progress','fulfilled') NOT NULL DEFAULT 'open',
  `ServiceLevel` varchar(255) DEFAULT NULL,
  `FulfilledFromLocationCode` varchar(45) DEFAULT NULL,
  `Weight` float DEFAULT NULL,
  `Dimensions` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `Tracking` json DEFAULT NULL,
  `PickUpBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `LabelURL` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `FulfilledDate` datetime DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`SOFulfillmentPackageID`),
  KEY `FK_SOFP_SOF` (`SOFulfillmentID`),
  CONSTRAINT `FK_SOFP_SOF` FOREIGN KEY (`SOFulfillmentID`) REFERENCES `SOFulfillment` (`SOFulfillmentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS omnichannel.`SOFulfillmentPackageLI`;

CREATE TABLE omnichannel.`SOFulfillmentPackageLI` (
  `SOFulfillmentPackageLIID` char(36) NOT NULL DEFAULT '',
  `SOFulfillmentPackageID` char(36) NOT NULL DEFAULT '',
  `SalesOrderLineItemID` char(36) NOT NULL DEFAULT '',
  `Quantity` int(11) NOT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`SOFulfillmentPackageLIID`),
  KEY `FK_SOFPLI_SOFP` (`SOFulfillmentPackageID`),
  KEY `SalesOrderLineItemID` (`SalesOrderLineItemID`),
  CONSTRAINT `FK_SOFPLI_SOFP` FOREIGN KEY (`SOFulfillmentPackageID`) REFERENCES `SOFulfillmentPackage` (`SOFulfillmentPackageID`),
  CONSTRAINT `SOFulfillmentPackageLI_ibfk_1` FOREIGN KEY (`SalesOrderLineItemID`) REFERENCES `SalesOrderLineItem` (`SOLineItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

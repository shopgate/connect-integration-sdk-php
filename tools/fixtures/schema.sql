SET GLOBAL sql_mode = 'NO_ENGINE_SUBSTITUTION';
SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS authservice;
DROP DATABASE IF EXISTS catalog;
DROP DATABASE IF EXISTS location;
DROP DATABASE IF EXISTS merchant;
DROP DATABASE IF EXISTS customer;

CREATE DATABASE authservice;
CREATE DATABASE catalog;
CREATE DATABASE location;
CREATE DATABASE merchant;
CREATE DATABASE customer;

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
  `Latitude` varchar(50) DEFAULT NULL,
  `Longitude` varchar(50) DEFAULT NULL,
  `IsDefault` TINYINT(1) DEFAULT 0,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`LocationID`),
  UNIQUE KEY `LocName` (`MerchantID`,`LocationCode`),
  KEY `LocationTypeID_idx` (`LocationTypeID`),
  CONSTRAINT `LocationTypeID` FOREIGN KEY (`LocationTypeID`) REFERENCES `LocationType` (`LocationTypeID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS location.`LocationAddress`;

CREATE TABLE location.`LocationAddress` (
  `LocationAddressID` char(36) NOT NULL DEFAULT '',
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
  CONSTRAINT `LocationID` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS location.`LocationDetail`;

CREATE TABLE location.`LocationDetail` (
  `LocationDetailID` char(36) NOT NULL,
  `LocationID` char(36) NOT NULL,
  `Manager` varchar(255) DEFAULT NULL,
  `LocationImage` varchar(255) DEFAULT NULL,
  `LocationDepartments` json DEFAULT NULL,
  `LocationServices` json DEFAULT NULL,
  `CreatedBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`LocationDetailID`),
  KEY `FKLocDtlLoc_idx` (`LocationID`),
  CONSTRAINT `FKLocDtlLoc` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`) ON DELETE NO ACTION ON UPDATE NO ACTION
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
  CONSTRAINT `FK_LG_LS` FOREIGN KEY (`StatusID`) REFERENCES `LocationGroupStatus` (`StatusID`)
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

DROP TABLE IF EXISTS customer.`Attribute`;

CREATE TABLE customer.`Attribute` (
  `AttributeId` char(36) NOT NULL DEFAULT '',
  `MerchantId` char(36) NOT NULL DEFAULT '',
  `AttributeCode` varchar(255) NOT NULL DEFAULT '',
  `AttributeType` enum('Text','Number','Boolean','Date','CollectionOfValues') DEFAULT NULL,
  `IsRequired` tinyint(1) DEFAULT NULL,
  `CreateBy` varchar(255) NOT NULL DEFAULT '',
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`AttributeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS customer.`AttributeContent`;

CREATE TABLE customer.`AttributeContent` (
  `AttributeContentId` char(36) NOT NULL DEFAULT '',
  `AttributeId` char(36) NOT NULL DEFAULT '',
  `LocaleCode` char(5) NOT NULL DEFAULT '',
  `Name` varchar(255) NOT NULL DEFAULT '',
  `CreateBy` varchar(255) NOT NULL DEFAULT '',
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `DeleteBy` varchar(255) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`AttributeContentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS customer.`AttributeValue`;

CREATE TABLE customer.`AttributeValue` (
  `AttributeValueID` char(36) NOT NULL,
  `AttributeID` char(36) NOT NULL,
  `AttributeValue` char(36) NOT NULL,
  `SequenceId` int(11) DEFAULT NULL,
  `CreateBy` varchar(255) NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `DeleteBy` varchar(255) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`AttributeValueID`),
  KEY `AttributeID` (`AttributeID`,`AttributeValue`),
  CONSTRAINT `AttributeValue_ibfk_1` FOREIGN KEY (`AttributeID`) REFERENCES `Attribute` (`AttributeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS customer.`AttributeValueContent`;

CREATE TABLE customer.`AttributeValueContent` (
  `AttributeValueContentID` char(36) NOT NULL,
  `AttributeValueID` char(36) NOT NULL,
  `LocaleCode` varchar(5) NOT NULL,
  `AttributeValueName` varchar(255) NOT NULL,
  `CreateBy` varchar(255) NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `DeleteBy` varchar(255) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`AttributeValueContentID`),
  KEY `AttributeValueID` (`AttributeValueID`),
  CONSTRAINT `AttributeValueContent_ibfk_1` FOREIGN KEY (`AttributeValueID`) REFERENCES `AttributeValue` (`AttributeValueID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS customer.`ContactType`;

CREATE TABLE customer.`ContactType` (
  `ContactTypeID` char(36) NOT NULL DEFAULT '',
  `ContactType` varchar(50) DEFAULT NULL,
  `ContactTypeName` varchar(255) DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`ContactTypeID`),
  UNIQUE KEY `ContactType` (`ContactType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS customer.`Customer`;

CREATE TABLE customer.`Customer` (
  `CustomerID` char(36) NOT NULL DEFAULT '',
  `MerchantID` char(36) NOT NULL DEFAULT '',
  `CustomerNumber` varchar(100) DEFAULT NULL COMMENT 'from external source',
  `Status` enum('active','inactive','deleted') NOT NULL DEFAULT 'active',
  `FirstName` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `MiddleName` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `LastName` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `EmailAddress` varchar(255) NOT NULL,
  `OriginalCreateDate` datetime DEFAULT NULL,
  `IsAnonymous` tinyint(1) NOT NULL DEFAULT '0',
  `ExternalUpdateDate` datetime DEFAULT NULL,
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(45) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`CustomerID`),
  UNIQUE KEY `MerchantID` (`MerchantID`,`CustomerNumber`),
  FULLTEXT KEY `FirstName` (`FirstName`,`MiddleName`,`LastName`),
  FULLTEXT KEY `EmailAddress` (`EmailAddress`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS customer.`CustomerAttribute`;

CREATE TABLE customer.`CustomerAttribute` (
  `CustomerAttributeId` char(36) NOT NULL DEFAULT '',
  `CustomerId` char(36) NOT NULL DEFAULT '',
  `AttributeId` char(36) NOT NULL DEFAULT '',
  `AttributeValueId` char(36) DEFAULT NULL,
  `Value` varchar(255) DEFAULT NULL,
  `CreateBy` varchar(255) NOT NULL DEFAULT '',
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `DeleteBy` varchar(255) DEFAULT '',
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`CustomerAttributeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS customer.`CustomerContact`;

CREATE TABLE customer.`CustomerContact` (
  `CustomerContactID` char(36) NOT NULL DEFAULT '',
  `CustomerID` char(36) NOT NULL DEFAULT '',
  `ContactTypeID` char(36) DEFAULT NULL,
  `ContactCode` varchar(255) DEFAULT NULL,
  `Status` enum('active','inactive','deleted') DEFAULT 'active',
  `FirstName` varchar(255) DEFAULT NULL,
  `MiddleName` varchar(255) DEFAULT NULL,
  `LastName` varchar(255) DEFAULT NULL,
  `CompanyName` varchar(255) DEFAULT NULL,
  `Address1` varchar(255) DEFAULT NULL,
  `Address2` varchar(255) DEFAULT NULL,
  `Address3` varchar(255) DEFAULT NULL,
  `Address4` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `Region` varchar(255) DEFAULT NULL,
  `PostalCode` varchar(255) DEFAULT NULL,
  `Country` varchar(2) DEFAULT NULL,
  `PhoneNumber` varchar(50) DEFAULT NULL,
  `FaxNumber` varchar(50) DEFAULT NULL,
  `MobileNumber` varchar(50) DEFAULT NULL,
  `EmailAddress` varchar(255) DEFAULT NULL,
  `IsPrimary` tinyint(1) NOT NULL DEFAULT '0',
  `IsDefaultBilling` tinyint(1) DEFAULT '0',
  `IsDefaultShipping` tinyint(1) DEFAULT '0',
  `ExternalUpdateDate` datetime DEFAULT NULL,
  `CreateBy` varchar(255) NOT NULL DEFAULT '',
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`CustomerContactID`),
  KEY `FK_Cont_ContType` (`ContactTypeID`),
  KEY `FK_Cust_Cont` (`CustomerID`),
  KEY `ContactCode` (`ContactCode`),
  CONSTRAINT `FK_Cont_ContType` FOREIGN KEY (`ContactTypeID`) REFERENCES `ContactType` (`ContactTypeID`),
  CONSTRAINT `FK_Cust_Cont` FOREIGN KEY (`CustomerID`) REFERENCES `Customer` (`CustomerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS customer.`CustomerMetric`;

CREATE TABLE customer.`CustomerMetric` (
  `CustomerMetricId` char(36) NOT NULL DEFAULT '',
  `CustomerId` char(36) NOT NULL DEFAULT '',
  `Key` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `Value` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `CreateBy` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `DeleteBy` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`CustomerMetricId`),
  UNIQUE KEY `CustomerMetric_Key` (`CustomerId`,`Key`),
  CONSTRAINT `CustomerMetric_CustomerID` FOREIGN KEY (`CustomerId`) REFERENCES `Customer` (`CustomerID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS customer.`CustomerNote`;

CREATE TABLE customer.`CustomerNote` (
  `CustomerNoteID` char(36) NOT NULL DEFAULT '',
  `CustomerID` char(36) NOT NULL DEFAULT '',
  `Code` varchar(255) DEFAULT NULL,
  `Status` enum('active','deleted') NOT NULL DEFAULT 'active',
  `Note` text NOT NULL,
  `Date` datetime NOT NULL,
  `Creator` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `CreateBy` varchar(255) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `UpdateBy` varchar(255) DEFAULT '',
  `DeleteDate` datetime DEFAULT NULL,
  `DeleteBy` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`CustomerNoteID`),
  KEY `IDX_CustomerId_Status` (`CustomerID`,`Status`),
  KEY `IDX_CustomerId_Status_Date` (`CustomerID`,`Status`,`Date`),
  CONSTRAINT `CustomerNote_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `Customer` (`CustomerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS customer.`CustomerSetting`;

CREATE TABLE customer.`CustomerSetting` (
  `CustomerSettingId` char(36) NOT NULL DEFAULT '',
  `CustomerId` char(36) NOT NULL DEFAULT '',
  `Key` varchar(255) NOT NULL DEFAULT '',
  `Value` varchar(255) NOT NULL DEFAULT '',
  `CreateBy` varchar(255) NOT NULL DEFAULT '',
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `DeleteBy` varchar(255) DEFAULT NULL,
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`CustomerSettingId`),
  KEY `CustomerId` (`CustomerId`),
  CONSTRAINT `CustomerSetting_ibfk_1` FOREIGN KEY (`CustomerId`) REFERENCES `Customer` (`CustomerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS customer.`Wishlist`;

CREATE TABLE customer.`Wishlist` (
  `WishlistId` char(36) NOT NULL DEFAULT '',
  `CustomerId` char(36) NOT NULL DEFAULT '',
  `WishlistCode` varchar(255) NOT NULL DEFAULT '',
  `WishlistName` varchar(255) DEFAULT NULL,
  `CreateBy` varchar(255) NOT NULL DEFAULT '',
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `DeleteBy` varchar(255) DEFAULT '',
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`WishlistId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS customer.`WishlistItem`;

CREATE TABLE customer.`WishlistItem` (
  `WishlistItemId` char(36) NOT NULL DEFAULT '',
  `WishlistId` char(36) NOT NULL DEFAULT '',
  `ProductCode` varchar(255) NOT NULL DEFAULT '',
  `CreateBy` varchar(255) NOT NULL DEFAULT '',
  `CreateDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdateBy` varchar(255) DEFAULT '',
  `UpdateDate` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `DeleteBy` varchar(255) DEFAULT '',
  `DeleteDate` datetime DEFAULT NULL,
  PRIMARY KEY (`WishlistItemId`),
  KEY `FK_WishlistItem_WishlistId` (`WishlistId`),
  CONSTRAINT `FK_WishlistItem_WishlistId` FOREIGN KEY (`WishlistId`) REFERENCES `Wishlist` (`WishlistId`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

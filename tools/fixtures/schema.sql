SET GLOBAL sql_mode = 'NO_ENGINE_SUBSTITUTION';
SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS catalog;
DROP DATABASE IF EXISTS location;
DROP DATABASE IF EXISTS merchant;

CREATE DATABASE catalog;
CREATE DATABASE location;
CREATE DATABASE merchant;

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
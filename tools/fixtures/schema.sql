SET GLOBAL sql_mode = 'NO_ENGINE_SUBSTITUTION';
SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS omnichannel_user;
DROP DATABASE IF EXISTS catalog;
DROP DATABASE IF EXISTS location;
DROP DATABASE IF EXISTS merchant;
DROP DATABASE IF EXISTS customer;
DROP DATABASE IF EXISTS import;
DROP DATABASE IF EXISTS omnichannel_order;
DROP DATABASE IF EXISTS omnichannel;
DROP DATABASE IF EXISTS omnichannel_auth;

CREATE DATABASE omnichannel_user;
CREATE DATABASE catalog;
CREATE DATABASE location;
CREATE DATABASE omnichannel;
CREATE DATABASE omnichannel_auth;
CREATE DATABASE import;
CREATE DATABASE omnichannel_order;

DROP TABLE IF EXISTS location.`Location`;

CREATE TABLE location.`Location`
(
    `LocationID`         char(36)                        NOT NULL      DEFAULT '',
    `MerchantID`         char(36)                        NOT NULL      DEFAULT '',
    `LocationTypeID`     char(36)                        NOT NULL,
    `LocationCode`       varchar(45) CHARACTER SET utf8  NOT NULL,
    `LocationName`       varchar(255) CHARACTER SET utf8 NOT NULL,
    `LocationStatus`     enum ('active','inactive','deleted','onhold') DEFAULT 'active',
    `Latitude`           float(10, 6)                                  DEFAULT NULL,
    `Longitude`          float(10, 6)                                  DEFAULT NULL,
    `TimeZone`           varchar(40)                                   DEFAULT NULL,
    `LocaleCode`         varchar(5)                                    DEFAULT NULL,
    `IsComingSoon`       tinyint(1)                                    DEFAULT '0',
    `IsDefault`          tinyint(1)                                    DEFAULT '0',
    `FulfillmentMethods` varchar(255)                                  DEFAULT NULL,
    `CreateBy`           varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`         datetime                        NOT NULL      DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`           varchar(255) CHARACTER SET utf8               DEFAULT NULL,
    `UpdateDate`         datetime                                      DEFAULT NULL,
    `DeleteBy`           varchar(255) CHARACTER SET utf8               DEFAULT NULL,
    `DeleteDate`         datetime                                      DEFAULT NULL,
    PRIMARY KEY (`LocationID`),
    KEY `LocMerchant` (`MerchantID`),
    KEY `LocationTypeID_idx` (`LocationTypeID`),
    CONSTRAINT `LocationTypeID` FOREIGN KEY (`LocationTypeID`) REFERENCES `LocationType` (`LocationTypeID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS location.`LocationAddress`;

CREATE TABLE location.`LocationAddress`
(
    `LocationAddressId` bigint(21) unsigned             NOT NULL AUTO_INCREMENT,
    `LocationID`        char(36)                        NOT NULL DEFAULT '',
    `AddressName`       varchar(255) CHARACTER SET utf8 NOT NULL,
    `AddressCode`       varchar(50) CHARACTER SET utf8  NOT NULL,
    `Address1`          varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `Address2`          varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `Address3`          varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `Address4`          varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `City`              varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `Region`            varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `PostalCode`        varchar(100) CHARACTER SET utf8          DEFAULT NULL,
    `CountryCode`       varchar(2) CHARACTER SET utf8            DEFAULT NULL,
    `PhoneNumber`       varchar(30) CHARACTER SET utf8           DEFAULT NULL,
    `FaxNumber`         varchar(30) CHARACTER SET utf8           DEFAULT NULL,
    `EmailAddress`      varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `IsPrimary`         tinyint(1)                               DEFAULT NULL,
    `CreateBy`          varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`        datetime                        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`          varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `UpdateDate`        datetime                                 DEFAULT NULL,
    `DeleteBy`          varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `DeleteDate`        datetime                                 DEFAULT NULL,
    PRIMARY KEY (`LocationAddressID`),
    KEY `LocationID_idx` (`LocationID`),
    CONSTRAINT `FKLocAddrLoc_idx` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS location.`LocationDetail`;

CREATE TABLE location.`Postalcode`
(
    `id`          int(11) unsigned NOT NULL AUTO_INCREMENT,
    `CountryCode` char(2)          NOT NULL DEFAULT '',
    `PostalCode`  varchar(20)      NOT NULL DEFAULT '',
    `PlaceName`   varchar(180)     NOT NULL DEFAULT '',
    `AdminName1`  varchar(100)     NOT NULL DEFAULT '',
    `AdminCode1`  varchar(20)      NOT NULL DEFAULT '',
    `AdminName2`  varchar(100)     NOT NULL DEFAULT '',
    `AdminCode2`  varchar(20)      NOT NULL DEFAULT '',
    `AdminName3`  varchar(100)     NOT NULL DEFAULT '',
    `AdminCode3`  varchar(20)      NOT NULL DEFAULT '',
    `Latitude`    float(10, 6)     NOT NULL,
    `Longitude`   float(10, 6)     NOT NULL,
    `Accuracy`    int(1) unsigned           DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `CountryCode` (`CountryCode`, `PostalCode`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


CREATE TABLE location.`LocationDetail`
(
    `LocationDetailID`    char(36)                        NOT NULL,
    `LocationID`          char(36)                        NOT NULL,
    `Manager`             varchar(255)                    DEFAULT NULL,
    `LocationImage`       varchar(255)                    DEFAULT NULL,
    `LocationDepartments` json                            DEFAULT NULL,
    `LocationServices`    json                            DEFAULT NULL,
    `CreateBy`            varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`          datetime                        NOT NULL,
    `UpdateBy`            varchar(255) CHARACTER SET utf8 DEFAULT NULL,
    `UpdateDate`          datetime                        DEFAULT NULL,
    `DeleteBy`            varchar(255) CHARACTER SET utf8 DEFAULT NULL,
    `DeleteDate`          datetime                        DEFAULT NULL,
    PRIMARY KEY (`LocationDetailID`),
    KEY `FKLocDtlLoc_idx` (`LocationID`),
    CONSTRAINT `FKLocDtlLoc` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS location.`LocationSetting`;

CREATE TABLE location.`LocationSetting`
(
    `LocationSettingID` char(36)                        NOT NULL,
    `LocationID`        char(36)                        NOT NULL,
    `SettingKey`        varchar(255)                         DEFAULT NULL,
    `SettingValue`      varchar(255)                         DEFAULT NULL,
    `SettingType`       enum ('string', 'boolean', 'number') DEFAULT 'string',
    `CreateBy`          varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`        datetime                        NOT NULL,
    `UpdateBy`          varchar(255) CHARACTER SET utf8      DEFAULT NULL,
    `UpdateDate`        datetime                             DEFAULT NULL,
    `DeleteBy`          varchar(255) CHARACTER SET utf8      DEFAULT NULL,
    `DeleteDate`        datetime                             DEFAULT NULL,
    PRIMARY KEY (`LocationSettingID`),
    UNIQUE KEY `idx_LocSet_Location_SettingKey` (`LocationID`, `SettingKey`),
    CONSTRAINT `FKLocSetLoc` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS location.`LocationInventorySetting`;

CREATE TABLE location.`LocationInventorySetting`
(
    `LocationInventorySettingID` char(36)                        NOT NULL,
    `LocationID`                 char(36)                        NOT NULL,
    `IsManaged`                  tinyint(1)                      DEFAULT 0,
    `Mode`                       enum ('blind', 'integrated')    DEFAULT 'blind',
    `SafetyStockMode`            enum ('enabled', 'disabled')    DEFAULT 'disabled',
    `SafetyStock`                int(11)                         DEFAULT 0,
    `SafetyStockType`            enum ('percentage', 'count')    DEFAULT 'count',
    `CreateBy`                   varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`                 datetime                        NOT NULL,
    `UpdateBy`                   varchar(255) CHARACTER SET utf8 DEFAULT NULL,
    `UpdateDate`                 datetime                        DEFAULT NULL,
    `DeleteBy`                   varchar(255) CHARACTER SET utf8 DEFAULT NULL,
    `DeleteDate`                 datetime                        DEFAULT NULL,
    PRIMARY KEY (`LocationInventorySettingID`),
    CONSTRAINT `FKLocInvLoc` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS location.`LocationGroup`;

CREATE TABLE location.`LocationGroup`
(
    `LocationGroupID` char(36)                        NOT NULL,
    `MerchantID`      char(36)                        NOT NULL,
    `Priority`        int(11)                         NOT NULL,
    `GroupName`       varchar(255) CHARACTER SET utf8 NOT NULL,
    `StatusID`        char(36)                        NOT NULL,
    `CreatedBy`       varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`      datetime                        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`        varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `UpdateDate`      datetime                                 DEFAULT NULL,
    `DeleteBy`        varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `DeleteDate`      datetime                                 DEFAULT NULL,
    PRIMARY KEY (`LocationGroupID`),
    KEY `FK_LG_Merc` (`MerchantID`),
    KEY `FK_LG_LS` (`StatusID`),
    CONSTRAINT `FK_LG_LS` FOREIGN KEY (`StatusID`) REFERENCES `LocationGroupStatus` (`StatusID`),
    CONSTRAINT `FK_LG_Merc` FOREIGN KEY (`MerchantID`) REFERENCES `Merchant` (`MerchantID`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS location.`LocationGroupMember`;

CREATE TABLE location.`LocationGroupMember`
(
    `LocationGroupMemberID` char(36)                        NOT NULL,
    `LocationGroupID`       char(36)                        NOT NULL,
    `LocationID`            char(36)                        NOT NULL,
    `Priority`              int(11)                         NOT NULL,
    `CreatedBy`             varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`            datetime                        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`              varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `UpdateDate`            datetime                                 DEFAULT NULL,
    `DeleteBy`              varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `DeleteDate`            datetime                                 DEFAULT NULL,
    PRIMARY KEY (`LocationGroupMemberID`),
    UNIQUE KEY `UKLGM_Priority` (`LocationGroupID`, `LocationID`, `Priority`),
    KEY `FK_LOGM_LOC` (`LocationID`),
    CONSTRAINT `FK_LG_LOGM` FOREIGN KEY (`LocationGroupID`) REFERENCES `LocationGroup` (`LocationGroupID`),
    CONSTRAINT `FK_LOGM_LOC` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS location.`LocationGroupStatus`;

CREATE TABLE location.`LocationGroupStatus`
(
    `StatusID`   char(36)                        NOT NULL,
    `Status`     varchar(50)                     NOT NULL,
    `CreatedBy`  varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate` datetime                        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`   varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `UpdateDate` datetime                                 DEFAULT NULL,
    `DeleteBy`   varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `DeleteDate` datetime                                 DEFAULT NULL,
    PRIMARY KEY (`StatusID`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS location.`LocationHour`;

CREATE TABLE location.`LocationHour`
(
    `LocationHourID` char(36)                        NOT NULL DEFAULT '',
    `LocationID`     char(36)                        NOT NULL DEFAULT '',
    `SundayHours`    varchar(100) CHARACTER SET utf8          DEFAULT NULL,
    `MondayHours`    varchar(100) CHARACTER SET utf8          DEFAULT NULL,
    `TuesdayHours`   varchar(100)                             DEFAULT NULL,
    `WednesdayHours` varchar(100)                             DEFAULT NULL,
    `ThursdayHours`  varchar(100)                             DEFAULT NULL,
    `FridayHours`    varchar(100)                             DEFAULT NULL,
    `SaturdayHours`  varchar(100)                             DEFAULT NULL,
    `CreateBy`       varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`     datetime                        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`       varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `UpdateDate`     datetime                                 DEFAULT NULL,
    `DeleteBy`       varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `DeleteDate`     datetime                                 DEFAULT NULL,
    PRIMARY KEY (`LocationHourID`),
    UNIQUE KEY `LocationID` (`LocationID`),
    CONSTRAINT `LocationHourID` FOREIGN KEY (`LocationID`) REFERENCES `Location` (`LocationID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS location.`LocationType`;

CREATE TABLE location.`LocationType`
(
    `LocationTypeID`   char(36)                        NOT NULL DEFAULT '',
    `LocationTypeCode` varchar(120) CHARACTER SET utf8 NOT NULL,
    `TypeDesc`         varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `CreateBy`         varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`       datetime                        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`         varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `UpdateDate`       datetime                                 DEFAULT NULL,
    `DeleteBy`         varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `DeleteDate`       datetime                                 DEFAULT NULL,
    PRIMARY KEY (`LocationTypeID`),
    UNIQUE KEY `Unique` (`LocationTypeCode`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel.`Merchant`;

CREATE TABLE omnichannel.`Merchant`
(
    `MerchantID`   char(36)                        NOT NULL DEFAULT '',
    `OwnerUserID`  char(36)                        NOT NULL,
    `MerchantName` varchar(255) CHARACTER SET utf8 NOT NULL,
    `MerchantCode` varchar(64) CHARACTER SET utf8  NOT NULL,
    `Region`       varchar(100) CHARACTER SET utf8 NOT NULL,
    `AppLogo`      varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `CreateBy`     varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`   datetime                        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`     varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `UpdateDate`   datetime                                 DEFAULT NULL,
    `DeleteBy`     varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `DeleteDate`   datetime                                 DEFAULT NULL,
    PRIMARY KEY (`MerchantID`),
    UNIQUE KEY `UKMerchantCode` (`MerchantCode`),
    KEY `FKOwnerID_UserID` (`OwnerUserID`),
    CONSTRAINT `FKOwnerID_UserID` FOREIGN KEY (`OwnerUserID`) REFERENCES `User` (`UserID`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel.`MerchantSetting`;

CREATE TABLE omnichannel.`MerchantSetting`
(
    `MerchantSettingID` char(36)     NOT NULL,
    `MerchantID`        char(36)     NOT NULL,
    `Key`               varchar(255) DEFAULT 'NULL',
    `Value`             longtext     NOT NULL,
    `CreateBy`          varchar(255) NOT NULL,
    `CreateDate`        datetime     NOT NULL,
    `UpdateBy`          varchar(255) DEFAULT NULL,
    `UpdateDate`        datetime     DEFAULT NULL,
    `DeleteBy`          varchar(255) DEFAULT NULL,
    `DeleteDate`        datetime     DEFAULT NULL,
    PRIMARY KEY (`MerchantSettingID`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel.`MerchantApiKey`;

CREATE TABLE omnichannel.`MerchantApiKey`
(
    `MerchantApiKeyid` char(36)     NOT NULL DEFAULT '',
    `MerchantId`       char(36)     NOT NULL DEFAULT '',
    `ApiKey`           varchar(100) NOT NULL DEFAULT '',
    `CreateBy`         varchar(255) NOT NULL,
    `CreateDate`       datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`         varchar(255)          DEFAULT NULL,
    `UpdateDate`       datetime              DEFAULT NULL,
    `DeleteBy`         varchar(255)          DEFAULT NULL,
    `DeleteDate`       datetime              DEFAULT NULL,
    PRIMARY KEY (`MerchantApiKeyid`),
    KEY `FKMerchAPI_Merch` (`MerchantId`),
    CONSTRAINT `FKMerchAPI_Merch` FOREIGN KEY (`MerchantId`) REFERENCES `Merchant` (`MerchantID`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel.`MerchantEngageApp`;

CREATE TABLE omnichannel.`MerchantEngageApp`
(
    `MerchantEngageAppId` char(36)     NOT NULL DEFAULT '',
    `MerchantId`          char(36)     NOT NULL DEFAULT '',
    `ShopNumber`          varchar(36)  NOT NULL DEFAULT '',
    `CreateBy`            varchar(255) NOT NULL DEFAULT '',
    `CreateDate`          datetime     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`            varchar(255)          DEFAULT NULL,
    `UpdateDate`          datetime              DEFAULT NULL,
    `DeleteBy`            varchar(255)          DEFAULT NULL,
    `DeleteDate`          datetime              DEFAULT NULL,
    PRIMARY KEY (`MerchantEngageAppId`),
    KEY `FK_MERCHANTID` (`MerchantId`),
    CONSTRAINT `FK_MERCHANTID` FOREIGN KEY (`MerchantId`) REFERENCES `Merchant` (`MerchantID`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel.`MerchantEntitlement`;

CREATE TABLE omnichannel.`MerchantEntitlement`
(
    `EntitlementID` char(36)                                         NOT NULL,
    `MerchantID`    char(36)                                         NOT NULL,
    `ProductType`   enum ('ConsURVrApp','Clienteling','Fulfillment') NOT NULL,
    `Status`        enum ('Active','Deactive')                       NOT NULL,
    `CreateDate`    datetime                                         NOT NULL,
    `CreateBy`      varchar(255)                                     NOT NULL,
    `UpdateDate`    datetime     DEFAULT NULL,
    `UpdateBy`      varchar(255) DEFAULT NULL,
    `DeleteDate`    datetime     DEFAULT NULL,
    `DeleteBy`      varchar(255) DEFAULT NULL,
    PRIMARY KEY (`EntitlementID`),
    UNIQUE KEY `MerchantID_2` (`MerchantID`, `ProductType`),
    KEY `UKMerchantIDType` (`MerchantID`, `ProductType`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel.`MerchantPartner`;

CREATE TABLE omnichannel.`MerchantPartner`
(
    `MerchantPartnerID` char(36)                        NOT NULL,
    `MerchantID`        char(36)                        NOT NULL,
    `PartnerName`       varchar(255)                    NOT NULL,
    `PartnerURL`        varchar(255)                             DEFAULT NULL,
    `PartnerLogo`       varchar(255)                             DEFAULT NULL,
    `PartnerPhone`      varchar(50)                              DEFAULT NULL,
    `CreateBy`          varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`        datetime                        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`          varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `UpdateDate`        datetime                                 DEFAULT NULL,
    `DeleteBy`          varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `DeleteDate`        datetime                                 DEFAULT NULL,
    PRIMARY KEY (`MerchantPartnerID`),
    KEY `FkMercPartnerMerc_idx` (`MerchantID`),
    CONSTRAINT `FkMercPartnerMerc` FOREIGN KEY (`MerchantID`) REFERENCES `Merchant` (`MerchantID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel.`User`;

CREATE TABLE omnichannel.`User`
(
    `UserID`       char(36)                        NOT NULL DEFAULT '',
    `UserEmail`    varchar(255) CHARACTER SET utf8 NOT NULL,
    `FirstName`    varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `LastName`     varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `ProfileImage` varchar(255)                             DEFAULT NULL,
    `CreateBy`     varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`   datetime                        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`     varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `UpdateDate`   datetime                                 DEFAULT NULL,
    `DeleteBy`     varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `DeleteDate`   datetime                                 DEFAULT NULL,
    `UserCode`     varchar(64) CHARACTER SET utf8  NOT NULL,
    PRIMARY KEY (`UserID`),
    UNIQUE KEY `UKUserCode` (`UserCode`),
    UNIQUE KEY `UserEmail` (`UserEmail`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel.`UserRole`;

CREATE TABLE omnichannel.`UserRole`
(
    `UserRoleID` char(36)                        NOT NULL DEFAULT '',
    `UserID`     char(36)                        NOT NULL DEFAULT '',
    `MerchantID` char(36)                        NOT NULL DEFAULT '',
    `RoleID`     char(36)                        NOT NULL DEFAULT '',
    `LocationID` char(36)                        NOT NULL DEFAULT '',
    `CreateBy`   varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate` datetime                        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`   varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `UpdateDate` datetime                                 DEFAULT NULL,
    `DeleteBy`   varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `DeleteDate` datetime                                 DEFAULT NULL,
    PRIMARY KEY (`UserRoleID`),
    KEY `FKMerchantID_UR` (`MerchantID`),
    KEY `UserID_LocationID` (`UserID`, `LocationID`),
    CONSTRAINT `FKMerchantID_UR` FOREIGN KEY (`MerchantID`) REFERENCES `Merchant` (`MerchantID`),
    CONSTRAINT `FKUserRoleID_UserID` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel_auth.`Client`;

CREATE TABLE omnichannel_auth.`Client`
(
    `ClientId`             int(11)      NOT NULL AUTO_INCREMENT,
    `Name`                 varchar(100) NOT NULL,
    `Secret`               varchar(100) NOT NULL,
    `GrantTypes`           varchar(100) NOT NULL,
    `UserId`               int(11)                         DEFAULT NULL,
    `AccessTokenLifetime`  int(10)      NOT NULL,
    `RefreshTokenLifetime` int(10)      NOT NULL,
    `ApplicationType`      enum ('admin','relate&deliver') DEFAULT NULL,
    PRIMARY KEY (`ClientId`),
    UNIQUE KEY `Name` (`Name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS omnichannel_auth.`RefreshToken`;

CREATE TABLE omnichannel_auth.`RefreshToken`
(
    `RefreshTokenId` int(11)     NOT NULL AUTO_INCREMENT,
    `Token`          varchar(50) NOT NULL DEFAULT '',
    `Expires`        datetime    NOT NULL,
    `ClientId`       int(11)     NOT NULL,
    `UserId`         char(36)    NOT NULL DEFAULT '',
    `CreateDate`     datetime             DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`RefreshTokenId`),
    UNIQUE KEY `token` (`Token`),
    KEY `expires` (`Expires`),
    KEY `clientId` (`ClientId`),
    CONSTRAINT `RefreshToken_ibfk_1` FOREIGN KEY (`ClientId`) REFERENCES `Client` (`ClientId`) ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

DROP TABLE IF EXISTS omnichannel_user.`Permission`;
CREATE TABLE omnichannel_user.`Permission`
(
    `PermissionID`     char(36)                                NOT NULL DEFAULT '',
    `PermissionStatus` enum ('active','inactive','deleted')    NOT NULL DEFAULT 'active',
    `PermissionCode`   varchar(10)                             NOT NULL DEFAULT '',
    `Application`      enum ('admin','relate','deliver','api') NOT NULL,
    `Module`           varchar(100)                                     DEFAULT '',
    `Submodule`        varchar(100)                                     DEFAULT '',
    `Function`         enum ('view','edit','create','delete')           DEFAULT NULL,
    `CreateBy`         varchar(255) CHARACTER SET utf8         NOT NULL,
    `CreateDate`       datetime                                NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`         varchar(255) CHARACTER SET utf8                  DEFAULT NULL,
    `UpdateDate`       datetime                                         DEFAULT NULL,
    `DeleteBy`         varchar(255) CHARACTER SET utf8                  DEFAULT NULL,
    `DeleteDate`       datetime                                         DEFAULT NULL,
    PRIMARY KEY (`PermissionID`),
    UNIQUE KEY `UNQ_Func` (`Application`, `Module`, `Submodule`, `Function`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel_user.`PermissionMapping`;
CREATE TABLE omnichannel_user.`PermissionMapping`
(
    `PermissionMappingId`   char(36) NOT NULL DEFAULT '',
    `PermissionId`          char(36) NOT NULL DEFAULT '',
    `DependingPermissionId` char(36) NOT NULL DEFAULT '',
    `CreateBy`              varchar(255)      DEFAULT NULL,
    `CreateDate`            datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`              varchar(255)      DEFAULT NULL,
    `UpdateDate`            datetime          DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `DeleteBy`              varchar(255)      DEFAULT '',
    `DeleteDate`            datetime          DEFAULT NULL,
    PRIMARY KEY (`PermissionMappingId`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel_user.`Role`;
CREATE TABLE omnichannel_user.`Role`
(
    `RoleID`          char(36)                             NOT NULL DEFAULT '',
    `MerchantID`      char(36)                                      DEFAULT '',
    `RoleCode`        varchar(100) CHARACTER SET utf8      NOT NULL,
    `RoleName`        varchar(255) CHARACTER SET utf8      NOT NULL,
    `RoleStatus`      enum ('active','inactive','deleted') NOT NULL DEFAULT 'active',
    `ApplicationType` enum ('admin','relate&deliver')      NOT NULL,
    `CreateBy`        varchar(255) CHARACTER SET utf8      NOT NULL,
    `CreateDate`      datetime                             NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`        varchar(255) CHARACTER SET utf8               DEFAULT NULL,
    `UpdateDate`      datetime                                      DEFAULT NULL,
    `DeleteBy`        varchar(255) CHARACTER SET utf8               DEFAULT NULL,
    `DeleteDate`      datetime                                      DEFAULT NULL,
    PRIMARY KEY (`RoleID`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel_user.`RolePermission`;

CREATE TABLE omnichannel_user.`RolePermission`
(
    `RolePermissionID` char(36)                        NOT NULL DEFAULT '',
    `RoleID`           char(36)                        NOT NULL DEFAULT '',
    `PermissionID`     char(36)                        NOT NULL DEFAULT '',
    `CreateBy`         varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`       datetime                        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`         varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `UpdateDate`       datetime                                 DEFAULT NULL,
    `DeleteBy`         varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `DeleteDate`       datetime                                 DEFAULT NULL,
    PRIMARY KEY (`RolePermissionID`, `RoleID`, `PermissionID`),
    KEY `PermissionID_idx` (`PermissionID`),
    KEY `RoleID_idx` (`RoleID`),
    CONSTRAINT `FK_Permission` FOREIGN KEY (`PermissionID`) REFERENCES omnichannel_user.`Permission` (`PermissionID`),
    CONSTRAINT `FK_Role` FOREIGN KEY (`RoleID`) REFERENCES `Role` (`RoleID`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel_user.`User`;

CREATE TABLE omnichannel_user.`User`
(
    `UserID`         char(36)                        NOT NULL DEFAULT '',
    `UserEmail`      varchar(255) CHARACTER SET utf8 NOT NULL,
    `UserPassword`   varchar(100) CHARACTER SET utf8          DEFAULT NULL,
    `UserStatus`     enum ('active', 'inactive')     not null DEFAULT 'active',
    `FirstName`      varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `LastName`       varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `ProfileImage`   varchar(255)                             DEFAULT NULL,
    `Token`          varchar(255)                             DEFAULT NULL,
    `TokenExpiresAt` dateTime                                 DEFAULT NULL,
    `CreateBy`       varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`     datetime                        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`       varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `UpdateDate`     datetime                                 DEFAULT NULL,
    `DeleteBy`       varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `DeleteDate`     datetime                                 DEFAULT NULL,
    PRIMARY KEY (`UserID`),
    UNIQUE KEY `UserEmail` (`UserEmail`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel_user.`UserRole`;

CREATE TABLE omnichannel_user.`UserRole`
(
    `UserRoleID`  char(36)                        NOT NULL DEFAULT '',
    `UserID`      char(36)                        NOT NULL DEFAULT '',
    `MerchantID`  char(36)                        NOT NULL DEFAULT '',
    `RoleID`      char(36)                        NOT NULL DEFAULT '',
    `ContextType` varchar(255)                             DEFAULT NULL,
    `ContextId`   varchar(255)                             DEFAULT NULL,
    `CreateBy`    varchar(255) CHARACTER SET utf8 NOT NULL,
    `CreateDate`  datetime                        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`    varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `UpdateDate`  datetime                                 DEFAULT NULL,
    `DeleteBy`    varchar(255) CHARACTER SET utf8          DEFAULT NULL,
    `DeleteDate`  datetime                                 DEFAULT NULL,
    PRIMARY KEY (`UserRoleID`),
    KEY `UserID_LocationID` (`UserID`, `ContextType`),
    KEY `FKRoleID` (`RoleID`),
    CONSTRAINT `FKRoleID` FOREIGN KEY (`RoleID`) REFERENCES `Role` (`RoleID`),
    CONSTRAINT `FKUserRoleID_UserID` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

DROP TABLE IF EXISTS omnichannel_user.`UserMerchant`;

CREATE TABLE omnichannel_user.`UserMerchant`
(
    `UserMerchantID` char(36)                             NOT NULL DEFAULT '',
    `UserID`         char(36)                             NOT NULL DEFAULT '',
    `MerchantID`     char(36)                             NOT NULL DEFAULT '',
    `Status`         enum ('active','inactive','deleted') NOT NULL DEFAULT 'active',
    `LastLoginDate`  datetime                                      DEFAULT NULL,
    `CreateBy`       varchar(255) CHARACTER SET utf8      NOT NULL,
    `CreateDate`     datetime                             NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `UpdateBy`       varchar(255) CHARACTER SET utf8               DEFAULT NULL,
    `UpdateDate`     datetime                                      DEFAULT NULL,
    `DeleteBy`       varchar(255) CHARACTER SET utf8               DEFAULT NULL,
    `DeleteDate`     datetime                                      DEFAULT NULL,
    PRIMARY KEY (`UserMerchantID`),
    KEY `UserID_LocationID` (`UserID`, `Status`),
    CONSTRAINT `UserMerchant_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `User` (`UserID`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


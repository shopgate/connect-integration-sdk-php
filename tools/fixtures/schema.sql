SET GLOBAL sql_mode = 'NO_ENGINE_SUBSTITUTION';
SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS catalog;
DROP DATABASE IF EXISTS location;
DROP DATABASE IF EXISTS merchant;
DROP DATABASE IF EXISTS customer;
DROP DATABASE IF EXISTS import;
DROP DATABASE IF EXISTS webhook;
DROP DATABASE IF EXISTS omnichannel_order;
DROP DATABASE IF EXISTS omnichannel_user;
DROP DATABASE IF EXISTS omnichannel;
DROP DATABASE IF EXISTS omnichannel_auth;

CREATE DATABASE catalog;
CREATE DATABASE merchant;
CREATE DATABASE location;
CREATE DATABASE omnichannel;
CREATE DATABASE omnichannel_auth;
CREATE DATABASE import;
CREATE DATABASE omnichannel_order;
CREATE DATABASE omnichannel_user;
CREATE DATABASE webhook;

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

# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

### Added
- getProductDescriptions direct logic
- addInventory direct logic
- updateInventory direct logic
- deleteInventory direct logic
- bulkImport for inventory

## [0.5.0]- 2019-06-28
### Added
- bulkImport for attributes
- bulkImport for categories
- bulkImport for products

## [0.4.0]- 2019-06-13
### Added
- getProduct direct logic
- getCategory direct logic
- updateAttribute event & direct logic
- createAttribute event & direct logic
- deleteAttribute event & direct logic
- updateAttributeValue event & direct logic
- createAttributeValue event & direct logic
- deleteAttributeValue event & direct logic
- own client exceptions

### Changed
- SDK structure
- DTO structure

## [0.3.0]- 2019-06-06
### Added
- oauth2 authentication for endpoint calls
- encrypted token saving to a file

### Changed
- client SDK configuration to be more flat

## [0.2.1]- 2019-05-23
### Changed
- symphony option-resolver version to be compatible with Mage 2.3+

## [0.2.0] - 2019-05-23
### Added
- updateProduct event & direct logic
- createProduct event & direct logic
- deleteProduct event & direct logic
- getProduct direct logic

### Fixed
- direct call endpoint
- most Guzzle HTTP options are now allowed

## 0.1.0 - 2019-05-10
### Added
- updateCategory event & direct logic
- createCategory event & direct logic
- deleteCategory event & direct logic

[Unreleased]: https://github.com/shopgate/cart-integration-sdk-php/compare/0.5.0...HEAD
[0.5.0]: https://github.com/shopgate/cart-integration-sdk-php/compare/0.4.0...0.5.0
[0.4.0]: https://github.com/shopgate/cart-integration-sdk-php/compare/0.3.0...0.4.0
[0.3.0]: https://github.com/shopgate/cart-integration-sdk-php/compare/0.2.1...0.3.0
[0.2.1]: https://github.com/shopgate/cart-integration-sdk-php/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/shopgate/cart-integration-sdk-php/compare/0.1.0...0.2.0

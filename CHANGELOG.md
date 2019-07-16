# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Changed
- localized category url
- localized category image

### Added
- addNotes direct call
- getNotes direct call
- getAttributes
- getAttribute
- addAttributes direct call
- updateAttribute direct call
- deleteAttribute direct call
- addAttributeValue direct call
- updateAttributeValue direct call
- deleteAttributeValue direct call
- getCustomers
- getCustomer
- addCustomers direct call
- updateCustomer direct call
- deleteCustomer direct call
- addContacts direct call
- updateContact direct call
- deleteContact direct call
- getProductDescriptions direct call
- addInventory direct call
- updateInventory direct call
- deleteInventory direct call
- bulkImport for inventory

## [0.5.0]- 2019-06-28
### Added
- bulkImport for attributes
- bulkImport for categories
- bulkImport for products

## [0.4.0]- 2019-06-13
### Added
- getProduct direct call
- getCategory direct call
- updateAttribute event & direct call
- createAttribute event & direct call
- deleteAttribute event & direct call
- updateAttributeValue event & direct call
- createAttributeValue event & direct call
- deleteAttributeValue event & direct call
- SDK client exceptions

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
- updateProduct event & direct call
- createProduct event & direct call
- deleteProduct event & direct call
- getProduct direct call

### Fixed
- direct call endpoint
- most Guzzle HTTP options are now allowed

## 0.1.0 - 2019-05-10
### Added
- updateCategory event & direct call
- createCategory event & direct call
- deleteCategory event & direct call

[Unreleased]: https://github.com/shopgate/cart-integration-sdk-php/compare/0.5.0...HEAD
[0.5.0]: https://github.com/shopgate/cart-integration-sdk-php/compare/0.4.0...0.5.0
[0.4.0]: https://github.com/shopgate/cart-integration-sdk-php/compare/0.3.0...0.4.0
[0.3.0]: https://github.com/shopgate/cart-integration-sdk-php/compare/0.2.1...0.3.0
[0.2.1]: https://github.com/shopgate/cart-integration-sdk-php/compare/0.2.0...0.2.1
[0.2.0]: https://github.com/shopgate/cart-integration-sdk-php/compare/0.1.0...0.2.0

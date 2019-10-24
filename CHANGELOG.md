# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) and this project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Changed
- Updated location DTOs to include isComingSoon property

## [1.1.1] - 2019-10-18
### Added
- bulkImport for customer
- support for the order analytic endpoints

### Changed
- Updated fulfillment order DTO to reflect the addition of heldUntil and pickupReminderApplicableAt properties

## [1.1.0] - 2019-10-16
### Added
- addCatalog direct call
- addParentCatalog direct call
- updateCatalog direct call
- getCatalog
- getCatalogs
- webhook services added
- webhook create, get, getList, webhook event DTOs
- webhook unit and integration tests
- SimpleFulfillmentOrder - add property fulfillmentOrderAddress
- Order - add property updateDate
- LineItem - add property options

### Changed
- SimpleFulfillmentOrder - rename property orderSubmittedDate to submitDate
- SimpleFulfillmentOrder - rename property acceptedDate to acceptDate
- SimpleFulfillmentOrder - rename property completedDate to completeDate
- Order - remove property acceptDate
- FulfillmentPackage - rename property fulfilledDate to fulfillmentDate

## [1.0.0] - 2019-10-02
### Added
- getFulfillmentOrders direct call

### Changed
- catalogCode is no longer needed when creating an inventory feed

## [0.9.0] - 2019-09-13
### Added
- catalogCode in payload of events

## [0.8.0] - 2019-09-10
### Added
- getFulfillmentOrder direct call

### Fixed
- an error related to not throwing exceptions we got from the api

## [0.7.0] - 2019-09-03
### Changed
- switch to the new .io service endpoints
- handle the new auth methods with username and password

## [0.6.0] - 2019-08-23
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
- getLocations direct call
- getLocation direct call
- addLocations direct call
- updateLocation direct call
- deleteLocation direct call
- bulkImport for inventory
- addWishlists direct call
- updateWishlist direct call
- deleteWishlist direct call
- getWishlists
- getWishlist
- addWishlistItems direct call
- deleteWishlistItem direct call
- addOrders direct call
- getOrders
- getOrder
- addReservations direct call
- updateReservations direct call
- deleteReservations direct call
- getReservation
- getReservations

## [0.5.0] - 2019-06-28
### Added
- bulkImport for attributes
- bulkImport for categories
- bulkImport for products

## [0.4.0] - 2019-06-13
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

## [0.3.0] - 2019-06-06
### Added
- oauth2 authentication for endpoint calls
- encrypted token saving to a file

### Changed
- client SDK configuration to be more flat

## [0.2.1] - 2019-05-23
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

## [0.1.0] - 2019-05-10
### Added
- updateCategory event & direct call
- createCategory event & direct call
- deleteCategory event & direct call

[Unreleased]: https://github.com/shopgate/connect-integration-sdk/compare/v1.1.1...HEAD
[1.1.1]: https://github.com/shopgate/connect-integration-sdk/compare/v1.1.0...v1.1.1
[1.1.0]: https://github.com/shopgate/connect-integration-sdk/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/shopgate/connect-integration-sdk/compare/v0.9.0...v1.0.0
[0.9.0]: https://github.com/shopgate/connect-integration-sdk/compare/v0.8.0...v0.9.0
[0.8.0]: https://github.com/shopgate/connect-integration-sdk/compare/v0.7.0...v0.8.0
[0.7.0]: https://github.com/shopgate/connect-integration-sdk/compare/v0.6.0...v0.7.0
[0.6.0]: https://github.com/shopgate/connect-integration-sdk/compare/v0.5.0...v0.6.0
[0.5.0]: https://github.com/shopgate/connect-integration-sdk/compare/v0.4.0...v0.5.0
[0.4.0]: https://github.com/shopgate/connect-integration-sdk/compare/v0.3.0...v0.4.0
[0.3.0]: https://github.com/shopgate/connect-integration-sdk/compare/v0.2.1...v0.3.0
[0.2.1]: https://github.com/shopgate/connect-integration-sdk/compare/v0.2.0...v0.2.1
[0.2.0]: https://github.com/shopgate/connect-integration-sdk/compare/v0.1.0...v0.2.0
[0.1.0]: https://github.com/shopgate/connect-integration-sdk/releases/v0.1.0

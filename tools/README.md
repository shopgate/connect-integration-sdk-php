# Internal Tools

This section refers to internal tools that help developers & maintainers of this project assuring code quality and
stability. Following documentation is meant for internal use.

## Table Of Contents
1. [Requirements](#requirements)
1. [Code Style](#code-style)
1. [Dependency Checking / Audit](#dependency-checking--audit)
1. [Unit Tests & Coverage](#unit-tests--coverage)
1. [Integration Tests](#integration-tests)
1. [Local Stack Of Shopgate Services](#local-stack-of-shopgate-services)

## Requirements
Following software components should be installed on your system for everything below to work:
* PHP >= 5.6 (>= 7.0 for dependency checking / audit)
* [Composer](https://getcomposer.org) >= 1.7.0 (1.8.x will work)
* [Docker](https://docs.docker.com/install/) >= 18.09
* [Docker Compose](https://docs.docker.com/compose/install/) >= 1.22
* [XDebug](https://xdebug.org) >= 2.6.0

## Code Style
To check or fix the code style use one of the following composer scripts:
* `composer cs-fixer-dry` to check the code style
* `composer cs-fixer` to check and auto-fix the code style

## Dependency Checking / Audit
To check for vulnerabilities in the project's dependencies run:

    composer audit

Vulnerable packages can be ignored by adding a line with the **composer package name** to the `audit-exclusions` file:

    guzzlehttp/guzzle
    phpunit/phpunit

## Unit Tests & Coverage
Unit tests with or without coverage can be run by executing one of the following composer scripts:

* `composer unit-tests` for just running the tests without coverage report
* `composer cover-clover` for an XML coverage report that can be interpreted by other tools (e.g. coveralls)
* `composer cover-text` for a human readable text summary displayed below test results
* `composer cover-html` for an HTML coverage report that visualizes (un)covered files & lines

The XML coverage report is located in `build/clover.xml`. The HTML coverage report can be viewed  by opening
`build/coverage-html/index.html` in your browser.

The `composer coveralls` command is meant for CI usage only, it should not be used locally.

## Integration Tests
### On Local Stack
To run the integration tests on the local stack, log in to ECR, then:

    composer start
    composer integration-tests

### On Your Machine
Running tests on your machine directly offers debugging options that are currently not supported when running within
them in the local stack. The down-side is you have to have PHP and all required extensions installed locally.

**Note:** Tests will still run against the local stack, so it has to be boot up either way.

#### Prerequisites
Create the file `tests/Integration/.env` and put the following into it:

    oauthBaseUri="http://auth.shopgatedev.services:8080/oauth/token"
    oauthStoragePath="./access_token.txt"
    baseUri="http://{service}.shopgatedev.services:8080/v{ver}/merchants/{merchantCode}/"
    clientId="integration-tests"
    clientSecret="integration-tests"
    merchantCode="TM2"
    env="dev"

In your `/etc/hosts` file (usually `C:\Windows\System32\Drivers\etc\hosts` on Windows) add the following entries:

    127.0.0.1       auth.shopgatedev.services
    127.0.0.1       catalog.shopgatedev.services
    127.0.0.1       omni-event-receiver.shopgatedev.services

Log in to ECR and boot up the local stack using ```composer start```.

#### Run Tests
Execute the tests in you preferred IDE or by executing ```composer integration-tests-local```.

## Local Stack Of Shopgate Services
### ECR Login
On the first boot-up or upon updates you'll need to log in to ECR in your preferred CLI.

### Composer Commands
Composer commands are available for the most commonly used actions:
* ```composer unit-tests``` - run unit tests
* ```composer integration-tests-local``` run integration tests - needs setup, see below
* ```composer start``` - boot up the local stack
* ```composer ps``` - show a list of all services and their current status
* ```composer logs [service name]``` - show (and follow) logs of the specified service
* ```composer reset-db``` - reset the database within the local stack
* ```composer integration-tests``` - run integration tests within the local stack
* ```composer shutdown``` - shut down the local stack

### Inspecting the Stack
The stack configuration and all fixtures are located in the `tools` folder. Use `docker-compose` to view logs or open
a shell within a container, e.g.:

* ```cd tools```
* ```docker-compose exec php bash```
* ```docker-compose exec mysql -u root -p```
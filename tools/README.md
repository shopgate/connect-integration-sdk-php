# Integration Tests & Local Stack

Booting up the local service stack and execution of integration tests is restricted to Shopgate employees. This
documentation is meant for internal use.

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

## Local Stack Options
### ECR Login
On the first boot-up or upon updates you'll need to log in to ECR in you preferred CLI.

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
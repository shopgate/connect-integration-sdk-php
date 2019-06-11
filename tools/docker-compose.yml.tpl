version: '2.1'
networks:
  default:
    external:
      name: sdk-integration-network
services:

### PHP container for local testing
  php:
    build:
      context: ./dockerfiles
      dockerfile: Php7
    environment:
      - ETCD_HOST=http://etcd:2379
      - PUBSUB_EMULATOR_HOST=googlepubsub-emulator:8085

    volumes:
      - ..:/sdk
    tty: true

### infra-structure
  etcd:
    image: elcolio/etcd
    restart: always
    ports:
      - 2379:2379
    environment:
      - 'ETCD_ADVERTISE_CLIENT_URLS=http://etcd:2379,http://etcd:4001'

  mysql:
    image: mysql:5.7
    environment:
      - MYSQL_ROOT_PASSWORD=secret
    %MYSQL_PORTS%

  auth:
    image: 602824140852.dkr.ecr.us-east-1.amazonaws.com/development/service/auth2:latest
    restart: always
    links:
      - etcd
    environment:
      - NODE_ENV=development
      - APP_PORT=80
      - MANAGEMENT_PORT=81
      - LOG_TO_SYSLOG=false
    networks:
      default:
        aliases:
          - auth.shopgatedev.services
          - omni-merchant.shopgatedev.services
          - omni-location.shopgatedev.services

  googlepubsub-emulator:
    build:
      context: ./dockerfiles
      dockerfile: GooglePubsub
    ports:
      - 8085:8085

### services
  omni-worker:
    image: 602824140852.dkr.ecr.us-east-1.amazonaws.com/development/service/omni/worker:v1.0.0-beta.10c
    links:
      - etcd
    environment:
      - NODE_ENV=development
      - APP_PORT=80
      - LOG_TO_SYSLOG=false
      - PUBSUB_EMULATOR_HOST=googlepubsub-emulator:8085

  omni-event-receiver:
    image: 602824140852.dkr.ecr.us-east-1.amazonaws.com/development/service/omni/event-receiver:v1.0.0-beta.11
    restart: always
    links:
      - etcd
    environment:
      - NODE_ENV=development
      - APP_PORT=80
      - LOG_TO_SYSLOG=false
      - PUBSUB_EMULATOR_HOST=googlepubsub-emulator:8085

  omni-merchant:
    image: 602824140852.dkr.ecr.us-east-1.amazonaws.com/service/omni/merchant:v1.0.0-beta.13
    links:
      - etcd
    environment:
      - NODE_ENV=development
      - APP_PORT=80
      - LOG_TO_SYSLOG=false

  omni-location:
    image: 602824140852.dkr.ecr.us-east-1.amazonaws.com/service/omni/location:v1.0.0-alpha.32
    links:
      - etcd
    environment:
      - NODE_ENV=development
      - APP_PORT=80
      - LOG_TO_SYSLOG=false

  catalog:
    image: 602824140852.dkr.ecr.us-east-1.amazonaws.com/service/catalog:v1.0.0-alpha.58-development
    restart: always
    links:
      - etcd
    ports:
      - 8080:80
    environment:
      - NODE_ENV=development
      - APP_PORT=80
      - LOG_TO_SYSLOG=false
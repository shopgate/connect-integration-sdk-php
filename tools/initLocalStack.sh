#!/usr/bin/env bash

function retry {
    echo "Waiting for $1"
    retry=0
    maxRetries=60
    retryInterval=1
    until [ ${retry} -ge ${maxRetries} ]
    do
        sh -c "$2" && break
        retry=$[${retry}+1]
        retryInterval=$[${retryInterval}]
        echo "Retrying [${retry}/${maxRetries}] in ${retryInterval}s"
        sleep ${retryInterval}
    done
    if [ ${retry} -ge ${maxRetries} ]; then
        echo "$1 failed after ${maxRetries} attempts!"
        exit 1
    fi
    echo "$1 ready"
}

BUILD="build\: \."
pipe="> docker-compose.yml"
DOCKER_COMPOSE_FILES="-f docker-compose.yml"
if [ -n "$TEST_IMAGE" ]; then
    TEST_IMAGE=${TEST_IMAGE//:/\\:}
    BUILD="image\: ${TEST_IMAGE}"
    DOCKER_COMPOSE_FILES="-f docker-compose.yml"
fi

if [[ ! -z "$EXPOSED_PORT_MYSQL" ]]; then
    sh -c "sed -e \"s:%MYSQL_PORTS%:ports\:\n      - \$\{EXPOSED_PORT_MYSQL\:-3306\}\:3306:g\" docker-compose.yml.tpl > docker-compose.yml.tmp"
else
    sh -c "sed -e \"s:%MYSQL_PORTS%::g\" docker-compose.yml.tpl > docker-compose.yml.tmp"
fi

sh -c "sed -e \"s:%BUILD%:$BUILD:g\" docker-compose.yml.tmp $pipe"

export SERVICE=sdk

docker network create ${SERVICE}-integration-network

set -e
docker-compose $DOCKER_COMPOSE_FILES up -d php
docker-compose $DOCKER_COMPOSE_FILES up -d mysql

docker-compose $DOCKER_COMPOSE_FILES up -d etcd
docker-compose $DOCKER_COMPOSE_FILES up -d googlepubsub-emulator

docker-compose exec -T php cp -R /vendor /sdk
docker-compose exec -T php ls -al /sdk
docker-compose exec -T php composer update
docker-compose exec -T php php ./tools/pubsubfiller.php
docker-compose exec -T php php ./tools/etcdfiller.php

docker-compose $DOCKER_COMPOSE_FILES build
retry "MySQL" "docker-compose exec -T mysql mysql -uroot -psecret -e \"select 1 from dual\" 2>&1"

docker-compose exec -T mysql mysql -u root -psecret < ./fixtures/schema.sql

docker-compose $DOCKER_COMPOSE_FILES up -d omni-event-receiver
retry "EventReceiver" "docker-compose exec -T omni-event-receiver curl http://localhost/health -o /dev/null 2>&1"

docker-compose $DOCKER_COMPOSE_FILES up -d catalog
retry "CatalogService" "docker-compose exec -T catalog curl http://localhost/health -o /dev/null 2>&1"

docker-compose $DOCKER_COMPOSE_FILES up -d

retry "AuthService" "docker-compose exec -T auth curl http://localhost/health -o /dev/null 2>&1"
retry "MerchantService" "docker-compose exec -T omni-merchant curl http://localhost/health -o /dev/null 2>&1"
retry "LocationService" "docker-compose exec -T omni-location curl http://localhost/health -o /dev/null 2>&1"

docker-compose exec -T mysql mysql -u root -psecret < ./fixtures/sampleData.sql
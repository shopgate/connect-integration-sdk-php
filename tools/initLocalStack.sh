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

CI_STACK=${CI_STACK:-}
DOCKER_COMPOSE_FILES="-f docker-compose.yml"
if [[ -z "$CI_STACK" ]]; then
    DOCKER_COMPOSE_FILES="-f docker-compose.yml -f docker-compose.dev.yml"
fi

export SERVICE=sdk

docker network create ${SERVICE}-integration-network

set -e

docker-compose $DOCKER_COMPOSE_FILES build php56

docker-compose $DOCKER_COMPOSE_FILES up -d php56
docker-compose $DOCKER_COMPOSE_FILES up -d mysql

docker-compose $DOCKER_COMPOSE_FILES up -d etcd
docker-compose $DOCKER_COMPOSE_FILES up -d googlepubsub-emulator

if [[ -n "$CI_STACK" ]]; then
    docker-compose $DOCKER_COMPOSE_FILES build php73
    docker-compose $DOCKER_COMPOSE_FILES up -d php73
fi

docker-compose exec -T php56 php ./tools/pubsubfiller.php
docker-compose exec -T php56 php ./tools/etcdfiller.php

retry "MySQL" "docker-compose exec -T mysql mysql -uroot -psecret -e \"select 1 from dual\" 2>&1"

docker-compose exec -T mysql mysql -u root -psecret < ./fixtures/schema.sql

docker-compose $DOCKER_COMPOSE_FILES up -d omni-event-receiver
retry "EventReceiver" "docker-compose exec -T omni-event-receiver curl http://localhost/health -o /dev/null 2>&1"

docker-compose stop catalog && docker-compose $DOCKER_COMPOSE_FILES up -d catalog
retry "CatalogService" "docker-compose exec -T catalog curl http://localhost/health -o /dev/null 2>&1"

docker-compose $DOCKER_COMPOSE_FILES up -d elasticsearch
retry "elasticsearch" "docker-compose exec -T elasticsearch curl http://localhost:9200/_cluster/health?wait_for_status=yellow 2>&1"

docker-compose $DOCKER_COMPOSE_FILES up -d mysql auth redis omni-worker omni-event-receiver omni-merchant omni-location s3 import omni-customer

retry "AuthService" "docker-compose exec -T auth curl http://localhost/health -o /dev/null 2>&1"
retry "MerchantService" "docker-compose exec -T omni-merchant curl http://localhost/health -o /dev/null 2>&1"
retry "LocationService" "docker-compose exec -T omni-location curl http://localhost/health -o /dev/null 2>&1"

retry "SampleData" "docker-compose exec -T mysql mysql -u root -psecret < ./fixtures/sampleData.sql 2>&1"

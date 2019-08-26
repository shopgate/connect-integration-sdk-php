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

DOCKER_PREFIX=${DOCKER_PREFIX:-}
CI_STACK=${CI_STACK:-}
DOCKER_COMPOSE_PARAMETERS="-f docker-compose.yml"
if [[ -z "$CI_STACK" ]]; then
    DOCKER_COMPOSE_PARAMETERS="-f docker-compose.yml -f docker-compose.dev.yml"
fi

export SERVICE=sdk

set -e

if [[ -n "$CI_STACK" ]]; then
    DOCKER_COMPOSE_PARAMETERS="${DOCKER_COMPOSE_PARAMETERS} -p ${DOCKER_PREFIX}"
    docker-compose $DOCKER_COMPOSE_PARAMETERS build --no-cache php56
    docker-compose $DOCKER_COMPOSE_PARAMETERS build --no-cache php73
    docker-compose $DOCKER_COMPOSE_PARAMETERS up -d --remove-orphans php73
else
    docker-compose $DOCKER_COMPOSE_PARAMETERS build php56
fi

docker-compose $DOCKER_COMPOSE_PARAMETERS up -d --remove-orphans php56
docker-compose $DOCKER_COMPOSE_PARAMETERS up -d mysql

docker-compose $DOCKER_COMPOSE_PARAMETERS up -d etcd
docker-compose $DOCKER_COMPOSE_PARAMETERS up -d googlepubsub-emulator

docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T php56 php ./tools/pubsubfiller.php
docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T php56 php ./tools/etcdfiller.php

docker-compose $DOCKER_COMPOSE_PARAMETERS build import-script
retry "MySQL" "docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T mysql mysql -uroot -psecret -e \"select 1 from dual\" 2>&1"

docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T mysql mysql -u root -psecret < ./fixtures/schema.sql

docker-compose $DOCKER_COMPOSE_PARAMETERS stop omni-customer catalog import import-script omni-order && docker-compose $DOCKER_COMPOSE_PARAMETERS up -d omni-customer catalog import import-script omni-order
retry "CustomerService" "docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T omni-customer curl http://localhost/health -o /dev/null 2>&1"
retry "CatalogService" "docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T catalog curl http://localhost/health -o /dev/null 2>&1"
retry "ImportService" "docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T import curl http://localhost/health -o /dev/null 2>&1"
retry "OrderService" "docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T omni-order curl http://localhost/health -o /dev/null 2>&1"

docker-compose $DOCKER_COMPOSE_PARAMETERS up -d omni-event-receiver
retry "EventReceiver" "docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T omni-event-receiver curl http://localhost/health -o /dev/null 2>&1"

docker-compose $DOCKER_COMPOSE_PARAMETERS up -d elasticsearch
retry "elasticsearch" "docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T elasticsearch curl http://localhost:9200/_cluster/health?wait_for_status=yellow 2>&1"

docker-compose $DOCKER_COMPOSE_PARAMETERS up -d mysql sqs omni-auth omni-user omni-worker omni-merchant omni-location s3

# add DE postalcodes
docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T mysql sh -c "apt-get update && apt-get install -y curl unzip && curl http://download.geonames.org/export/zip/DE.zip --output de.zip && unzip -o de.zip"
docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T mysql sh -c "echo \"LOAD DATA LOCAL INFILE 'DE.txt' INTO TABLE Postalcode (CountryCode,PostalCode,PlaceName,AdminName1,AdminCode1,AdminName2,AdminCode2,AdminName3,AdminCode3,Latitude,Longitude,Accuracy);\" | mysql  -u root -psecret location"

retry "UserService" "docker-compose exec -T omni-user curl http://localhost/health -o /dev/null 2>&1"
retry "AuthService" "docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T omni-auth curl http://localhost/health -o /dev/null 2>&1"
retry "MerchantService" "docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T omni-merchant curl http://localhost/health -o /dev/null 2>&1"
retry "LocationService" "docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T omni-location curl http://localhost/health -o /dev/null 2>&1"

retry "SampleData" "docker-compose $DOCKER_COMPOSE_PARAMETERS exec -T mysql mysql -u root -psecret < ./fixtures/sampleData.sql 2>&1"

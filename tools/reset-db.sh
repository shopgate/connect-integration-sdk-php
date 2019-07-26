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

DOCKER_COMPOSE_PARAMETERS="-f docker-compose.yml -f docker-compose.dev.yml"

docker-compose ${DOCKER_COMPOSE_PARAMETERS} exec -T mysql mysql -uroot -psecret < ./fixtures/schema.sql
docker-compose ${DOCKER_COMPOSE_PARAMETERS} stop omni-customer catalog import import-script && docker-compose ${DOCKER_COMPOSE_PARAMETERS} up -d omni-customer catalog import import-script

retry "CustomerService" "docker-compose ${DOCKER_COMPOSE_PARAMETERS} exec -T omni-customer curl http://localhost/health -o /dev/null 2>&1"
retry "CatalogService" "docker-compose ${DOCKER_COMPOSE_PARAMETERS} exec -T catalog curl http://localhost/health -o /dev/null 2>&1"
retry "ImportService" "docker-compose ${DOCKER_COMPOSE_PARAMETERS} exec -T import curl http://localhost/health -o /dev/null 2>&1"

docker-compose ${DOCKER_COMPOSE_PARAMETERS} exec -T mysql mysql -uroot -psecret < ./fixtures/sampleData.sql

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

docker-compose exec -T mysql mysql -uroot -psecret < ./fixtures/schema.sql
docker-compose stop catalog import import-script && docker-compose up -d catalog import import-script

retry "CatalogService" "docker-compose exec -T catalog curl http://localhost/health -o /dev/null 2>&1"

docker-compose exec -T mysql mysql -uroot -psecret < ./fixtures/sampleData.sql
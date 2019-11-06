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

docker-compose ${DOCKER_COMPOSE_PARAMETERS} exec -T elasticsearch curl -X DELETE 'http://localhost:9200/_all'
docker-compose ${DOCKER_COMPOSE_PARAMETERS} exec -T mysql mysql -uroot -psecret < ./fixtures/schema.sql
docker-compose ${DOCKER_COMPOSE_PARAMETERS} stop customer catalog import import-script order webhook && docker-compose ${DOCKER_COMPOSE_PARAMETERS} up -d customer catalog import import-script order webhook

retry "CustomerService" "docker-compose ${DOCKER_COMPOSE_PARAMETERS} exec -T customer curl http://localhost/health -o /dev/null 2>&1"
retry "CatalogService" "docker-compose ${DOCKER_COMPOSE_PARAMETERS} exec -T catalog curl http://localhost/health -o /dev/null 2>&1"
retry "ImportService" "docker-compose ${DOCKER_COMPOSE_PARAMETERS} exec -T import curl http://localhost/health -o /dev/null 2>&1"
retry "OrderService" "docker-compose ${DOCKER_COMPOSE_PARAMETERS} exec -T order curl http://localhost/health -o /dev/null 2>&1"
retry "WebhookService" "docker-compose ${DOCKER_COMPOSE_PARAMETERS} exec -T webhook curl http://localhost/health -o /dev/null 2>&1"

# add DE postalcodes
docker-compose $DOCKER_COMPOSE_PARAMETERS  exec -T mysql sh -c "curl https://download.geonames.org/export/dump/DE.zip --output de.zip && unzip -o de.zip"
docker-compose $DOCKER_COMPOSE_PARAMETERS  exec -T mysql sh -c "echo \"LOAD DATA LOCAL INFILE 'DE.txt' INTO TABLE Postalcode (CountryCode,PostalCode,PlaceName,AdminName1,AdminCode1,AdminName2,AdminCode2,AdminName3,AdminCode3,Latitude,Longitude,Accuracy);\" | mysql  -u root -psecret location"

docker-compose ${DOCKER_COMPOSE_PARAMETERS} exec -T mysql mysql -uroot -psecret < ./fixtures/sampleData.sql

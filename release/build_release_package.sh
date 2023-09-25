#!/bin/sh

SUFFIX=${1:-generic}

mkdir release/shopgate-connect-integration-sdk-php
rm -rf vendor
composer install -vvv --no-dev
rsync -av --exclude-from './release/exclude-filelist.txt' ./ release/shopgate-connect-integration-sdk-php
cd release
zip -r ../shopgate-connect-integration-sdk-php-$1.zip shopgate-connect-integration-sdk-php
cd ..
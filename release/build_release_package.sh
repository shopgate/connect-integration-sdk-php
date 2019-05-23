#!/bin/sh

mkdir release/shopgate-cloud-integration-sdk-php
rm -rf vendor
composer install -vvv --no-dev
rsync -av --exclude-from './release/exclude-filelist.txt' ./ release/shopgate-cloud-integration-sdk-php
cd release
zip -r ../shopgate-cloud-integration-sdk-php.zip shopgate-cloud-integration-sdk-php

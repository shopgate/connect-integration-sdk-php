#!/bin/sh

mkdir release/shopgate-connect-integration-sdk-php
rm -rf vendor
composer install -vvv --no-dev --ignore-platform-reqs
rsync -av --exclude-from './release/exclude-filelist.txt' ./ release/shopgate-connect-integration-sdk-php
cd release
zip -r ../shopgate-connect-integration-sdk-php.zip shopgate-connect-integration-sdk-php

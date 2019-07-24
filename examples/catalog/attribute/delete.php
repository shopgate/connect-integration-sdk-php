<?php

require_once(dirname(__FILE__) . '/../../bootstrap.php');

try {
    $sdk->getCatalogService()->deleteAttribute(EXTRA_CODE);
    $sdk->getCatalogService()->deleteAttribute(EXTRA_CODE_SECOND);
} catch (Exception $exception) {
    echo $exception->getMessage();
}

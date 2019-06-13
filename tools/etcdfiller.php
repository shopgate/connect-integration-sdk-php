<?php

require(__DIR__ . '/../vendor/autoload.php');

use LinkORB\Component\Etcd\Client;
use LinkORB\Component\Etcd\Exception\KeyExistsException;
use Symfony\Component\Yaml\Yaml;

if (!getenv('ETCD_HOST')) {
    echo 'GOT NO ETCD';
    putenv('ECTD_HOST=http://localhost:2379');
}

$etcdClient = new Client(getenv('ETCD_HOST'));
$etcdValues = Yaml::parseFile(__DIR__ . '/fixtures/etcd.yml');

fillEtcd($etcdValues);

function fillEtcd($etcdValues, $path = [])
{
    global $etcdClient;

    foreach ($etcdValues as $key => $values) {
        $currentPath = $path;
        $currentPath[] = $key;

        if (is_array($values)) {
            try {
                $etcdClient->mkdir(implode('/', $currentPath));
            } catch (KeyExistsException $e) { /* that's fine :) */
            }

            fillEtcd($values, $currentPath);
            continue;
        }

        $etcdClient->set(implode('/', $currentPath), $values);
    }
}

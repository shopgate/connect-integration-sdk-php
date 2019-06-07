<?php
require(__DIR__ . '/../vendor/autoload.php');

use Google\Cloud\PubSub\PubSubClient;

if (!getenv('PUBSUB_EMULATOR_HOST')) {
    echo 'GOT NO EMULATOR!!!111!1!11!!!';
    putenv('PUBSUB_EMULATOR_HOST=localhost:8085');
}

$pubSub = new PubSubClient(['projectId' => 'test-project']);

$topic = null;
$subscriptionCreated = false;
$tries = 0;
while (!$topic && !$subscriptionCreated && $tries < 10) {
    try {
        $topic = $pubSub->createTopic('entityChanged');
        $topicCreated = true;
    } catch (Exception $e) {
        if ($e->getCode() === 409) {
            $topic = $pubSub->topic('entityChanged');
        }
    }

    if (!$topic) {
        $tries++;
        sleep(1);
        continue;
    }

    try {
        $pubSub->subscribe('workerSvcEntityChanged', 'entityChanged');
        $subscriptionCreated = true;
        continue;
    } catch (Exception $e) {
        if ($e->getCode() === 409) {
            $subscriptionCreated = true;
            continue;
        }
    }

    $tries++;
    sleep(1);
}

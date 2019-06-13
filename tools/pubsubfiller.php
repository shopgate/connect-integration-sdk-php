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
        echo 'Creating Google PubSub topic "entityChanged" . . . ';
        $topic = $pubSub->createTopic('entityChanged');
        $topicCreated = true;
        echo "[OK]\n";
    } catch (Exception $e) {
        if ($e->getCode() === 409) {
            echo "[OK] Already exists.\n";
            $topic = $pubSub->topic('entityChanged');
        }
    }

    if (!$topic) {
        echo "[FAILED] Retrying in 1s.\n";
        $tries++;
        sleep(1);
        continue;
    }

    try {
        echo 'Creating Google PubSub subscription "workerScvEntityChanged" to topic "entityChanged" . . . ';
        $pubSub->subscribe('workerSvcEntityChanged', 'entityChanged');
        $subscriptionCreated = true;
        echo "[OK]\n";
        continue;
    } catch (Exception $e) {
        if ($e->getCode() === 409) {
            echo "[OK] Already exists.\n";
            $subscriptionCreated = true;
            continue;
        }
    }

    echo "[FAILED] Retrying in 1s.\n";
    $tries++;
    sleep(1);
}

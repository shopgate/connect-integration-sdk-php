<?php

require(__DIR__ . '/../vendor/autoload.php');

use Google\Cloud\PubSub\PubSubClient;

if (!getenv('PUBSUB_EMULATOR_HOST')) {
    putenv('PUBSUB_EMULATOR_HOST=localhost:8085');
}

$pubSub = new PubSubClient(['projectId' => 'test-project']);
$topics = [
    'entityChanged-development' => [
        'entityChanged-development'
    ],
    'fulfillmentOrderStatusUpdated' => [
        'orderSvcFulfillmentOrderStatusUpdated-development'
    ],
    'salesOrderAdded' => [
        'testerSalesOrderAdded-development'
    ],
    'salesOrderFulfillmentAdded' => [
        'testerSOFulfillmentAdded-development'
    ],
    'salesOrderStatusUpdated' => [
        'orderSvcSalesOrderStatusUpdated-development',
        'testerSalesOrderStatusUpdated-development'
    ],
    'userCreated' => [
        'testerUserCreated-development',
    ],
    'userAssigned' => [
        'testerUserAssigned-development',
    ],
    'userPasswordReset' => [
        'testerUserPasswordReset-development',
        'userSvcUserPasswordReset-development',
    ],
    'userPasswordChanged' => [
        'testerUserPasswordChanged-development',
        'userSvcUserPasswordChanged-development',
    ],
    'userAuthorized' => [
        'testerUserAuthorized-development',
        'userSvcUserAuthorized-development',
    ],
    'userAuthorizationFailed' => [
        'testerUserAuthorizationFailed-development',
        'userSvcUserAuthorizationFailed-development',
    ],
];

foreach ($topics as $topic => $subscriptions) {
    createTopic($pubSub, $topic, $subscriptions);
}

/**
 * @param PubSubClient $pubSub
 * @param string       $topic
 * @param string[]     $subscriptions
 */
function createTopic($pubSub, $topic, $subscriptions)
{
    $createdTopic = null;
    $subscriptionCreated = 0;
    $tries = 0;
    while (!$createdTopic && $subscriptionCreated != count($subscriptions) && $tries < 15) {
        try {
            echo "Creating Google PubSub topic '$topic' . . . ";
            $createdTopic = $pubSub->createTopic($topic);
            echo "[OK]\n";
        } catch (Exception $e) {
            var_dump($e->getMessage());
            if ($e->getCode() === 409) {
                echo "[OK] Already exists.\n";
                $createdTopic = $pubSub->topic($topic);
            }
        }

        if (!$createdTopic) {
            echo "[FAILED] Retrying in 1s.\n";
            $tries++;
            sleep(1);
            continue;
        }

        foreach ($subscriptions as $subscription) {
            try {
                echo "Creating Google PubSub subscription '$subscription' to topic $topic . . . ";
                $pubSub->subscribe($subscription, $topic);
                $subscriptionCreated++;
                echo "[OK]\n";
                continue;
            } catch (Exception $e) {
                var_dump($e->getMessage());
                if ($e->getCode() === 409) {
                    echo "[OK] Already exists.\n";
                    $subscriptionCreated++;
                    continue;
                }
            }
        }

        if ($subscriptionCreated != count($subscriptions)) {
            echo "[FAILED] Retrying in 1s.\n";
            $subscriptionCreated = 0;
            $tries++;
            sleep(1);
        }
    }
}

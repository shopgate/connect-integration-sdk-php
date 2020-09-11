<?php

require(__DIR__ . '/../vendor/autoload.php');

use Google\Cloud\PubSub\PubSubClient;

if (!getenv('PUBSUB_EMULATOR_HOST')) {
    putenv('PUBSUB_EMULATOR_HOST=localhost:8085');
}

$pubSub = new PubSubClient(['projectId' => 'test-project']);
$topics = [
    'entityChanged-development' => [
        'entityChanged-development',
        'workerSvcEntityChanged-development',
    ],
    'entityCreated-development' => [
        'entityCreated-development',
    ],
    'entityUpdated-development' => [
        'entityUpdated-development',
    ],
    'entityDeleted-development' => [
        'entityDeleted-development',
    ],
    'fulfillmentOrderAdded-development' => [
        'webhookTransSalesOrderFulfillmentAdded-development',
        'webhookTransFulfillmentOrderAdded-development',
    ],
    'fulfillmentOrderStatusUpdated-development' => [
        'orderSvcFulfillmentOrderStatusUpdated-development',
        'webhookFulfillmentOrderStatusUpdated-development',
        'webhookTransFulfillmentOrderStatusUpdated-development',
    ],
    'fulfillmentOrderUpdated-development' => [
        'analyticsSvcFulfillmentOrderUpdated-development',
    ],
    'inventoryReservationDeleted-development' => [
        'webhookTransInventoryReservationDeleted-development',
    ],
    'inventoryReservationSettled-development' => [
        'webhookTransInventoryReservationSettled-development',
    ],
    'salesOrderAdded-development' => [
        'orderSvcSalesOrderAdded-development',
        'routingSvcSalesOrderAdded-development',
        'webhookSalesOrderAdded-development',
        'webhookTransSalesOrderAdded-development',
    ],
    'salesOrderFulfillmentAdded-development' => [
        'webhookSalesOrderFulfillmentAdded-development',
    ],
    'salesOrderStatusUpdated-development' => [
        'orderSvcSalesOrderStatusUpdated-development',
        'webhookSalesOrderStatusUpdated-development',
        'webhookTransSalesOrderStatusUpdated-development',
    ],
    'userAssigned-development' => [
        'notificationSvcUserAssigned-development',
    ],
    'userAuthorizationFailed-development' => [
        'userSvcUserAuthorizationFailed-development',
    ],
    'userAuthorized-development' => [
        'userSvcUserAuthorized-development',
    ],
    'userCreated-development' => [
        'notificationSvcUserCreated-development',
    ],
    'userPasswordChanged-development' => [
        'userSvcUserPasswordChanged-development',
    ],
    'userPasswordReset-development' => [
        'userSvcUserPasswordReset-development',
    ],
    'webhookRouted-development' => [
        'webhookTransWebhookRouted-development',
    ],
    'orderNotPickedUp-development' => [
        'webhookTransOrderNotPickedUp-development',
    ],
    'productCreated-development' => [],
    'productUpdated-development' => [],
    'segmentChanged-development' => [],
    'importCompleted-development' => [],
    'importDone-development' => [
        'workerSvcImportDone-development',
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
    $tries = 0;

    while ($createdTopic === null && $tries < 15) {
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
    }

    if (!$createdTopic) return;

    foreach ($subscriptions as $subscription) {
        $tries = 0;
        $subscriptionCreated = false;
        while (!$subscriptionCreated && $tries < 15) {
            if ($tries > 0) {
                echo "[FAILED] Retrying in 1s.\n";
                sleep(1);
            }

            $tries++;
            try {
                echo "Creating Google PubSub subscription '$subscription' to topic $topic . . . ";
                $pubSub->subscribe($subscription, $topic);
                $subscriptionCreated = true;
                echo "[OK]\n";
            } catch (Exception $e) {
                var_dump($e->getMessage());
                if ($e->getCode() === 409) {
                    echo "[OK] Already exists.\n";
                    $subscriptionCreated = true;
                }
            }
        }
    }
}

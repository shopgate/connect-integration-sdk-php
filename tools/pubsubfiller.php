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
        'testerSalesOrderAdded-development', # maybe not needed
    ],
    'salesOrderFulfillmentAdded-development' => [
        'webhookSalesOrderFulfillmentAdded-development',
        'testerSOFulfillmentAdded-development', # maybe not needed
    ],
    'salesOrderStatusUpdated-development' => [
        'orderSvcSalesOrderStatusUpdated-development',
        'webhookSalesOrderStatusUpdated-development',
        'webhookTransSalesOrderStatusUpdated-development',
        'testerSalesOrderStatusUpdated-development', # maybe not needed
    ],
    'userAssigned-development' => [
        'notificationSvcUserAssigned-development',
        'testerUserAssigned-development', # maybe not needed
    ],
    'userAuthorizationFailed-development' => [
        'userSvcUserAuthorizationFailed-development',
        'testerUserAuthorizationFailed-development', # maybe not needed
    ],
    'userAuthorized-development' => [
        'userSvcUserAuthorized-development',
        'testerUserAuthorized-development', # maybe not needed
    ],
    'userCreated-development' => [
        'notificationSvcUserCreated-development',
        'testerUserCreated-development', # maybe not needed
    ],
    'userPasswordChanged-development' => [
        'userSvcUserPasswordChanged-development',
        'testerUserPasswordChanged-development', #maybe not needed
    ],
    'userPasswordReset-development' => [
        'userSvcUserPasswordReset-development',
        'testerUserPasswordReset-development', # maybe not needed
    ],
    'webhookRouted-development' => [
        'webhookTransWebhookRouted-development',
    ],
    'orderNotPickedUp-development' => [
        'webhookTransOrderNotPickedUp-development',
    ]
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

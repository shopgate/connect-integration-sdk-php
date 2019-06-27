<?php


namespace Shopgate\ConnectSdk\Tests\Integration\Http;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Http\Client;
use Shopgate\ConnectSdk\ShopgateSdk;
use Shopgate\ConnectSdk\Tests\Integration\ShopgateSdkTest;

class ClientTest extends ShopgateSdkTest
{
    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $merchantCode
     * @param string $baseUri
     * @param string $env
     * @param string $accessTokenPath
     *
     * @return ShopgateSdk
     *
     * @throws Exception
     */
    public function createNewSdk($clientId, $clientSecret, $merchantCode, $baseUri, $env, $accessTokenPath)
    {
        $client = Client::createInstance(
            $clientId,
            $clientSecret,
            $merchantCode,
            $baseUri,
            $env,
            $accessTokenPath
        );

        if ((int)getenv('requestLogging')) {
            $client->enableRequestLogging(new Logger('request_logger_integration_tests',
                [new StreamHandler('php://stdout')]));
        }

        return new ShopgateSdk(['client' => $client]);
    }

    /**
     * @param string $expectedException
     * @param string $requestType
     * @param string $clientId
     * @param string $clientSecret
     * @param string $merchantCode
     * @param string $baseUri
     * @param string $env
     * @param string $accessTokenPath
     *
     * @throws Exception
     *
     * @dataProvider provideConfigurations
     */
    public function testDifferentFailingConfigurations(
        $expectedException,
        $requestType,
        $clientId,
        $clientSecret,
        $merchantCode,
        $baseUri,
        $env,
        $accessTokenPath
    ) {
        $sdk = $this->createNewSdk($clientId, $clientSecret, $merchantCode, $baseUri, $env, $accessTokenPath);
        try {
            $sdk->getClient()->doRequest([
                'method' => 'get',
                // direct
                'service' => 'catalog',
                'path' => 'categories',
                'requestType' => $requestType,
                // event
                'action' => 'update',
                'entity' => 'category',
            ]);
        } catch (Exception $exception) {
            $this->assertInstanceOf($expectedException, $exception);
            return;
        }

        $this->fail('Expected Exception to be thrown');
    }

    /**
     * @return array
     */
    public function provideConfigurations()
    {
        return [
            'wrong path' => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_DIRECT,
                getenv('clientId'),
                getenv('clientSecret'),
                getenv('merchantCode'),
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                '/test.txt',
            ],
            'wrong clientId' => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_DIRECT,
                'wrong',
                getenv('clientSecret'),
                getenv('merchantCode'),
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                getenv('accessTokenPath')
            ],
            'wrong clientSecret' => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_DIRECT,
                getenv('clientId'),
                'wrong',
                getenv('merchantCode'),
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                getenv('accessTokenPath')
            ],
            'wrong merchantCode' => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_DIRECT,
                getenv('clientId'),
                getenv('clientSecret'),
                'wrong',
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                getenv('accessTokenPath')
            ],
            'wrong baseUri' => [
                RequestException::class,
                ShopgateSdk::REQUEST_TYPE_DIRECT,
                getenv('clientId'),
                getenv('clientSecret'),
                getenv('merchantCode'),
                'httpp://localhost',
                getenv('env') ?: '',
                getenv('accessTokenPath')
            ],
            'wrong env' => [
                RequestException::class,
                ShopgateSdk::REQUEST_TYPE_DIRECT,
                getenv('clientId'),
                getenv('clientSecret'),
                getenv('merchantCode'),
                getenv('baseUri') ?: '',
                'wrong',
                getenv('accessTokenPath')
            ],
            'event - wrong path' => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_EVENT,
                getenv('clientId'),
                getenv('clientSecret'),
                getenv('merchantCode'),
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                '/test.txt',
            ],
            'event - wrong clientId' => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_EVENT,
                'wrong',
                getenv('clientSecret'),
                getenv('merchantCode'),
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                getenv('accessTokenPath')
            ],
            'event - wrong clientSecret' => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_EVENT,
                getenv('clientId'),
                'wrong',
                getenv('merchantCode'),
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                getenv('accessTokenPath')
            ],
            'event - wrong merchantCode' => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_EVENT,
                getenv('clientId'),
                getenv('clientSecret'),
                'wrong',
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                getenv('accessTokenPath')
            ],
            'event - wrong baseUri' => [
                RequestException::class,
                ShopgateSdk::REQUEST_TYPE_EVENT,
                getenv('clientId'),
                getenv('clientSecret'),
                getenv('merchantCode'),
                'httpp://localhost',
                getenv('env') ?: '',
                getenv('accessTokenPath')
            ],
            'event - wrong env' => [
                RequestException::class,
                ShopgateSdk::REQUEST_TYPE_EVENT,
                getenv('clientId'),
                getenv('clientSecret'),
                getenv('merchantCode'),
                getenv('baseUri') ?: '',
                'wrong',
                getenv('accessTokenPath')
            ],
        ];
    }
}
<?php


namespace Shopgate\ConnectSdk\Tests\Integration\Http;

use Dotenv\Dotenv;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\SeekException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use kamermans\OAuth2\OAuth2Middleware;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Shopgate\ConnectSdk\Exception\AuthenticationInvalidException;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use Shopgate\ConnectSdk\Http\Client;
use Shopgate\ConnectSdk\Http\Client as SdkClient;
use Shopgate\ConnectSdk\Http\Persistence\TokenPersistenceException;
use Shopgate\ConnectSdk\ShopgateSdk;
use Shopgate\ConnectSdk\Tests\Integration\ShopgateSdkUtility;

class ClientTest extends ShopgateSdkUtility
{
    private $accessTokenPath = './access_token_client_test.txt';

    public function setUp()
    {
        parent::setUp();

        if (file_exists($this->accessTokenPath)) {
            unlink($this->accessTokenPath);
        }
    }

    /**
     * @param string $expectedException
     * @param string $requestType
     * @param string $clientId
     * @param string $clientSecret
     * @param string $merchantCode
     * @param string $username
     * @param string $password
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
        $username,
        $password,
        $baseUri,
        $env,
        $accessTokenPath
    ) {
        $sdk = $this->createNewSdk(
            $clientId,
            $clientSecret,
            $merchantCode,
            $username,
            $password,
            $baseUri,
            $env,
            $accessTokenPath
        );
        try {
            $sdk->getClient()->doRequest([
                'method'      => 'get',
                // direct
                'service'     => 'catalog',
                'path'        => 'categories',
                'requestType' => $requestType,
                // event
                'action'      => 'update',
                'entity'      => 'category',
            ]);
        } catch (Exception $exception) {
            $this->assertInstanceOf($expectedException, $exception);

            return;
        }

        $this->fail('Expected Exception to be thrown');
    }

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $merchantCode
     * @param string $username
     * @param string $password
     * @param string $baseUri
     * @param string $env
     * @param string $accessTokenPath
     *
     * @return ShopgateSdk
     *
     * @throws Exception
     */
    public function createNewSdk(
        $clientId,
        $clientSecret,
        $merchantCode,
        $username,
        $password,
        $baseUri,
        $env,
        $accessTokenPath
    ) {
        $client = Client::createInstance(
            $clientId,
            $clientSecret,
            $merchantCode,
            $username,
            $password,
            $baseUri,
            $env,
            $accessTokenPath
        );

        if ((int)getenv('requestLogging')) {
            $client->enableRequestLogging(new Logger(
                'request_logger_integration_tests',
                [new StreamHandler('php://stdout')]
            ));
        }

        return new ShopgateSdk(['client' => $client]);
    }

    /**
     * @return array
     */
    public function provideConfigurations()
    {
        $env = Dotenv::create(__DIR__ . '/../');
        $env->load();
        $env->required(
            [
                'clientId',
                'clientSecret',
                'merchantCode',
                'username',
                'password',
            ]
        );

        return [
            'wrong path'                 => [
                TokenPersistenceException::class,
                ShopgateSdk::REQUEST_TYPE_DIRECT,
                getenv('clientId'),
                getenv('clientSecret'),
                getenv('merchantCode'),
                getenv('username'),
                getenv('password'),
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                '/invalid/test.txt',
            ],
            'wrong clientId' => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_DIRECT,
                'wrong',
                getenv('clientSecret'),
                getenv('merchantCode'),
                getenv('username'),
                getenv('password'),
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                $this->accessTokenPath
            ],

            'wrong clientSecret' => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_DIRECT,
                getenv('clientId'),
                'wrong',
                getenv('merchantCode'),
                getenv('username'),
                getenv('password'),
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                $this->accessTokenPath
            ],
            'wrong merchantCode'         => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_DIRECT,
                getenv('clientId'),
                getenv('clientSecret'),
                'wrong',
                getenv('username'),
                getenv('password'),
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                $this->accessTokenPath
            ],
            'wrong username'         => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_DIRECT,
                getenv('clientId'),
                getenv('clientSecret'),
                getenv('merchantCode'),
                'wrong',
                getenv('password'),
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                $this->accessTokenPath
            ],
            'wrong password'         => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_DIRECT,
                getenv('clientId'),
                getenv('clientSecret'),
                getenv('merchantCode'),
                getenv('username'),
                'wrong',
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                $this->accessTokenPath
            ],
            'wrong baseUri'              => [
                RequestException::class,
                ShopgateSdk::REQUEST_TYPE_DIRECT,
                getenv('clientId'),
                getenv('clientSecret'),
                getenv('merchantCode'),
                getenv('username'),
                getenv('password'),
                'httpp://localhost',
                getenv('env') ?: '',
                $this->accessTokenPath
            ],
            'wrong env'                  => [
                RequestException::class,
                ShopgateSdk::REQUEST_TYPE_DIRECT,
                getenv('clientId'),
                getenv('clientSecret'),
                getenv('merchantCode'),
                getenv('username'),
                getenv('password'),
                '',
                'wrong',
                $this->accessTokenPath
            ],
            'event - wrong clientId'     => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_EVENT,
                'wrong',
                getenv('clientSecret'),
                getenv('merchantCode'),
                getenv('username'),
                getenv('password'),
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                $this->accessTokenPath
            ],
            'event - wrong clientSecret' => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_EVENT,
                getenv('clientId'),
                'wrong',
                getenv('merchantCode'),
                getenv('username'),
                getenv('password'),
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                $this->accessTokenPath
            ],
            'event - wrong merchantCode' => [
                AuthenticationInvalidException::class,
                ShopgateSdk::REQUEST_TYPE_EVENT,
                getenv('clientId'),
                getenv('clientSecret'),
                'wrong',
                getenv('username'),
                getenv('password'),
                getenv('baseUri') ?: '',
                getenv('env') ?: '',
                $this->accessTokenPath
            ],
            'event - wrong baseUri'      => [
                RequestException::class,
                ShopgateSdk::REQUEST_TYPE_EVENT,
                getenv('clientId'),
                getenv('clientSecret'),
                getenv('merchantCode'),
                getenv('username'),
                getenv('password'),
                'httpp://localhost',
                getenv('env') ?: '',
                $this->accessTokenPath
            ],
        ];
    }

    /**
     * @throws \Shopgate\ConnectSdk\Exception\Exception
     */
    public function testClientThrowsRequestException()
    {
        // Arrange
        $mockHandler  = new MockHandler([]);
        $handlerStack = HandlerStack::create($mockHandler);

        /** @var GuzzleClient $client */
        $client = $this
            ->getMockBuilder(GuzzleClient::class)
            ->setConstructorArgs([['handler' => $handlerStack]])
            ->setMethods(null)
            ->getMock();

        /** @var OAuth2Middleware $OAuthMiddleware */
        $OAuthMiddleware = $this->getMockBuilder(OAuth2Middleware::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $sdkClient = new SdkClient(
            $client,
            $OAuthMiddleware,
            'http://{service}.local',
            'TM2'
        );

        $mockHandler->append(new Response(500, [], 'internal server error'));

        // Assert
        $this->expectException(RequestException::class);

        // Act
        $sdkClient->doRequest([
            'method'      => 'get',
            // direct
            'service'     => 'catalog',
            'path'        => 'categories',
            'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
            // event
            'action'      => 'update',
            'entity'      => 'category',
        ]);
    }

    /**
     * @throws Exception
     */
    public function testClientThrowsUnknownException()
    {
        // Arrange
        $mockHandler  = new MockHandler([]);
        $handlerStack = HandlerStack::create($mockHandler);

        $mockHandler->append(new RequestException());

        /** @var GuzzleClient $client */
        $client = $this
            ->getMockBuilder(GuzzleClient::class)
            ->setConstructorArgs([['handler' => $handlerStack]])
            ->setMethods(null)
            ->getMock();

        /** @var OAuth2Middleware $OAuthMiddleware */
        $OAuthMiddleware = $this->getMockBuilder(OAuth2Middleware::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();

        $sdkClient = new SdkClient(
            $client,
            $OAuthMiddleware,
            'http://{service}.local',
            'TM2'
        );

        // Assert
        $this->expectException(UnknownException::class);

        // Act
        $sdkClient->doRequest([
            'method'      => 'get',
            // direct
            'service'     => 'catalog',
            'path'        => 'categories',
            'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
            // event
            'action'      => 'update',
            'entity'      => 'category',
        ]);
    }
}

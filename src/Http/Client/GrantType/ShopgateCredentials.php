<?php

namespace Shopgate\ConnectSdk\Http\Client\GrantType;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use kamermans\OAuth2\GrantType\GrantTypeInterface;
use kamermans\OAuth2\Signer\ClientCredentials\SignerInterface;
use kamermans\OAuth2\Utils\Collection;
use GuzzleHttp\Psr7;
use Psr\Http\Message\StreamInterface;

class ShopgateCredentials implements GrantTypeInterface
{
    /** @var string  */
    const DEFAULT_TENANT_TYPE = 'merchant';

    /**
     * The token endpoint client.
     *
     * @var ClientInterface
     */
    private $client;

    /**
     * Configuration settings.
     *
     * @var Collection
     */
    private $config;

    /**
     * @param ClientInterface $client
     * @param array           $config
     */
    public function __construct(ClientInterface $client, array $config)
    {
        $this->client = $client;
        $this->config = Collection::fromConfig(
            $config,
            // Default
            [
                'client_secret' => '',
                'scope' => '',
            ],
            // Required
            [
                'client_id',
                'client_secret',
                'username',
                'password',
                'merchant_code'
            ]
        );
    }

    /**
     * @param SignerInterface $clientCredentialsSigner
     * @param null            $refreshToken
     *
     * @return array|mixed
     * @throws GuzzleException
     */
    public function getRawData(SignerInterface $clientCredentialsSigner, $refreshToken = null)
    {
        $request = (new Psr7\Request('POST', $this->client->getConfig()['base_uri']))
            ->withBody($this->getPostBody())
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded');

        $request = $clientCredentialsSigner->sign(
            $request,
            $this->config['client_id'],
            $this->config['client_secret']
        );

        $response = $this->client->send($request);

        return json_decode($response->getBody(), true);
    }

    /**
     * @return StreamInterface
     */
    protected function getPostBody()
    {
        $data = [
            'grant_type' => 'password',
            'username'   => $this->config['username'],
            'password'   => $this->config['password'],
            'tenantType' => self::DEFAULT_TENANT_TYPE,
            'tenantId' => $this->config['merchant_code']

        ];

        if ($this->config['scope']) {
            $data['scope'] = $this->config['scope'];
        }

        return Psr7\stream_for(http_build_query($data, '', '&'));
    }
}

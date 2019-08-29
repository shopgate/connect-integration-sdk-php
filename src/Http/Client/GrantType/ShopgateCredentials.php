<?php

namespace Shopgate\ConnectSdk\Http\Client\GrantType;

use GuzzleHttp\ClientInterface;
use kamermans\OAuth2\GrantType\GrantTypeInterface;
use kamermans\OAuth2\Signer\ClientCredentials\SignerInterface;
use kamermans\OAuth2\Utils\Collection;
use kamermans\OAuth2\Utils\Helper;

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

    public function getRawData(SignerInterface $clientCredentialsSigner, $refreshToken = null)
    {
        if (Helper::guzzleIs('>=', 6)) {
            $request = (new \GuzzleHttp\Psr7\Request('POST', $this->client->getConfig()['base_uri']))
                ->withBody($this->getPostBody())
                ->withHeader('Content-Type', 'application/x-www-form-urlencoded');
        } else {
            $request = $this->client->createRequest('POST', null);
            $request->setBody($this->getPostBody());
        }

        $request = $clientCredentialsSigner->sign(
            $request,
            $this->config['client_id'],
            $this->config['client_secret']
        );

        $response = $this->client->send($request);

        return json_decode($response->getBody(), true);
    }

    /**
     * @return PostBody
     */
    protected function getPostBody()
    {
        if (Helper::guzzleIs('>=', '6')) {
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

            return \GuzzleHttp\Psr7\stream_for(http_build_query($data, '', '&'));
        }

        $postBody = new PostBody();
        $postBody->replaceFields([
            'grant_type' => 'password',
            'username'   => $this->config['username'],
            'password'   => $this->config['password'],
            'tenantType' => self::DEFAULT_TENANT_TYPE,
            'tenantId' => $this->config['merchant_code']
        ]);

        if ($this->config['scope']) {
            $postBody->setField('scope', $this->config['scope']);
        }

        return $postBody;
    }
}

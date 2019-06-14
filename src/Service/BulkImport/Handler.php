<?php
/**
 * Created by PhpStorm.
 * User: alexanderwesselburg
 * Date: 13.06.19
 * Time: 21:00
 */

namespace Shopgate\ConnectSdk\Service\BulkImport;

use Shopgate\ConnectSdk\ClientInterface;

class Handler
{
    /** Define handler type */
    const HANDLER_TYPE = '';

    /** @var  ClientInterface */
    protected $client;

    /** @var  string */
    protected $importReference;

    /**
     * Stream constructor.
     *
     * @param ClientInterface $client
     * @param string          $importReference
     */
    public function __construct(ClientInterface $client, $importReference)
    {
        $this->client          = $client;
        $this->importReference = $importReference;
    }

    /**
     * @param string $catalogCode
     *
     * @return Feed\Category
     */
    public function createCategoryFeed($catalogCode)
    {
        return new Feed\Category(
            $this->client,
            $this->importReference,
            $this::HANDLER_TYPE,
            array_merge(['entity' => 'category'], ['catalogCode' => $catalogCode])
        );
    }

    /**
     * @param string $catalogCode
     *
     * @return Feed\Product
     */
    public function createProductFeed($catalogCode)
    {
        return new Feed\Product(
            $this->client,
            $this->importReference,
            $this::HANDLER_TYPE,
            array_merge(['entity' => 'product'], ['catalogCode' => $catalogCode])
        );
    }

    public function trigger()
    {
        $response = $this->client->doRequest(
            [
                // general
                'method'      => 'post',
                'body'        => [],
                'requestType' => 'direct',
                'service'     => 'import',
                'path'        => 'imports/' . $this->importReference,
            ]
        );

        print_r($response->getStatusCode());
        $response = json_decode($response->getBody(), true);
        print_r($response);
    }
}

<?php
/**
 * @var string $p
 * @var array $pathParams
 * @var ServiceGenerator $this
 * @var array $pathSubstitutions
 * @var string $methodArgsStr
 * @var string $subPath
 */

?>

    /**
<?php foreach ($pathSubstitutions as $pathSubstitution) : ?>
     * @param string $<?= $pathSubstitution ?>
<?php endforeach; ?>
     * @param array  $data
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function <?= $this->methodName($path, $pathParams) ?>(<?= $methodArgsStr ?>array $data = [])
    {
        $response = $this->client->doRequest(
            [
                'service' => self::SERVICE_NAME,
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'method' => 'post',
                'path' => '<?= $subPath ?>',
                'json' => $data,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);
        return $response;
    }


<?php
/**
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
     * @param array  $query
     *
     * @return array
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function <?= $this->methodName($path, $pathParams) ?>(<?= $methodArgsStr?>array $query = [])
    {
        if (isset($query['filters'])) {
            $query['filters'] = $this->jsonHelper->encode($query['filters']);
        }
        $response = $this->client->doRequest(
            [
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'service' => self::SERVICE_NAME,
                'method' => 'get',
                'path' => '<?= $subPath ?>',
                'query' => $query,
            ]
        );

        $response = $this->jsonHelper->decode($response->getBody(), true);

        if (isset($response['meta'])) {
            $response['meta'] = new Meta($response['meta']);
        }

<?php if (isset($pathParams['responses'][200])) : ?>
        return $response;
<?php endif; ?>
    }


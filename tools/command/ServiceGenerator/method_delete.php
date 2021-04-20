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
     * @return ResponseInterface
     *
     * @throws AuthenticationInvalidException
     * @throws NotFoundException
     * @throws RequestException
     * @throws UnknownException
     * @throws InvalidDataTypeException
     */
    public function <?= $this->camelCase($pathParams['summary']) ?>(<?= $methodArgsStr?>array $query = [])
    {
        return $this->client->doRequest(
            [
                'requestType' => ShopgateSdk::REQUEST_TYPE_DIRECT,
                'service' => self::SERVICE_NAME,
                'method' => 'delete',
                'path' => '<?= $subPath ?>',
                'query' => $query,
            ]
        );
    }

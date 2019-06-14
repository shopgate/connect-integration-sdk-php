<?php
/**
 * Created by PhpStorm.
 * User: alexanderwesselburg
 * Date: 13.06.19
 * Time: 18:50
 */

namespace Shopgate\ConnectSdk\Service\BulkImport;

use Shopgate\ConnectSdk\ClientInterface;
use Shopgate\ConnectSdk\Service\BulkImport\Handler\File;
use Shopgate\ConnectSdk\Service\BulkImport\Handler\Stream;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use GuzzleHttp\Client;
use Shopgate\ConnectSdk\Exception\RequestException;
use Shopgate\ConnectSdk\Exception\UnknownException;
use GuzzleHttp\Exception\GuzzleException;

class Feed
{
    /** @var  ClientInterface */
    protected $client;

    /** @var  string */
    protected $importReference;

    /** @var  string */
    private $url;

    /** @var \Psr\Http\Message\StreamInterface */
    protected $stream;

    /** @var string */
    protected $handlerType;

    /** @var */
    protected $importClient;

    /**
     * Feed constructor.
     *
     * @param ClientInterface $client
     * @param string          $importReference
     * @param string          $handlerType
     */
    public function __construct(ClientInterface $client, $importReference, $handlerType)
    {
        $this->client          = $client;
        $this->importReference = $importReference;
        $this->handlerType     = $handlerType;

        $this->client       = $client;
        $this->importClient = new Client();
        $this->url          = $this->getUrl();

        switch ($this->handlerType) {
            case Stream::HANDLER_TYPE:
                $this->stream = \GuzzleHttp\Psr7\stream_for();
                $this->stream->write('[');
                break;
            case File::HANDLER_TYPE:
                $this->stream = tmpfile();
                fwrite($this->stream, '[');
                break;
        }
    }

    /**
     *
     */
    public function end()
    {
        $requestOption = [];
        switch ($this->handlerType) {
            case Stream::HANDLER_TYPE:
                $this->stream->write(']');
                $requestOption = ['body' => $this->stream];
                $this->importClient->request(
                    'PUT',
                    $this->url,
                    ['body' => $this->stream]
                );;
                break;
            case File::HANDLER_TYPE:
                fwrite($this->stream, ']');
                fseek($this->stream, 0);
                $requestOption = ['body' => fread($this->stream, filesize(stream_get_meta_data($this->stream)['uri']))];
                fclose($this->stream);
                break;
        }

        if (count($requestOption)) {
            try {
                $this->importClient->request('PUT', $this->url, $requestOption);
            } catch (GuzzleRequestException $e) {
                throw new RequestException($e->getResponse()->getBody()->getContents());
            } catch (GuzzleException $e) {
                throw new UnknownException($e->getMessage());
            } catch (\Exception $e) {
                throw new UnknownException($e->getMessage());
            }
        }
    }
}

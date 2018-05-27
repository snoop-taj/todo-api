<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Behat;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;

class RestClient
{
    public const VERB_GET       = 'GET';
    public const VERB_POST      = 'POST';
    public const VERB_PUT       = 'PUT';
    public const VERB_PATCH     = 'PATCH';
    public const VERB_DELETE    = 'DELETE';

    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $endPoint
     * @param array $options
     * @return ResponseInterface
     */
    public function sendGetRequest(string $endPoint, array $options): ResponseInterface
    {
        return $this->sendRequest(self::VERB_GET, $endPoint, $options);
    }

    /**
     * @param string $endPoint
     * @param array $options
     * @return ResponseInterface
     */
    public function sendPostRequest(string $endPoint, array $options): ResponseInterface
    {
        return $this->sendRequest(self::VERB_POST, $endPoint, $options);
    }

    /**
     * @param string $endPoint
     * @param array $options
     * @return ResponseInterface
     */
    public function sendPutRequest(string $endPoint, array $options): ResponseInterface
    {
        return $this->sendRequest(self::VERB_PUT, $endPoint, $options);
    }

    /**
     * @param string $endPoint
     * @param array $options
     * @return ResponseInterface
     */
    public function sendPatchRequest(string $endPoint, array $options): ResponseInterface
    {
        return $this->sendRequest(self::VERB_PATCH, $endPoint, $options);
    }

    /**
     * @param string $endPoint
     * @param array $options
     * @return ResponseInterface
     */
    public function sendDeleteRequest(string $endPoint, array $options): ResponseInterface
    {
        return $this->sendRequest(self::VERB_DELETE, $endPoint, $options);
    }

    /**
     * @param string $httpVerb
     * @param string $endPoint
     * @param array $options
     * @return ResponseInterface
     * @throws BadResponseException
     */
    private function sendRequest(string $httpVerb, string $endPoint, array $options): ResponseInterface
    {
        try {
            return $this->client->request($httpVerb, $endPoint, $options);
        } catch (BadResponseException $exception) {
            if ($exception->getResponse() instanceof ResponseInterface) {
                return $exception->getResponse();
            }

            throw $exception;
        }
    }

    /**
     * @param ResponseInterface $response
     * @param string $key
     * @return array|null
     */
    public static function getResponseContentByKey(ResponseInterface $response, string $key): ?array
    {
        $body = json_decode((string)$response->getBody(), true);
        return $body[$key] ?? null;
    }
}

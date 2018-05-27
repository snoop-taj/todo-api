<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Behat;

use Psr\Http\Message\ResponseInterface;

class TodoApiClient
{
    private static $headers = [
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
    ];

    private const TODO = '/api/v1/todo';

    /** @var RestClient */
    private $restClient;

    /**
     * @param RestClient $restClient
     */
    public function __construct(RestClient $restClient)
    {
        $this->restClient = $restClient;
    }

    /**
     * @param string $payload
     * @return ResponseInterface
     */
    public function createTodo(string $payload): ResponseInterface
    {
        $endPoint = self::TODO;
        return $this->restClient->sendPostRequest($endPoint, self::options(['body' => $payload]));
    }

    /**
     * @param string $id
     * @param string $payload
     * @return ResponseInterface
     */
    public function updateTodo(string $id, string $payload): ResponseInterface
    {
        $endPoint = sprintf(self::TODO . '/%s', $id);
        return $this->restClient->sendPutRequest($endPoint, self::options(['body' => $payload]));
    }

    /**
     * @return ResponseInterface
     */
    public function getTodoList(): ResponseInterface
    {
        $endPoint = self::TODO . '/list';
        return $this->restClient->sendGetRequest($endPoint, self::options());
    }

    /**
     * @param string $id
     * @return ResponseInterface
     */
    public function getTodoListById(string $id): ResponseInterface
    {
        $endPoint = sprintf(self::TODO . '/%s', $id);
        return $this->restClient->sendGetRequest($endPoint, self::options());
    }

    /**
     * @param string $id
     * @return ResponseInterface
     */
    public function deleteTodo(string $id): ResponseInterface
    {
        $endPoint = sprintf(self::TODO . '/%s', $id);
        return $this->restClient->sendDeleteRequest($endPoint, self::options());
    }

    /**
     * @param array ...$options
     * @return array
     */
    private static function options(...$options): array
    {
        return array_reduce($options, function (array $carry, array $option) {
            return array_merge($carry, $option);
        }, ['headers' => self::$headers]);
    }
}

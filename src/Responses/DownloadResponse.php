<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Responses;

use Psr\Http\Message\ResponseInterface;
use Rovereto\YandexMetrika\Contracts\DeserializeResponseInterface;
use Rovereto\YandexMetrika\Responses\Concerns\ErrorResponse;
use Rovereto\YandexMetrika\YandexMetrika;

/**
 * Class DownloadResponse
 *
 * @package Rovereto\YandexMetrika\Responses
 */
class DownloadResponse implements DeserializeResponseInterface
{
    use ErrorResponse;

    /**
     * Десериализация ответа
     *
     * @param  YandexMetrika  $client
     * @param  ResponseInterface  $response
     * @param  string  $format
     * @return array|mixed|object|\Psr\Http\Message\StreamInterface
     */
    public static function deserialize(YandexMetrika $client, ResponseInterface $response, string $format)
    {
        if (200 === $response->getStatusCode()) {
            return $response->getBody();
        }

        return $client->getSerializer()->deserialize($response->getBody()->getContents(), self::class, $format);
    }
}

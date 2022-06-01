<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Contracts;

use Psr\Http\Message\ResponseInterface;
use Rovereto\YandexMetrika\YandexMetrika;

/**
 * Interface DeserializeResponseInterface
 *
 * @package Rovereto\YandexMetrika\Contracts
 */
interface DeserializeResponseInterface
{
    /**
     * Десериализация ответа
     *
     * @param  YandexMetrika  $client
     * @param  ResponseInterface  $response
     * @param  string  $format
     * @return mixed
     */
    public static function deserialize(YandexMetrika $client, ResponseInterface $response, string $format);
}

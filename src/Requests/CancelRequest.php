<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Requests;

use Rovereto\YandexMetrika\Contracts\Request;
use Rovereto\YandexMetrika\Requests\Concerns\RequestCore;

/**
 * Class CancelRequest
 *
 * @package Rovereto\YandexMetrika\Requests
 */
class CancelRequest extends RequestCore implements Request
{
    protected const METHOD = 'POST';
    protected const ADDRESS = 'https://api-metrika.yandex.net/management/v1/counter/{counterId}/logrequest/{requestId}/cancel';

    /**
     * Идентификатор запроса
     *
     * @var null|int
     */
    private static $requestId = null;

    public function __construct(int $counterId, int $requestId)
    {
        parent::__construct($counterId);

        $this->setRequestId($requestId);
    }

    /**
     * Установка запроса
     *
     * @param  int  $requestId
     * @return CancelRequest
     */
    public function setRequestId(int $requestId): CancelRequest
    {
        self::$requestId = $requestId;

        return $this;
    }

    /**
     * Адрес для отправки запроса
     *
     * @return string
     */
    public function getAddress(): string
    {
        return str_replace('{requestId}', self::$requestId, parent::getAddress());
    }
}

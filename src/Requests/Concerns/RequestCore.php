<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Requests\Concerns;

/**
 * Class RequestCore
 *
 * @package Rovereto\YandexMetrika\Requests\Concerns
 */
abstract class RequestCore
{
    protected const METHOD = '';

    protected const ADDRESS = '';

    /**
     * Идентификатор счётчика
     *
     * @var null|int
     */
    private static $counterId = null;

    public function __construct(int $counterId)
    {
        $this->setCounterId($counterId);
    }

    /**
     * Установка счетчика
     *
     * @param int $counterId
     * @return RequestCore
     */
    public function setCounterId(int $counterId): RequestCore
    {
        self::$counterId = $counterId;

        return $this;
    }

    /**
     * Адрес для отправки запроса
     *
     * @return string
     */
    public function getAddress(): string
    {
        return str_replace('{counterId}', (string)self::$counterId, static::ADDRESS);
    }

    /**
     * Метод отправки запроса
     *
     * @return string
     */
    public function getMethod(): string
    {
        return static::METHOD;
    }

    /**
     * Идентификатор счётчика
     *
     * @return int|null
     */
    public function getCounterId(): ?int
    {
        return static::$counterId;
    }


}

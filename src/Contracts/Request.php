<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Contracts;

/**
 * Interface Request
 *
 * @package Rovereto\YandexMetrika\Contracts
 */
interface Request
{
    /**
     * Адрес для отправки запроса
     *
     * @return string
     */
    public function getAddress(): string;

    /**
     * Метод отправки запроса
     *
     * @return string
     */
    public function getMethod(): string;
}

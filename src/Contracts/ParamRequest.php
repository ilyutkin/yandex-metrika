<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Contracts;

/**
 * Interface ParamRequest
 *
 * @package Rovereto\YandexMetrika\Contracts
 */
interface ParamRequest extends Request
{
    /**
     * Параметры запроса
     *
     * @return array
     */
    public function getParams(): array;
}

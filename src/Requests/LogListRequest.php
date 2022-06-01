<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Requests;

use Rovereto\YandexMetrika\Contracts\Request;
use Rovereto\YandexMetrika\Requests\Concerns\RequestCore;

/**
 * Class LogListRequest
 *
 * @package Rovereto\YandexMetrika\Requests
 */
class LogListRequest extends RequestCore implements Request
{
    protected const METHOD = 'GET';
    protected const ADDRESS = 'https://api-metrica.yandex.net/management/v1/counter/{counterId}/logrequests';
}

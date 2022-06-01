<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Responses;

use JMS\Serializer\Annotation as JMS;
use Rovereto\YandexMetrika\Responses\Concerns\ErrorResponse;
use Rovereto\YandexMetrika\Responses\Types\LogRequest;

/**
 * Class CleanResponse
 *
 * @package Rovereto\YandexMetrika\Responses
 */
class CleanResponse
{
    use ErrorResponse;

    /**
     * Запрос
     *
     * @JMS\Type("Rovereto\YandexMetrika\Responses\Types\LogRequest")
     *
     * @var LogRequest
     */
    protected $log_request;

    /**
     * Запрос
     *
     * @return LogRequest
     */
    public function getLogRequest(): LogRequest
    {
        return $this->log_request;
    }
}

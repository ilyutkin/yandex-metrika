<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Responses;

use JMS\Serializer\Annotation as JMS;
use Rovereto\YandexMetrika\Responses\Concerns\ErrorResponse;

/**
 * Class LogListResponse
 *
 * @package Rovereto\YandexMetrika\Responses
 */
class LogListResponse
{
    use ErrorResponse;

    /**
     * Запросы
     *
     * @JMS\Type("array<Rovereto\YandexMetrika\Responses\Types\LogRequest>")
     *
     * @var array
     */
    protected $requests = [];

    /**
     * Есть ли запросы?
     *
     * @return bool
     */
    public function hasRequests(): bool
    {
        return !empty($this->requests);
    }

    /**
     * Запросы
     *
     * @return array
     */
    public function getRequests(): array
    {
        return $this->requests;
    }
}

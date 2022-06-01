<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Responses;

use JMS\Serializer\Annotation as JMS;
use Rovereto\YandexMetrika\Responses\Concerns\ErrorResponse;
use Rovereto\YandexMetrika\Responses\Types\LogRequestEvaluation;

/**
 * Class CapabilityResponse
 *
 * @package Rovereto\YandexMetrika\Responses
 */
class CapabilityResponse
{
    use ErrorResponse;

    /**
     * Оценка возможности создания запросов логов
     *
     * @JMS\Type("Rovereto\YandexMetrika\Responses\Types\LogRequestEvaluation")
     *
     * @var LogRequestEvaluation
     */
    protected $log_request_evaluation;

    /**
     * Оценка возможности создания запросов логов
     *
     * @return LogRequestEvaluation
     */
    public function getLogRequestEvaluation(): LogRequestEvaluation
    {
        return $this->log_request_evaluation;
    }
}

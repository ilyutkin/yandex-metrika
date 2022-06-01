<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Responses\Types;

use JMS\Serializer\Annotation as JMS;

/**
 * Class LogRequestEvaluation
 *
 * @package Rovereto\YandexMetrika\Responses\Types
 */
class LogRequestEvaluation
{
    /**
     * Возможность создания запроса логов за указанный период
     *
     * @JMS\Type("bool")
     *
     * @var bool
     */
    protected $possible;

    /**
     * Максимальное количество дней периода, который можно указать в запросах логов, с учетом текущей квоты
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $max_possible_day_quantity;

    /**
     * Возможность создания запроса логов за указанный период
     *
     * @return bool
     */
    public function isPossible(): bool
    {
        return $this->possible;
    }

    /**
     * Максимальное количество дней периода, который можно указать в запросах логов, с учетом текущей квоты
     *
     * @return int
     */
    public function getMaxPossibleDayQuantity(): int
    {
        return $this->max_possible_day_quantity;
    }
}

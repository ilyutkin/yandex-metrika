<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Responses\Types;

use JMS\Serializer\Annotation as JMS;

/**
 * Class MetrikaRequestData
 *
 * Исходный запрос.
 * Содержит параметры запроса,
 * включая развернутые параметры из шаблона и параметры для схемы параметризации атрибутов.
 *
 * @package Rovereto\YandexMetrika\Responses\Types
 */
class MetrikaRequestData
{
    /**
     * Массив значений группировок для данной строки. Каждое из значений группировки представляет собой объект.
     * В нем обязательно присутствует поле name — текстовое значение, но могут присутствовать дополнительные поля,
     * например идентификатор — id.
     *
     * @JMS\Type("array")
     *
     * @var array
     */
    protected $dimensions;

    /**
     * Массив значений метрик для данной строки. Значения этого массива — числа или null
     *
     * @JMS\Type("array")
     *
     * @var array
     */
    protected $metrics;

    /**
     * Массив значений группировок для данной строки
     *
     * @return array
     */
    public function getDimensions(): array
    {
        return $this->dimensions;
    }

    /**
     * Массив значений метрик для данной строки
     *
     * @return array
     */
    public function getMetrics(): array
    {
        return $this->metrics;
    }
}

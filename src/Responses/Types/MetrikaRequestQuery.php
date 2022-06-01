<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Responses\Types;

use JMS\Serializer\Annotation as JMS;

/**
 * Class MetrikaRequestQuery
 *
 * Исходный запрос.
 * Содержит параметры запроса,
 * включая развернутые параметры из шаблона и параметры для схемы параметризации атрибутов.
 *
 * @package Rovereto\YandexMetrika\Responses\Types
 */
class MetrikaRequestQuery
{
    /**
     * Идентификаторы счетчиков
     *
     * @JMS\Type("array")
     *
     * @var array
     */
    protected $ids;

    /**
     * Дата начала периода выборки в формате YYYY-MM-DD
     *
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $date1;

    /**
     * Дата окончания периода выборки в формате YYYY-MM-DD
     *
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $date2;

    /**
     * Массив группировок
     *
     * @JMS\Type("array")
     *
     * @var array
     */
    protected $dimensions;

    /**
     * Фильтр сегментации
     *
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $filters;

    /**
     * Количество элементов на странице выдачи
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $limit;

    /**
     * Массив метрик
     *
     * @JMS\Type("array")
     *
     * @var array
     */
    protected $metrics;

    /**
     * Индекс первой строки выборки, начиная с 1
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $offset;

    /**
     * Пресет отчета
     *
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $preset;

    /**
     * Массив сортировок
     *
     * @JMS\Type("array")
     *
     * @var array
     */
    protected $sort;

    /**
     * Часовой пояс периода выборки в формате ±hh:mm
     *
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $timezone;

    /**
     * Идентификаторы счетчиков
     *
     * @return array
     */
    public function getIds(): array
    {
        return $this->ids;
    }

    /**
     * Дата начала периода выборки в формате YYYY-MM-DD
     *
     * @return string
     */
    public function getDate1(): string
    {
        return $this->date1;
    }

    /**
     * Дата окончания периода выборки в формате YYYY-MM-DD
     *
     * @return string
     */
    public function getDate2(): string
    {
        return $this->date2;
    }

    /**
     * Массив группировок
     *
     * @return array
     */
    public function getDimensions(): array
    {
        return $this->dimensions;
    }

    /**
     * Фильтр сегментации
     *
     * @return string
     */
    public function getFilters(): string
    {
        return $this->filters;
    }

    /**
     * Количество элементов на странице выдачи
     *
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Массив метрик
     *
     * @return array
     */
    public function getMetrics(): array
    {
        return $this->metrics;
    }

    /**
     * Индекс первой строки выборки, начиная с 1
     *
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Пресет отчета
     *
     * @return string
     */
    public function getPreset(): string
    {
        return $this->preset;
    }

    /**
     * Массив сортировок
     *
     * @return array
     */
    public function getSort(): array
    {
        return $this->sort;
    }

    /**
     * Часовой пояс периода выборки в формате ±hh:mm
     *
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->timezone;
    }
}

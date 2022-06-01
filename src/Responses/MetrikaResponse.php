<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Responses;

use JMS\Serializer\Annotation as JMS;
use Rovereto\YandexMetrika\Concerns\DataPreparation;
use Rovereto\YandexMetrika\Responses\Concerns\ErrorResponse;
use Rovereto\YandexMetrika\Responses\Types\MetrikaRequestData;
use Rovereto\YandexMetrika\Responses\Types\MetrikaRequestQuery;

/**
 * Class CreateResponse
 *
 * @package Rovereto\YandexMetrika\Responses
 */
class MetrikaResponse
{
    use ErrorResponse, DataPreparation;

    /**
     * Исходный запрос.
     * Содержит параметры запроса,
     * включая развернутые параметры из шаблона и параметры для схемы параметризации атрибутов.
     *
     * @JMS\Type("Rovereto\YandexMetrika\Responses\Types\MetrikaRequestQuery")
     *
     * @var MetrikaRequestQuery
     */
    protected $query;

    /**
     * Строки ответа
     *
     * @JMS\Type("array<Rovereto\YandexMetrika\Responses\Types\MetrikaRequestData>")
     *
     * @var array
     */
    protected $data;

    /**
     * Общее количество строк в ответе по всему множеству данных (с учетом фильтра)
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $total_rows;

    /**
     * Признак того, что общее количество строк было округлено
     *
     * @JMS\Type("boolean")
     *
     * @var boolean
     */
    protected $total_rows_rounded;

    /**
     * Признак семплирования. Показывает, был ли применен семплинг. Возможные значения: true, false
     *
     * @JMS\Type("boolean")
     *
     * @var boolean
     */
    protected $sampled;

    /**
     * Признак возможного отсутствия конфиденциальных данных в ответе.
     * К ним относятся данные, которые рассчитываются алгоритмами Яндекса, например, социально-демографические
     * (пол, возраст и др.), адреса страниц входа, поисковые фразы, информация о роботах.
     * При значении true в ответе не отобразятся такие данные, если выборка составляет меньше 10 посетителей.
     *
     * @JMS\Type("boolean")
     *
     * @var boolean
     */
    protected $contains_sensitive_data;

    /**
     * Доля данных, по которым осуществлялся расчет. Доступно значение в пределах от 0 до 1
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $sample_share;

    /**
     * Количество строк в выборке данных
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $sample_size;

    /**
     * Количество строк данных
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $sample_space;

    /**
     * Задержка в обновлении данных, в секундах
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $data_lag;

    /**
     * Общие результаты для метрик по всему множеству данных (с учетом фильтра)
     *
     * @JMS\Type("array")
     *
     * @var array
     */
    protected $totals;

    /**
     * Минимальные результаты для метрик среди попавших в выдачу ключей
     *
     * @JMS\Type("array")
     *
     * @var array
     */
    protected $min;

    /**
     * Максимальные результаты для метрик среди попавших в выдачу ключей
     *
     * @JMS\Type("array")
     *
     * @var array
     */
    protected $max;

    /**
     * Исходный запрос
     *
     * @return MetrikaRequestQuery
     */
    public function getQuery(): MetrikaRequestQuery
    {
        return $this->query;
    }

    /**
     * Строки ответа
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Общее количество строк в ответе по всему множеству данных (с учетом фильтра)
     *
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total_rows;
    }
}

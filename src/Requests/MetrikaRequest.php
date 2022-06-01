<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Requests;

use DateTimeInterface;
use Rovereto\YandexMetrika\Contracts\ParamRequest;
use Rovereto\YandexMetrika\Requests\Concerns\RequestCore;

/**
 * Class MetrikaRequest
 *
 * @package Rovereto\YandexMetrika\Requests
 */
class MetrikaRequest extends RequestCore implements ParamRequest
{
    protected const METHOD = 'GET';
    protected const ADDRESS = 'https://api-metrika.yandex.net/stat/v1/data';

    /**
     * Первый день
     *
     * @var DateTimeInterface
     */
    protected $date1;

    /**
     * Последний день
     *
     * @var DateTimeInterface
     */
    protected $date2;

    /**
     * Группировки
     *
     * @var array
     */
    protected $dimensions = [];

    /**
     * Метрики
     *
     * @var array
     */
    protected $metrics = [];

    /**
     * Шаблон отчета
     *
     * @var string
     */
    protected $preset = '';

    /**
     * Сортировка
     *
     * @var array
     */
    protected $sort = [];

    /**
     * Фильтры
     *
     * @var string
     */
    protected $filters = '';

    /**
     * Лимит
     *
     * @var int
     */
    protected $limit = 0;

    /**
     * Установка первого дня
     *
     * @param DateTimeInterface $date
     * @return MetrikaRequest
     */
    public function setDate1(DateTimeInterface $date): MetrikaRequest
    {
        $this->date1 = $date;

        return $this;
    }

    /**
     * Установка первого дня
     *
     * @param DateTimeInterface $date
     * @return MetrikaRequest
     */
    public function setStartDate(DateTimeInterface $date): MetrikaRequest
    {
        return $this->setDate1($date);
    }

    /**
     * Установка последнего дня
     *
     * @param DateTimeInterface $date
     * @return MetrikaRequest
     */
    public function setDate2(DateTimeInterface $date): MetrikaRequest
    {
        $this->date2 = $date;

        return $this;
    }

    /**
     * Установка последнего дня
     *
     * @param DateTimeInterface $date
     * @return MetrikaRequest
     */
    public function setEndDate(DateTimeInterface $date): MetrikaRequest
    {
        return $this->setDate2($date);
    }

    /**
     * Установка полей
     *
     * @param array $fields
     * @return MetrikaRequest
     */
    public function setDimensions(array $dimensions): MetrikaRequest
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    /**
     * Установка источника логов
     *
     * @param array $metrics
     * @return MetrikaRequest
     */
    public function setMetrics(array $metrics): MetrikaRequest
    {
        $this->metrics = $metrics;

        return $this;
    }

    /**
     * Шаблон отчета
     *
     * @param string $preset
     * @return MetrikaRequest
     */
    public function setPreset(string $preset): MetrikaRequest
    {
        $this->preset = $preset;

        return $this;
    }

    /**
     * Установка сортировки
     *
     * @param array $sort
     * @return MetrikaRequest
     */
    public function setSort(array $sort): MetrikaRequest
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Установка фильтров
     *
     * @param string $filters
     * @return MetrikaRequest
     */
    public function setFilters(string $filters): MetrikaRequest
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Установка лимита
     *
     * @param int $limit
     * @return MetrikaRequest
     */
    public function setLimit(int $limit): MetrikaRequest
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Установка параметров запроса
     *
     * @param array $params
     * @return MetrikaRequest
     */
    public function setParams(array $params): MetrikaRequest
    {
        if (!empty($params['date1']) && ($params['date1'] instanceof DateTimeInterface)) {
            $this->setDate1($params['date1']);
        }
        if (!empty($params['date2']) && ($params['date2'] instanceof DateTimeInterface)) {
            $this->setDate2($params['date2']);
        }
        if (!empty($params['metrics'])) {
            if(!is_array($params['metrics'])) {
                $params['metrics'] = [$params['metrics']];
            }
            $this->setMetrics($params['metrics']);
        }
        if (!empty($params['dimensions'])) {
            if(!is_array($params['dimensions'])) {
                $params['dimensions'] = [$params['dimensions']];
            }
            $this->setDimensions($params['dimensions']);
        }
        if (!empty($params['filters'])) {
            $this->setFilters($params['filters']);
        }
        if (!empty($params['preset'])) {
            $this->setPreset($params['preset']);
        }
        if (!empty($params['sort'])) {
            if(!is_array($params['sort'])) {
                $params['sort'] = [$params['sort']];
            }
            $this->setSort($params['sort']);
        }
        if (!empty($params['limit'])) {
            $this->setLimit($params['limit']);
        }

        return $this;
    }

    public function getParams(): array
    {
        $params = [
            'ids' => $this->getCounterId(),
            'date1' => $this->date1->format('Y-m-d'),
            'date2' => $this->date2->format('Y-m-d')
        ];

        if (!empty($this->dimensions)) {
            $params['dimensions'] = implode(',', $this->dimensions);
        }
        if (!empty($this->filters)) {
            $params['filters'] = $this->filters;
        }
        if (!empty($this->metrics)) {
            $params['metrics'] = implode(',', $this->metrics);
        }
        if (!empty($this->preset)) {
            $params['preset'] = $this->preset;
        }
        if (!empty($this->sort)) {
            $params['sort'] = implode(',', $this->sort);
        }
        if (!empty($this->limit)) {
            $params['limit'] = $this->limit;
        }

        return $params;
    }
}

<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Requests;

use DateTimeInterface;
use Rovereto\YandexMetrika\Contracts\ParamRequest;
use Rovereto\YandexMetrika\Requests\Concerns\RequestCore;

/**
 * Class CapabilityRequest
 *
 * @package Rovereto\YandexMetrika\Requests
 */
class CapabilityRequest extends RequestCore implements ParamRequest
{
    protected const METHOD = 'GET';
    protected const ADDRESS = 'https://api-metrica.yandex.net/management/v1/counter/{counterId}/logrequests/evaluate';

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
     * Список полей
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Источник логов
     *
     * @var string
     */
    protected $source;

    /**
     * Установка первого дня
     *
     * @param  DateTimeInterface  $date
     * @return CapabilityRequest
     */
    public function setDate1(DateTimeInterface $date): CapabilityRequest
    {
        $this->date1 = $date;

        return $this;
    }

    /**
     * Установка последнего дня
     *
     * @param  DateTimeInterface  $date
     * @return CapabilityRequest
     */
    public function setDate2(DateTimeInterface $date): CapabilityRequest
    {
        $this->date2 = $date;

        return $this;
    }

    /**
     * Установка полей
     *
     * @param  array  $fields
     * @return CapabilityRequest
     */
    public function setFields(array $fields): CapabilityRequest
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Установка источника логов
     *
     * @param  string  $source
     * @return CapabilityRequest
     */
    public function setSource(string $source): CapabilityRequest
    {
        $this->source = $source;

        return $this;
    }

    public function getParams(): array
    {
        return [
            'date1' => $this->date1->format('Y-m-d'),
            'date2' => $this->date2->format('Y-m-d'),
            'fields' => implode(',', $this->fields),
            'source' => $this->source,
        ];
    }
}

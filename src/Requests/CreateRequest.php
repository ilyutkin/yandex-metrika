<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Requests;

use DateTimeInterface;
use Rovereto\YandexMetrika\Contracts\ParamRequest;
use Rovereto\YandexMetrika\Requests\Concerns\RequestCore;

/**
 * Class CreateRequest
 *
 * @package Rovereto\YandexMetrika\Requests
 */
class CreateRequest extends RequestCore implements ParamRequest
{
    protected const METHOD = 'POST';
    protected const ADDRESS = 'https://api-metrika.yandex.net/management/v1/counter/{counterId}/logrequests';

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
     * @return CreateRequest
     */
    public function setDate1(DateTimeInterface $date): CreateRequest
    {
        $this->date1 = $date;

        return $this;
    }

    /**
     * Установка последнего дня
     *
     * @param  DateTimeInterface  $date
     * @return CreateRequest
     */
    public function setDate2(DateTimeInterface $date): CreateRequest
    {
        $this->date2 = $date;

        return $this;
    }

    /**
     * Установка полей
     *
     * @param  array  $fields
     * @return CreateRequest
     */
    public function setFields(array $fields): CreateRequest
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Установка источника логов
     *
     * @param  string  $source
     * @return CreateRequest
     */
    public function setSource(string $source): CreateRequest
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

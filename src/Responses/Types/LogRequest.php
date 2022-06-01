<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Responses\Types;

use DateTime;
use JMS\Serializer\Annotation as JMS;

/**
 * Class LogRequest
 *
 * @package Rovereto\YandexMetrika\Responses\Types
 */
class LogRequest
{
    /**
     * Идентификатор запроса логов
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $request_id;

    /**
     * Идентификатор счётчика
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $counter_id;

    /**
     * Источник логов
     *
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $source;

    /**
     * Первый день
     *
     * @JMS\Type("DateTime<'Y-m-d'>")
     *
     * @var null|DateTime
     */
    protected $date1;

    /**
     * Последний день
     *
     * @JMS\Type("DateTime<'Y-m-d'>")
     *
     * @var null|DateTime
     */
    protected $date2;

    /**
     * Список полей
     *
     * @JMS\Type("array<string>")
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Статус запроса
     *
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $status;

    /**
     * Размер логов запроса в байтах
     *
     * @JMS\Type("int")
     *
     * @var null|int
     */
    protected $size;

    /**
     * Части ответа
     *
     * @JMS\Type("array<Rovereto\YandexMetrika\Responses\Types\LogRequestPart>")
     *
     * @var array
     */
    protected $parts = [];

    /**
     * Идентификатор запроса логов
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->request_id;
    }

    /**
     * Идентификатор счётчика
     *
     * @return int
     */
    public function getCounterId(): int
    {
        return $this->counter_id;
    }

    /**
     * Источник логов
     *
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * Первый день
     *
     * @return DateTime|null
     */
    public function getDate1(): ?DateTime
    {
        return $this->date1;
    }

    /**
     * Последний день
     *
     * @return DateTime|null
     */
    public function getDate2(): ?DateTime
    {
        return $this->date2;
    }

    /**
     * Список полей
     *
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Статус запроса
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Размер логов запроса в байтах
     *
     * @return int|null
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * Части ответа
     *
     * @return array
     */
    public function getParts(): array
    {
        return $this->parts;
    }
}

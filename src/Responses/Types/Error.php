<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Responses\Types;

use JMS\Serializer\Annotation as JMS;

/**
 * Class Error
 *
 * @package Rovereto\YandexMetrika\Responses\Types
 */
class Error
{
    /**
     * Тип ошибки
     *
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $error_type;

    /**
     * Причина ошибки
     *
     * @JMS\Type("string")
     *
     * @var string
     */
    protected $message;

    /**
     * Место возникновения ошибки
     *
     * @JMS\Type("string")
     *
     * @var null|string
     */
    protected $location;

    /**
     * Тип ошибки
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->error_type;
    }

    /**
     * Причина ошибки
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Место возникновения ошибки
     *
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }
}

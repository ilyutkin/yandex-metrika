<?php

declare(strict_types=1);

namespace Rovereto\YandexMetrika\Responses\Types;

use JMS\Serializer\Annotation as JMS;

/**
 * Class LogRequestPart
 *
 * @package Rovereto\YandexMetrika\Responses\Types
 */
class LogRequestPart
{
    /**
     * Номер части
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $part_number;

    /**
     * Размер в байтах
     *
     * @JMS\Type("int")
     *
     * @var int
     */
    protected $size;

    /**
     * Номер части
     *
     * @return int
     */
    public function getNumber(): int
    {
        return $this->part_number;
    }

    /**
     * Размер в байтах
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }
}

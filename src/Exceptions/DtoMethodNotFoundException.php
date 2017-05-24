<?php

namespace Zurbaev\DataTransferObjects\Exceptions;

use Zurbaev\DataTransferObjects\DataTransferObject;

class DtoMethodNotFoundException extends \RuntimeException
{
    /**
     * @param DataTransferObject $data
     * @param string             $method
     *
     * @return static
     */
    public static function make(DataTransferObject $data, string $method)
    {
        return new static('Method '.$method.' was not found on '.get_class($data).' DTO.');
    }
}

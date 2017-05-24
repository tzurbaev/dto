<?php

namespace Zurbaev\DataTransferObjects\Exceptions;

use Zurbaev\DataTransferObjects\DataTransferObject;

class DtoValidationException extends \InvalidArgumentException
{
    /**
     * Creates new exception with validation error message.
     *
     * @param DataTransferObject $data
     *
     * @return static
     */
    public static function make(DataTransferObject $data)
    {
        return new static($data->errors()->first() ?? 'Given DTO is invalid.');
    }
}

<?php

namespace Tests\Stubs;

use Zurbaev\DataTransferObjects\DataTransferObject;

/**
 * Class ExampleData
 *
 * @property string $first
 * @property int $second
 *
 * @method setFirst(string $first): DataTransferObject
 * @method getFirst(): string
 * @method setSecond(int $second): DataTransferObject
 * @method getSecond(): int
 */
class ExampleData extends DataTransferObject
{
    /**
     * ExampleData constructor.
     *
     * @param string|null $first
     * @param int|null    $second
     */
    public function __construct(string $first = null, int $second = null)
    {
        $this->first = $first;
        $this->second = $second;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            'first' => 'required|string',
            'second' => 'required|integer',
        ];
    }
}

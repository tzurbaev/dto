<?php

namespace Zurbaev\DataTransferObjects;

use Zurbaev\DataTransferObjects\Exceptions\DtoMethodNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

abstract class DataTransferObject
{
    /**
     * DTO attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * DTO validation rules.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * Validation errors bag.
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Get DTO attribute value.
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (!isset($this->attributes[$name])) {
            return null;
        }

        return $this->attributes[$name];
    }

    /**
     * Set DTO attribute value.
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Check if DTO attribute exists.
     *
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return $this->hasAttribute($name);
    }

    /**
     * Call virtual getter or setter.
     *
     * @param $method
     * @param $arguments
     *
     * @throws DtoMethodNotFoundException
     *
     * @return $this|mixed
     */
    public function __call($method, $arguments)
    {
        if (Str::startsWith($method, 'set')) {
            return $this->virtualSetter($method, $arguments);
        } elseif (Str::startsWith($method, 'get')) {
            return $this->virtualGetter($method);
        }

        throw DtoMethodNotFoundException::make($this, $method);
    }

    /**
     * Creates new DTO instance from given array.
     *
     * @param array $data
     *
     * @return static
     */
    public static function fromArray(array $data)
    {
        $instance = new static;

        foreach ($data as $key => $value) {
            $instance->{$key} = $value;
        }

        return $instance;
    }

    /**
     * @param string $method
     *
     * @return mixed
     */
    protected function virtualGetter(string $method)
    {
        $attribute = Str::snake(Str::substr($method, 3));

        return $this->getAttribute($attribute);
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return $this
     */
    protected function virtualSetter(string $method, array $arguments)
    {
        $attribute = Str::snake(Str::substr($method, 3));

        $this->{$attribute} = array_get($arguments, 0);

        return $this;
    }

    /**
     * Check if given attribute exists in current DTO.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function hasAttribute(string $attribute): bool
    {
        return isset($this->attributes[$attribute]);
    }

    /**
     * Check if given attribute exists in current DTO & has not-NULL value.
     *
     * @param string $attribute
     *
     * @return bool
     */
    public function hasNotNullAttribute(string $attribute): bool
    {
        return $this->hasAttribute($attribute) && !is_null($this->attributes[$attribute]);
    }

    /**
     * Get attribute value.
     *
     * @param string $attribute
     *
     * @return mixed
     */
    public function getAttribute(string $attribute)
    {
        return $this->attributes[$attribute];
    }

    /**
     * Validates DTO.
     *
     * @return bool
     */
    public function validate(): bool
    {
        /**
         * @var \Illuminate\Validation\Validator $validator
         */
        $validator = Validator::make($this->attributes, $this->rules());

        $result = $validator->passes();

        if (!$result) {
            $this->errors = $validator->errors();
        }

        return $result;
    }

    /**
     * Get validation errors bag.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Validation rules.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }

    /**
     * Set validation rules from external source.
     *
     * @param array $rules
     *
     * @return $this
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * Merge existed validation rules with given rules.
     *
     * @param array $rules
     *
     * @return $this
     */
    public function mergeRules(array $rules)
    {
        $this->rules = array_merge($this->rules, $rules);

        return $this;
    }
}

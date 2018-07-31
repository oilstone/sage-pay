<?php

namespace Oilstone\SagePay\Registries;


class Registry
{
    /**
     * @var array
     */
    protected static $data;

    /**
     * @param array $data
     */
    public static function store(array $data)
    {
        static::$data = $data;
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public static function get($name)
    {
        return static::$data[$name] ?? null;
    }
}
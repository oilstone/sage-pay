<?php

namespace Oilstone\SagePay\Contracts;

/**
 * Interface Transaction
 * @package Oilstone\SagePay\Contracts
 */
interface DataType
{
    /**
     * Whether a offset exists
     * @param mixed $offset
     * @return boolean true on success or false on failure.
     */
    public function offsetExists($offset);

    /**
     * Offset to retrieve
     * @param mixed $offset
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset);

    /**
     * Offset to set
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value);

    /**
     * Offset to unset
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset);

    /**
     * Return the current element
     * @return mixed
     */
    public function current();

    /**
     * Move forward to next element
     * @return void Any returned value is ignored.
     */
    public function next();

    /**
     * Return the key of the current element
     * @return mixed scalar on success, or null on failure.
     */
    public function key();

    /**
     * Checks if current position is valid
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid();

    /**
     * Rewind the Iterator to the first element
     * @return void Any returned value is ignored.
     */
    public function rewind();

    /**
     * Count elements of an object
     * @return int The custom count as an integer.
     * The return value is cast to an integer.
     */
    public function count();

    /**
     * @param $name
     * @return mixed|null
     */
    public function __get($name);

    /**
     * @return array
     */
    public function toArray(): array;
}
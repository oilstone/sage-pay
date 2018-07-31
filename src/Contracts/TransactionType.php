<?php

namespace Oilstone\SagePay\Contracts;

/**
 * Interface Transaction
 * @package Oilstone\SagePay\Contracts
 */
interface TransactionType
{
    /**
     * @return TransactionType
     */
    public function send(): TransactionType;

    /**
     * @return bool
     */
    public function failed(): bool;

    /**
     * @return bool
     */
    public function succeeded(): bool;

    /**
     * @return string
     */
    public function status(): string;

    /**
     * @return string
     */
    public function id(): string;

    /**
     * @return array
     */
    public function response(): array;

    /**
     * @return string
     */
    public function result(): string;

    /**
     * @return float
     */
    public function amount(): float;

    /**
     * @return string
     */
    public function type(): string;

    /**
     * @return string
     */
    public function reference(): string;
}
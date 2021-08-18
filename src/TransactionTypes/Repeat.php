<?php

namespace Oilstone\SagePay\TransactionTypes;

use BadMethodCallException;
use Oilstone\SagePay\Contracts\TransactionType as TypeContract;

/**
 * Class Repeat
 * @package Oilstone\SagePay\TransactionTypes
 */
class Repeat extends Transaction implements TypeContract
{
    /**
     * @return TypeContract
     */
    public function send(): TypeContract
    {
        throw new BadMethodCallException('This transaction type is not yet available in this implementation.');
    }

    /**
     * @return string
     */
    public function result(): string
    {
        return '';
    }
}

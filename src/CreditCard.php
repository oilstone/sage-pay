<?php

namespace Oilstone\SagePay;

use Omnipay\Common\CreditCard as BaseCreditCard;
use Omnipay\Common\Exception\InvalidCreditCardException;

class CreditCard extends BaseCreditCard
{
    /**
     * @throws InvalidCreditCardException
     */
    public function validate()
    {
        $requiredParameters = array(
            'number' => 'credit card number',
            'expiryMonth' => 'expiration month',
            'expiryYear' => 'expiration year'
        );

        foreach ($requiredParameters as $key => $val) {
            if (!$this->getParameter($key)) {
                throw new InvalidCreditCardException("The $val is required");
            }
        }

        if ($this->getExpiryDate('Ym') < gmdate('Ym')) {
            throw new InvalidCreditCardException('Card has expired');
        }
    }
}

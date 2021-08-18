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

    /**
     * @return string|null
     */
    public function getBrand(): ?string
    {
        return $this->getCardType() ?? parent::getBrand();
    }

    /**
     * Get Card type.
     *
     * @return string|null
     */
    public function getCardType(): ?string
    {
        return $this->getParameter('cardType');
    }

    /**
     * Set Card type.
     *
     * @param string|null $value Parameter value
     * @return $this
     */
    public function setCardType(?string $value): CreditCard
    {
        return $this->setParameter('cardType', $value);
    }
}

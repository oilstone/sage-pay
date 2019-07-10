<?php

namespace Oilstone\SagePay\Concerns;

use Oilstone\SagePay\Card;
use Oilstone\SagePay\Exceptions\SagePayException;

/**
 * Trait CreatesCards
 * @package Oilstone\SagePay\Concerns
 */
trait CreatesCards
{
    /**
     * @param array $cardDetails
     * @return $this
     * @throws SagePayException
     */
    public function card(array $cardDetails)
    {
        $card = new Card();
        $card->create($cardDetails);

        return $this;
    }
}
<?php

namespace Oilstone\SagePay\Concerns;

use Oilstone\SagePay\Card;

/**
 * Trait CreatesCards
 * @package Oilstone\SagePay\Concerns
 */
trait CreatesCards
{
    /**
     * @param array $cardDetails
     * @return $this
     */
    public function card(array $cardDetails)
    {
        $card = new Card();
        $card->create($cardDetails);

        return $this;
    }
}
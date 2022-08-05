<?php

namespace Perspective\CashierCheckout\Api;

interface CartIdAndCashierDependencyRepositoryInterface
{
    /**
     * @param $cartId
     * @param $cashierId
     * @return mixed
     */
    public function markCartAndCashier($cartId, $cashierId);
    /**
     * @param $cartId
     * @return \Perspective\CashierCheckout\Model\CartIdAndCashierId
     */
    public function getByCartId($cartId);

    /**
     * @param $cartId
     * @return \Perspective\CashierCheckout\Model\CartIdAndCashierId
     */
    public function getByCashierId($cashierId);
}

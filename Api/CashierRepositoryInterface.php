<?php

namespace Perspective\CashierCheckout\Api;

interface CashierRepositoryInterface
{
    /**
     * @param string|int $cashierId
     * @return \Perspective\CashierCheckout\Model\Cashier
     */
    public function getByCashierId($cashierId);

    /**
     * @param string $cashierName
     * @return \Perspective\CashierCheckout\Model\Cashier
     */
    public function getByCashierName($cashierName);

}

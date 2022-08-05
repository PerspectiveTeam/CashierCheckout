<?php

namespace Perspective\CashierCheckout\Api;

interface PreparedCashierRepositoryInterface
{
    /**
     * Retrieve cashier matching name.
     *
     * @param string $term | null
     * @return string | null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
public function prepareCashierArray($term = null);
}

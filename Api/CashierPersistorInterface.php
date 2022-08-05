<?php

namespace Perspective\CashierCheckout\Api;

interface CashierPersistorInterface
{
    /**
     * Persist cashier.
     *
     * @param string $cashierId | null
     * @param string $quoteId | null
     * @return string | null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setCashier($cashierId = null, $quoteId = null);
}

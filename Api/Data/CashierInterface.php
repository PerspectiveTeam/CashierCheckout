<?php

namespace Perspective\CashierCheckout\Api\Data;

interface CashierInterface
{
    /**
     * String constants for property names
     */
    const ID = "id";

    const CASHIER_ID = "cashier_id";

    const CASHIER_NAME = "cashier_name";

    const UPDATED_AT = "updated_at";

    /**
     * Getter for CashierId.
     *
     * @return int|null
     */
    public function getCashierId(): ?int;

    /**
     * Setter for CashierId.
     *
     * @param int|null $cashierId
     *
     * @return void
     */
    public function setCashierId(?int $cashierId): void;

    /**
     * Getter for UpdatedAt.
     *
     * @return int|null
     */
    public function getUpdatedAt(): ?int;

    /**
     * Setter for UpdatedAt.
     *
     * @param int|null $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt($updatedAt): void;
}

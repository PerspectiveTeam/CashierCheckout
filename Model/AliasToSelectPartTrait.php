<?php
declare(strict_types=1);

namespace Perspective\CashierCheckout\Model;

trait AliasToSelectPartTrait
{
    /**
     * Allow to add alias to select part.
     *
     * @throws \Zend_Db_Select_Exception
     */
    protected function addAliasToSelectPart(
        \Magento\Framework\Db\Select $select,
        string $partName,
        string $column,
        string $columnAlias
    ): void {
        $addAliasFunction = fn($part) => str_replace(
            "`$column`",
            "`$columnAlias`.`$column`",
            $part
        );

        $parts = $select->getPart($partName);
        $select->reset($partName);
        $select->setPart($partName, array_map($addAliasFunction, $parts));
    }
}

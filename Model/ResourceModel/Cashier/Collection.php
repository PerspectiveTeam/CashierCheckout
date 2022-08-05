<?php

namespace Perspective\CashierCheckout\Model\ResourceModel\Cashier;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Perspective\CashierCheckout\Model\Cashier as Model;
use Perspective\CashierCheckout\Model\ResourceModel\Cashier as ResourceModel;
use Zend_Db_Select_Exception;

class Collection extends AbstractCollection
{
    use \Perspective\CashierCheckout\Model\AliasToSelectPartTrait;

    /**
     * @var string
     */
    protected $_eventPrefix = 'ps_cc_cashiers_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }

    protected function _beforeLoad()
    {
        $cashierDetailsTable = 'ps_cc_cashiers_details';
        $cashierSortOrderTable = 'ps_cc_cashiers_sort_order';
        try {
            $this->joinCashiersDetailsTable(
                ['*'],
                'joinLeft',
                $cashierDetailsTable
            );
            $this->joinCashiersSortOrderTable(
                ['*'],
                'joinLeft',
                $cashierSortOrderTable
            );

            $this->addAliasToSelectPart(
                $this->getSelect(),
                'where',
                'id',
                'main_table'
            );
        } catch (LocalizedException|Zend_Db_Select_Exception $e) {
            // Do nothing. Should not be excepted never.
        }
    }

    public function joinCashiersDetailsTable(
        array $columns = [],
        string $joinType = 'joinLeft',
        string &$tableAlias = 'ps_cc_cashiers_details'
    ): self {
        $this->getSelect()
            ->$joinType(
                [$tableAlias => $this->getTable('ps_cc_cashiers_details')],
                "$tableAlias.cashier_id = main_table.id",
                $columns
            );

        return $this;
    }
    public function joinCashiersSortOrderTable(
        array $columns = [],
        string $joinType = 'joinLeft',
        string &$tableAlias = 'ps_cc_cashiers_sort_order'
    ): self {
        $this->getSelect()
            ->$joinType(
                [$tableAlias => $this->getTable('ps_cc_cashiers_sort_order')],
                "$tableAlias.cashier_id = main_table.id",
                $columns
            );

        return $this;
    }
}

<?php

namespace Perspective\CashierCheckout\Model\ResourceModel\Order\Grid;

use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as OriginalCollection;
// when extends from already overridden class, M2 is hangs
use CodeCustom\Sales\Model\ResourceModel\Order\Grid\Collection as CustomCollection;
class Collection extends OriginalCollection
{
    use \Perspective\CashierCheckout\Model\AliasToSelectPartTrait;

    protected function _renderFiltersBefore()
    {
        $joinTableCashierDependency = $this->getTable('ps_cc_cashiers_cart_cashier_dependency');
        $joinTableCashier = $this->getTable('ps_cc_cashiers');
        $joinTableSalesOrder = $this->getTable('sales_order');
        $joinTableCashierDetails = $this->getTable('ps_cc_cashiers_details');
        $this->getSelect()->joinLeft($joinTableSalesOrder, 'main_table.entity_id = sales_order.entity_id', ['sales_order.entity_id','sales_order.quote_id','attachment_url', 'attachment_name']);
        $this->getSelect()->joinLeft($joinTableCashierDependency, 'sales_order.quote_id = ps_cc_cashiers_cart_cashier_dependency.cart_id', ['cart_id','cashier_id']);
        $this->getSelect()->joinLeft($joinTableCashier, 'ps_cc_cashiers_cart_cashier_dependency.cashier_id = ps_cc_cashiers.id', ['id']);
        $this->getSelect()->joinLeft($joinTableCashierDetails, 'ps_cc_cashiers_details.cashier_id = ps_cc_cashiers.id', ['cashier_name','cashier_id']);
        parent::_renderFiltersBefore();
    }
}

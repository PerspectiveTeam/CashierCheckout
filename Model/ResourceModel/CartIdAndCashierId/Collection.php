<?php

namespace Perspective\CashierCheckout\Model\ResourceModel\CartIdAndCashierId;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Perspective\CashierCheckout\Model\CartIdAndCashierId as Model;
use Perspective\CashierCheckout\Model\ResourceModel\CartIdAndCashierId as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ps_cc_cashiers_cart_cashier_dependency_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}

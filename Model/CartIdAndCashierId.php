<?php

namespace Perspective\CashierCheckout\Model;

use Magento\Framework\Model\AbstractModel;
use Perspective\CashierCheckout\Model\ResourceModel\CartIdAndCashierId as ResourceModel;

class CartIdAndCashierId extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ps_cc_cashiers_cart_cashier_dependency_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}

<?php

namespace Perspective\CashierCheckout\Model;

use Magento\Framework\Model\AbstractModel;
use Perspective\CashierCheckout\Model\ResourceModel\CashierSortOrder as ResourceModel;

class CashierSortOrder extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ps_cc_cashiers_sort_order_model';

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

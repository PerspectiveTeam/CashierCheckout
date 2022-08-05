<?php

namespace Perspective\CashierCheckout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CashierSortOrder extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ps_cc_cashiers_sort_order_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('ps_cc_cashiers_sort_order', 'id');
        $this->_useIsObjectNew = true;
    }
}

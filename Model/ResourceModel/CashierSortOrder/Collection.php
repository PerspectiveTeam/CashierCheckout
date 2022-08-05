<?php

namespace Perspective\CashierCheckout\Model\ResourceModel\CashierSortOrder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Perspective\CashierCheckout\Model\CashierSortOrder as Model;
use Perspective\CashierCheckout\Model\ResourceModel\CashierSortOrder as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ps_cc_cashiers_sort_order_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}

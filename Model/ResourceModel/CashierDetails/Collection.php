<?php

namespace Perspective\CashierCheckout\Model\ResourceModel\CashierDetails;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Perspective\CashierCheckout\Model\CashierDetails as Model;
use Perspective\CashierCheckout\Model\ResourceModel\CashierDetails as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ps_cc_cashiers_details_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}

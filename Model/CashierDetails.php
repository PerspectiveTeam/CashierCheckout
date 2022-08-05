<?php

namespace Perspective\CashierCheckout\Model;

use Magento\Framework\Model\AbstractModel;
use Perspective\CashierCheckout\Model\ResourceModel\CashierDetails as ResourceModel;

class CashierDetails extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ps_cc_cashiers_details_model';

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

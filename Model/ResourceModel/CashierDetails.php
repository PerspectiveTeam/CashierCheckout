<?php

namespace Perspective\CashierCheckout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CashierDetails extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ps_cc_cashiers_details_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('ps_cc_cashiers_details', 'id');
        $this->_useIsObjectNew = true;
    }
}

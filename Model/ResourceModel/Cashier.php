<?php

namespace Perspective\CashierCheckout\Model\ResourceModel;

use Exception;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Perspective\CashierCheckout\Model\CashierDetailsFactory;

class Cashier extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ps_cc_cashiers_resource_model';

    /**
     * @var \Perspective\CashierCheckout\Model\ResourceModel\CashierDetails
     */
    private CashierDetails $cashierDetailsResourceModel;

    private CashierDetailsFactory $cashierDetailsFactory;

    /**
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Perspective\CashierCheckout\Model\ResourceModel\CashierDetails $cashierDetailsResourceModel
     * @param \Perspective\CashierCheckout\Model\CashierDetailsFactory $cashierDetailsFactory
     * @param string|null $connectionName
     */
    public function __construct(
        Context $context,
        CashierDetails $cashierDetailsResourceModel,
        CashierDetailsFactory $cashierDetailsFactory,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->cashierDetailsResourceModel = $cashierDetailsResourceModel;
        $this->cashierDetailsFactory = $cashierDetailsFactory;
    }

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('ps_cc_cashiers', 'id');
        $this->_useIsObjectNew = true;
    }



}

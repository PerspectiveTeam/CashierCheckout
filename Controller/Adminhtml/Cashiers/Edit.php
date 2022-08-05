<?php
declare(strict_types=1);

namespace Perspective\CashierCheckout\Controller\Adminhtml\Cashiers;

class Edit extends Index
{
    /**
     * Execute action based on request and return result
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute(): \Magento\Framework\Controller\ResultInterface
    {
        $result = parent::execute();
        if ($this->getRequest()->getParam('id')) {
            $result->getConfig()->getTitle()->prepend(__('Edit Salesman'));
        } else {
            $result->getConfig()->getTitle()->prepend(__('Add Salesman'));
        }

        return $result;
    }
}

<?php

namespace Perspective\CashierCheckout\Controller\Adminhtml\Cashiers;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Perspective\CashierCheckout\Model\ResourceModel\Cashier as Resource;
use Perspective\CashierCheckout\Model\CashierFactory as ModelFactory;
use Perspective\CashierCheckout\Model\Cashier as Model;

class Delete extends Index
{
    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var ModelFactory
     */
    private $modelFactory;

    public function __construct(
        Context $context,
        Resource $notificationResource,
        ModelFactory $notificationFactory
    ) {
        parent::__construct($context);
        $this->resource = $notificationResource;
        $this->modelFactory = $notificationFactory;
    }

    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                /** @var Model $cashier */
                $cashier = $this->modelFactory->create();
                $this->resource->load($cashier, $id);
                $this->resource->delete($cashier);
                $cashier->deleteCashierDetails();
                $cashier->deleteCashierSortOrder();
                $this->messageManager->addSuccessMessage(__('You deleted the Salesman.'));
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}

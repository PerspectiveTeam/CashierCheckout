<?php

namespace Perspective\CashierCheckout\Controller\Adminhtml\Cashiers;

use Exception;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Perspective\CashierCheckout\Model\ResourceModel\Cashier as Resource;
use Perspective\CashierCheckout\Model\CashierFactory as ModelFactory;
use Perspective\CashierCheckout\Model\Cashier as Model;

class Save extends Index
{

    private Resource $resource;

    private ModelFactory $modelFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private PageFactory $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Resource $resource,
        ModelFactory $modelFactory
    ) {
        parent::__construct($context);
        $this->resource = $resource;
        $this->modelFactory = $modelFactory;
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute(): ResultInterface
    {
        $id = $this->getRequest()->getParam('id');

        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getParams();

        if ($data) {
            $model = $this->initModel();

            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Salesman no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            foreach ($data as $key => $value) {
                try {
                    if ($key === 'cashier_id') {
                        $model->setId($value);
                        continue;
                    }
                    $model->setDataUsingMethod($key, $value);
                } catch (Exception $e) {
                    //do nothing
                }
            }

            try {
                $this->resource->save($model);
                $model->saveCashierDetails();
                $model->saveInitCashierOrder();
                $model->saveCashierOrders();
                $this->messageManager->addSuccessMessage(__('You saved the Salesman.'));

                if ($this->getRequest()->getParam('back') == 'edit') {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }

                return $resultRedirect->setPath(
                    '*/*/edit',
                    ['id' => $this->getRequest()->getParam('id')]
                );
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath(
                    '*/*/edit',
                    ['id' => $this->getRequest()->getParam('id')]
                );
            }
        } else {
            $resultRedirect->setPath('*/*/');
            $this->messageManager->addErrorMessage('No data to save.');

            return $resultRedirect;
        }
    }

    /**
     * @return Model
     */
    private function initModel()
    {
        $id = $this->getRequest()->getParam('cashier_id');
        $model = $this->modelFactory->create();
        $this->resource->load($model, $id);
        return $model;
    }
}

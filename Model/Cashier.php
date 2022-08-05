<?php

namespace Perspective\CashierCheckout\Model;

use Exception;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Perspective\CashierCheckout\Api\Data\CashierInterface;
use Perspective\CashierCheckout\Model\ResourceModel\Cashier as ResourceModel;
use Perspective\CashierCheckout\Model\ResourceModel\CashierDetails;
use Perspective\CashierCheckout\Model\ResourceModel\CashierSortOrder;
use Perspective\CashierCheckout\Model\CashierSortOrderFactory;

class Cashier extends AbstractModel implements CashierInterface
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'ps_cc_cashiers_model';

    /**
     * @var \Perspective\CashierCheckout\Model\ResourceModel\CashierDetails
     */
    private CashierDetails $cashierDetailsResourceModel;

    /**
     * @var \Perspective\CashierCheckout\Model\CashierDetailsFactory
     */
    private CashierDetailsFactory $cashierDetailsFactory;

    /**
     * @var \Perspective\CashierCheckout\Model\ResourceModel\CashierSortOrder
     */
    private CashierSortOrder $cashierSortOrderResourceModel;

    private \Perspective\CashierCheckout\Model\CashierSortOrderFactory $cashierSortOrderFactory;

    public function __construct(
        Context $context,
        Registry $registry,
        CashierDetails $cashierDetailsResourceModel,
        CashierDetailsFactory $cashierDetailsFactory,
        CashierSortOrder $cashierSortOrderResourceModel,
        CashierSortOrderFactory $cashierSortOrderFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = [])
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->cashierDetailsResourceModel = $cashierDetailsResourceModel;
        $this->cashierDetailsFactory = $cashierDetailsFactory;
        $this->cashierSortOrderResourceModel = $cashierSortOrderResourceModel;
        $this->cashierSortOrderFactory = $cashierSortOrderFactory;
    }

    /**
     * Getter for CashierId.
     *
     * @return int|null
     */
    public function getCashierId(): ?int
    {
        return $this->getData(self::CASHIER_ID) === null ? null
            : (int)$this->getData(self::CASHIER_ID);
    }

    /**
     * Getter for UpdatedAt.
     *
     * @return int|null
     */
    public function getUpdatedAt(): ?int
    {
        return $this->getData(self::UPDATED_AT) === null ? null
            : (int)$this->getData(self::UPDATED_AT);
    }

    /**
     * Setter for UpdatedAt.
     *
     * @param int|null $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt($updatedAt): void
    {
        //nothing
    }

    /**
     * @return \Perspective\CashierCheckout\Model\CashierDetails
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function saveCashierDetails()
    {
        $object = $this;
        $cashierDetailModel = $this->cashierDetailsFactory->create();
        $this->cashierDetailsResourceModel->load($cashierDetailModel, $object->getId(), 'cashier_id');
        foreach ($object->getData() as $key => $value) {
            try {
                if ($key === 'id') {
                    $cashierDetailModel->setCashierId($value);
                    continue;
                }
                $cashierDetailModel->setDataUsingMethod($key, $value);
            } catch (Exception $e) {
                // no throw
            }
        }
        $this->cashierDetailsResourceModel->save($cashierDetailModel);
        return $cashierDetailModel;
    }

    /**
     * Setter for CashierId.
     *
     * @param int|null $cashierId
     *
     * @return void
     */
    public function setCashierId(?int $cashierId): void
    {
        $this->setData(self::CASHIER_ID, $cashierId);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function saveCashierOrders()
    {
        $object = $this;
        $cashierOrders = $object->getData('ps_cc_cashier_order') ?? [];
        foreach ($cashierOrders as $cashierOrder) {
            $cashierSortOrderModel = $this->cashierSortOrderFactory->create();
            $this->cashierSortOrderResourceModel->load($cashierSortOrderModel, $cashierOrder['cashier_id'], 'cashier_id');
            foreach ($cashierOrder as $key => $value) {
                try {
                    if ($key === 'id' || $key === 'cashier_id') {
                        continue;
                    }
                    if ($key === 'position') {
                        $cashierSortOrderModel->setSortOrder($value);
                    }
                    $cashierSortOrderModel->setDataUsingMethod($key, $value);
                } catch (Exception $e) {
                    // no throw
                }
            }
            $this->cashierSortOrderResourceModel->save($cashierSortOrderModel);
        }
        return $this;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function saveInitCashierOrder()
    {
        $object = $this;
        $cashierOrders = $object->getData('ps_cc_cashier_order') ?? [];

        /** @var \Perspective\CashierCheckout\Model\CashierSortOrder $cashierSortOrderModel */
        $cashierSortOrderModel = $this->cashierSortOrderFactory->create();
        $this->cashierSortOrderResourceModel->load($cashierSortOrderModel, $object->getId(), 'cashier_id');
        if (!$cashierSortOrderModel->getId()) {
            $cashierSortOrderModel->setCashierId($object->getId());
            $this->cashierSortOrderResourceModel->save($cashierSortOrderModel);
        }

        return $this;
    }

    /**
     * @return \Perspective\CashierCheckout\Model\CashierDetails
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function deleteCashierDetails()
    {
        $object = $this;
        $cashierDetailModel = $this->cashierDetailsFactory->create();
        $this->cashierDetailsResourceModel->load($cashierDetailModel, $object->getId(), 'cashier_id');
        $this->cashierDetailsResourceModel->delete($cashierDetailModel);
        return $cashierDetailModel;
    }

    /**
     * @return \Perspective\CashierCheckout\Model\CashierSortOrder
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function deleteCashierSortOrder()
    {
        $object = $this;
        $cashierDetailModel = $this->cashierSortOrderFactory->create();
        $this->cashierSortOrderResourceModel->load($cashierDetailModel, $object->getId(), 'cashier_id');
        $this->cashierSortOrderResourceModel->delete($cashierDetailModel);
        return $cashierDetailModel;
    }

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

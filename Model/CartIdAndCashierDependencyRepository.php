<?php

namespace Perspective\CashierCheckout\Model;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Perspective\CashierCheckout\Api\CartIdAndCashierDependencyRepositoryInterface;
use Perspective\CashierCheckout\Block\Checkout\LayoutProcessor;
use Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory;

class CartIdAndCashierDependencyRepository implements CartIdAndCashierDependencyRepositoryInterface
{
    /**
     * @var \Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    private DataPersistorInterface $dataPersistor;

    /**
     * @var \Perspective\CashierCheckout\Model\CartIdAndCashierIdFactory
     */
    private CartIdAndCashierIdFactory $modelFactory;

    /**
     * @var \Perspective\CashierCheckout\Model\ResourceModel\CartIdAndCashierId
     */
    private ResourceModel\CartIdAndCashierId $resourceModel;

    /**
     * @param \Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Perspective\CashierCheckout\Model\CartIdAndCashierIdFactory $modelFactory
     * @param \Perspective\CashierCheckout\Model\ResourceModel\CartIdAndCashierId $resourceModel
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        SerializerInterface $serializer,
        DataPersistorInterface $dataPersistor,
        CartIdAndCashierIdFactory $modelFactory,
        \Perspective\CashierCheckout\Model\ResourceModel\CartIdAndCashierId $resourceModel
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->serializer = $serializer;
        $this->dataPersistor = $dataPersistor;
        $this->modelFactory = $modelFactory;
        $this->resourceModel = $resourceModel;
    }

    public function markCartAndCashier($cartId, $cashierId)
    {
        $cashierIdFromPersistor = $this->dataPersistor->get(LayoutProcessor::CASHIER_CHECKOUT_CASHIER);
        /** @var \Perspective\CashierCheckout\Model\CartIdAndCashierId $cacheModel */
        $cacheModel = $this->modelFactory->create();
        $this->resourceModel->load($cacheModel, $cartId, 'cart_id');
        $cacheModel->setCartId($cartId);
        $cacheModel->setCashierId($cashierId ?? $cashierIdFromPersistor);
        $this->resourceModel->save($cacheModel);
    }

    public function getByCartId($cartId)
    {
        $cacheModel = $this->modelFactory->create();
        $this->resourceModel->load($cacheModel, $cartId, 'cart_id');
        return $cacheModel;
    }

    public function getByCashierId($cashierId)
    {
        $cacheModel = $this->modelFactory->create();
        $this->resourceModel->load($cacheModel, $cashierId, 'cashier_id');
        return $cacheModel;
    }
}

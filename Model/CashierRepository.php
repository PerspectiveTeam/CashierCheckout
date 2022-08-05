<?php

namespace Perspective\CashierCheckout\Model;

use Perspective\CashierCheckout\Api\CashierRepositoryInterface;
use Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory;

class CashierRepository implements CashierRepositoryInterface
{
    /**
     * @var \Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @param \Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function getByCashierId($cashierId)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('id', ['like' => $cashierId]);
        return $collection->getFirstItem();
    }
    public function getByCashierName($cashierName)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('cashier_name', ['like' => $cashierName]);
        return $collection->getFirstItem();
    }
}

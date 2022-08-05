<?php

namespace Perspective\CashierCheckout\Model;

use Magento\Framework\Serialize\SerializerInterface;
use Perspective\CashierCheckout\Api\PreparedCashierRepositoryInterface;
use Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory;

class PreparedCashierRepository implements PreparedCashierRepositoryInterface
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
     * @param \Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        SerializerInterface $serializer
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->serializer = $serializer;
    }

    public function prepareCashierArray($term = null)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('cashier_name', ['like' => '%' . $term . '%']);
        $filteredItems = $collection->getItems();
        $cashierOrderArray = [];
        foreach ($filteredItems as $item) {
            $cashierItem = [];
            foreach ($item->getData() as $key => $value) {
                if ($key == 'cashier_id') {
                    $cashierItem['id'] = $value;
                }
                if ($key == 'cashier_name') {
                    $cashierItem['text'] = $value;
                }
                $cashierItem[$key] = $value;
            }
            $cashierOrderArray[] = $cashierItem;
        }
        usort(
            $cashierOrderArray,
            fn(array $first, array $second) => $first['sort_order'] - $second['sort_order']
        );

        foreach ($cashierOrderArray as &$cashiers) {
            $cashiers['current_sort_order'] = $cashiers['sort_order'];
            $cashiers['position'] = $cashiers['sort_order'];
        }
        return $this->serializer->serialize($cashierOrderArray);
    }
}

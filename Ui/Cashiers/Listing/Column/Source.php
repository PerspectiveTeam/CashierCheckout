<?php

namespace Perspective\CashierCheckout\Ui\Cashiers\Listing\Column;

use Magento\Framework\Option\ArrayInterface;
use Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory;

class Source implements ArrayInterface
{
    /**
     * @var \Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param \Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $collection = $this->collectionFactory->create();
        $filteredItems = $collection->getItems();
        $cashierOrderArray = [];
        foreach ($filteredItems as $item) {
            $cashierItem = [];
            foreach ($item->getData() as $key => $value) {
                if ($key == 'cashier_id') {
                    continue;
                    //$cashierItem['value'] = $value;
                }
                if ($key == 'cashier_name') {
                    $cashierItem['value'] = $value;
                    $cashierItem['label'] = $value;
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
        return $cashierOrderArray;
    }
}

<?php

namespace Perspective\CashierCheckout\Ui\Cashiers\Entity;

use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory;

class DataProvider extends AbstractDataProvider
{


    /**
     * @var \Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->collectionFactory = $collectionFactory;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getData()
    {
        $collection = $this->getCollection()->load();
        $mainItem = $collection->getFirstItem();
        $allItems = $this->collectionFactory->create()->getItems();
        $cashierOrderArray = [];
        foreach ($allItems as $item) {
            $cashierItem = [];
            foreach ($item->getData() as $key => $value) {
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
        $mainItem->setData('ps_cc_cashier_order', $cashierOrderArray);
        $this->data[$mainItem->getCashierId()] = $mainItem->toArray();
        return $this->data;
    }

    public function getCollection()
    {
        return $this->collection;
    }


}

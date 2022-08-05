<?php

declare(strict_types=1);

namespace Perspective\CashierCheckout\Ui\Cashiers\Listing;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Perspective\CashierCheckout\Model\ResourceModel\Cashier\Collection;
use Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory;

/**
 * DataProvider for admin listin
 *
 * @property \Perspective\CashierCheckout\Model\ResourceModel\Cashier\Collection $collection
 */
class DataProvider extends AbstractDataProvider
{

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = [])
    {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }

    /**
     * Get collection
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;

    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->joinCashiersDetailsTable(['cashier_name']);
            $this->getCollection()->load();
        }
        return $this->getCollection()->toArray();
    }

}

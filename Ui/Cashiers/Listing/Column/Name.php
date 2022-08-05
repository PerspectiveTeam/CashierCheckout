<?php

namespace Perspective\CashierCheckout\Ui\Cashiers\Listing\Column;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Perspective\CashierCheckout\Api\CashierRepositoryInterface;

class Name extends Column
{
    protected $orderRepository;

    protected $searchCriteria;

    private CashierRepositoryInterface $cashierRepository;

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $criteria
     * @param \Perspective\CashierCheckout\Api\CashierRepositoryInterface $cashierRepository
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $criteria,
        CashierRepositoryInterface $cashierRepository,
        array $components = [],
        array $data = []
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteria = $criteria;
        $this->cashierRepository = $cashierRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $order = $this->orderRepository->get($item["entity_id"]);
                //   $managerName = $order->getExtensionAttributes()->getManager()->getManagerName();
                $cashierId = $order->getData('cashier_id');
                $cashier = $this->cashierRepository->getByCashierId($cashierId);
                $cashierName = $cashier->getData('cashier_name');
                // $this->getData('name') returns the name of the column so in this case it would return cashier_name
                $item[$this->getData('name')] = $cashierName ?? '';
            }
        }

        return $dataSource;
    }
}

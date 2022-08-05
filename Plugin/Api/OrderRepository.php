<?php

namespace Perspective\CashierCheckout\Plugin\Api;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Perspective\CashierCheckout\Api\CartIdAndCashierDependencyRepositoryInterface;

class OrderRepository
{
    private CartIdAndCashierDependencyRepositoryInterface $cartIdAndCashierDependencyRepository;

    public function __construct(
        CartIdAndCashierDependencyRepositoryInterface $cartIdAndCashierDependencyRepository
    ) {
        $this->cartIdAndCashierDependencyRepository = $cartIdAndCashierDependencyRepository;
    }

    /**
     * @param \Magento\Sales\Api\OrderRepositoryInterface $subject
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     *
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ) {
        $this->addCashierIdToOrderByOrderEntity($order);
        return $order;
    }

    /**
     * @param \Magento\Sales\Api\OrderRepositoryInterface $subject
     * @param \Magento\Sales\Api\Data\OrderSearchResultInterface $searchResult
     *
     * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        $searchResult
    ) {
        foreach ($searchResult->getItems() as &$order) {
            $this->addCashierIdToOrderByOrderEntity($order);
        }

        return $searchResult;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return void
     */
    protected function addCashierIdToOrderByOrderEntity(OrderInterface &$order): void
    {
        $dependencyModel = $this->cartIdAndCashierDependencyRepository->getByCartId($order->getQuoteId());
        $cashierId = $dependencyModel->getCashierId() ?? null;
        $order->setData('cashier_id', $cashierId);
    }
}

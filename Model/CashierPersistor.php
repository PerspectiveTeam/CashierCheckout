<?php

namespace Perspective\CashierCheckout\Model;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface;
use Perspective\CashierCheckout\Api\CartIdAndCashierDependencyRepositoryInterface;
use Perspective\CashierCheckout\Api\CashierPersistorInterface;
use Perspective\CashierCheckout\Block\Checkout\LayoutProcessor;
use Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory;

class CashierPersistor implements CashierPersistorInterface
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

    private CartIdAndCashierDependencyRepositoryInterface $cartIdAndCashierIdRepository;

    private MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId;

    /**
     * @param \Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Serialize\SerializerInterface $serializer
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Perspective\CashierCheckout\Api\CartIdAndCashierDependencyRepositoryInterface $cartIdAndCashierIdRepository
     * @param \Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        SerializerInterface $serializer,
        DataPersistorInterface $dataPersistor,
        CartIdAndCashierDependencyRepositoryInterface $cartIdAndCashierIdRepository,
        MaskedQuoteIdToQuoteIdInterface $maskedQuoteIdToQuoteId
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->serializer = $serializer;
        $this->dataPersistor = $dataPersistor;
        $this->cartIdAndCashierIdRepository = $cartIdAndCashierIdRepository;
        $this->maskedQuoteIdToQuoteId = $maskedQuoteIdToQuoteId;
    }

    public function setCashier($cashierId = null, $quoteId = null)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('id', ['like' => $cashierId]);
        $firstItem = $collection->getFirstItem();
        if ($firstItem->getId()) {
            $dataToSend = ['result' => 'success', 'cashier' => $firstItem->getData()];
            $this->dataPersistor->set(LayoutProcessor::CASHIER_CHECKOUT_CASHIER, $cashierId);
            $quoteIdUnmasked = $this->maskedQuoteIdToQuoteId->execute($quoteId);
            $this->cartIdAndCashierIdRepository->markCartAndCashier($quoteIdUnmasked, $cashierId);
        } else {
            $dataToSend = ['result' => 'fail'];
        }
        return $this->serializer->serialize($dataToSend);
    }
}

<?php

namespace Perspective\CashierCheckout\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Store\Model\StoreManagerInterface;
use Perspective\CashierCheckout\Helper\Config\General;
use Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory;

class LayoutProcessor implements LayoutProcessorInterface
{
    const CASHIER_CHECKOUT_CASHIER = 'cashier_checkout_cashier';

    private General $generalConfig;

    private StoreManagerInterface $storeManager;

    /**
     * @var \Magento\Framework\Stdlib\ArrayManager
     */
    private ArrayManager $arrayManager;

    /**
     * @var \Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    private DataPersistorInterface $dataPersistor;

    /**
     * @param \Perspective\CashierCheckout\Helper\Config\General $generalConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\ArrayManager $arrayManager
     * @param \Perspective\CashierCheckout\Model\ResourceModel\Cashier\CollectionFactory $collectionFactory
     */
    public function __construct(
        General $generalConfig,
        StoreManagerInterface $storeManager,
        ArrayManager $arrayManager,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor
    ) {
        $this->generalConfig = $generalConfig;
        $this->storeManager = $storeManager;
        $this->arrayManager = $arrayManager;
        $this->collectionFactory = $collectionFactory;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function process($jsLayout)
    {
        $websites = $this->generalConfig->getWebsitesAssumedAsCashier();
        if (in_array($this->storeManager->getWebsite()->getId(), $websites)) {
            //ordered list of cashier
            $cashierStartPath = 'components/checkoutProvider/dictionaries';
            if (!$this->arrayManager->exists("{$cashierStartPath}/cashier", $jsLayout)) {
                $savedCashier = $this->dataPersistor->get(self::CASHIER_CHECKOUT_CASHIER);
                $collection = $this->collectionFactory->create();
                $collection->addFieldToFilter('id', ['like' => $savedCashier]);
                $filteredItems  = $collection->getItems();
                $cashierOrderArray = [];
                foreach ($filteredItems as $item) {
                    $cashierItem = [];
                    foreach ($item->getData() as $key => $value) {
                        if ($key == 'cashier_id') {
                            $cashierItem['value'] = $value;
                        }
                        if ($key == 'cashier_name') {
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
                $jsLayout = $this->arrayManager->set("{$cashierStartPath}/cashier", $jsLayout, $cashierOrderArray);
            }
            $layoutToMerge = [
                'component' => 'Perspective_CashierCheckout/js/view/checkout/cashier',
                'config' => [
                    'imports' => [
                        'setOptions' => 'index = checkoutProvider:dictionaries.cashier'
                    ],
                    'visible' => true,
                ],
                'dataScope' => 'cashier',
                'displayArea' => 'cashier',
                'provider' => 'checkoutProvider',
                'label' => 'Seller',
                'template' => 'Perspective_CashierCheckout/checkout/cashier',
                'validation' => [
                    'required-entry' => true
                ],
            ];
            $jsLayout = $this->arrayManager->set('components/checkout/children/steps/children/shipping-step/children/shippingAddress/children/cashier', $jsLayout, $layoutToMerge);
            $jsLayout = $this->arrayManager->set('components/checkout/children/steps/children/billing-step/children/payment/children/cashier', $jsLayout, $layoutToMerge);
        }
        return $jsLayout;
    }
}

<?php

namespace Perspective\CashierCheckout\Block\Adminhtml\Cashier\Control;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' => 'primary',
            'on_click' => '',
            'sort_order' => 90,
        ];
    }
}

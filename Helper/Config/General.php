<?php

namespace Perspective\CashierCheckout\Helper\Config;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class General extends AbstractHelper
{
    const PATH_WEBSITES = 'perspective_cashiercheckout/general/websites';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager)
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
    }

    /**
     * Get config value by path.
     *
     * @param string $path
     * @param int|string|null $storeId
     * @return int|string|null
     */
    public function get(string $path, $storeId = null)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Is send action enabled.
     *
     * @return array
     */
    public function getWebsitesAssumedAsCashier()
    {
        return explode(',', $this->get(self::PATH_WEBSITES)) ?? [];
    }
}

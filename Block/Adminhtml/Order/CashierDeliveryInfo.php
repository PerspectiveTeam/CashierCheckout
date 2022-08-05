<?php


namespace Perspective\CashierCheckout\Block\Adminhtml\Order;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Sales\Api\OrderRepositoryInterface;
use Perspective\CashierCheckout\Api\CashierRepositoryInterface;


class CashierDeliveryInfo extends Template
{
    private $forbiddenKeys = [
        'id',
        'cart_id',
        'updated_at',
        'sort_order'
    ];


    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \Perspective\CashierCheckout\Api\CashierRepositoryInterface
     */
    private CashierRepositoryInterface $cashierRepository;

    /**
     * @var \Perspective\CashierCheckout\Model\Cashier
     */
    private $cashierInfo;

    /**
     * NovaposhtaDeliveryInfo constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Perspective\CashierCheckout\Api\CashierRepositoryInterface $cashierRepository
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        OrderRepositoryInterface $orderRepository,
        CashierRepositoryInterface $cashierRepository,
        Registry $registry,

        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->orderRepository = $orderRepository;
        $this->registry = $registry;
        $this->cashierRepository = $cashierRepository;
    }

    public function allowedToShow($key, $value)
    {
        $result = false;
        if (!in_array($key, $this->forbiddenKeys)) {
            $result = true;
        }
        if (empty($value)) {
            $result = false;
        }
        return $result;
    }

    public function decorateValue($key, string $value)
    {
        $result = $value;
        return $result;
    }
    public function decorateKey($key)
    {
        $result = $key;
        if ($key == 'cashier_id') {
            $result = 'Salesman ID';
        }
        if ($key == 'cashier_name') {
            $result = 'Salesman name';
        }
        return $result;
    }

    public function getInfo()
    {
        if ($this->cashierInfo) {
            return $this->cashierInfo;
        }
        $this->cashierInfo = $this->cashierRepository->getByCashierId($this->getOrder()->getData('cashier_id'));
        return $this->cashierInfo;
    }

    /**
     * @return \Magento\Sales\Api\Data\OrderInterface
     * Бывает, что в реквесте не будет ордер_ид, тогда берем из Регистра
     * И вроде как этот прикол нужен для создания заказа в админке
     */
    protected function getOrder()
    {
        $order_id_from_request = $this->getRequest()->getParam('order_id');
        $order_id_from_registry = $this->registry->registry('order_address')
            ? $this->registry->registry('order_address')->getParentId()
            : null;
        $order_id = $order_id_from_request ?: $order_id_from_registry;
        return $this->orderRepository->get($order_id);
    }

    /**
     * @return bool
     */
    public function getEnabled()
    {
        if ($this->getOrder()->getData('cashier_id')) {
            return true;
        }
    }

    /**
     * @return int|null
     */
    protected function getQuoteId()
    {
        return $this->getOrder()->getQuoteId();
    }
}

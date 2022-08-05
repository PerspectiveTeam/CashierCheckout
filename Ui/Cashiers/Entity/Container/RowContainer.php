<?php
declare(strict_types=1);

namespace Perspective\CashierCheckout\Ui\Cashiers\Entity\Container;

class RowContainer extends \Magento\Ui\Component\Container
{
    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        array $components = [],
        array $data = []
    ) {
        $data['config']['visible'] = $this->isVisible();

        parent::__construct(
            $context,
            $components,
            $data
        );
    }

    /**
     * Check if component should be visible.
     *
     * @return bool
     */
    protected function isVisible(): bool
    {
        return true;
    }
}

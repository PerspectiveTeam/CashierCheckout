<?php
declare(strict_types=1);

namespace Perspective\CashierCheckout\Controller\Adminhtml\Cashiers;

class Index extends \Magento\Backend\App\Action implements
    \Magento\Framework\App\Action\HttpGetActionInterface,
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     */
    public const ADMIN_RESOURCE = 'Perspective_CashierCheckout::cashierslist';

    /**
     * Execute.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute(): \Magento\Framework\Controller\ResultInterface
    {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $this->initPage($resultPage);

        return $resultPage;
    }

    /**
     * Init page.
     *
     * @param \Magento\Framework\View\Result\Layout $page
     *
     * @return \Magento\Framework\View\Result\Layout
     */
    protected function initPage(\Magento\Framework\View\Result\Layout $page): \Magento\Framework\View\Result\Layout
    {
        $page->setActiveMenu('Magento_Sales::sales');
        $page->getConfig()->getTitle()->prepend(__('Salesmans'));

        return $page;
    }
}

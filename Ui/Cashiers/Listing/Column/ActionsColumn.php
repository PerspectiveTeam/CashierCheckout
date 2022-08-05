<?php

declare(strict_types=1);

namespace Perspective\CashierCheckout\Ui\Cashiers\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Perspective\CashierCheckout\Api\Data\CashierInterface;

class ActionsColumn extends Column
{
    public const XML_PATH_INDEX_HREF = 'cashiercheckout/cashiers/index';

    public const XML_PATH_EDIT_HREF = 'cashiercheckout/cashiers/edit';

    public const XML_PATH_DELETE_HREF = 'cashiercheckout/cashiers/delete';

    public const XML_PATH_SAVE_HREF = 'cashiercheckout/cashiers/save';

    //todo change action url

    /**
     * @param array<mixed> $dataSource
     * @return array<mixed>
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')] = [
                    'edit' => [
                        'href' => $this->context->getUrl(self::XML_PATH_EDIT_HREF, [
                            CashierInterface::ID => $item[CashierInterface::ID],
                        ]),
                        'label' => __('Edit'),
                    ],
                    'delete' => [
                        'href' => $this->context->getUrl(self::XML_PATH_DELETE_HREF, [
                            CashierInterface::ID => $item[CashierInterface::ID],
                        ]),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __("Delete {$item[CashierInterface::CASHIER_NAME]}"),
                            'message' => __("Are you sure you wan't to delete the cashier with name '{$item[CashierInterface::CASHIER_NAME]}'?"),
                        ],
                        'post' => true
                    ],
                ];
            }
        }

        return $dataSource;
    }
}

<?php
/**
 * Navigate
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the navigatecommerce.com license that is
 * available through the world-wide-web at this URL:
 * https://www.navigatecommerce.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Navigate
 * @package     Navigate_DeleteOrder
 * @copyright   Copyright (c) Navigate (https://www.navigatecommerce.com/)
 * @license     https://www.navigatecommerce.com/LICENSE.txt
 */

namespace Navigate\DeleteOrders\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\ObjectManagerInterface;
use Magento\Sales\Model\ResourceModel\OrderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Navigate\DeleteOrders\Helper\AbstractData;

/**
 * Class Data
 * @package Navigate\DeleteOrders\Helper
 */
class Data extends AbstractData
{
    const CONFIG_MODULE_PATH = 'delete_orders';

    /**
     * @var OrderFactory
     */
    private $orderResourceFactory;

    /**
     * Data constructor.
     * @param Context $context
     * @param ObjectManagerInterface $objectManager
     * @param StoreManagerInterface $storeManager
     * @param OrderFactory $orderResourceFactory
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager,
        OrderFactory $orderResourceFactory
    ) {
        $this->orderResourceFactory = $orderResourceFactory;

        parent::__construct($context, $objectManager, $storeManager);
    }

    /**
     * @param $orderId
     */
    public function deleteRecord($orderId)
    {
        /** @var \Magento\Sales\Model\ResourceModel\Order $resource */
        $resource = $this->orderResourceFactory->create();
        $connection = $resource->getConnection();

        /** delete invoice grid record via resource model*/
        $connection->delete(
            $resource->getTable('sales_invoice_grid'),
            $connection->quoteInto('order_id = ?', $orderId)
        );

        /** delete shipment grid record via resource model*/
        $connection->delete(
            $resource->getTable('sales_shipment_grid'),
            $connection->quoteInto('order_id = ?', $orderId)
        );

        /** delete creditmemo grid record via resource model*/
        $connection->delete(
            $resource->getTable('sales_creditmemo_grid'),
            $connection->quoteInto('order_id = ?', $orderId)
        );

        return;
    }
}

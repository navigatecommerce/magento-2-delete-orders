<?php
/**
 * @author      Navigate Commerce
 * @package     Navigate_DeleteOrder
 * @copyright   Copyright (c) Navigate (https://www.navigatecommerce.com/)
 * @license     https://www.navigatecommerce.com/end-user-license-agreement
 */

namespace Navigate\DeleteOrders\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Sales\Model\ResourceModel\OrderFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Data extends AbstractHelper
{
    protected const IS_ENABLE = 'delete_orders/general/enabled';

    /**
     * @var OrderFactory
     */
    private $orderResourceFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Data constructor.
     * @param Context $context
     * @param OrderFactory $orderResourceFactory
     * @param ScopeConfigInterface $scopeConfig
     */

    public function __construct(
        Context $context,
        OrderFactory $orderResourceFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->orderResourceFactory = $orderResourceFactory;
        $this->scopeConfig  = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * Return Module Status.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return (bool) $this->scopeConfig->getValue(self::IS_ENABLE, ScopeInterface::SCOPE_STORE);
    }

    /**
     * To delete order data using orderId.
     *
     * @param int $orderId
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
    }
}

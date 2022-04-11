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

namespace Navigate\DeleteOrders\Controller\Adminhtml\Order;

use Magento\Sales\Controller\Adminhtml\Order;
use Navigate\DeleteOrders\Helper\Data;

/**
 * Class Delete
 * @package Navigate\DeleteOrders\Controller\Adminhtml\Order
 */
class Delete extends Order
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Sales::delete';

    /**
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $helper = $this->_objectManager->get(Data::class);
        if (!$helper->isEnabled()) {
            $this->messageManager->addError(__('Cannot delete the order.'));

            return $resultRedirect->setPath('sales/order/view', ['order_id' => $this->getRequest()->getParam('order_id')]);
        }

        $order = $this->_initOrder();
        if ($order) {
            try {
                /** delete order*/
                $this->orderRepository->delete($order);
                /** delete order data on grid report data related*/
                $helper->deleteRecord($order->getId());

                $this->messageManager->addSuccessMessage(__('The order has been deleted.'));
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('sales/order/view', ['order_id' => $order->getId()]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('An error occurred while deleting the order. Please try again later.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);

                return $resultRedirect->setPath('sales/order/view', ['order_id' => $order->getId()]);
            }
        }

        return $resultRedirect->setPath('sales/*/');
    }
}

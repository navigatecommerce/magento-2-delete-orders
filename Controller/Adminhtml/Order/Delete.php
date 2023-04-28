<?php
/**
 * @author      Navigate Commerce
 * @package     Navigate_DeleteOrder
 * @copyright   Copyright (c) Navigate (https://www.navigatecommerce.com/)
 * @license     https://www.navigatecommerce.com/end-user-license-agreement
 */

namespace Navigate\DeleteOrders\Controller\Adminhtml\Order;

use Magento\Sales\Controller\Adminhtml\Order;
use Navigate\DeleteOrders\Helper\Data;

class Delete extends Order
{
    /**
     * To delete single order
     *
     * @param AbstractCollection $collection
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $order = $this->_initOrder();
        $helper = $this->_objectManager->get(Data::class);

        if (!$helper->isEnabled()) {
            $this->messageManager->addError(__('Cannot delete the order.'));
            $id = $this->getRequest()->getParam('order_id');
            return $resultRedirect->setPath('sales/order/view', ['order_id' => $id]);
        }

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
                $errorMsg = __('An error occurred while deleting the order. Please try again later.');
                $this->messageManager->addErrorMessage($errorMsg);
                return $resultRedirect->setPath('sales/order/view', ['order_id' => $order->getId()]);
            }
        }
        return $resultRedirect->setPath('sales/*/');
    }
}

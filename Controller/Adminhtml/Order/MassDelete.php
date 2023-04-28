<?php
/**
 * @author      Navigate Commerce
 * @package     Navigate_DeleteOrder
 * @copyright   Copyright (c) Navigate (https://www.navigatecommerce.com/)
 * @license     https://www.navigatecommerce.com/end-user-license-agreement
 */

namespace Navigate\DeleteOrders\Controller\Adminhtml\Order;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Sales\Controller\Adminhtml\Order\AbstractMassAction;
use Magento\Sales\Model\OrderRepository;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Ui\Component\MassAction\Filter;
use Navigate\DeleteOrders\Helper\Data as DataHelper;

class MassDelete extends AbstractMassAction
{
    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var DataHelper
     */
    protected $helper;

    /**
     * MassDelete constructor.
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param OrderRepository $orderRepository
     * @param DataHelper $dataHelper
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        OrderRepository $orderRepository,
        DataHelper $dataHelper
    ) {
        parent::__construct($context, $filter);

        $this->collectionFactory = $collectionFactory;
        $this->orderRepository   = $orderRepository;
        $this->helper            = $dataHelper;
    }

    /**
     * To delete selected order(s)
     *
     * @param AbstractCollection $collection
     */
    protected function massAction(AbstractCollection $collection)
    {
        if ($this->helper->isEnabled()) {
            $deleted = 0;

            /** @var \Magento\Sales\Api\Data\OrderInterface $order */
            foreach ($collection->getItems() as $order) {
                try {
                    /** delete order*/
                    $this->orderRepository->delete($order);
                    /** delete order data on grid report data related*/
                    $this->helper->deleteRecord($order->getId());

                    $deleted++;
                } catch (\Exception $e) {
                    $errorMsg = __('Cannot delete order #%1. Please try again later.', $order->getIncrementId());
                    $this->messageManager->addErrorMessage($errorMsg);
                }
            }
            if ($deleted) {
                $this->messageManager->addSuccessMessage(__('A total of %1 order(s) has been deleted.', $deleted));
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath($this->getComponentRefererUrl());

        return $resultRedirect;
    }
}

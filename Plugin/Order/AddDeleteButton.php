<?php
/**
 * @author      Navigate Commerce
 * @package     Navigate_DeleteOrder
 * @copyright   Copyright (c) Navigate (https://www.navigatecommerce.com/)
 * @license     https://www.navigatecommerce.com/end-user-license-agreement
 */

namespace Navigate\DeleteOrders\Plugin\Order;

use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\Sales\Block\Adminhtml\Order\View;
use Navigate\DeleteOrders\Helper\Data;

class AddDeleteButton
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var AuthorizationInterface
     */
    protected $authorization;

    /**
     * AddDeleteButton constructor.
     *
     * @param Data $helper
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        Data $helper,
        AuthorizationInterface $authorization
    ) {
        $this->helper         = $helper;
        $this->authorization = $authorization;
    }

    /**
     * Add Delete Button on Order View
     *
     * @param View $object
     * @param LayoutInterface $layout
     * @return array
     */
    public function beforeSetLayout(View $object, LayoutInterface $layout)
    {
        if ($this->helper->isEnabled() && $this->authorization->isAllowed('Magento_Sales::delete')) {
            $message = __('Are you sure you want to delete this order?');
            $object->addButton(
                'order_delete',
                [
                    'label' => __('Delete'),
                    'class' => 'delete',
                    'id'    => 'order-view-delete-button',
                    'onclick' => "confirmSetLocation('{$message}', '{$object->getDeleteUrl()}')"
                ]
            );
        }

        return [$layout];
    }
}

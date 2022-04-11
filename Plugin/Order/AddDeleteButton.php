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

namespace Navigate\DeleteOrders\Plugin\Order;

use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\Sales\Block\Adminhtml\Order\View;
use Navigate\DeleteOrders\Helper\Data;

/**
 * Class AddDeleteButton
 * @package Navigate\DeleteOrders\Plugin\Order
 */
class AddDeleteButton
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var AuthorizationInterface
     */
    protected $_authorization;

    /**
     * AddDeleteButton constructor.
     * @param Data $helper
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        Data $helper,
        AuthorizationInterface $authorization
    ) {
        $this->helper         = $helper;
        $this->_authorization = $authorization;
    }

    /**
     * @param View $object
     * @param LayoutInterface $layout
     * @return array
     */
    public function beforeSetLayout(View $object, LayoutInterface $layout)
    {
        if ($this->helper->isEnabled() && $this->_authorization->isAllowed('Magento_Sales::delete')) {
            $message = __('Are you sure you want to delete this order?');
            $object->addButton(
                'order_delete',
                [
                    'label'          => __('Delete'),
                    'class'          => 'delete',
                    'id'             => 'order-view-delete-button',
                    'onclick' => "confirmSetLocation('{$message}', '{$object->getDeleteUrl()}')"
                ]
            );
        }

        return [$layout];
    }
}

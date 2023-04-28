<?php
/**
 * @author      Navigate Commerce
 * @package     Navigate_DeleteOrder
 * @copyright   Copyright (c) Navigate (https://www.navigatecommerce.com/)
 * @license     https://www.navigatecommerce.com/end-user-license-agreement
 */

namespace Navigate\DeleteOrders\Plugin\Order;

use Magento\Framework\AuthorizationInterface;
use Magento\Ui\Component\MassAction;
use Navigate\DeleteOrders\Helper\Data;

class AddDeleteAction
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
     * AddDeleteAction constructor.
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
     * Add Delete Action in Order Grid
     *
     * @param MassAction $object
     * @param array $result
     * @return mixed
     */

    public function afterGetChildComponents(MassAction $object, $result)
    {
        if (!isset($result['nc_delete'])) {
            return $result;
        }

        if (!$this->helper->isEnabled() || !$this->authorization->isAllowed('Magento_Sales::delete')) {
            unset($result['nc_delete']);
        }

        return $result;
    }
}

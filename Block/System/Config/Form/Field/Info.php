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
namespace Navigate\DeleteOrders\Block\System\Config\Form\Field;

use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Backend system config datetime field renderer
 */
class Info extends \Magento\Config\Block\System\Config\Form\Field
{

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     * @return string
     * @codeCoverageIgnore
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $html = '<div style="background: #EF432E;padding: 10px;border-radius: 5px;text-align: center">
                    <a target="_blank" href="https://www.navigatecommerce.com/magento-extensions.html" style="color: #fff">Navigate - Marketplace Extensions</a>
                </div>';
        
        return $html;
    }
}

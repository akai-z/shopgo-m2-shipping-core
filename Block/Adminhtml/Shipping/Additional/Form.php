<?php
/**
 * Copyright © 2016 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace ShopGo\ShippingCore\Block\Adminhtml\Shipping\Additional;

use Magento\Backend\Block\Template as BackendTemplate;

class Form extends BackendTemplate
{
    /**
     * @param BackendTemplate\Context $context
     * @param array $data
     */
    public function __construct(
        BackendTemplate\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->setFormTemplate();
    }

    /**
     * Set wrapper template
     *
     * @return void
     */
    public function setFormTemplate()
    {
        $this->setTemplate('shipping/additional/form/wrapper.phtml');
    }
}

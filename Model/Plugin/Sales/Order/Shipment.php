<?php
/**
 * Copyright © 2016 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\ShippingCore\Model\Plugin\Sales\Order;

use Magento\Sales\Model\Order\Shipment as OrderShipment;

/**
 * Sales order shipment plugin
 */
class Shipment
{
    /**
     * @var \Magento\Framework\App\Action\Context
     */
    protected $context;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->context = $context;
    }

    /**
     * Inject ShopGo shipment form data before registering shipment
     *
     * @param OrderShipment $subject
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeRegister(OrderShipment $subject)
    {
        $request = $this->context->getRequest();
        $shipmentData = $request->getParam('shipment');

        if (isset($shipmentData['shopgo'])) {
            $subject->setShopgo($shipmentData['shopgo']);
        }
    }
}

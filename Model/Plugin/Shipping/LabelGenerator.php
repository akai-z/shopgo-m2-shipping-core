<?php
/**
 * Copyright © 2016 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\ShippingCore\Model\Plugin\Shipping;

use Magento\Shipping\Model\Shipping\LabelGenerator as ShippingLabelGenerator;
use Magento\Sales\Model\Order\Shipment as OrderShipment;

/**
 * Shipping label generator plugin
 */
class LabelGenerator
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
     * Inject ShopGo shipment form data before creating a shipping label
     *
     * @param ShippingLabelGenerator $subject
     * @param OrderShipment $shipment
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeCreate(ShippingLabelGenerator $subject, OrderShipment $shipment)
    {
        $request = $this->context->getRequest();
        $shipmentData = $request->getParam('shipment');

        if (isset($shipmentData['shopgo'])) {
            $shipment->setShopgo($shipmentData['shopgo']);
        }
    }
}

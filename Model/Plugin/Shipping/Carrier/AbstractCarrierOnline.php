<?php
/**
 * Copyright © 2016 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\ShippingCore\Model\Plugin\Shipping\Carrier;

use Magento\Shipping\Model\Carrier\AbstractCarrierOnline as ShippingAbstractCarrierOnline;
use Magento\Shipping\Model\Shipment\Request as ShipmentRequest;

/**
 * Shipping abstract carrier online plugin
 */
class AbstractCarrierOnline
{
    /**
     * XML path general store information cell phone
     */
    const XML_PATH_GENERAL_STORE_INFO_CELLPHONE = 'general/store_information/cellphone';

    /**
     * @var \ShopGo\ShippingCore\Helper\Data
     */
    protected $helper;

    /**
     * @param \ShopGo\ShippingCore\Helper\Data $helper
     */
    public function __construct(
        \ShopGo\ShippingCore\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * Set some shipment request data
     *
     * @param ShippingAbstractCarrierOnline $subject
     * @param ShipmentRequest $request
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeRequestToShipment(ShippingAbstractCarrierOnline $subject, ShipmentRequest $request)
    {
        $cellphone = $this->helper->getConfig()->getValue(self::XML_PATH_GENERAL_STORE_INFO_CELLPHONE);
        $request->setShipperContactCellPhoneNumber($cellphone);

        $carrierCode = $subject->getCarrierCode();
        $shippingAdditionalData = $request->getOrderShipment()->getShopgo();
        if (isset($shippingAdditionalData[$carrierCode])) {
            $request->setShippingAdditionalData($shippingAdditionalData[$carrierCode]);
        }
    }
}

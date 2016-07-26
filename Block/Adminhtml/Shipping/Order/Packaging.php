<?php
/**
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\ShippingCore\Block\Adminhtml\Shipping\Order;

/**
 * Adminhtml shipment packaging
 */
class Packaging extends \Magento\Shipping\Block\Adminhtml\Order\Packaging
{
    /**
     * Event set shipping additional form
     */
    const EVENT_SET_SHIPPING_ADDITIONAL_FORM = 'set_shipping_additional_form';

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $session;

    /**
     * @var \Magento\Shipping\Model\Carrier\CarrierInterface
     */
    protected $carrier;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Shipping\Model\Carrier\Source\GenericInterface $sourceSizeModel
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Shipping\Model\CarrierFactory $carrierFactory
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Backend\Model\Session $session
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Shipping\Model\Carrier\Source\GenericInterface $sourceSizeModel,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Shipping\Model\CarrierFactory $carrierFactory,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Backend\Model\Session $session,
        array $data = []
    ) {
        $this->eventManager = $eventManager;
        $this->session = $session;
        parent::__construct($context, $jsonEncoder, $sourceSizeModel, $coreRegistry, $carrierFactory, $data);
    }

    /**
     * Get carrier model
     *
     * @return \Magento\Shipping\Model\Carrier\CarrierInterface
     */
    public function getCarrier()
    {
        if (!$this->carrier) {
            $order = $this->getShipment()->getOrder();
            $this->carrier = $this->_carrierFactory->create(
                $order->getShippingMethod(true)->getCarrierCode()
            );
        }

        return $this->carrier;
    }

    /**
     * Get carrier code
     *
     * @return string
     */
    public function getCarrierCode()
    {
        $carrierCode = '';
        $carrier = $this->getCarrier();

        if ($carrier) {
            $carrierCode = $carrier->getCarrierCode();
        }

        return $carrierCode;
    }

    /**
     * Check whether shipping additional forms available
     *
     * @return bool
     */
    public function isShippingAdditionalFormAvailable()
    {
        $result  = false;
        $carrier = $this->getCarrier();

        if ($carrier && method_exists($carrier, 'isShippingAdditionalFormAvailable')) {
            $result = $carrier->isShippingAdditionalFormAvailable();
        }

        return $result;
    }

    /**
     * Get additional form wrapper
     *
     * @param string $formHtml
     * @return string
     */
    public function getAdditionalFormWrapper($formHtml)
    {
        return $this->getLayout()->createBlock('ShopGo\ShippingCore\Block\Adminhtml\Shipping\Additional\Form')
            ->setData(
                'shipping_additional_form_html',
                $formHtml
            )
            ->toHtml();
    }

    /**
     * Get additional form
     *
     * @return string
     */
    public function getAdditionalForm()
    {
        $form = '';

        if (!$this->isShippingAdditionalFormAvailable()) {
            return $form;
        }

        $carrier = $this->getCarrierCode();
        if (!$carrier) {
            return $form;
        }

        $this->eventManager->dispatch(
            self::EVENT_SET_SHIPPING_ADDITIONAL_FORM,
            ['carrier' => $carrier]
        );

        $shippingForm = $this->session->getShippingAdditionalForm();
        if (isset($shippingForm['carrier'][$carrier])) {
            $form = $shippingForm['carrier'][$carrier];
        }

        $form = $this->getAdditionalFormWrapper($form);

        return $form;
    }
}

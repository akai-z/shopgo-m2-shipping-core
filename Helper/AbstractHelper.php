<?php
/**
 * Copyright © 2016 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\ShippingCore\Helper;

use ShopGo\DimensionalWeightAttributes\Helper\Data as DimensionalWeightAttributesHelper;

abstract class AbstractHelper extends \ShopGo\Core\Helper\AbstractHelper
{
    /**
     * Field mask
     */
    const FIELD_MASK = '******';

    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $currency;

    /**
     * @var DimensionalWeightAttributesHelper
     */
    protected $dimensionalWeightAttributesHelper;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \ShopGo\Core\Helper\Utility $utility
     * @param DimensionalWeightAttributesHelper $dimensionalWeightAttributesHelper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \ShopGo\Core\Helper\Utility $utility,
        DimensionalWeightAttributesHelper $dimensionalWeightAttributesHelper,
        \Magento\Directory\Model\Currency $currency
    ) {
        $this->currency = $currency;
        $this->dimensionalWeightAttributesHelper = $dimensionalWeightAttributesHelper;

        parent::__construct($context, $utility);
    }

    /**
     * Convert between currencies
     *
     * @param float $price
     * @param string $to
     * @param string $from
     * @return float
     */
    public function convertCurrency($price, $to, $from = '')
    {
        $to   = strtoupper($to);
        $from = strtoupper($from);

        if (!$from) {
            $from = $this->currency->getCode();
        }
        if ($from == $to) {
            return $price;
        }

        $toCurrencyRate   = $this->currency->getAnyRate($to);
        $fromCurrencyRate = $this->currency->getAnyRate($from);

        if ($toCurrencyRate && $fromCurrencyRate) {
            $price = $this->currency->format(($price * $toCurrencyRate) / $fromCurrencyRate);
        }

        return $price;
    }

    /**
     * Convert street address into a single line
     *
     * @param string $address
     * @return string
     */
    public function getSingleLineStreetAddress($address)
    {
        return is_string($address)
            ? trim(preg_replace('/\s+/', ' ', $address)) //Replace newlines with spaces
            : $address;
    }

    /**
     * Get dimensional weight attributes codes
     *
     * @return array
     */
    public function getDimensionalWeightAttributes()
    {
        return $this->dimensionalWeightAttributesHelper->getDimensionalWeightAttributes();
    }
}

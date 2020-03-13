<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\ShippingRateByProductAttribute\Model\Config\Source;


use Magento\Framework\Exception\LocalizedException;

class ShippingCategory extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $eavConfig;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * ShippingCategory constructor.
     *
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Eav\Model\Config $eavConfig,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->eavConfig = $eavConfig;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getAllOptions()
    {
        if (empty($this->_options)) {
            try {
                $attribute      = $this->eavConfig->getAttribute('catalog_product', 'shippingnew');
                $this->_options = $attribute->getSource()->getAllOptions();
            } catch (LocalizedException $localizedException) {
                $this->logger->critical($localizedException->getLogMessage());
            }
        }

        return $this->_options;
    }
}

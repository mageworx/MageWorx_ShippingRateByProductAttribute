<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\ShippingRateByProductAttribute\Model\Config\Source;

use Magento\Framework\Exception\LocalizedException;

/**
 * Class ShippingCategory
 *
 * Obtain options for specified product attribute
 */
class ShippingCategory extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var \Magento\Catalog\Api\ProductAttributeRepositoryInterface
     */
    protected $productAttributeRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * ShippingCategory constructor.
     *
     * @param \Magento\Catalog\Api\ProductAttributeRepositoryInterface $productAttributeRepository
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $productAttributeRepository,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->productAttributeRepository = $productAttributeRepository;
        $this->logger                     = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getAllOptions()
    {
        if (empty($this->_options)) {
            try {
                /** @var \Magento\Catalog\Api\Data\ProductAttributeInterface $attribute */
                $attribute      = $this->productAttributeRepository->get('shippingnew');
                $this->_options = $attribute->usesSource() ? $attribute->getSource()->getAllOptions() : [];
            } catch (LocalizedException $localizedException) {
                $this->logger->critical($localizedException->getLogMessage());
            }
        }

        return $this->_options;
    }
}

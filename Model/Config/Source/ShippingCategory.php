<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\ShippingRateByProductAttribute\Model\Config\Source;


class ShippingCategory extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    /**
     * @inheritDoc
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['label' => __('A'), 'value' => '0'],
            ['label' => __('B'), 'value' => '1'],
            ['label' => __('C'), 'value' => '2'],
            ['label' => __('D'), 'value' => '3'],
            ['label' => __('E'), 'value' => '4'],
            ['label' => __('F'), 'value' => '5'],
            ['label' => __('G'), 'value' => '6'],
            ['label' => __('H'), 'value' => '7'],
        ];

        return $this->_options;
    }
}

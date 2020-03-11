<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\ShippingRateByProductAttribute\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class FilterRatesCollectionByShippingNewAttribute
 *
 * Filter rates collection before we load it by custom attribute: shippingnew.
 *
 * For more details
 *
 * @see \MageWorx\ShippingRules\Model\Carrier\Artificial::getSuitableRatesAccordingRequest()
 *
 */
class FilterRatesCollectionByShippingNewAttribute implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var \MageWorx\ShippingRules\Model\ResourceModel\Rate\Collection $collection */
        $collection = $observer->getEvent()->getData('rates_collection');
        if (!$collection instanceof \MageWorx\ShippingRules\Model\ResourceModel\Rate\Collection) {
            return;
        }

        /** @var \Magento\Quote\Model\Quote\Address\RateRequest $request */
        $request = $observer->getEvent()->getData('request');
        if (!$request instanceof \Magento\Quote\Model\Quote\Address\RateRequest) {
            return;
        }

        /** @var \Magento\Quote\Model\Quote\Item[] $items */
        $items        = $request->getAllItems() ?? [];
        $shippingCategories = [];
        foreach ($items as $item) {
            $value = $item->getProduct()->getData('shippingnew');
            if ($value !== null) {
                $shippingCategories[] = $value;
            }
        }
        $shippingCategories = array_unique($shippingCategories);

        $joinTable = $collection->getTable('mageworx_shippingrules_rates_shippingnew');
        $collection->getSelect()
                   ->joinLeft(
                       ['sn' => $joinTable],
                       '`main_table`.`rate_id` = `sn`.`rate_id`',
                       ['shippingnew']
                   );

        // @TODO Check it during validation
        $collection->getSelect()->where(
            "`sn`.`shippingnew` IN (?)",
            $shippingCategories
        );
    }
}

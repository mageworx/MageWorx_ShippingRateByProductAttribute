<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\ShippingRateByProductAttribute\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class AddShippingNewToRatesCollection
 *
 * Adds custom attribute to the rates collection.
 * It will be used later during quote validation.
 */
class AddShippingNewToRatesCollection implements ObserverInterface
{
    /**
     * Join custom table to the rates collection to obtain the `shippingnew` attribute anywhere in the code.
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var \MageWorx\ShippingRules\Model\ResourceModel\Rate\Collection $collection */
        $collection = $observer->getEvent()->getData('collection');

        if (!$collection instanceof \MageWorx\ShippingRules\Model\ResourceModel\Rate\Collection) {
            return;
        }

        if ($collection->isLoaded()) {
            return;
        }

        $joinTable = $collection->getTable('mageworx_shippingrules_rates_shippingnew');
        $collection->getSelect()
                   ->joinLeft(
                       $joinTable,
                       '`main_table`.`rate_id` = `' . $joinTable . '`.`rate_id`',
                       ['shippingnew']
                   );
    }
}

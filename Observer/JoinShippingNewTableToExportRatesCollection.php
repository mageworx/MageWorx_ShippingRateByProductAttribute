<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\ShippingRateByProductAttribute\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MageWorx\ShippingRules\Model\ResourceModel\Rate\Collection as RateCollection;

/**
 * Class JoinShippingNewTableToExportRatesCollection
 *
 * Join table with shipping category attribute to the export rates collection
 */
class JoinShippingNewTableToExportRatesCollection implements ObserverInterface
{

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $collection = $observer->getEvent()->getData('collection');
        if (!$collection instanceof RateCollection) {
            return;
        }

        $joinTable = $collection->getTable('mageworx_shippingrules_rates_shippingnew');
        $select    = $collection->getSelect();
        $partsFrom = $select->getPart('from');
        foreach ($partsFrom as $part) {
            if ($part['tableName'] === $joinTable) {
                return;
            }
        }

        $collection->getSelect()
                   ->joinLeft(
                       ['sn' => $joinTable],
                       '`main_table`.`rate_id` = `sn`.`rate_id`',
                       ['shippingnew']
                   );
    }
}

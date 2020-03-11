<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\ShippingRateByProductAttribute\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class RemoveShippingNewBeforeInsert
 */
class RemoveShippingNewBeforeInsert implements ObserverInterface
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $dataTransferObject = $observer->getEvent()->getData('data_transfer_object');
        $data = $dataTransferObject->getData('rates_data');
        foreach ($data as $key => &$datum) {
            unset($data[$key]['shippingnew']);
        }

        $dataTransferObject->setData('rates_data', $data);
    }
}

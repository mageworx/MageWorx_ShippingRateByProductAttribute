<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\ShippingRateByProductAttribute\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class ShippingNew
 */
class ShippingNew extends AbstractDb
{
    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageworx_shippingrules_rates_shippingnew', 'rate_id');
    }

    /**
     * @param $rateId
     * @param int $shippingNew
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function insertUpdateRecord($rateId, int $shippingNew)
    {
        $rowsAffected = $this->getConnection()->insertOnDuplicate(
            $this->getMainTable(),
            [
                'rate_id' => $rateId,
                'shippingnew' => $shippingNew
            ]
        );

        return $rowsAffected;
    }

    /**
     * @param $rateId
     * @return int
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteRecord($rateId)
    {
        $rowsAffected = $this->getConnection()->delete(
            $this->getMainTable(),
            [
                'rate_id = ?' => $rateId
            ]
        );

        return $rowsAffected;
    }
}

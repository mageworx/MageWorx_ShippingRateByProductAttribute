<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\ShippingRateByProductAttribute\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class ShippingNew
 */
class ShippingNew extends AbstractModel
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'mageworx_shippingnew';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'shippingnew';

    /**
     * Set resource model and Id field name
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('MageWorx\ShippingRateByProductAttribute\Model\ResourceModel\ShippingNew');
        $this->setIdFieldName('rate_id');
    }
}

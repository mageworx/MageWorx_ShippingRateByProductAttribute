<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\ShippingRateByProductAttribute\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use MageWorx\ShippingRules\Api\Data\RateInterface;

/**
 * Class SaveShippingNewRateAttribute
 *
 * Saves custom attribute (`shippingnew`) values after model was saved
 */
class SaveShippingNewRateAttribute implements ObserverInterface
{
    /**
     * @var \MageWorx\ShippingRateByProductAttribute\Model\ResourceModel\ShippingNew
     */
    private $resource;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messagesManager;

    /**
     * SaveVolumeWeightRateAttribute constructor.
     *
     * @param \MageWorx\ShippingRateByProductAttribute\Model\ResourceModel\ShippingNew $resource
     * @param \Magento\Framework\Message\ManagerInterface $messagesManager
     */
    public function __construct(
        \MageWorx\ShippingRateByProductAttribute\Model\ResourceModel\ShippingNew $resource,
        \Magento\Framework\Message\ManagerInterface $messagesManager
    ) {
        $this->resource        = $resource;
        $this->messagesManager = $messagesManager;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var RateInterface $model */
        $model = $observer->getEvent()->getData('rate');
        if (!$model instanceof RateInterface) {
            return;
        }

        $shippingNewValue = $model->getData('shippingnew') !== '' ? $model->getData('shippingnew') : null;


        if ($shippingNewValue === null) {
            try {
                $this->resource->deleteRecord($model->getRateId());
            } catch (LocalizedException $deleteException) {
                $this->messagesManager->addErrorMessage(
                    __('Unable to delete the Shipping Category for the Rate %1', $model->getRateId())
                );
            }
        } else {
            try {
                $this->resource->insertUpdateRecord($model->getRateId(), $shippingNewValue);
            } catch (LocalizedException $saveException) {
                $this->messagesManager->addErrorMessage(
                    __('Unable to save the Shipping Category for the Rate %1', $model->getRateId())
                );
            }
        }


        return;
    }
}

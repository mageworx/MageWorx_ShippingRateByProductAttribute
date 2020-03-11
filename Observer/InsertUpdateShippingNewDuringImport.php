<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\ShippingRateByProductAttribute\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use MageWorx\ShippingRules\Model\ImportExport\ExpressImportHandler;
use MageWorx\ShippingRules\Model\ResourceModel\Rate\Collection as RateCollection;
use Magento\Framework\Message\ManagerInterface as MessageManager;

/**
 * Class InsertUpdateShippingNewDuringImport
 */
class InsertUpdateShippingNewDuringImport implements ObserverInterface
{
    /**
     * @var ExpressImportHandler
     */
    private $importHandler;

    /**
     * @var MessageManager
     */
    private $messageManager;

    /**
     * InsertUpdateVolumeWeightDuringImport constructor.
     *
     * @param ExpressImportHandler $expressImportHandler
     * @param MessageManager $messageManager
     */
    public function __construct(
        ExpressImportHandler $expressImportHandler,
        MessageManager $messageManager
    ) {
        $this->importHandler  = $expressImportHandler;
        $this->messageManager = $messageManager;
    }

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

        $data = $observer->getEvent()->getData('data');
        if (empty($data)) {
            return;
        }

        if (!isset($data[0]['shippingnew'])) {
            // There is no shipping category column in the imported file
            return;
        }

        $conn = $collection->getConnection();

        $ratesDataWithId = $this->importHandler->fillRatesWithRateId($data);
        $dataToInsert    = [];
        $dataToDelete    = [];
        foreach ($ratesDataWithId as $rateData) {
            if ($rateData['shippingnew'] === '') {
                $dataToDelete[] = $rateData['rate_id'];
            } else {
                $dataToInsert[] = [
                    'rate_id'            => $rateData['rate_id'],
                    'shippingnew'   => $rateData['shippingnew'] === '' ? null : (int)$rateData['shippingnew']
                ];
            }
        }

        if (!empty($dataToDelete)) {
            $conn->delete(
                $collection->getTable('mageworx_shippingrules_rates_shippingnew'),
                ['rate_id' => ['in' => $dataToDelete]]
            );
        }

        if (empty($dataToInsert)) {
            return;
        }

        try {
            $this->importHandler->insertData(
                $dataToInsert,
                $conn,
                $collection->getTable('mageworx_shippingrules_rates_shippingnew')
            );
        } catch (\Zend_Db_Exception $exception) {
            $this->messageManager->addErrorMessage(__('Unable to import the shipping category attributes'));
            $this->messageManager->addErrorMessage($exception->getMessage());

            return;
        }
    }
}

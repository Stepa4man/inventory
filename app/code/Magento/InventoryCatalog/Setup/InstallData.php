<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\InventoryCatalog\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\InventoryApi\Api\Data\SourceInterfaceFactory;
use Magento\InventoryApi\Api\Data\SourceInterface;
use Magento\InventoryApi\Api\SourceRepositoryInterface;
use Magento\InventoryApi\Api\Data\StockInterfaceFactory;
use Magento\InventoryApi\Api\Data\StockInterface;
use Magento\InventoryApi\Api\StockRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;

/**
 * Install Default Source, Stock and link them together
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var SourceRepositoryInterface
     */
    private $sourceRepository;

    /**
     * @var SourceInterfaceFactory
     */
    private $sourceFactory;

    /**
     * @var StockRepositoryInterface
     */
    private $stockRepository;

    /**
     * @var StockInterfaceFactory
     */
    private $stockFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @param SourceRepositoryInterface $sourceRepository
     * @param SourceInterfaceFactory $sourceFactory
     * @param StockRepositoryInterface $stockRepository
     * @param StockInterfaceFactory $stockFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        SourceRepositoryInterface $sourceRepository,
        SourceInterfaceFactory $sourceFactory,
        StockRepositoryInterface $stockRepository,
        StockInterfaceFactory $stockFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->sourceRepository = $sourceRepository;
        $this->sourceFactory = $sourceFactory;
        $this->stockRepository = $stockRepository;
        $this->stockFactory = $stockFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->addDefaultSource();
        $this->addDefaultStock();
    }

    /**
     * Add default source
     *
     * @return void
     */
    private function addDefaultSource()
    {
        $data = [
            SourceInterface::SOURCE_ID => 1,
            SourceInterface::NAME => 'Default Source',
            SourceInterface::ENABLED => 1,
            SourceInterface::DESCRIPTION => 'Default Source',
            SourceInterface::LATITUDE => 0,
            SourceInterface::LONGITUDE => 0,
            SourceInterface::PRIORITY => 0,
            SourceInterface::COUNTRY_ID => 'US',
            SourceInterface::POSTCODE => '00000'
        ];
        $source = $this->sourceFactory->create();
        $this->dataObjectHelper->populateWithArray($source, $data, SourceInterface::class);
        $this->sourceRepository->save($source);
    }

    /**
     * Add default stock
     *
     * @return void
     */
    private function addDefaultStock()
    {
        $data = [
            StockInterface::STOCK_ID => 1,
            StockInterface::NAME => 'Default Stock'
        ];
        $source = $this->stockFactory->create();
        $this->dataObjectHelper->populateWithArray($source, $data, StockInterface::class);
        $this->stockRepository->save($source);
    }
}
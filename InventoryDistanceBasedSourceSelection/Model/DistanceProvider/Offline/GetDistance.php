<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryDistanceBasedSourceSelection\Model\DistanceProvider\Offline;

use Magento\InventoryDistanceBasedSourceSelectionApi\Model\DistanceProvider\GetDistanceInterface;
use Magento\InventoryDistanceBasedSourceSelectionApi\Model\Request\LatLngRequestInterface;

/**
 * @inheritdoc
 */
class GetDistance implements GetDistanceInterface
{
    private const EARTH_RADIUS_KM = 6371000;

    /**
     * @inheritdoc
     */
    public function execute(LatLngRequestInterface $source, LatLngRequestInterface $destination): float
    {
        $latFrom = deg2rad($source->getLat());
        $lonFrom = deg2rad($source->getLng());
        $latTo = deg2rad($destination->getLat());
        $lonTo = deg2rad($destination->getLng());

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt((sin($latDelta / 2) ** 2) +
                cos($latFrom) * cos($latTo) * (sin($lonDelta / 2) ** 2)));

        return $angle * (float) self::EARTH_RADIUS_KM;
    }
}

<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Sales\Service\V1;

use Magento\TestFramework\TestCase\WebapiAbstract;
use Magento\Webapi\Model\Rest\Config;

class OrderGetTest extends WebapiAbstract
{
    const RESOURCE_PATH = '/V1/order';

    const SERVICE_READ_NAME = 'salesOrderRepositoryV1';

    const SERVICE_VERSION = 'V1';

    const ORDER_INCREMENT_ID = '100000001';

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
    }

    /**
     * @magentoApiDataFixture Magento/Sales/_files/order.php
     */
    public function testOrderGet()
    {
        $expectedOrderData = [
            'base_subtotal' => '100.0000',
            'subtotal' => '100.0000',
            'customer_is_guest' => '1',
            'increment_id' => self::ORDER_INCREMENT_ID,
        ];
        $expectedPayments = ['method' => 'checkmo'];
        $expectedBillingAddressNotEmpty = [
            'city',
            'postcode',
            'lastname',
            'street',
            'region',
            'telephone',
            'country_id',
            'firstname',
        ];

        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->objectManager->create('Magento\Sales\Model\Order');
        $order->loadByIncrementId(self::ORDER_INCREMENT_ID);

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH . '/' . $order->getId(),
                'httpMethod' => Config::HTTP_METHOD_GET,
            ],
            'soap' => [
                'service' => self::SERVICE_READ_NAME,
                'serviceVersion' => self::SERVICE_VERSION,
                'operation' => self::SERVICE_READ_NAME . 'get',
            ],
        ];
        $result = $this->_webApiCall($serviceInfo, ['id' => $order->getId()]);

        foreach ($expectedOrderData as $field => $value) {
            $this->assertArrayHasKey($field, $result);
            $this->assertEquals($value, $result[$field]);
        }

        $this->assertArrayHasKey('payments', $result);
        foreach ($expectedPayments as $field => $value) {
            $paymentsKey = key($result['payments']);
            $this->assertArrayHasKey($field, $result['payments'][$paymentsKey]);
            $this->assertEquals($value, $result['payments'][$paymentsKey][$field]);
        }

        $this->assertArrayHasKey('billing_address', $result);
        $this->assertArrayHasKey('shipping_address', $result);
        foreach ($expectedBillingAddressNotEmpty as $field) {
            $this->assertArrayHasKey($field, $result['billing_address']);
            $this->assertArrayHasKey($field, $result['shipping_address']);
        }
    }
}

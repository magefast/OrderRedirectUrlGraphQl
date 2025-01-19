<?php

declare(strict_types=1);

namespace Dragonfly\OrderRedirectUrlGraphQl\Service;

use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Model\OrderFactory;

class PaymentRedirectUrl
{
    /**
     * @var OrderFactory
     */
    private OrderFactory $orderFactory;

    /**
     * @param OrderFactory $orderFactory
     */
    public function __construct(
        OrderFactory $orderFactory
    )
    {
        $this->orderFactory = $orderFactory;
    }

    /**
     * @param $orderIncrementNumber
     * @return string|null
     * @throws LocalizedException
     */
    public function getRedirectUrl($orderIncrementNumber): ?string
    {
        $order = $this->getOrder($orderIncrementNumber);

        if ($order) {
            return $this->getOrderPaymentRedirectUrl($order);
        }

        return null;
    }

    /**
     * @param $incrementId
     * @return Order|null
     */
    private function getOrder($incrementId): ?OrderInterface
    {
        try {
            $orderModel = $this->orderFactory->create();
            $order = $orderModel->loadByIncrementId($incrementId);
            $orderId = $order->getId();

            if ($orderId) {
                return $order;
            }
        } catch (LocalizedException $exception) {

        }

        return null;
    }

    /**
     * @param OrderInterface $order
     * @return string|null
     */
    private function getOrderPaymentRedirectUrl(OrderInterface $order): ?string
    {
        try {
            $payment = $order->getPayment();
            $paymentMethodInstance = $payment->getMethodInstance();

            if (method_exists($paymentMethodInstance, 'getOrderRedirectUrl')) {
                return $paymentMethodInstance->getOrderRedirectUrl($order);
            }
        } catch (LocalizedException $exception) {

        }

        return null;
    }
}

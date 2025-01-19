<?php

declare(strict_types=1);

namespace Dragonfly\OrderRedirectUrlGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Dragonfly\OrderRedirectUrlGraphQl\Service\PaymentRedirectUrl;

class RedirectUrl implements ResolverInterface
{
    /**
     * @var PaymentRedirectUrl
     */
    private PaymentRedirectUrl $paymentRedirectUrl;

    /**
     * @param PaymentRedirectUrl $paymentRedirectUrl
     */
    public function __construct(PaymentRedirectUrl $paymentRedirectUrl)
    {
        $this->paymentRedirectUrl =  $paymentRedirectUrl;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field       $field,
                    $context,
        ResolveInfo $info,
        array       $value = null,
        array       $args = null
    ): string
    {
        $redirectUrl = '';

        if(isset($value['order_number'])) {
            $orderPaymentUrl = $this->paymentRedirectUrl->getRedirectUrl($value['order_number']);
            if($orderPaymentUrl) {
                $redirectUrl = $orderPaymentUrl;
            }
        }

        return $redirectUrl;
    }
}

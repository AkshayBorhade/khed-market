<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SalesGraphQl\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Sales\Api\Data\InvoiceInterface as Invoice;
use Magento\Sales\Model\Order;

/**
 * Resolver for Invoice total
 */
class InvoiceTotal implements ResolverInterface
{
    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($value['model']) && !($value['model'] instanceof Invoice)) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        if (!isset($value['order']) && !($value['order'] instanceof Order)) {
            throw new LocalizedException(__('"order" value should be specified'));
        }

        /** @var Order $orderModel */
        $orderModel = $value['order'];
        /** @var Invoice $invoiceModel */
        $invoiceModel = $value['model'];
        $currency = $orderModel->getOrderCurrencyCode();
        return [
            'base_grand_total' => ['value' => $invoiceModel->getBaseGrandTotal(), 'currency' => $currency],
            'grand_total' => ['value' =>  $invoiceModel->getGrandTotal(), 'currency' => $currency],
            'subtotal' => ['value' =>  $invoiceModel->getSubtotal(), 'currency' => $currency],
            'total_tax' => ['value' =>  $invoiceModel->getTaxAmount(), 'currency' => $currency],
            'total_shipping' => ['value' => $invoiceModel->getShippingAmount(), 'currency' => $currency],
            'shipping_handling' => [
                'amount_excluding_tax' => ['value' => $invoiceModel->getShippingAmount(), 'currency' => $currency],
                'amount_including_tax' => ['value' => $invoiceModel->getShippingInclTax(), 'currency' => $currency],
                'total_amount' => ['value' => $invoiceModel->getBaseShippingAmount(), 'currency' => $currency],
            ]
        ];
    }
}

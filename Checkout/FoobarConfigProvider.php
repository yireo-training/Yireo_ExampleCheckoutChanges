<?php declare(strict_types=1);

namespace Yireo\ExampleCheckoutChanges\Checkout;

use Magento\Checkout\Model\ConfigProviderInterface;

class FoobarConfigProvider implements ConfigProviderInterface
{
    public function getConfig()
    {
        return ['customerData' => ['foo' => 'bar']];
    }
}


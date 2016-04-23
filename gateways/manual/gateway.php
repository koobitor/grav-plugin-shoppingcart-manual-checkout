<?php
namespace Grav\Plugin\ShoppingCart;

use RocketTheme\Toolbox\Event\Event;
use Omnipay\Omnipay;

/**
 * Class GatewayManual
 * @package Grav\Plugin\ShoppingCart
 */
class GatewayManual extends Gateway
{
    protected $name = 'manual_checkout';
    
    /**
     * Handle paying via this gateway
     *
     * @param Event $event
     *
     * @event onShoppingCartSaveOrder signal to save the order
     * @event onShoppingCartReturnOrderPageUrlForAjax signal to return the order page and exit, for AJAX processing
     *
     * @return mixed|void
     */
    public function onShoppingCartPay(Event $event)
    {
        if (!$this->isCurrentGateway($event['gateway'])) { return false; }

        $order = $this->getOrderFromEvent($event);

        try {
            $this->grav->fireEvent('onShoppingCartSaveOrder', new Event(['gateway' => $this->name, 'order' => $order]));
            $this->grav->fireEvent('onShoppingCartReturnOrderPageUrlForAjax', new Event(['gateway' => $this->name, 'order' => $order]));
        } catch (\Exception $e) {
            // internal error, log exception and display a generic message to the customer
            throw new \RuntimeException('Sorry, there was an error processing your payment: ' . $e->getMessage());
        }
    }
}



<?php namespace Milkyway\Shop\CheckoutExtras\Checkout;
/**
 * Milkyway Multimedia
 * PaymentForm.php
 *
 * @package reggardocolaianni.com
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
class PaymentForm extends \PaymentForm {
    public function __construct($controller, $name, \CheckoutComponentConfig $config) {
        parent::__construct($controller, $name, $config);

        if($this->Validator && $this->Validator instanceof \CheckoutComponentValidator)
            $this->Validator = \CheckoutComponentValidator::create($this->config);
    }
} 
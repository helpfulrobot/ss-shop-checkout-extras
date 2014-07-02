<?php

/**
 * Milkyway Multimedia
 * DependantBillingAddressCheckoutComponent.php
 *
 * @package reggardocolaianni.com.au
 * @author Mellisa Hankins <mell@milkywaymultimedia.com.au>
 */
abstract class DependantAddressCheckoutComponent extends AddressCheckoutComponent implements \Milkyway\Shop\CheckoutExtras\Contracts\ConditionalRequiredFields
{
    public function getFormFields(Order $order)
    {
        $field = $this->addresstype . 'ToSameAddress';
        $checkbox = CheckboxField::create($field, _t('Dependant' . $this->addresstype . 'AddressCheckoutComponent.SAME_AS_' . $this->useAddresstype, 'Use ' . $this->useAddresstype . ' address'), true);

        return FieldList::create(
            $checkbox,
            CompositeField::create(
                parent::getFormFields($order)
            )
                ->setName($this->addresstype . 'Address')
                ->setAttribute('data-hide-if', '[name=' . get_class($this) . '_' . $field . ']:checked')
        );
    }

    public function getRequiredFields(Order $order) {
        return array();
    }

    public function setData(Order $order, array $data) {
        if(isset($data[$this->addresstype . 'ToSameAddress'])) {
            parent::setData($order, $data);
        }
        else {
            $order->{$this->addresstype."AddressID"} = $order->{$this->useAddresstype."AddressID"};

            if(!$order->BillingAddressID)
                $order->BillingAddressID = $order->{$this->useAddresstype."AddressID"};
        }
    }

    public function getRequiredIf(Order $order) {
        $required = parent::getRequiredFields($order);
        $requiredIf = array();
        $namespace = get_class($this);

        foreach($required as $requirement)
            $requiredIf[$namespace . '_' . $requirement] = $namespace . '_' . $this->addresstype . 'ToSameAddress:not(:checked)';

        return $requiredIf;
    }
}

class DependantShippingAddressCheckoutComponent extends DependantAddressCheckoutComponent {

    protected $addresstype = "Shipping";
    protected $useAddresstype = "Billing";

}

class DependantBillingAddressCheckoutComponent extends DependantAddressCheckoutComponent {

    protected $addresstype = "Billing";
    protected $useAddresstype = "Shipping";
}
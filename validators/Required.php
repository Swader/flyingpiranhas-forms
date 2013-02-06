<?php

namespace flyingpiranhas\forms\validators;

use flyingpiranhas\forms\elements;
use flyingpiranhas\forms\elements\Checkbox;

/**
 * The Required validator can be used with a number of elements
 * to check whether that element posted anything.
 * Returns true if the element value is set.
 * For Select, Text and derived elements, it checks for empty value.
 * For Checkbox and derived elements, it checks the $bIsChecked property.
 * For elements that post arrays of values it checks the number of values, requiring at least one
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        Apache-2.0
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Required extends Validator
{

    /** @var string */
    protected $sHint = '*';

    /** @var string */
    protected $sType = 'required';

    /** @var string */
    protected $sErrorMessage = 'Required';

    /**
     * @param array $aProperties
     */
    public function __construct(array $aProperties = array())
    {
        parent::__construct($aProperties);
        $this->setValidatorFunction(
            function () {
                $oElement = $this->oElement;

                if ($oElement instanceof Checkbox) {
                    /** @var $oElement Checkbox */
                    if ($oElement->isChecked()) {
                        return true;
                    }
                    return false;
                }

                $mValue = $oElement->getValue();

                if ($oElement instanceof elements\File) {
                    if (empty($mValue['size'])) {
                        return false;
                    }
                }

                if (is_array($mValue)) {
                    foreach ($mValue as $mVal) {
                        if (!empty($mVal)) {
                            return true;
                        }
                    }
                    return false;
                }

                $mValue = trim($mValue);
                if ($mValue == '') {
                    return false;
                }
                return true;
            }
        );
    }

}
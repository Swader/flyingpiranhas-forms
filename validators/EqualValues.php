<?php

namespace flyingpiranhas\forms\validators;

/**
 * The EqualValues validator should be used with elements
 * that post an array of values (Groups, Select with multiselect options)
 * to check that all posted values are the same.
 * When the input is not an array, it always validates succesfully.
 *
 * An example would be a group of password elements,
 * with password and repeat password elements.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        Apache-2.0
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class EqualValues extends Validator
{

    /** @var string */
    protected $sType = 'equalValues';

    /** @var string */
    protected $sErrorMessage = 'These values need to match';

    /**
     * @param array $aProperties
     */
    public function __construct(array $aProperties = array())
    {
        parent::__construct($aProperties);
        $this->setValidatorFunction(
            function () {
                $aValues = array();

                if (!is_array($this->oElement->getValue())) {
                    return true;
                }
                foreach ($this->oElement->getValue() as $sValue) {
                    if (!empty($aValues) && !in_array($sValue, $aValues)) {
                        return false;
                    }
                    $aValues[] = $sValue;
                }

                return true;
            }
        );
    }

}
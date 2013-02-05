<?php

namespace flyingpiranhas\forms\validators;

/**
 * The Range validator can be used with a number of elements
 * to check if the values are within a required range.
 * Returns true if the value is within the given range.
 * For numeric values it checks the number itself.
 * For text values it checks the number of characters.
 * For array values it checks the nubmer of elements.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        BSD License
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Range extends Validator
{

    /** @var int */
    protected $iMin = 0;

    /** @var int */
    protected $iMax = 0;

    /** @var string */
    protected $sType = 'range';

    /** @var string */
    protected $sErrorMessage = 'Out of range';

    /**
     * @param array $aProperties
     */
    public function __construct(array $aProperties = array())
    {
        parent::__construct($aProperties);

        $iMin = $this->iMin;
        $iMax = $this->iMax;
        $this->setValidatorFunction(
            function () use ($iMin, $iMax) {
                $mValue = $this->oElement->getValue();

                $iCount = 0;
                if (is_numeric($mValue)) {
                    $iCount = $mValue;
                } else if (is_array($mValue)) {
                    foreach ($mValue as $mVal) {
                        if ($mVal) {
                            $iCount++;
                        }
                    }
                } else {
                    $iCount = strlen($mValue);
                }

                if (($iMin !== false && $iCount < $iMin) || ($iMax !== false && $iCount > $iMax)) {
                    return false;
                }

                return true;
            }
        );
    }

    /**
     * @param int $iMin
     *
     * @return Range
     */
    public function setMin($iMin)
    {
        $this->iMin = $iMin;
        return $this;
    }

    /**
     * @param int $iMax
     *
     * @return Range
     */
    public function setMax($iMax)
    {
        $this->iMax = $iMax;
        return $this;
    }

}
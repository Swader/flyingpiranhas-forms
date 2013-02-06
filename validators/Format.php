<?php

namespace flyingpiranhas\forms\validators;

use flyingpiranhas\forms\exceptions\FormException;

/**
 * The Format validator should be used with Text elements, or derivations,
 * to check that the posted value is in the required format.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        Apache-2.0
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Format extends Validator
{

    const ALPHA = '/^[a-zA-Z]+$/';
    const ALNUM = '/^[a-zA-Z0-9]+$/';
    const NUM = '/^[0-9]+$/';
    const EMAIL = '';

    /** @var string */
    protected $sFormat = '';

    /** @var string */
    protected $sType = 'format';

    /** @var string */
    protected $sErrorMessage = 'Invalid format';

    /**
     * @param array $aProperties
     *
     * @throws FormException
     */
    public function __construct(array $aProperties = array())
    {
        parent::__construct($aProperties);

        $sFormat = $this->sFormat;
        $this->setValidatorFunction(
            function () use ($sFormat) {
                $mValue = $this->oElement->getValue();

                if (!is_scalar($mValue) && $mValue !== null) {
                    throw new FormException('Cannot validate format of a non scalar value');
                }

                if ($sFormat && $mValue && !preg_match($sFormat, $mValue)) {
                    return false;
                }

                return true;
            }
        );
    }

    /**
     * @param string $sFormat
     *
     * @return Format
     */
    protected function setFormat($sFormat)
    {
        $this->sFormat = trim($sFormat);
        return $this;
    }

}
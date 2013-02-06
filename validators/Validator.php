<?php

namespace flyingpiranhas\forms\validators;

use flyingpiranhas\common\traits\ProtectedPropertySetter;
use Closure;
use BadFunctionCallException;
use flyingpiranhas\forms\elements\abstracts\ElementAbstract;

/**
 * The Validator is the base class for all other validators.
 * It holds a reference to the element, and provides wrappers for
 * easy access to some properties of the element.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        Apache-2.0
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Validator
{

    use ProtectedPropertySetter;

    /** @var ElementAbstract */
    protected $oElement;

    /** @var Closure */
    protected $oValidatorFunction;

    /** @var string */
    protected $sHint = '';

    /** @var string */
    protected $sErrorMessage = '';

    /** @var string */
    protected $sType = 'validator';

    /**
     * @param array $aProperties
     */
    public function __construct(array $aProperties = array())
    {
        $this->setProperties($aProperties);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->sType;
    }

    /**
     * @return bool
     * @throws BadFunctionCallException
     */
    public function isValid()
    {
        if (!$this->isPostback()) {
            return true;
        }

        if (!$this->oValidatorFunction instanceof Closure) {
            throw new BadFunctionCallException('The validator function is not a valid PHP closure');
        }

        $oValFun = $this->oValidatorFunction;
        return $oValFun();
    }

    /**
     * @return bool
     */
    public function isPostback()
    {
        return $this->oElement->isPostback();
    }

    /**
     * @param string $sHint
     *
     * @return Validator
     */
    public function setHint($sHint)
    {
        $this->sHint = $sHint;
        return $this;
    }

    /**
     * @param string $sErrorMessage
     *
     * @return Validator
     */
    public function setErrorMessage($sErrorMessage)
    {
        $this->sErrorMessage = $sErrorMessage;
        return $this;
    }

    /**
     * @param ElementAbstract $oElement
     *
     * @return Validator
     */
    public function setElement(ElementAbstract $oElement)
    {
        $this->oElement = $oElement;
        return $this;
    }

    /**
     * @param Closure $oValidatorFunction
     *
     * @return Validator
     */
    public function setValidatorFunction(Closure $oValidatorFunction)
    {
        $this->oValidatorFunction = $oValidatorFunction;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return ($this->isValid()) ? $this->sHint : $this->sErrorMessage;
    }

}
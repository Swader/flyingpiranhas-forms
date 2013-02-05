<?php

namespace flyingpiranhas\forms\elements;

use flyingpiranhas\forms\exceptions\FormException;
use flyingpiranhas\forms\elements\abstracts\ElementAbstract;
use flyingpiranhas\forms\validators\Validator;

/**
 * A Group of elements groups the elements of a form under a common name.
 * When rendering, each element will have the name: groupName[elementName].
 * The submitted values of the group will be an array, whith the element names as keys.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        BSD License
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Group extends ElementAbstract
{

    /** @var array */
    protected $aElements = array();

    /** @var array */
    protected $aElementTemplate = array();

    /**
     * @param array $aProperties
     */
    public function __construct(array $aProperties = array())
    {
        foreach ($aProperties as $sSetter => $mValue) {
            if ($sSetter == 'elements') {
                continue;
            }

            $sSetter = 'set' . ucfirst($sSetter);
            if (method_exists($this, $sSetter)) {
                $this->$sSetter($mValue);
            }
        }

        if (isset($aProperties['elements'])) {
            $this->setElements($aProperties['elements']);
        }
    }

    /**
     * @return array
     */
    public function getElements()
    {
        return $this->aElements;
    }

    /**
     * @param $sKey
     *
     * @return ElementAbstract
     * @throws FormException
     */
    public function getElement($sKey)
    {
        if (!isset($this->aElements[$sKey])) {
            throw new FormException('The element ' . $sKey . ' was not defined');
        }
        return $this->aElements[$sKey];
    }

    /**
     * @return ElementAbstract
     */
    public function getElementTemplate()
    {
        return $this->buildElement($this->aElementTemplate);
    }

    /**
     * @return array
     */
    public function getValue()
    {
        $aValues = array();

        foreach ($this->aElements as $sKey => $oElement) {
            if ($oElement instanceof Checkbox && $oElement->isChecked()) {
                $aValues[$sKey] = $oElement->getValue();
            } else if (!($oElement instanceof Checkbox)) {
                $aValues[$sKey] = $oElement->getValue();
            }
        }
        return $aValues;
    }

    /**
     * @param array $aElements
     *
     * @return Group
     */
    public function setElements($aElements)
    {
        $this->aElements = array();
        foreach ($aElements as $sKey => $oElement) {
            $this->addElement($sKey, $oElement);
        }
        return $this;
    }

    /**
     * @param array $aElementTemplate
     *
     * @return Group
     */
    public function setElementTemplate(array $aElementTemplate)
    {
        $this->aElementTemplate = $aElementTemplate;
        return $this;
    }

    /**
     * @param $sKey
     * @param $oElement
     *
     * @return Group
     */
    public function addElement($sKey, $oElement)
    {
        $oElement = $this->buildElement($oElement);
        $this->aElements[$sKey] = $oElement;
        if ($oElement->getName() === null) {
            $oElement->setName($sKey);
        }
        return $this;
    }

    /**
     * @param array $mValue
     *
     * @return Group
     */
    public function setValue($mValue)
    {
        $mValue = (!empty($mValue)) ? $mValue : array();

        $this->buildElementsFromValues($mValue);

        /** @var $oElement ElementAbstract */
        foreach ($this->aElements as $sKey => $oElement) {
            if ($oElement instanceof Checkbox) {
                /** @var $oElement Checkbox */
                $oElement->setChecked(isset($mValue[$sKey]) && $mValue[$sKey] == $oElement->getValue());
            } else if (isset($mValue[$sKey])) {
                $oElement->setValue($mValue[$sKey]);
            } else {
                $oElement->setValue(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        /** @var $oValidator Validator */
        foreach ($this->getValidators() as $oValidator) {
            if (!$oValidator->isValid()) {
                return false;
            }
        }

        /** @var $oElement ElementAbstract */
        foreach ($this->aElements as $oElement) {
            if ($oElement instanceof Group) {
                /** @var $oElement Group */

                /** @var $oInnerElement ElementAbstract */
                foreach ($oElement->getElements() as $oInnerElement) {
                    if (!$oInnerElement->isValid()) {
                        return false;
                    }
                }
            } else {
                /** @var $oValidator Validator */
                foreach ($oElement->getValidators() as $oValidator) {
                    if (!$oValidator->isValid()) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Builds an element from an array of settings
     *
     * @param ElementAbstract|array|null $aElement
     *
     * @return ElementAbstract
     */
    protected function buildElement(&$aElement)
    {
        if (empty($aElement)) {
            return null;
        }

        if ($aElement instanceof ElementAbstract) {
            return $aElement->setForm($this->oForm)->setGroup($this);
        }

        $sClassName = __NAMESPACE__ . '\\' . ucfirst($aElement['type']);
        $aElement['form'] = $this->oForm;
        $aElement['group'] = $this;

        unset($aElement['type']);

        return new $sClassName($aElement);
    }

    /**
     * If there are more values than elements, then the elements are build using the elementTemplate.
     * This is useful when adding elements on the client side before submitting
     *
     * @param array $mValue
     *
     * @return Group
     */
    protected function buildElementsFromValues($mValue)
    {
        $mValue = (!empty($mValue)) ? $mValue : array();

        if (empty($this->aElementTemplate)) {
            return $this;
        }

        $this->aElements = array();
        foreach ($mValue as $sKey => $mVal) {
            if (!isset($this->aElements[$sKey])) {
                $this->addElement($sKey, $this->aElementTemplate);
                $this->getElement($sKey)->setValue($mVal);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $sElement = '';

        /** @var $oElement ElementAbstract */
        foreach ($this->getElements() as $oElement) {
            $sElement = $oElement->__toString();
        }
        return $sElement;
    }

}
<?php

namespace flyingpiranhas\forms\elements\abstracts;

use flyingpiranhas\common\traits\PropertySetter;
use flyingpiranhas\forms\elements\Group;
use flyingpiranhas\forms\exceptions\FormException;
use flyingpiranhas\forms\Form;
use flyingpiranhas\forms\validators\Validator;
use InvalidArgumentException;

/**
 * The base class for all elements.
 * Holds references to the parent form, parent group (if any)
 * and the validators for the element
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        BSD License
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
abstract class ElementAbstract
{

    use PropertySetter;

    /** @var string */
    protected $sName = '';

    /** @var string */
    protected $sLabel = '';

    /** @var mixed */
    protected $mValue;

    /** @var array */
    protected $aHtmlAttributes = array();

    /** @var array */
    protected $aValidators = array();

    /** @var Form */
    protected $oForm;

    /** @var Group */
    protected $oGroup;

    /**
     * @param array $aProperties
     */
    public function __construct(array $aProperties = array())
    {
        $this->setProperties($aProperties);
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->oForm;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->sName;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->sLabel;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->mValue;
    }

    /**
     * @return array
     */
    public function getHtmlAttributes()
    {
        return $this->aHtmlAttributes;
    }

    /**
     * @param $sName
     *
     * @return string
     */
    public function getHtmlAttribute($sName)
    {
        return $this->aHtmlAttributes[$sName];
    }

    /**
     * @return array
     */
    public function getValidators()
    {
        return $this->aValidators;
    }

    /**
     * @param $sKey
     *
     * @return Validator
     * @throws FormException
     */
    public function getValidator($sKey)
    {
        if (!isset($this->aValidators[$sKey])) {
            throw new FormException('Validator ' . $sKey . ' not defined for element ' . $this->getName());
        }

        return $this->aValidators[$sKey];
    }

    /**
     * @return bool
     */
    public function isPostback()
    {
        return $this->oForm->isPostback();
    }

    /**
     * @param Form $oForm
     *
     * @return ElementAbstract
     */
    public function setForm(Form $oForm)
    {
        $this->oForm = $oForm;
        return $this;
    }

    /**
     * @param Group|null $oGroup
     *
     * @return ElementAbstract
     * @throws InvalidArgumentException
     */
    public function setGroup($oGroup)
    {
        if (!($oGroup instanceof Group || $oGroup === null)) {
            throw new InvalidArgumentException('Invalid element group. Set the group to either \\flyingpiranhas\\forms\\elements\\Group or null');
        }

        $this->oGroup = $oGroup;
        return $this;
    }

    /**
     * @param string $sName
     *
     * @return ElementAbstract
     */
    public function setName($sName)
    {
        $this->sName = $sName;
        return $this;
    }

    /**
     * @param string $sLabel
     *
     * @return ElementAbstract
     */
    public function setLabel($sLabel)
    {
        $this->sLabel = $sLabel;
        return $this;
    }

    /**
     * Sets the value of the element.
     * String values are automatically trimmed.
     *
     * @param mixed $mValue
     *
     * @return ElementAbstract
     */
    public function setValue($mValue)
    {
        if (is_string($mValue)) {
            $mValue = trim($mValue);
        }

        $this->mValue = $mValue;
        return $this;
    }

    /**
     * Sets the html tag attributes that are used when rendering elements
     *
     * @param array $aHtmlAttributes
     *
     * @return ElementAbstract
     */
    public function setHtmlAttributes(array $aHtmlAttributes)
    {
        foreach ($aHtmlAttributes as $sKey => $sValue) {
            $this->setHtmlAttribute($sKey, $sValue);
        }
        return $this;
    }

    /**
     * Sets a single html tag attribute
     *
     * @param string $sName
     * @param string $sValue
     *
     * @return ElementAbstract
     */
    public function setHtmlAttribute($sName, $sValue)
    {
        if (is_numeric($sName)) {
            $sName = $sValue;
        }
        $this->aHtmlAttributes[$sName] = $sValue;
        return $this;
    }

    /**
     * @param string $sName
     *
     * @return ElementAbstract
     */
    public function removeHtmlAttribute($sName)
    {
        if (isset($this->aHtmlAttributes[$sName])) {
            unset($this->aHtmlAttributes[$sName]);
        }
        return $this;
    }

    /**
     * @param array $aValidators
     *
     * @return ElementAbstract
     */
    public function setValidators(array $aValidators)
    {
        foreach ($aValidators as $sKey => $oValidator) {
            $this->addValidator($sKey, $oValidator);
        }
        return $this;
    }

    /**
     * @param string $sType
     * @param mixed  $oValidator
     *
     * @return ElementAbstract
     * @throws FormException
     */
    public function addValidator($sType, $oValidator)
    {
        if (empty($sType) && is_string($oValidator)) {
            $sType = $oValidator;
            $oValidator = array();
        }

        if (isset($this->aValidators[$sType])) {
            throw new FormException("Validator {$sType} already set on element {$this->getName()}");
        }

        $oValidator = $this->buildValidator($sType, $oValidator);
        $oValidator->setElement($this);
        $this->aValidators[$sType] = $oValidator;
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        /** @var $oValidator Validator */
        foreach ($this->aValidators as $oValidator) {
            if (!$oValidator->isValid()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string          $sType
     * @param array|Validator $aValidator
     *
     * @return Validator
     */
    protected function buildValidator($sType, $aValidator)
    {
        if ($aValidator instanceof Validator) {
            return $aValidator;
        }

        $sClassName = '\\flyingpiranhas\\forms\\validators\\' . ucfirst($sType);
        $oValidator = new $sClassName($aValidator);

        return $oValidator;
    }

    /**
     * Override this method to implement custom rendering for a form element
     */
    public abstract function __toString();

    /**
     * If the element is a part of a group, its name will be: groupName[elementName].
     *
     * @return string
     */
    public function getDisplayName()
    {
        if ($this->oGroup instanceof Group) {
            return $this->oGroup->getDisplayName() . '[' . $this->sName . ']';
        }
        return $this->sName;
    }

}
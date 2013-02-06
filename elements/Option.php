<?php

namespace flyingpiranhas\forms\elements;

use flyingpiranhas\common\traits\PropertySetter;
use flyingpiranhas\forms\elements\abstracts\ElementAbstract;

/**
 * The option is used for elements with selectable values, like Select, Radio and Submit
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        Apache-2.0
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Option
{

    use PropertySetter;

    /** @var ElementAbstract */
    protected $oElement;

    /** @var int */
    protected $mValue = 0;

    /** @var string */
    protected $sLabel = "";

    /** @var array */
    protected $aHtmlAttributes = array();

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->mValue;
    }

    /**
     * @param string $mValue
     *
     * @return Option
     */
    public function setValue($mValue)
    {
        $this->mValue = $mValue;
        return $this;
    }

    /**
     * @param string $sLabel
     *
     * @return Option
     */
    public function setLabel($sLabel)
    {
        $this->sLabel = $sLabel;
        return $this;
    }

    /**
     * @param ElementAbstract $oElement
     *
     * @return Option
     */
    public function setElement(ElementAbstract $oElement)
    {
        $this->oElement = $oElement;
        return $this;
    }

    /**
     * Sets the html tag attributes that are used when rendering elements
     *
     * @param array $aHtmlAttributes
     *
     * @return Option
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
     * @return Option
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
     * @return Option
     */
    public function removeHtmlAttribute($sName)
    {
        if (isset($this->aHtmlAttributes[$sName])) {
            unset($this->aHtmlAttributes[$sName]);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $sElement = '';
        if ($this->oElement instanceof Submit) {
            $sElement .= '<button type="submit" name="' . $this->oElement->getDisplayName() . '" value="' . htmlspecialchars($this->mValue, ENT_QUOTES) . '"';
            foreach ($this->aHtmlAttributes as $sKey => $sVal) {
                $sElement .= ' ' . $sKey . '="' . $sVal . '"';
            }
            $sElement .= '>' . htmlspecialchars($this->sLabel, ENT_QUOTES) . '</button>';
        } else if ($this->oElement instanceof Radio) {
            $sElement .= '<input type="radio" name="' . $this->oElement->getDisplayName() . '" value="' . htmlspecialchars($this->mValue, ENT_QUOTES) . '"';
            foreach ($this->aHtmlAttributes as $sKey => $sVal) {
                $sElement .= ' ' . $sKey . '="' . $sVal . '"';
            }
            $sElement .= '/> ' . htmlspecialchars($this->sLabel, ENT_QUOTES);
        } else if ($this->oElement instanceof Select) {
            $sElement .= '<option value="' . htmlspecialchars($this->mValue, ENT_QUOTES) . '"';
            foreach ($this->aHtmlAttributes as $sKey => $sVal) {
                $sElement .= ' ' . $sKey . '="' . $sVal . '"';
            }
            $sElement .= '>' . htmlspecialchars($this->sLabel, ENT_QUOTES) . '</option>';
        }
        return $sElement;
    }

}
<?php

namespace flyingpiranhas\forms\elements;

use flyingpiranhas\forms\elements\abstracts\ElementAbstract;

/**
 * The element object representing a select field.
 * The $aOptions property is used to hold all available options.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        BSD License
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Select extends ElementAbstract
{

    /** @var array */
    protected $aOptions = array();

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->aOptions;
    }

    /**
     * @param string $sKey
     *
     * @return Option
     */
    public function getOption($sKey)
    {
        return $this->aOptions[$sKey];
    }

    /**
     * Sets the avaliable options in the form of key => value pairs
     *
     * @param array $aOptions
     *
     * @return Select
     */
    public function setOptions(array $aOptions)
    {
        foreach ($aOptions as $mValue => $sLabel) {
            $this->setOption($mValue, $sLabel);
        }
        return $this;
    }

    /**
     * @param string $sValue
     * @param Option $mOption
     *
     * @return Select
     */
    public function setOption($sValue, $mOption)
    {
        $mOption = $this->buildOption($sValue, $mOption);

        $mOption->setElement($this);
        $this->aOptions[$sValue] = $mOption;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $sElement = '<select name="' . $this->getDisplayName() . '"';
        foreach ($this->aHtmlAttributes as $sKey => $sVal) {
            $sElement .= ' ' . $sKey . '="' . $sVal . '"';
        }
        $sElement .= '>';

        /** @var $oOption Option */
        foreach ($this->aOptions as $oOption) {
            $oOption->removeHtmlAttribute('selected');
            if ($oOption->getValue() == $this->mValue || is_array($this->mValue) && in_array($oOption->getValue(), $this->mValue)) {
                $oOption->setHtmlAttribute('selected', 'selected');
            }
            $sElement .= $oOption->__toString();
        }

        $sElement .= '</select>';

        return $sElement;
    }

    /**
     * If the element is a part of a group, its name will be: groupName[elementName].
     * Some elements, like the Radio element may override this and return its own name.
     *
     * @return string
     */
    public function getDisplayName()
    {
        if ($this->oGroup instanceof Group) {
            return $this->oGroup->getDisplayName() . '[' . $this->sName . ']' . ((isset($this->aHtmlAttributes['multiple'])) ? '[]' : '');
        }
        return $this->sName . ((isset($this->aHtmlAttributes['multiple'])) ? '[]' : '');
    }

    /**
     * @param string       $sValue
     * @param array|Option $aOption
     *
     * @return Option
     */
    public function buildOption($sValue, $aOption)
    {
        if ($aOption instanceof Option) {
            return $aOption;
        }

        if (!is_array($aOption)) {
            $aOption = array(
                'label' => $aOption
            );
        }
        $aOption['value'] = $sValue;

        $oOption = new Option;
        $oOption->setProperties($aOption);
        return $oOption;
    }

}
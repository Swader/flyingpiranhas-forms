<?php

namespace flyingpiranhas\forms\elements;

/**
 * The element object representing the submit element.
 * The submit element is rendered as a button with type="submit",
 * as this allows for multiple submit elements with different values and custom text.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        BSD License
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Submit extends Select
{

    /**
     * @param string $sValue
     * @param Option $mOption
     *
     * @return Submit
     */
    public function setOption($sValue, $mOption)
    {
        $mOption = $this->buildOption($sValue, $mOption);

        $mOption->setElement($this)
            ->setHtmlAttributes($this->aHtmlAttributes);

        $this->aOptions[$sValue] = $mOption;
        return $this;
    }

    /**
     * @param string $mValue
     *
     * @return Submit
     */
    public function setValue($mValue)
    {
        if ($mValue) {
            parent::setValue($mValue);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $sElement = '';
        if (empty($this->aOptions)) {
            $sElement .= '<button type="submit" name="' . $this->getDisplayName() . '" value="' . htmlspecialchars($this->mValue, ENT_QUOTES) . '" ';
            foreach ($this->aHtmlAttributes as $sKey => $sVal) {
                $sElement .= $sKey . '="' . $sVal . '" ';
            }
            $sElement .= '>' . htmlspecialchars($this->sLabel, ENT_QUOTES) . '</button>';
        } else {
            /** @var $oOption Option */
            foreach ($this->aOptions as $oOption) {
                $sElement .= $oOption->__toString();
            }
        }
        return $sElement;
    }

}
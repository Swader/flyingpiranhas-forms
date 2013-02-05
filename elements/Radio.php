<?php

namespace flyingpiranhas\forms\elements;

/**
 * The Radio element is a specialization of the Checkbox element.
 * The difference is in the group behaviour. If the radio button is a part of a RadioGroup,
 * its name will be overriden by the group name
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        BSD License
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Radio extends Select
{

    /**
     * @param string       $sValue
     * @param Option|array $mOption
     *
     * @return Radio
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
     * @return string
     */
    public function __toString()
    {
        $sElement = '';
        /** @var $oOption Option */
        foreach ($this->aOptions as $oOption) {
            $oOption->removeHtmlAttribute('checked');
            if ($oOption->getValue() == $this->mValue) {
                $oOption->setHtmlAttribute('checked', 'checked');
            }
            $sElement .= $oOption->__toString();
        }
        return $sElement;
    }

}

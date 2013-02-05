<?php

namespace flyingpiranhas\forms\elements;

/**
 * The Hidden element is a specialization of the Text element.
 * Other than the fact that the type is hidden, the usage is the same.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        BSD License
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Hidden extends Text
{

    /**
     * @param string $mValue
     *
     * @return Hidden
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
        $sElement = '<input type="hidden" name="' . $this->getDisplayName() . '"';
        foreach ($this->aHtmlAttributes as $sKey => $sVal) {
            $sElement .= ' ' . $sKey . '="' . $sVal . '"';
        }
        $sElement .= ' value="' . htmlspecialchars($this->mValue, ENT_QUOTES) . '"/>';

        return $sElement;
    }

}
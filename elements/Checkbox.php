<?php

namespace flyingpiranhas\forms\elements;

use flyingpiranhas\forms\elements\abstracts\ElementAbstract;

/**
 * The element object representing a checkbox input field.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        Apache-2.0
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Checkbox extends ElementAbstract
{

    /** @var bool */
    protected $bChecked = false;

    /**
     * @return bool
     */
    public function isChecked()
    {
        return $this->bChecked;
    }

    /**
     * @param bool $bChecked
     *
     * @return Checkbox
     */
    public function setChecked($bChecked)
    {
        $this->bChecked = (bool)$bChecked;
        return $this;
    }

    /**
     * Returns the html checkbox input string
     * @return string
     */
    public function __toString()
    {
        $sElement = '<input type="checkbox" name="' . $this->getDisplayName() . '"';
        foreach ($this->aHtmlAttributes as $sKey => $sVal) {
            $sElement .= ' ' . $sKey . '="' . $sVal . '"';
        }
        $sElement .= ' value="' . htmlspecialchars($this->mValue, ENT_QUOTES) . '" ' . ($this->bChecked ? 'checked' : '') . '/>';

        return $sElement;
    }

}
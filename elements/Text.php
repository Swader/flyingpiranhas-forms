<?php

namespace flyingpiranhas\forms\elements;

use flyingpiranhas\forms\elements\abstracts\ElementAbstract;

/**
 * The element object representing a text input field.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        BSD License
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Text extends ElementAbstract
{

    /**
     * @return string
     */
    public function __toString()
    {
        $sElement = '<input type="text" name="' . $this->getDisplayName() . '"';
        foreach ($this->aHtmlAttributes as $sKey => $sVal) {
            $sElement .= ' ' . $sKey . '="' . $sVal . '"';
        }
        $sElement .= ' value="' . htmlspecialchars($this->mValue, ENT_QUOTES) . '"/>';

        return $sElement;
    }

}
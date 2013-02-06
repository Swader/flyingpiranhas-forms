<?php

namespace flyingpiranhas\forms\elements;

/**
 * The File element is a specialization of the Text element.
 * IMPORTANT: when a file value is present, it takes precedence over other values with the same name.
 * A value with the same name from $_FILES will override a value from $_POST or $_GET with the same name.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        Apache-2.0
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class File extends Text
{

    /**
     * Returns the html file input string
     *
     * @return string
     */
    public function __toString()
    {
        $sElement = '<input type="file" name="' . $this->getDisplayName() . '"';
        foreach ($this->aHtmlAttributes as $sKey => $sVal) {
            $sElement .= ' ' . $sKey . '="' . $sVal . '"';
        }
        $sElement .= ' value=""/>';

        return $sElement;
    }

}
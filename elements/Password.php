<?php

namespace flyingpiranhas\forms\elements;

/**
 * The Password element is a specialization of the Text element.
 * Other than the fact that the type is password, the usage is the same.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        BSD License
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Password extends Text
{

    /**
     * Returns the html password input string
     *
     * @return string
     */
    public function __toString()
    {
        $sElement = '<input type="password" name="' . $this->getDisplayName() . '"';
        foreach ($this->aHtmlAttributes as $sKey => $sVal) {
            $sElement .= ' ' . $sKey . '="' . $sVal . '"';
        }
        $sElement .= ' value=""/>';

        return $sElement;
    }

}

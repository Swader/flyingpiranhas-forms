<?php

namespace flyingpiranhas\forms\elements;

/**
 * The Textarea element is a specialization of the Text element
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        BSD License
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Textarea extends Text
{

    /**
     * @return string
     */
    public function __toString()
    {
        $sElement = '<textarea name="' . $this->getDisplayName() . '"';
        foreach ($this->aHtmlAttributes as $sKey => $sVal) {
            $sElement .= ' ' . $sKey . '="' . $sVal . '"';
        }
        $sElement .= '>' . $this->mValue . '</textarea>';

        return $sElement;
    }

}
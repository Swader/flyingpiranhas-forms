<?php

namespace flyingpiranhas\forms;

use flyingpiranhas\common\traits\PropertySetter;
use flyingpiranhas\common\http\Request;
use flyingpiranhas\common\http\interfaces\RequestInterface;
use flyingpiranhas\forms\elements\abstracts\ElementAbstract;
use flyingpiranhas\forms\exceptions\FormException;
use flyingpiranhas\forms\elements\Checkbox;

/**
 * The form object is responsible for parsing and validating form input.
 * It holds references to various element objects and can process input.
 *
 * Create forms by extending this class and adding an array of elements to it.
 * Override the process method to implement form processing.
 * Override the sViewFragmentPath property to set the view file path, for easy form html rendering.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        Apache-2.0
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class Form
{

    use PropertySetter;

    /** @var bool */
    protected $bPostback = false;

    /** @var array */
    protected $aElements = array();

    /** @var string */
    protected $sViewFragmentPath = '';

    /**
     * @param array $aProperties
     */
    public function __construct(array $aProperties = array())
    {
        $this->setProperties($aProperties);
        $this->setElements($this->aElements);
    }


    /**
     * @return array
     */
    public function getElements()
    {
        return $this->aElements;
    }

    /**
     * @param string $sKey
     *
     * @return ElementAbstract
     * @throws FormException
     */
    public function getElement($sKey)
    {
        if (!isset($this->aElements[$sKey])) {
            throw new FormException('The element ' . $sKey . ' was not defined');
        }

        return $this->aElements[$sKey];
    }

    /**
     * @return bool
     */
    public function isPostback()
    {
        return $this->bPostback;
    }

    /**
     * @return array
     */
    public function getValue()
    {
        $aValues = array();

        foreach ($this->aElements as $sKey => $oElement) {
            if ($oElement instanceof Checkbox && $oElement->isChecked()) {
                $aValues[$sKey] = $oElement->getValue();
            } else if (!($oElement instanceof Checkbox)) {
                $aValues[$sKey] = $oElement->getValue();
            }
        }

        return $aValues;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        if (!$this->isPostback()) {
            return true;
        }

        /** @var $oElement ElementAbstract */
        foreach ($this->aElements as $oElement) {
            if (!$oElement->isValid()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $aElements
     *
     * @return Form
     */
    public function setElements(array $aElements)
    {
        $this->aElements = array();
        foreach ($aElements as $sKey => $oElement) {
            $this->addElement($sKey, $oElement);
        }
        return $this;
    }

    /**
     * @param string                                                         $sKey
     * @param ElementAbstract|array                                          $oElement
     *
     * @return Form
     * @throws FormException
     */
    public function addElement($sKey, $oElement)
    {
        $oElement = $this->buildElement($oElement);

        $this->aElements[$sKey] = $oElement;
        if ($oElement->getName() === null) {
            $oElement->setName($sKey);
        }
        return $this;
    }

    /**
     * @param array $aValue
     *
     * @return Form
     * @throws FormException
     */
    public function setValue(array $aValue)
    {
        /** @var $oElement ElementAbstract|Checkbox */
        foreach ($this->aElements as $sKey => $oElement) {
            if ($oElement instanceof Checkbox) {
                $oElement->setChecked(isset($aValue[$sKey]) && $aValue[$sKey] == $oElement->getValue());
            } else if (isset($aValue[$sKey])) {
                $oElement->setValue($aValue[$sKey]);
            } else {
                $oElement->setValue(null);
            }
        }

        return $this;
    }

    /**
     * @param string $sViewFragmentPath
     *
     * @return Form
     */
    public function setViewFragmentPath($sViewFragmentPath)
    {
        $this->sViewFragmentPath = $sViewFragmentPath;
        return $this;
    }

    /**
     * Echos the form html
     *
     * @throws FormException
     */
    public function render()
    {
        if (!empty($this->sViewFragmentPath) && is_readable($this->sViewFragmentPath)) {
            include $this->sViewFragmentPath;
            return;
        }

        throw new FormException('No form view file could be found');
    }


    /**
     * @param RequestInterface $oRequest
     *
     * @return Form
     */
    public function process(RequestInterface $oRequest)
    {
        $this->bPostback = true;

        $aValues = $oRequest->getParams($oRequest->getServer()->REQUEST_METHOD);
        foreach ($oRequest->getParams(Request::PARAM_TYPES_FILES) as $sKey => $aFile) {
            $aValues[$sKey] = $aFile;
        }

        $this->setValue($aValues->toArray());
        return $this->processPost();
    }

    /**
     * Called when processing the request.
     * Override this method to provide custom form processing logic,
     * after the validation has been performed.
     * Return true if processing was successful.
     * Return false if processing has failed.
     *
     * If not overriden, returns the validation result.
     *
     * @return bool
     */
    protected function processPost()
    {
        return $this->isValid();
    }


    /**
     * Builds an element from an array of settings
     *
     * @param ElementAbstract|null $aElement
     *
     * @return ElementAbstract
     */
    protected function buildElement(&$aElement)
    {
        if ($aElement instanceof ElementAbstract) {
            return $aElement->setForm($this);
        }

        $sClassName = (isset($aElement['class'])) ? $aElement['class'] : __NAMESPACE__ . '\\elements\\' . ucfirst($aElement['type']);

        $aElement['form'] = $this;

        if (isset($aElement['class'])) {
            unset($aElement['class']);
        }
        if (isset($aElement['type'])) {
            unset($aElement['type']);
        }

        return new $sClassName($aElement);
    }


}
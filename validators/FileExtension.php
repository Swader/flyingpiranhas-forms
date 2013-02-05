<?php

namespace flyingpiranhas\forms\validators;

/**
 * The FileExtension validator should be used with File elements
 * to check that the of the posted file extension is one of the allowed extensions.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        BSD License
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class FileExtension extends Validator
{

    /** @var array */
    protected $aFileExtensions = array();

    /** @var string */
    protected $sType = 'fileExtension';

    /** @var string */
    protected $sErrorMessage = 'Invalid file extension';

    /**
     * @param array $aProperties
     */
    public function __construct(array $aProperties = array())
    {

        parent::__construct($aProperties);
        $aFileExtensions = $this->aFileExtensions;

        $this->setValidatorFunction(
            function () use ($aFileExtensions) {
                $aFile = $this->oElement->getValue();
                $sExt = pathinfo($aFile['name'], PATHINFO_EXTENSION);

                if (!empty($aFile['tmp_name']) && !in_array($sExt, $aFileExtensions)) {
                    return false;
                }
                return true;
            }
        );
    }

    /**
     * @param array $aFileExtensions
     *
     * @return FileExtension
     */
    protected function setFileExtensions(array $aFileExtensions)
    {
        $this->aFileExtensions = $aFileExtensions;
        return $this;
    }

}
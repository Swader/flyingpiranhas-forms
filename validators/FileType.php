<?php

namespace flyingpiranhas\forms\validators;

use flyingpiranhas\forms\elements\abstracts\ElementAbstract;

/**
 * The FileType validator should be used with File elements
 * to check that the mime type of the posted file is one of the allowed types.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        Apache-2.0
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class FileType extends Validator
{

    /** @var array */
    protected $aFileTypes = array();

    /** @var string */
    protected $sType = 'fileType';

    /** @var string */
    protected $sErrorMessage = 'Invalid file type';

    /**
     * @param array $aProperties
     */
    public function __construct(array $aProperties = array())
    {
        parent::__construct($aProperties);

        $aFileTypes = $this->aFileTypes;
        $this->setValidatorFunction(
            function ($oElement) use ($aFileTypes) {
                /** @var $oElement ElementAbstract */
                $aFile = $oElement->getValue();
                if (!empty($aFile['tmp_name']) && !in_array($aFile['type'], $aFileTypes)) {
                    return false;
                }

                return true;
            }
        );
    }

    /**
     * @param array $aFileTypes
     *
     * @return FileType
     */
    public function setFileTypes(array $aFileTypes)
    {
        $this->aFileTypes = $aFileTypes;
        return $this;
    }

}
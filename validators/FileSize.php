<?php

namespace flyingpiranhas\forms\validators;

/**
 * The FileSize validator should be used with File elements
 * to check that the size of the posted file is not above the maximum file size limit.
 *
 * @category       forms
 * @package        flyingpiranhas.forms
 * @license        Apache-2.0
 * @version        0.01
 * @since          2012-09-07
 * @author         Ivan Pintar
 */
class FileSize extends Validator
{

    /** @var int */
    protected $iFileSize = 0;

    /** @var string */
    protected $sType = 'fileSize';

    /** @var string */
    protected $sErrorMessage = 'File too large';

    /**
     * @param array $aProperties
     */
    public function __construct(array $aProperties = array())
    {
        parent::__construct($aProperties);
        $iFileSize = $this->iFileSize;
        $this->setValidatorFunction(function () use ($iFileSize) {
            $aFile = $this->oElement->getValue();
            if (!empty($aFile['tmp_name']) && $aFile['size'] > $iFileSize) {
                return false;
            }
            return true;
        });
    }

    /**
     * @param int $iFileSize
     *
     * @return FileSize
     */
    public function setFileSize($iFileSize)
    {
        $this->iFileSize = $iFileSize;
        return $this;
    }

}
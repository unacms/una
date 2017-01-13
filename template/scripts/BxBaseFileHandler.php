<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * @see BxDolFileHandler
 */
class BxBaseFileHandler extends BxDolFileHandler
{
    protected static $_isCssJsAdded = false;
    
    protected $_oTemplate;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    protected function addCssJs ()
    {   
        if (!self::$_isCssJsAdded) {
            $this->_oTemplate->addCss('file_handler.css');
            self::$_isCssJsAdded = true;
        }
    }
}

/** @} */

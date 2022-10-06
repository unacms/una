<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Database queries for captcha objects.
 * @see BxDolCaptcha
 */
class BxDolCaptchaQuery extends BxDolFactoryObjectQuery
{
    protected $_aObject;

    public function __construct($aObject)
    {
        parent::__construct();
        $this->_aObject = $aObject;
    }

    static public function getCaptchaObject ($sObject)
    {
        return parent::getObjectFromTable($sObject, 'sys_objects_captcha');
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_captcha` WHERE `object` = ?", $sObject);
        $aObject = $oDb->getRow($sQuery);
        if (!$aObject || !is_array($aObject))
            return false;

        return $aObject;
    }

    static public function getObjects ()
    {
        return parent::getObjectsFromTable('sys_objects_captcha');
    }
}

/** @} */

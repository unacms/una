<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Database queries for editor objects.
 * @see BxDolEditor
 */
class BxDolEditorQuery extends BxDolDb
{
    protected $_aObject;

    public function __construct($aObject)
    {
        parent::__construct();
        $this->_aObject = $aObject;
    }

    static public function getEditorObject ($sObject)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_editor` WHERE `object` = ?", $sObject);
        $aObject = $oDb->getRow($sQuery);
        if (!$aObject || !is_array($aObject))
            return false;

        return $aObject;
    }

}

/** @} */

<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Database queries for file handlers objects.
 * @see BxDolFileHandler
 */
class BxDolFileHandlerQuery extends BxDolDb
{
    protected $_aObject;

    public function __construct($aObject)
    {
        parent::__construct();
        $this->_aObject = $aObject;
    }

    static public function getObjects ()
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_file_handlers` ORDER BY `order` ASC");
        $aObjects = $oDb->getAllWithKey($sQuery, 'object');
        if (!$aObjects || !is_array($aObjects))
            return false;

        return $aObjects;
    }

}

/** @} */

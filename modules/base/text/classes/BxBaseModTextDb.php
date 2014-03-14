<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleDb');

/*
 * Module database queries
 */
class BxBaseModTextDb extends BxDolModuleDb
{
    protected $_oConfig;

    function __construct(&$oConfig) 
    {
        parent::__construct($oConfig);
        $this->_oConfig = $oConfig;
    }

    function getContentInfoById ($iContentId) 
    {
        $sQuery = $this->prepare ("SELECT `c`.* FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `c` WHERE `c`.`id` = ?", $iContentId);
        return $this->getRow($sQuery);
    }
}

/** @} */ 

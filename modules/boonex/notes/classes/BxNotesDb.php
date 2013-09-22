<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Notes Notes
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleDb');

/*
 * Notes module database queries
 */
class BxNotesDb extends BxDolModuleDb {

    /*
     * Constructor.
     */
    function __construct(&$oConfig) {
        parent::__construct($oConfig);
    }

    function getContentInfoById ($iContentId) {
        $sQuery = $this->prepare ("SELECT `c`.* FROM `" . $this->_sPrefix . "posts` AS `c` WHERE `c`.`id` = ?", $iContentId);
        return $this->getRow($sQuery);
    }

}

/** @} */ 

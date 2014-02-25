<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Antispam Antispam
 * @ingroup     DolphinModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxTemplGrid');

class BxAntispamGridBlockLog extends BxTemplGrid 
{
    public function __construct ($aOptions, $oTemplate = false) 
    {
        parent::__construct ($aOptions, $oTemplate);
    }

    protected function _getCellIp ($mixedValue, $sKey, $aField, $aRow) 
    {
        return parent::_getCellDefault (long2ip($mixedValue), $sKey, $aField, $aRow);
    }
    protected function _getCellType ($mixedValue, $sKey, $aField, $aRow) 
    {
        return parent::_getCellDefault (_t('_bx_antispam_type_' . $mixedValue), $sKey, $aField, $aRow);
    }
    protected function _getCellAdded ($mixedValue, $sKey, $aField, $aRow) 
    {
        return parent::_getCellDefault (bx_time_js($mixedValue, BX_FORMAT_DATE), $sKey, $aField, $aRow);
    }
}

/** @} */

<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Antispam Antispam
 * @ingroup     TridentModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

class BxAntispamGridBlockLog extends BxTemplGrid
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
        $this->_sDefaultSortingOrder = 'DESC';
    }

    protected function _getCellIp ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault (long2ip($mixedValue), $sKey, $aField, $aRow);
    }
    protected function _getCellProfileId ($mixedValue, $sKey, $aField, $aRow)
    {
        $s = '<span class="bx-def-font-grayed">' . _t('_undefined') . '</span>';
        if ($mixedValue && ($oProfile = BxDolProfile::getInstance((int)$mixedValue)))
            $s = '<a href="' . $oProfile->getUrl() . '">' . $oProfile->getDisplayName() . '</span>';

        return parent::_getCellDefault ($s, $sKey, $aField, $aRow);
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

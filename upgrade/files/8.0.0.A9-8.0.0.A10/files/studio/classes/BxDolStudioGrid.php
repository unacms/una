<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

bx_import('BxTemplGrid');
bx_import('BxDolStudioUtils');

define('BX_DOL_STUDIO_GRID_PARAMS_DIVIDER', '#-#');

class BxDolStudioGrid extends BxTemplGrid
{
    protected $sParamsDivider;
    public $oDb;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate ? $oTemplate : BxDolStudioTemplate::getInstance());

        $this->oDb = null;
        $this->sParamsDivider = BX_DOL_STUDIO_GRID_PARAMS_DIVIDER;
    }

    public function getSystemName($sValue)
    {
        return BxDolStudioUtils::getSystemName($sValue);
    }

    public function getClassName($sValue)
    {
        return BxDolStudioUtils::getClassName($sValue);
    }

    protected function getModuleTitle($sName)
    {
        return BxDolStudioUtils::getModuleTitle($sName);
    }

    protected function getModules($bShowCustom = true, $bShowSystem = true)
    {
        return BxDolStudioUtils::getModules($bShowCustom, $bShowSystem);
    }

    protected function _isVisibleGrid ($a)
    {
        return isAdmin();
    }
}
/** @} */

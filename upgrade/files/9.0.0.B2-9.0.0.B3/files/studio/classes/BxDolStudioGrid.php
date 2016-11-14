<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

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

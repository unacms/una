<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

bx_import('BxDol');

define('BX_DOL_STUDIO_MODULE_SYSTEM', 'system');
define('BX_DOL_STUDIO_MODULE_CUSTOM', 'custom');

define('BX_DOL_STUDIO_MODULE_ICON_DEFAULT', 'lightbulb-o');

define('BX_DOL_STUDIO_VISIBLE_ALL', 'all');
define('BX_DOL_STUDIO_VISIBLE_SELECTED', 'selected');

class BxDolStudioUtils extends BxDol
{
    function __construct()
    {
        parent::__construct();
    }

    public static function getSystemName($sValue)
    {
        return str_replace(' ', '_', strtolower($sValue));
    }

    public static function getClassName($sValue)
    {
        return bx_gen_method_name($sValue);
    }

    public static function getIconDefault($sType = '')
    {
    	$sIcon = '';

		switch ($sType) {
			case BX_DOL_MODULE_TYPE_MODULE:
				$sIcon = 'cube';
				break;

			case BX_DOL_MODULE_TYPE_LANGUAGE:
				$sIcon = 'language';
				break;

			case BX_DOL_MODULE_TYPE_TEMPLATE:
				$sIcon = 'adjust';
				break;

			default:
				$sIcon = BX_DOL_STUDIO_MODULE_ICON_DEFAULT;
		}

		return $sIcon;
    }
    
    public static function getModuleIcon($mixedModule, $sType = 'menu', $bReturnAsUrl = true)
    {
        $aType2Prefix = array('menu' => 'mi', 'page' => 'pi', 'store' => 'si');

        if(!is_array($mixedModule)) {
	        bx_import('BxDolModuleQuery');
	        $aModule = BxDolModuleQuery::getInstance()->getModuleByName($mixedModule);
        }
        else 
        	$aModule = $mixedModule;

		$sDefaultIcon = self::getIconDefault(isset($aModule['type']) ? $aModule['type'] : '');
        if(empty($aModule))
            return $sDefaultIcon;

        $sModuleIcon = '';
        if(isset($aModule['path']))
			$sModuleIcon = $aModule['name'] . '@modules/' . $aModule['path'] . '|std-' . $aType2Prefix[$sType] . '.png';
		else if(isset($aModule['dir']))
			$sModuleIcon = $aModule['name'] . '@modules/' . $aModule['dir'] . '|std-' . $aType2Prefix[$sType] . '.png';
        $sModuleIconUrl = BxDolStudioTemplate::getInstance()->getIconUrl($sModuleIcon);

        return !empty($sModuleIcon) && !empty($sModuleIconUrl) ? ($bReturnAsUrl ? $sModuleIconUrl : $sModuleIcon) : $sDefaultIcon;
    }

    public static function getModuleTitle($sName)
    {
        $sPrefix = '_adm_txt_module_';

        if(in_array($sName, array(BX_DOL_STUDIO_MODULE_SYSTEM, BX_DOL_STUDIO_MODULE_CUSTOM)))
            return _t($sPrefix . $sName);

        bx_import('BxDolModuleQuery');
        $aModule = BxDolModuleQuery::getInstance()->getModuleByName($sName);
        if(!empty($aModule))
            return $aModule['title'];

        return _t($sPrefix . strtolower($sName));
    }

    public static function getModules($bShowCustom = true, $bShowSystem = true)
    {
        $aResult = array();

        if($bShowSystem)
            $aResult[BX_DOL_STUDIO_MODULE_SYSTEM] = self::getModuleTitle(BX_DOL_STUDIO_MODULE_SYSTEM);

        if($bShowCustom)
            $aResult[BX_DOL_STUDIO_MODULE_CUSTOM] = self::getModuleTitle(BX_DOL_STUDIO_MODULE_CUSTOM);

        bx_import('BxDolModuleQuery');
        $aModules = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'modules', 'active' => 1));
        foreach($aModules as $aModule)
            $aResult[$aModule['name']] = $aModule['title'];

        return $aResult;
    }
    public static function getVisibilityTitle($iValue)
    {
        $iCount = self::getVisibilityCount($iValue);

        $sResult = "";
        switch($iCount) {
            case 1:
                bx_import('BxDolAcl');
                $aLevel = BxDolAcl::getInstance()->getMembershipInfo(log($iValue, 2) + 1);
                $sResult = _t($aLevel['name']);
                break;
            case 0:
                $sResult = _t('_adm_prm_txt_visibility_items_nobody');
                break;
            case -1:
                $sResult = _t('_adm_prm_txt_visibility_items_anyone');
                break;
            default:
                $sResult = _t('_adm_prm_txt_visibility_items_n_user_levels', $iCount);
        }

        return $sResult;
    }

    public static function getVisibilityCount($iValue)
    {
        $iResult = 0;

        $iValue = (int)$iValue;
        if($iValue == 0)
            return $iResult;
        else if($iValue == BX_DOL_INT_MAX)
            return -1;

        bx_import('BxDolAcl');
        $aLevels = BxDolAcl::getInstance()->getMemberships(false, true);

        $iIndex = 1;
        do {
            if(array_key_exists($iIndex++, $aLevels))
                $iResult += $iValue & 1;
        } while(($iValue = $iValue >> 1) != 0);

        return $iResult;
    }

    public static function getVisibilityValue($sVisibleFor, $aVisibleForLevels)
    {
        if($sVisibleFor == BX_DOL_STUDIO_VISIBLE_ALL)
            return BX_DOL_INT_MAX;

        $iVisibleFor = 0;
        if(is_array($aVisibleForLevels))
            foreach($aVisibleForLevels as $iLevelId)
                $iVisibleFor += pow(2, (int)$iLevelId - 1);

        return $iVisibleFor;
    }

    public static function getVisibilityValues($iValue, &$aValuesAll, &$aValuesSelected)
    {
        bx_import('BxDolAcl');
        $aLevels = BxDolAcl::getInstance()->getMemberships(false, true);
        foreach($aLevels as $iKey => $sValue) {
            if(((int)$iValue & pow(2, (int)$iKey - 1)) != 0)
                $aValuesSelected[] = $iKey;

            $aValuesAll[$iKey] = _t($sValue);
        }
    }

    public static function addInArray($aInput, $sKey, $aValues)
    {
        reset($aInput);
        $iInput = count($aInput);
        for($i = 0; $i < $iInput; $i++, next($aInput))
            if(key($aInput) == $sKey)
                break;

        $aOutput = array_slice($aInput, 0, $i + 1);
        $aOutput = array_merge($aOutput, $aValues);
        $aOutput = array_merge($aOutput, array_slice($aInput, $i + 1));

        return $aOutput;
    }
}

/** @} */

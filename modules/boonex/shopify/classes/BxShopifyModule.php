<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Shopify Shopify
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_SHOPIFY_MODE_TEST', 'test');
define('BX_SHOPIFY_MODE_LIVE', 'live');

/**
 * Shopify module
 */
class BxShopifyModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $this->_aSearchableNamesExcept[] = $this->_oConfig->CNF['FIELD_CODE'];
    }

    public function getSettings($iProfileId = 0)
    {
        if(empty($iProfileId))
            $iProfileId = bx_get_logged_profile_id();

        $aSettings = $this->_oDb->getSettings(array('type' => 'author', 'author' => $iProfileId));
        if(empty($aSettings) || !is_array($aSettings))
            return array();

        return $aSettings;
    }

    public function serviceInclude($iProfileId = 0)
    {
        $this->serviceIncludeCssJs();
        return $this->serviceIncludeCode($iProfileId);
    }

    public function serviceIncludeCssJs()
    {
        $this->_oTemplate->getIncludeCssJs();
    }

    public function serviceIncludeCode($iProfileId = 0)
    {
        $iProfileId = !empty($iProfileId) ? $iProfileId : $this->_iProfileId;
        if(empty($iProfileId))
            return '';

        $aSettings = $this->getSettings($iProfileId);
        if(empty($aSettings))
            return '';

        return $this->_oTemplate->getIncludeCode($iProfileId, $aSettings);
    }

    public function serviceGetResultsSearchExtended($aParams)
    {
        $this->serviceIncludeCssJs();
        return BxDolService::call('system', 'get_results', array($aParams), 'TemplSearchExtendedServices');
    }

    public function serviceEntityCreate ()
    {
        $CNF = &$this->_oConfig->CNF;

    	$aSettings = $this->_oDb->getSettings(array('type' => 'author', 'author' => $this->_iProfileId));
        if(empty($aSettings) || !is_array($aSettings))
    		return MsgBox(_t('_bx_shopify_err_not_configured', BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_SETTINGS'])));

    	return parent::serviceEntityCreate();
    }

    public function serviceSettings()
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$this->_iProfileId)
            return MsgBox(_t('_Access denied'));

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_SETTINGS'], $CNF['OBJECT_FORM_SETTINGS_DISPLAY_EDIT'], $this->_oTemplate);
        if(!$oForm)
            return MsgBox(_t('_sys_txt_error_occured'));       

        $aSettings = $this->_oDb->getSettings(array('type' => 'author', 'author' => $this->_iProfileId));
        $bSettings = !empty($aSettings) && is_array($aSettings);

        $sResultTimer = 0;
        $sResultMessage = '';

        $oForm->initChecker($aSettings);
        if($oForm->isSubmittedAndValid()) {
            if(!$bSettings) {
                if((int)$oForm->insert(array('author' => $this->_iProfileId)) > 0) {
                    $sResultTimer = 3;
                    $sResultMessage = '_bx_shopify_msg_save_settings';
                }
                else 
                    $sResultMessage = '_bx_shopify_err_save_settings';
            }
            else {
                if($oForm->update($aSettings['id']) !== false) {
                    $sResultTimer = 3;
                    $sResultMessage = '_bx_shopify_msg_save_settings';
                }
                else 
                    $sResultMessage = '_bx_shopify_err_save_settings';
            }
        }

        return array(
        	'content' => (!empty($sResultMessage) ? MsgBox(_t($sResultMessage), $sResultTimer) : '') . $oForm->getCode()
        );
    }

    // ====== PERMISSION METHODS
    public function isAllowedViewDashboard()
    {
        $aSettings = $this->getSettings();
        if(empty($aSettings))
            return false;

        return true;
    }

    public function checkAllowedSetThumb()
    {
        return _t('_sys_txt_access_denied');
    }
}

/** @} */

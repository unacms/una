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

    /**
     * @page service Service Calls
     * @section bx_shopify Shopify
     * @subsection bx_shopify-integration Integration
     * @subsubsection bx_shopify-include include
     * 
     * @code bx_srv('bx_shopify', 'include', [...]); @endcode
     * 
     * Get integration code with all necessary CSS and JS includes. 
     *
     * @return HTML sting with integration code to display on the site, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxShopifyModule::serviceInclude
     */
    /** 
     * @ref bx_shopify-include "include"
     */
    public function serviceInclude($iProfileId = 0)
    {
        $this->serviceIncludeCssJs();
        return $this->serviceIncludeCode($iProfileId);
    }

    /**
     * @page service Service Calls
     * @section bx_shopify Shopify
     * @subsection bx_shopify-integration Integration
     * @subsubsection bx_shopify-include_css_js include_css_js
     * 
     * @code bx_srv('bx_shopify', 'include_css_js', [...]); @endcode
     * 
     * Include all necessary CSS and JS files.
     * 
     * @see BxShopifyModule::serviceIncludeCssJs
     */
    /** 
     * @ref bx_shopify-include_css_js "include_css_js"
     */
    public function serviceIncludeCssJs()
    {
        $this->_oTemplate->getIncludeCssJs();
    }

    /**
     * @page service Service Calls
     * @section bx_shopify Shopify
     * @subsection bx_shopify-integration Integration
     * @subsubsection bx_shopify-include_code include_code
     * 
     * @code bx_srv('bx_shopify', 'include_code', [...]); @endcode
     * 
     * Get integration code. 
     *
     * @return HTML sting with integration code to display on the site. Empty string is returned if there is no enough input data.
     * 
     * @see BxShopifyModule::serviceIncludeCode
     */
    /** 
     * @ref bx_shopify-include_code "include_code"
     */
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

    /**
     * @page service Service Calls
     * @section bx_shopify Shopify
     * @subsection bx_shopify-page_blocks Page Blocks
     * @subsubsection bx_shopify-get_results_search_extended get_results_search_extended
     * 
     * @code bx_srv('bx_shopify', 'get_results_search_extended', [...]); @endcode
     * 
     * Get page block with the results of Extended Search.
     *
     * @param $aParams an array with search params.
     * @return HTML string with block content to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxShopifyModule::serviceGetResultsSearchExtended
     */
    /** 
     * @ref bx_shopify-get_results_search_extended "get_results_search_extended"
     */
    public function serviceGetResultsSearchExtended($aParams)
    {
        $this->serviceIncludeCssJs();
        return BxDolService::call('system', 'get_results', array($aParams), 'TemplSearchExtendedServices');
    }

    /**
     * @page service Service Calls
     * @section bx_shopify Shopify
     * @subsection bx_shopify-page_blocks Page Blocks
     * @subsubsection bx_shopify-entity_create entity_create
     * 
     * @code bx_srv('bx_shopify', 'entity_create', [...]); @endcode
     * 
     * Get page block with product creation form or an error message if something wasn't configured correctly.
     *
     * @param $sDisplay form display name to use
     * @return HTML string with block content to display on the site, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxShopifyModule::serviceEntityCreate
     */
    /** 
     * @ref bx_shopify-entity_create "entity_create"
     */
    public function serviceEntityCreate ($sDisplay = false)
    {
        $CNF = &$this->_oConfig->CNF;

    	$aSettings = $this->_oDb->getSettings(array('type' => 'author', 'author' => $this->_iProfileId));
        if(empty($aSettings) || !is_array($aSettings))
    		return MsgBox(_t('_bx_shopify_err_not_configured', bx_absolute_url(BxDolPermalinks::getInstance()->permalink($CNF['URL_SETTINGS']))));

    	return parent::serviceEntityCreate($sDisplay);
    }

    /**
     * @page service Service Calls
     * @section bx_shopify Shopify
     * @subsection bx_shopify-page_blocks Page Blocks
     * @subsubsection bx_shopify-settings settings
     * 
     * @code bx_srv('bx_shopify', 'settings', [...]); @endcode
     * 
     * Get page block with configuration settings form.
     *
     * @return an array describing a block to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxShopifyModule::serviceSettings
     */
    /** 
     * @ref bx_shopify-settings "settings"
     */
    public function serviceSettings()
    {
        $CNF = &$this->_oConfig->CNF;

        if(!$this->_iProfileId)
            return array(
        		'content' => MsgBox(_t('_Access denied'))
            );

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_SETTINGS'], $CNF['OBJECT_FORM_SETTINGS_DISPLAY_EDIT'], $this->_oTemplate);
        if(!$oForm)
            return array(
        		'content' => MsgBox(_t('_sys_txt_error_occured'))
            );       

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

    public function checkAllowedSetThumb($iContentId = 0)
    {
        return _t('_sys_txt_access_denied');
    }
}

/** @} */

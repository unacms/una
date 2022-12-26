<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Snipcart Snipcart
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_SNIPCART_MODE_TEST', 'test');
define('BX_SNIPCART_MODE_LIVE', 'live');

/**
 * Snipcart module
 */
class BxSnipcartModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $CNF = &$this->_oConfig->CNF;
        $this->_aSearchableNamesExcept = array_merge($this->_aSearchableNamesExcept, array(
             $CNF['FIELD_PRICE'],
             $CNF['FIELD_WEIGHT']
        ));
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
     * @section bx_snipcart Snipcart
     * @subsection bx_snipcart-other Other
     * @subsubsection bx_snipcart-get_searchable_fields get_searchable_fields
     * 
     * @code bx_srv('bx_snipcart', 'get_searchable_fields', [...]); @endcode
     * 
     * Get a list of searchable fields. Is used in common search engine. 
     *
     * @return an array with a list of searchable fields.
     * 
     * @see BxSnipcartModule::serviceGetSearchableFields
     */
    /** 
     * @ref bx_snipcart-get_searchable_fields "get_searchable_fields"
     */
    public function serviceGetSearchableFields ($aInputsAdd = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetSearchableFields($aInputsAdd);
        unset($aResult[$CNF['FIELD_PRICE']], $aResult[$CNF['FIELD_WEIGHT']]);

        return $aResult;
    }

    /**
     * @page service Service Calls
     * @section bx_snipcart Snipcart
     * @subsection bx_snipcart-integration Integration
     * @subsubsection bx_snipcart-include_css_js include_css_js
     * 
     * @code bx_srv('bx_snipcart', 'include_css_js', [...]); @endcode
     * 
     * Get integration code with all necessary CSS and JS includes. 
     *
     * @return HTML sting with integration code to display on the site, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML. Empty string is returned if there is no enough input data.
     * 
     * @see BxSnipcartModule::serviceIncludeCssJs
     */
    /** 
     * @ref bx_snipcart-include_css_js "include_css_js"
     */
    public function serviceIncludeCssJs($iProfileId = 0)
    {
        $iProfileId = !empty($iProfileId) ? $iProfileId : $this->_iProfileId;
        if(empty($iProfileId))
            return '';

        return $this->_oTemplate->getSctInclude($iProfileId);
    }

	/**
     * @page service Service Calls
     * @section bx_snipcart Snipcart
     * @subsection bx_snipcart-page_blocks Page Blocks
     * @subsubsection bx_snipcart-entity_create entity_create
     * 
     * @code bx_srv('bx_snipcart', 'entity_create', [...]); @endcode
     * 
     * Get page block with product creation form or an error message if something wasn't configured correctly.
     *
     * @param $sDisplay form display name to use
     * @return HTML string with block content to display on the site, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxSnipcartModule::serviceEntityCreate
     */
    /** 
     * @ref bx_snipcart-entity_create "entity_create"
     */
    public function serviceEntityCreate ($sDisplay = false)
    {
        $CNF = &$this->_oConfig->CNF;

    	$aSettings = $this->_oDb->getSettings(array('type' => 'author', 'author' => $this->_iProfileId));
        if(empty($aSettings) || !is_array($aSettings))
    		return MsgBox(_t('_bx_snipcart_err_not_configured', bx_absolute_url(BxDolPermalinks::getInstance()->permalink($CNF['URL_SETTINGS']))));

    	return parent::serviceEntityCreate($sDisplay);
    }

    /**
     * @page service Service Calls
     * @section bx_snipcart Snipcart
     * @subsection bx_snipcart-page_blocks Page Blocks
     * @subsubsection bx_snipcart-settings settings
     * 
     * @code bx_srv('bx_snipcart', 'settings', [...]); @endcode
     * 
     * Get page block with configuration settings form.
     *
     * @return an array describing a block to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxSnipcartModule::serviceSettings
     */
    /** 
     * @ref bx_snipcart-settings "settings"
     */
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
                    $sResultMessage = '_bx_snipcart_msg_save_settings';
                }
                else 
                    $sResultMessage = '_bx_snipcart_err_save_settings';
                
            }
            else {
                if($oForm->update($aSettings['id']) !== false) {
                    $sResultTimer = 3;
                    $sResultMessage = '_bx_snipcart_msg_save_settings';
                }
                else 
                    $sResultMessage = '_bx_snipcart_err_save_settings';
            }
        }

        return array(
        	'content' => (!empty($sResultMessage) ? MsgBox(_t($sResultMessage), $sResultTimer) : '') . $oForm->getCode()
        );
    }

    protected function _getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams = array())
    {
        $aResult = parent::_getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams);

        $aResult['raw'] = $this->_oTemplate->getBuyButton($aContentInfo);
        if(isset($aBrowseParams['dynamic_mode']) && (bool)$aBrowseParams['dynamic_mode'] === true)
            $aResult['raw'] .= $this->_oTemplate->addCss(array('timeline.css'), true);

        return $aResult;
    }
}

/** @} */

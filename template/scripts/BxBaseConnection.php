<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * @see BxBaseConnection
 */
class BxBaseConnection extends BxDolConnection
{
    protected $_sStylePrefix;
    protected $_sJsObjName;
    protected $_aHtmlIds;
    protected $_oTemplate;
    
    public function __construct($aObject)
    {
        parent::__construct($aObject);
        
        $this->_sStylePrefix = 'bx_conn_' . $this->_sObject;
        $this->_sJsObjName = 'oConn' . bx_gen_method_name($this->_sObject, array('_' , '-')) . '';
        $this->_oTemplate = BxDolTemplate::getInstance();
    }
    
    public function actionGetConnected ($iStart = 0, $iPerPage = 0)
    {
        $sContentType = bx_get('content_type');
        $iProfileId = (int)bx_get('id');
        $bIsMutual = (bool)bx_get('mutual');
        return $this->_getConnected ($sContentType, $iProfileId, $bIsMutual, $iStart, $iPerPage);
    }
    
    public function actionGetUsers ($iStart = 0, $iPerPage = 0)
    {
        $sContentType = bx_get('content_type');
        $iProfileId = (int)bx_get('id');
        $bIsMutual = (bool)bx_get('mutual');
        $iStart = (int)bx_get('start');
        $iPerPage = (int)bx_get('per_page');
        
        return echoJson(array(
            'content' => $this->_getConnected($sContentType, $iProfileId, $bIsMutual, $iStart, $iPerPage),
            'eval' => $this->getJsObjectName($iProfileId) . '.onGetUsers(oData)'
        ));
    }
    
    public function getJsObjectName($iProfileId)
    {
        return $this->_sJsObjName . $iProfileId;
    }
    
    public function getCounter ($iProfileId, $bIsMutual, $aParams, $sContentType)
    {
        if (!$iProfileId)
            $iProfileId = bx_get_logged_profile_id();
        
        $this->_sStylePrefix = 'bx_conn_' . $this->_sObject;
        
        $aHtmlIds = array(
            'main' => 'bx-conn-' . $this->_sObject . $iProfileId,
            'counter' => 'bx-conn-counter-' . $this->_sObject . $iProfileId,
            'by_popup' => 'bx-conn-by-popup-' . $this->_sObject . $iProfileId,
        );
        
        $aParamsDefault = [
            'dynamic_mode' => true,
            'show_do_as_button_small' => true,
            'show_do_as_button' => true,
            'show_script' => true,
        ];
        
        $aParams = array_merge($aParams, $aParamsDefault);
        
        $iCount = $sContentType == BX_CONNECTIONS_CONTENT_TYPE_INITIATORS ? $this->getConnectedInitiatorsCount($iProfileId, $bIsMutual) : $this->getConnectedContentCount($iProfileId, $bIsMutual);
        
        if ($iCount > 0){   
            $sJsObject = $this->getJsObjectName($iProfileId);

            $bDynamicMode = isset($aParams['dynamic_mode']) && $aParams['dynamic_mode'] === true;
            $bShowDoAsButtonSmall = isset($aParams['show_do_as_button_small']) && $aParams['show_do_as_button_small'] == false;
            $bShowDoAsButton = !$bShowDoAsButtonSmall && isset($aParams['show_do_as_button']) && $aParams['show_do_as_button'] == true;
            $bShowScript = !isset($aParams['show_script']) || $aParams['show_script'] == true;

            $sClass = $this->_sStylePrefix . '-counter';
            if($bShowDoAsButtonSmall)
                $sClass .= ' bx-btn-small-height';
            if($bShowDoAsButton)
                $sClass .= ' bx-btn-height';
        
            $sContent =$this->_getCounterLabel($iCount, $aParams);
            
            return $this->_oTemplate->parseHtmlByName('connection_counter.html', array(
                'html_id' => $aHtmlIds['counter'],
                'style_prefix' => $this->_sStylePrefix,
                'bx_if:show_text' => array(
                    'condition' => false,
                    'content' => array(
                        'class' => $sClass,
                        'bx_repeat:attrs' => array(
                            array('key' => 'id', 'value' => $aHtmlIds['counter']),
                        ),
                        'content' => $sContent
                    )
                ),
                'bx_if:show_link' => array(
                    'condition' => true,
                    'content' => array(
                        'class' => $sClass,
                        'bx_repeat:attrs' => array(
                            array('key' => 'id', 'value' => $aHtmlIds['counter']),
                            array('key' => 'href', 'value' => 'javascript:void(0)'),
                            array('key' => 'onclick', 'value' => 'javascript:' . $sJsObject . '.toggleByPopup(this)'),
                            array('key' => 'title', 'value' => _t('_view_do_view_by'))
                        ),
                        'content' => $sContent
                    )
                ),
                'script' => $bShowScript ? $this->_getJsScript($iProfileId, $sContentType, $bIsMutual, $aHtmlIds, $bDynamicMode) : ''
            ));
        }
    }
    
    protected function _getJsScript($iProfileId, $sContentType, $bIsMutual, $aHtmlIds, $bDynamicMode = false)
    {
        $sJsObject = $this->getJsObjectName($iProfileId);

        $aParamsJs = array(
            'sObjName' => $sJsObject,
            'sSystem' => $this->_sObject,
            'iObjId' => $iProfileId,
            'sContentType' => $sContentType,
            'bIsMutual' => $bIsMutual ? 1 : 0,
            'sRootUrl' => BX_DOL_URL_ROOT,
            'sStylePrefix' => $this->_sStylePrefix,
            'aHtmlIds' => $aHtmlIds
        );
        
        $sCode = "if(window['" . $sJsObject . "'] == undefined) var " . $sJsObject . " = new BxDolConnection(" . json_encode($aParamsJs) . ");";

        return $this->_oTemplate->_wrapInTagJsCode($sCode);
    }
    
    protected function _getCounterLabel($iCount, $aParams = array())
    {
       return $this->_oTemplate->parseHtmlByName('connection_counter_label.html', array(
            'style_prefix' => $this->_sStylePrefix,
            'bx_if:show_icon' => array(
                'condition' => !isset($aParams['show_counter_label_icon']) || $aParams['show_counter_label_icon'] === true,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'name' => isset($aParams['custom_icon']) && $aParams['custom_icon'] != '' ? $aParams['custom_icon'] : BxTemplFunctions::getInstanceWithTemplate($this->_oTemplate)->getFontIconAsHtml($this->_getIconDo())
                )
            ),
            'bx_if:show_text' => array(
                'condition' => !isset($aParams['show_counter_label_text']) || $aParams['show_counter_label_text'] === true,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'text' => _t(isset($aParams['caption']) ? $aParams['caption'] : '_view_counter', $iCount)
                )
            )
        ));
    }
    
    protected function _getIconDo()
    {
        if ($this->_sObject == 'sys_profiles_subscriptions')
            return 'user-friends';
        
        if (strpos($this->_sObject, 'fan'))
            return 'users';
    }
    
    public function _getConnected ($sContentType, $iProfileId, $bIsMutual, $iStart = 0, $iPerPage = 0)
    {
        $sContentType = bx_get('content_type');
        $iProfileId = (int)bx_get('id');
        $bIsMutual = (bool)bx_get('mutual');
        
        $aUsers = $this->getConnectionsAsArray($sContentType, $iProfileId, null, $bIsMutual);
        
        $oPaginate = new BxTemplPaginate(array(
            'on_change_page' => $this->getJsObjectName($iProfileId) . '.getUsers(this, {start}, {per_page})',
            'start' => $iStart,
            'per_page' => $iPerPage,
        ));
        $oPaginate->setNumFromDataArray($aUsers);

        foreach($aUsers as $iProfile) {
            list($sUserName, $sUserUrl, $sUserIcon, $sUserUnit, $sUserUnitWoInfo) = $this->_getAuthorInfo($iProfile);

            $aTmplUsers[] = array(
                'style_prefix' => $this->_sStylePrefix,
                'user_unit' => $sUserUnitWoInfo,
                'user_url' => $sUserUrl,
            	'user_title' => bx_html_attribute($sUserName),
            	'user_name' => $sUserName
            );
        }
        
        if(empty($aTmplUsers))
            $aTmplUsers = MsgBox(_t('_Empty'));

        return $this->_oTemplate->parseHtmlByName('connected_by_list.html', array(
            'style_prefix' => $this->_sStylePrefix,
            'bx_repeat:list' => $aTmplUsers,
            'paginate' => $oPaginate->getSimplePaginate()
        ));
    }
    
    protected function _getAuthorInfo($iAuthorId = 0)
    {
        $oProfile = $this->_getAuthorObject($iAuthorId);

        return array(
            $oProfile->getDisplayName(),
            $oProfile->getUrl(),
            $oProfile->getThumb(),
            $oProfile->getUnit(),
            $oProfile->getUnit(0, array('template' => 'unit_wo_info'))
        );
    }

    protected function _getAuthorObject($iAuthorId = 0)
    {
    	if($iAuthorId == 0)
    		return BxDolProfileUndefined::getInstance();

        $oProfile = BxDolProfile::getInstance($iAuthorId);
        if(!$oProfile)
			$oProfile = BxDolProfileUndefined::getInstance();

        return $oProfile;
    }
}

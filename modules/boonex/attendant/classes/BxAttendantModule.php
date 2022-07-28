<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Attendant Attendant
 * @ingroup     UnaModules
 *
 * @{
 */
define('BX_ATTENDANT_ON_PROFILE_CREATION_METHOD', 'browse_recommended');
define('BX_ATTENDANT_ON_PROFILE_CREATION_EVENT_AFTER_REGISTRATION', 'registration');
define('BX_ATTENDANT_ON_PROFILE_CREATION_EVENT_AFTER_CONFIRMATION', 'confirmation');

class BxAttendantModule extends BxDolModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function actionRecomendedPopup($sModule, $sEvent, $iObjectId, $bManual)
    {
        $sRv = '';
        $aModules = explode(',', getParam('bx_attendant_on_profile_creation_modules'));
        $aModuleData = array();
        foreach($aModules as $sModuleName){
            if(BxDolRequest::serviceExists($sModuleName, BX_ATTENDANT_ON_PROFILE_CREATION_METHOD)){
                $aTmp = BxDolService::call($sModuleName, BX_ATTENDANT_ON_PROFILE_CREATION_METHOD, ['unit_view' => 'showcase', 'empty_message' => false, "ajax_paginate" => false]);

                if (isset($aTmp['content'])){
                    $sTmp = $aTmp['content'];
                    $sTmp = str_replace('bx_conn_action', 'bx_attendant_conn_action', $sTmp);
                    $aModuleData[$sModuleName] = $sTmp;
                }
            }
        }
        $bRedirect = true; // todo setting
        
        $oSession = BxDolSession::getInstance();
        $sFirstPage = $oSession->getValue('sys_entrance_url');
        $sFirstPage = 'page.php?i=view-group-profile&id=72';
       
        if ($sFirstPage){
            if ($bRedirect){
                echo json_encode(['redirect' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($sFirstPage)]);  
                exit();
            }
            else{
                list($sPageLink, $aPageParams) = bx_get_base_url($sFirstPage);
                if (isset($aPageParams['i']) && isset($aPageParams['id'])){
                    $oPage = BxDolPage::getObjectInstanceByURI($aPageParams['i']);

                    if ($oPage){
                        $sModuleName = $oPage->getModule();
                        if(bx_srv('system', 'is_module_context', [$sModuleName])){

                            $oModule = BxDolModule::getInstance($sModuleName);
                            $aTmp = $oModule->serviceBrowse([
                                'mode' => 'recent',
                                'params' => [
                                    'filter' => [
                                        'field' => 'id',
                                        'value' => [$aPageParams['id']],
                                        'operator' => 'in',
                                        'table' => 'tableSearch'
                                    ],
                                ]
                            ]);
                            echo $aPageParams['id'];
                            print_r($aTmp);
                            if (isset($aTmp['content'])){
                                $sTmp = $aTmp['content'];
                                $sTmp = str_replace('bx_conn_action', 'bx_attendant_conn_action', $sTmp);
                                $aModuleData[$sModuleName. '_initial_link'] = $sTmp;
                            }
                        }
                    }
                }
            }
        }
        
        $sRv = $this->_oTemplate->popup($aModuleData, $bManual);
        
        bx_alert('bx_attendant', 'show_popup', bx_get_logged_profile_id(), 0, ['module' => $sModule, 'event' => $sEvent, 'object_id' => $iObjectId, 'result' => &$sRv]);
        
        echo $sRv;
    }
    
    /**
     * Service methods
     */
    
    /**
     * @page service Service Calls
     * @section bx_attendant Attendant
     * @subsection bx_attendant-other Other
     * @subsubsection bx_attendant-on-profile get_profile_modules
     * 
     * @code bx_srv('bx_attendant', 'get_profile_modules', [...]); @endcode
     * 
     * Get list of avaliable modules for on_profile_creation event
     * 
     * @return an array with avaliable modules. 
     * 
     * @see BxAttendantModule::serviceGetProfileModules
     */
    /** 
     * @ref bx_attendant-get_profile_modules "get_profile_modules"
     */
    public function serviceGetProfileModules()
    {
        $aResult = array();
        $BxDolModuleQuery = BxDolModuleQuery::getInstance();
        $aModules = $BxDolModuleQuery->getModulesBy(array('type' => 'modules', 'active' => 1));
        foreach($aModules as $aModule){
            if(BxDolRequest::serviceExists($aModule['name'], BX_ATTENDANT_ON_PROFILE_CREATION_METHOD)){
                $aResult[$aModule['name']] = $aModule['title'];
            }
        }
        return $aResult;
    }
    
    
    /**
     * @page service Service Calls
     * @section bx_attendant Attendant
     * @subsection bx_attendant-other Other
     * @subsubsection bx_attendant-on-profile get_options_redirect_after_show
     * 
     * @code bx_srv('bx_attendant', 'get_options_redirect_after_show', [...]); @endcode
     * 
     * Get list avaliable redirct pages
     * 
     * @return an array with avaliable redirct pages. 
     * 
     * @see BxAttendantModule::serviceGetOptionsRedirectAfterShow
     */
    /** 
     * @ref bx_attendant-get_options_redirect_after_show "get_options_redirect_after_show"
     */
    public function serviceGetOptionsRedirectAfterShow()
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = [];
        $aChoices = ['noredirect', 'homepage', 'profile', 'custom'];
        foreach($aChoices as $sChoice) 
            $aResult[] = array('key' => $sChoice, 'value' => _t('_bx_attendant_option_redirect_show_' . $sChoice));

        return $aResult;
    }
    
    /**
     * @page service Service Calls
     * @section bx_attendant Attendant
     * @subsection bx_attendant-other Other
     * @subsubsection bx_attendant-on-profile get_popup_with_recommended_on_event_show
     * 
     * @code bx_srv('bx_attendant', 'get_popup_with_recommended_on_event_show', [...]); @endcode
     * 
     * Get list of events for on_profile_creation show
     * 
     * @return an array with avaliable events. 
     * 
     * @see BxAttendantModule::serviceGetPopupWithRecommendedOnEventShow
     */
    /** 
     * @ref bx_attendant-get_popup_with_recommended_on_event_show "get_popup_with_recommended_on_event_show"
     */
    public function serviceGetPopupWithRecommendedOnEventShow()
    {
        $aResult = array();
        $aChoices = array(BX_ATTENDANT_ON_PROFILE_CREATION_EVENT_AFTER_CONFIRMATION, BX_ATTENDANT_ON_PROFILE_CREATION_EVENT_AFTER_REGISTRATION);
        foreach($aChoices as $sChoice)
            $aResult[$sChoice] = _t('_bx_attendant_popup_event_after_' . $sChoice);
        
        return $aResult;
    }
    
    /**
     * @page service Service Calls
     * @section bx_attendant Attendant
     * @subsection bx_attendant-other Other
     * @subsubsection bx_attendant-get_profile_modules handle_action_view
     * 
     * @code bx_srv('bx_attendant', 'handle_action_view', [...]); @endcode
     * 
     * Get Include for injection
     * 
     * @return an include code
     * 
     * @see BxAttendantModule::HandleActionView
     */
    /** 
     * @ref bx_attendant-handle_action_view "handle_action_view"
     */
    public function serviceHandleActionView()
    {
        $sRv = $this->_oTemplate->init();
        
        if (!isLogged())
            return;
        
        $aEvents = $this->_oDb->getEvents(array('type' => 'active_by_action_and_profile_id', 'action' => 'view', 'profile_id' => bx_get_logged_profile_id()));
        foreach($aEvents as $aEvent){
            //$oRv = call_user_func_array(array($this, $aEvent['method']), array($aEvent['object_id']));
            $oRv = $this->processPopupOnEvent($aEvent);
            if ($oRv !== false){
                $sRv .= $oRv;
                $this->_oDb->setEventProcessed($aEvent['id']);
            }
        }
        
        return $sRv;
    }
    
    public function serviceHandleActionNonView()
    {
       //for some other actions fex cron - not implemented
    }
    
    public function addEvent($sAction, $iObjectId, $sModule, $sEvent, $iProfileId)
    {
        $this->_oDb->addEvent($sAction, $iObjectId, $sModule, $sEvent, $iProfileId);
    }
    
    public function initPopupByEvent($iObjectId, $sModule, $sEvent, $iProfileId)
    {
        $this->addEvent('view', $iObjectId, $sModule, $sEvent, $iProfileId);
    }
    
    public function processPopupOnEvent($aEvent)
    {
        $sRv = '';
        $sDefaultEvent = getParam('bx_attendant_on_profile_event_list');
        $oProfile = BxDolProfile::getInstance($aEvent['profile_id']);
        $oAccount = $oProfile ? $oProfile->getAccountObject() : null;
        $bNeedRaiseEvent = false;
        if ($aEvent['event'] == 'add' && $aEvent['module'] == 'profile'){
            if (getParam('bx_attendant_on_profile_creation_modules') != '' && ($sDefaultEvent == BX_ATTENDANT_ON_PROFILE_CREATION_EVENT_AFTER_REGISTRATION || ($sDefaultEvent == BX_ATTENDANT_ON_PROFILE_CREATION_EVENT_AFTER_CONFIRMATION  && $oAccount != null &&  $oAccount->isConfirmed()))){
                if ($oProfile->getModule() != 'system'){
                    $bNeedRaiseEvent = true;
                }
            }
        }
        else{
            bx_alert('bx_attendant', 'before_show_popup', bx_get_logged_profile_id(), 0, ['module' => $aEvent['module'], 'event' => $aEvent['event'], 'object_id' => $aEvent['object_id'], 'result' => &$bNeedRaiseEvent]);
        }
        if ($bNeedRaiseEvent)
            return getParam('bx_attendant_on_profile_creation_modules').'<script>oBxAttendant.showPopup("' . $aEvent['module'] . '", "' . $aEvent['event'] . '", ' . $aEvent['object_id'] . ')</script>';
        else
            return false;
    }
}

/** @} */

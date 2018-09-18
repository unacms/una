<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    AnonymousFollow Anonymous Follow
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAnonFollowModule extends BxDolModule
{
    public function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }
    
    /**
     * SERVICE METHODS
     */
    
    /**
     * @page service Service Calls
     * @section bx_anon_follow Mass mailer
     * @subsection bx_anon_follow-page_blocks Page Blocks
     * @subsubsection bx_anon_follow-get_profile_fields get_profile_fields
     * 
     * @code bx_srv('bx_anon_follow', 'get_profile_fields', [...]); @endcode
     * 
     * Get array with module filelds
     *
     * @param $aParams an array with search params.
     * @return array with module filelds
     * 
     * @see BxAnonFollowModule::GetProfileFields
     */
    /** 
     * @ref bx_anon_follow-get_profile_fields "get_profile_fields"
     */
    public function serviceGetProfileFields($sModule)
    {
        $aResult = array();
        $oModule = BxDolModule::getInstance($sModule);
        $aFields = $oModule->serviceGetSearchableFieldsExtended();
        foreach($aFields as $sKey => $aField){
            if ($aField['type'] == 'text' || $aField['type'] == 'select')
            $aResult[$sKey] = _t($aField['caption']);
        }
        return $aResult;
    }
    
    /**
     * @page service Service Calls
     * @section bx_anon_follow Mass mailer
     * @subsection bx_anon_follow-page_blocks Page Blocks
     * @subsubsection bx_anon_follow-check_is_subscribed check_is_subscribed
     * 
     * @code bx_srv('bx_anon_follow', 'check_is_subscribed', [...]); @endcode
     * 
     * Get subscribed connection for specified profile
     *
     * @param $aParams an array with search params.
     * @return boolean connection for specified profile
     * 
     * @see BxAnonFollowModule::CheckIsSubscribed
     */
    /** 
     * @ref bx_anon_follow-check_is_subscribed "check_is_subscribed"
     */
    public function serviceCheckIsSubscribed ($iProfileId)
    {
        if(isset($iProfileId)){
            return !$this->checkIsSubscribed($iProfileId);
        }
        return false;
    }
    
    /**
     * @page service Service Calls
     * @section bx_anon_follow Mass mailer
     * @subsection bx_anon_follow-page_blocks Page Blocks
     * @subsubsection bx_anon_follow-subscribed_me_table subscribed_me_table
     * 
     * @code bx_srv('bx_anon_follow', 'subscribed_me_table', [...]); @endcode
     * 
     * Get grid with subscribers list
     *
     * @param $aParams an array with search params.
     * @return HTML string with block content to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxAnonFollowModule::SubscribedMeTable
     */
    /** 
     * @ref bx_anon_follow-subscribed_me_table "subscribed_me_table"
     */
    public function serviceSubscribedMeTable ($iProfileId = 0)
    {
        if(!$iProfileId && bx_get('profile_id') !== false)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);

        $aProfile = BxDolProfile::getInstance($iProfileId)->getInfo();
        if(empty($aProfile) || !is_array($aProfile))
            return false;

        $CNF = &BxDolModule::getInstance($aProfile['type'])->_oConfig->CNF;
        if(getParam($CNF['PARAM_PUBLIC_SBSD']) != 'on' && $aProfile['id'] != bx_get_logged_profile_id())
            return false;

        $oGrid = BxDolGrid::getObjectInstance('bx_anon_follow_subscribed_me');

        if(!$oGrid)
            return false;

        $oGrid->setProfileId($iProfileId);
        $sContent = $oGrid->getCode();
        if(empty($sContent))
            return false;

        return $this->_oTemplate->parseHtmlByName('connections_list.html', array(
            'name' => 'subscribers',
            'content' => $sContent
        ));
    }
    
    /**
     * @page service Service Calls
     * @section bx_anon_follow Mass mailer
     * @subsection bx_anon_follow-page_blocks Page Blocks
     * @subsubsection bx_anon_follow-include_js include_js
     * 
     * @code bx_srv('bx_anon_follow', 'include_js', [...]); @endcode
     * 
     * Add js to injection in head
     *
     * @param $aParams an array with search params.
     * @return void
     * 
     * @see BxAnonFollowModule::serviceIncludeJs
     */
    /** 
     * @ref bx_anon_follow-include_js "include_js"
     */
    public function serviceIncludeJs ()
    {
        $this->_oTemplate->addJs(array('main.js'));
        return ;
    }
    
    public function checkIsSubscribed($iProfileId)
    {
        $bRv = false;
        $oConnection = BxDolConnection::getObjectInstance('sys_profiles_subscriptions');
        if(!$oConnection){
            $bRv = false;
        }
        if($oConnection->isConnected(bx_get_logged_profile_id(), $iProfileId)){
            $bRv = true;
        }
        return $bRv;
    }
}

/** @} */

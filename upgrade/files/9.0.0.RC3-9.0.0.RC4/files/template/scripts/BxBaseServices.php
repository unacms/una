<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services.
 */
class BxBaseServices extends BxDol implements iBxDolProfileService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceProfileUnit ($iContentId, $aParams = array())
    {
        return $this->_serviceProfileFunc('getUnit', $iContentId);
    }

    public function serviceHasImage ($iContentId)
    {
        return false;
    }

    public function serviceProfilePicture ($iContentId)
    {
        return $this->_serviceProfileFunc('getPicture', $iContentId);
    }

    public function serviceProfileAvatar ($iContentId)
    {
        return $this->_serviceProfileFunc('getAvatar', $iContentId);
    }

    public function serviceProfileEditUrl ($iContentId)
    {
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=account-settings-info');
    }

    public function serviceProfileThumb ($iContentId)
    {
        return $this->_serviceProfileFunc('getThumb', $iContentId);
    }

    public function serviceProfileIcon ($iContentId)
    {
        return $this->_serviceProfileFunc('getIcon', $iContentId);
    }

    public function serviceProfileName ($iContentId)
    {
        return $this->_serviceProfileFunc('getDisplayName', $iContentId);
    }

    public function serviceProfileUrl ($iContentId)
    {
        return $this->_serviceProfileFunc('getUrl', $iContentId);
    }

    /**
     * @see iBxDolProfileService::serviceCheckAllowedProfileView
     */ 
    public function serviceCheckAllowedProfileView($iContentId)
    {
        return _t('_Access denied');
    }

    /**
     * @see iBxDolProfileService::serviceCheckAllowedPostInProfile
     */ 
    public function serviceCheckAllowedPostInProfile($iContentId)
    {
        return _t('_Access denied');
    }

    public function serviceFormsHelper ()
    {
        return new BxTemplAccountForms();
    }

    public function serviceActAsProfile ()
    {
        return false;
    }

    public function servicePrepareFields ($aFieldsProfile)
    {
        return $aFieldsProfile;
    }

    public function serviceProfilesSearch ($sTerm, $iLimit)
    {
        $oDb = BxDolAccountQuery::getInstance();
        $aRet = array();
        $a = $oDb->searchByTerm($sTerm, $iLimit);
        foreach ($a as $r)
            $aRet[] = array ('label' => $this->serviceProfileName($r['content_id']), 'value' => $r['profile_id']);
        return $aRet;
    }

    public function _serviceProfileFunc ($sFunc, $iContentId)
    {
        if (!$iContentId)
            return false;
        if (!($oAccount = BxDolAccount::getInstance($iContentId)))
            return false;
        return $oAccount->$sFunc();
    }

    public function serviceAlertResponseProcessInstalled()
    {
        BxDolTranscoderImage::registerHandlersSystem();
    }

    public function serviceAlertResponseProcessStorageChange ($oAlert)
    {
        if ('sys_storage_default' != $oAlert->aExtras['option'])
            return;

        $aStorages = BxDolStorageQuery::getStorageObjects();
        foreach ($aStorages as $r) {
            if (0 == $r['current_size'] && 0 == $r['current_number'] && ($oStorage = BxDolStorage::getObjectInstance($r['object'])))
                $oStorage->changeStorageEngine($oAlert->aExtras['value']);
        }

    }

    /**
     * This service adds notification for users which open your site on mobile devices
     * and suggest to add your site to their mobile homepage.
     */ 
    public function serviceAddToMobileHomepage ()
    {
        BxDolTemplate::getInstance()->addJs('cubiq-add-to-homescreen/addtohomescreen.min.js');
        BxDolTemplate::getInstance()->addCss(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'cubiq-add-to-homescreen/style/|addtohomescreen.css');
        return "<script>addToHomescreen();</script>";
    }

    public function serviceAddPopupAlert()
    {
        return BxTemplFunctions::getInstance()->transBox('bx-popup-alert', BxDolTemplate::getInstance()->parseHtmlByName('popup_trans_alert_cnt.html', array()), true);
    }

    public function serviceAddPopupConfirm()
    {
        return BxTemplFunctions::getInstance()->transBox('bx-popup-confirm', BxDolTemplate::getInstance()->parseHtmlByName('popup_trans_confirm_cnt.html', array()), true);
    }

    public function serviceAddPopupPrompt()
    {
        $oTemplate = BxDolTemplate::getInstance();

        $sInputText = 'bx-popup-prompt-value';
        $aInputText = array(
            'type' => 'text',
            'name' => $sInputText,
            'attrs' => array(
                'id' => $sInputText,
            ),
            'value' => '',
            'caption' => ''
        );

        $oForm = new BxTemplFormView(array(), $oTemplate);
        return BxTemplFunctions::getInstance()->transBox('bx-popup-prompt', $oTemplate->parseHtmlByName('popup_trans_prompt_cnt.html', array(
            'input' => $oForm->genRow($aInputText)
        )), true);
    }

    public function serviceAddPushInit($iProfileId = 0)
    {
        $iProfileId = !empty($iProfileId) ? $iProfileId : bx_get_logged_profile_id();
        if(empty($iProfileId))
            return '';

        $sAppId = getParam('sys_push_app_id');
        if(empty($sAppId))
            return '';

        $oTemplate = BxDolTemplate::getInstance();

        $sShortName = getParam('sys_push_short_name');
        $sSafariWebId = getParam('sys_push_safari_id');

        $sSubfolder = '/plugins_public/onesignal/';
        $aUrl = parse_url(BX_DOL_URL_ROOT);
        if(!empty($aUrl['path'])) {
            $sPath = trim($aUrl['path'], '/');
            if(!empty($sPath))
                $sSubfolder = '/' . $sPath . $sSubfolder;
        }

        $oTemplate->addJs(array(
            'https://cdn.onesignal.com/sdks/OneSignalSDK.js',
            'BxDolPush.js',
        ));

        $sJsClass = 'BxDolPush';
        $sJsObject = 'oBxDolPush';

        $sContent = "var " . $sJsObject . " = new " . $sJsClass . "(" . json_encode(array(
            'sObjName' => $sJsObject,
            'sSiteName' => getParam('site_title'),
            'iProfileId' => $iProfileId,
            'sAppId' => $sAppId,
            'sShortName' => $sShortName,
            'sSafariWebId' => $sSafariWebId,
            'sSubfolder' => $sSubfolder,
            'sNotificationUrl' => BX_DOL_URL_ROOT,
            'sTxtNotificationRequest' => bx_js_string(_t('_sys_push_notification_request', getParam('site_title'))),
            'sTxtNotificationRequestYes' => bx_js_string(_t('_sys_push_notification_request_yes')),
            'sTxtNotificationRequestNo' => bx_js_string(_t('_sys_push_notification_request_no')),
        )) . ");";

        return $oTemplate->_wrapInTagJsCode($sContent);
    }
}

/** @} */

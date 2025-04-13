<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * OneSignal integration.
 * @see BxDolPush
 */
class BxBasePushOneSignal extends BxDolPushOneSignal
{
    protected function _getCodePageHeader()
    {
        if($this->_bCodeAdded || !$this->_sAppId)
            return '';

        $iProfileId = bx_get_logged_profile_id();
        if(empty($iProfileId))
            return '';

        $aTags = self::getTags($iProfileId);
        if(!$aTags)
            return '';

        $sSubfolder = '/plugins_public/onesignal/';
        $aUrl = parse_url(BX_DOL_URL_ROOT);
        if(!empty($aUrl['path'])) {
            $sPath = trim($aUrl['path'], '/');
            if(!empty($sPath))
                $sSubfolder = '/' . $sPath . $sSubfolder;
        }

        $sJsObject = 'oBxDolPushOneSignal';

        $this->_oTemplate->addJs([
            'https://cdn.onesignal.com/sdks/OneSignalSDK.js',
            'BxDolPushOneSignal.js',
        ]);

        $this->_bCodeAdded = true;
        return $this->_oTemplate->_wrapInTagJsCode("var " . $sJsObject . " = new BxDolPushOneSignal(" . json_encode([
            'sObjName' => $sJsObject,
            'sSiteName' => getParam('site_title'),
            'aTags' => $aTags,
            'sAppId' => $this->_sAppId,
            'sShortName' => $this->_sShortName,
            'sSafariWebId' => $this->_sSafariWebId,
            'sSubfolder' => $sSubfolder,
            'sNotificationUrl' => BX_DOL_URL_ROOT,
            'sTxtNotificationRequest' => _t('_sys_push_notification_request', getParam('site_title')),
            'sTxtNotificationRequestYes' => _t('_sys_push_notification_request_yes'),
            'sTxtNotificationRequestNo' => _t('_sys_push_notification_request_no'),
        ]) . ");");
    }
}

/** @} */

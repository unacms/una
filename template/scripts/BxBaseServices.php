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
}

/** @} */

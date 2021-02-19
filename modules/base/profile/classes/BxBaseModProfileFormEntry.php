<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/edit profile form.
 */
class BxBaseModProfileFormEntry extends BxBaseModGeneralFormEntry
{
    protected $_iAccountProfileId = 0;
    protected $_aImageFields = array ();

    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);

        $this->_sAuthorKey = 'profile_id';

        $CNF = &$this->_oModule->_oConfig->CNF;

        if (!empty($CNF['FIELD_PICTURE']) && isset($this->aInputs[$CNF['FIELD_PICTURE']])) {
            $this->_aImageFields[$CNF['FIELD_PICTURE']] = array (
                'storage_object' => $CNF['OBJECT_STORAGE'],
                'images_transcoder' => $CNF['OBJECT_IMAGES_TRANSCODER_THUMB'],
                'uploaders' => $CNF['OBJECT_UPLOADERS_PICTURE'],
            );
        }

        if (!empty($CNF['FIELD_COVER']) && isset($this->aInputs[$CNF['FIELD_COVER']])) {
            $this->_aImageFields[$CNF['FIELD_COVER']] = array (
                'storage_object' => $CNF['OBJECT_STORAGE_COVER'],
                'images_transcoder' => $CNF['OBJECT_IMAGES_TRANSCODER_COVER_THUMB'],
                'uploaders' => $CNF['OBJECT_UPLOADERS_COVER'],
            );
        }

        foreach ($this->_aImageFields as $sField => $aParams) {
            $this->aInputs[$sField]['storage_object'] = $aParams['storage_object'];
            $this->aInputs[$sField]['uploaders'] = !empty($this->aInputs[$sField]['value']) ? unserialize($this->aInputs[$sField]['value']) : $aParams['uploaders'];
            $this->aInputs[$sField]['images_transcoder'] = $aParams['images_transcoder'];
            $this->aInputs[$sField]['storage_private'] = 0;
            $this->aInputs[$sField]['multiple'] = false;
            $this->aInputs[$sField]['content_id'] = 0;
            $this->aInputs[$sField]['ghost_template'] = '';
        }

        $oAccountProfile = BxDolProfile::getInstanceAccountProfile();
        if ($oAccountProfile)
            $this->_iAccountProfileId = $oAccountProfile->id();
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aContentInfo = isset($CNF['FIELD_ID']) && isset($aValues[$CNF['FIELD_ID']]) ? $this->_oModule->_oDb->getContentInfoById ($aValues[$CNF['FIELD_ID']]) : array();
        
        foreach ($this->_aImageFields as $sField => $aParams) {

            if ($aValues && !empty($aValues[$CNF['FIELD_ID']]))
                $this->aInputs[$sField]['content_id'] = $aValues[$CNF['FIELD_ID']];

            $this->aInputs[$sField]['ghost_template'] = $this->_oModule->_oTemplate->parseHtmlByName('form_ghost_template.html', $this->_getProfilePhotoGhostTmplVars($sField, $aContentInfo));
        }

        parent::initChecker($aValues, $aSpecificValues);
    }

    function delete ($iContentId, $aContentInfo = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iProfileId = $this->getContentOwnerProfileId($iContentId);        

        foreach ($this->_aImageFields as $sField => $aParams) {
            $oStorage = BxDolStorage::getObjectInstance($aParams['storage_object']);
            $aFiles = $oStorage->getGhosts($iProfileId, $iContentId);

            foreach ($aFiles as $aFile) {
                if (!$oStorage->getFile($aFile['id']))
                    continue;
                $bRet = $oStorage->deleteFile($aFile['id'], $this->_iAccountProfileId);
            }
        }

        return parent::delete($iContentId, $aContentInfo);
    }

    protected function genCustomViewRowValueProfileEmail($aInput)
    {
        return $this->genCustomViewRowValueProfileEmailOrIp($aInput);
    }
    
    protected function genCustomViewRowValueProfileIp($aInput)
    {
        return $this->genCustomViewRowValueProfileEmailOrIp($aInput);
    }
    
    protected function genCustomViewRowValueFriendsCount($aInput)
    {
        if (isset($this->_oModule->_oConfig->CNF['URI_VIEW_FRIENDS'])){
            $oProfile = $this->_oModule->getProfileByCurrentUrl();
            if ($oProfile){
                $oConnection = BxDolConnection::getObjectInstance('sys_profiles_friends');
                $iCount = $oConnection->getConnectedContentCount($oProfile->id(), true);
                return $this->_oModule->_oTemplate->parseHtmlByName('name_link.html', array(
                    'href' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->_oModule->_oConfig->CNF['URI_VIEW_FRIENDS'] . '&profile_id=' . $oProfile->id()),
                    'title' => '',
                    'content' => $iCount
                ));
            }
        }
        return '';
    }
    
    protected function genCustomViewRowValueFollowersCount($aInput)
    {
        if (isset($this->_oModule->_oConfig->CNF['URI_VIEW_SUBSCRIPTIONS'])){
            $oProfile = $this->_oModule->getProfileByCurrentUrl();
            if ($oProfile){
                $oConnectionFollow = BxDolConnection::getObjectInstance('sys_profiles_subscriptions');
                $iCount = $oConnectionFollow->getConnectedInitiatorsCount($oProfile->id());
                return $this->_oModule->_oTemplate->parseHtmlByName('name_link.html', array(
                    'href' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->_oModule->_oConfig->CNF['URI_VIEW_SUBSCRIPTIONS'] . '&profile_id=' . $oProfile->id()),
                    'title' => '',
                    'content' => $iCount
                ));
            }
        }
        return '';
    }
    
    private function genCustomViewRowValueProfileEmailOrIp($aInput)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        if(empty($aInput['value']))
            return '';

        $sValue = $aInput['value'];

        $sModuleAccounts = 'bx_accounts';
    	if(!BxDolModuleQuery::getInstance()->isEnabledByName($sModuleAccounts))
    		return $sValue;

		$oModuleAccounts = BxDolModule::getInstance($sModuleAccounts);
		if(!$oModuleAccounts || empty($oModuleAccounts->_oConfig->CNF['URL_MANAGE_ADMINISTRATION']))
			return $sValue;

        return $this->_oModule->_oTemplate->parseHtmlByName('name_link.html', array(
            'href' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($oModuleAccounts->_oConfig->CNF['URL_MANAGE_ADMINISTRATION'], array(
            	'filter' => urlencode($sValue)
            )),
            'title' => '',
            'content' => $sValue
        ));
    }
    
    protected function genCustomViewRowValueProfileStatus($aInput)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        if(empty($aInput['value']))
            return '';

        $sStatus = _t('_sys_profile_status_' . $aInput['value']);
        if(empty($CNF['URL_MANAGE_ADMINISTRATION']) || empty($CNF['FIELD_TITLE']) || empty($this->aInputs[$CNF['FIELD_TITLE']]['value']))
            return $sStatus;

        return $this->_oModule->_oTemplate->parseHtmlByName('name_link.html', array(
            'href' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_MANAGE_ADMINISTRATION'], array(
            	'filter' => urlencode($this->aInputs[$CNF['FIELD_TITLE']]['value'])
            )),
            'title' => '',
            'content' => $sStatus
        ));
    }

    protected function _associalFileWithContent($oStorage, $iFileId, $iProfileId, $iContentId, $sPictureField = '')
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oStorage->updateGhostsContentId ($iFileId, $iProfileId, $iContentId, $this->_isAdmin($iContentId));

        $bResult = (int)$this->_oModule->_oDb->updateContentPictureById($iContentId, 0/*$iProfileId*/, $iFileId, $sPictureField) > 0;
        if($bResult) {
            $sModule = $this->_oModule->getName();
            switch($sPictureField) {
                case $CNF['FIELD_PICTURE']:
                    bx_alert($sModule, 'profile_picture_changed', $iFileId, $iProfileId, array('content' => $iContentId, 'field' => $CNF['FIELD_PICTURE']));
                    break;

                case $CNF['FIELD_COVER']:
                    bx_alert($sModule, 'profile_cover_changed', $iFileId, $iProfileId, array('content' => $iContentId, 'field' => $CNF['FIELD_COVER']));
                    break;
            }
        }
    }

    protected function _getProfilePhotoGhostTmplVars($sField, $aContentInfo = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	return array (
			'name' => $this->aInputs[$sField]['name'],
            'content_id' => $this->aInputs[$sField]['content_id'],
			'bx_if:set_thumb' => array (
				'condition' => false,
				'content' => array (),
			),
		);
    }

    protected function _isAdmin ($iContentId = 0)
    {
        if (parent::_isAdmin ($iContentId))
            return true;
        if (!$iContentId || !($aDataEntry = $this->_oModule->_oDb->getContentInfoById((int)$iContentId)))
            return false;
        return CHECK_ACTION_RESULT_ALLOWED == $this->_oModule->checkAllowedEdit ($aDataEntry);        
    }

    protected function _getPrivacyFields($aKeysF2O = array())
    {
        if(empty($aKeysF2O))
            $aKeysF2O = array(
                'FIELD_ALLOW_VIEW_TO' => 'OBJECT_PRIVACY_VIEW',
                'FIELD_ALLOW_POST_TO' => 'OBJECT_PRIVACY_POST',
                'FIELD_ALLOW_CONTACT_TO' => 'OBJECT_PRIVACY_CONTACT'
            );

        return parent::_getPrivacyFields($aKeysF2O);
    }
}

/** @} */

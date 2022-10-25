<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    CASConnect CAS Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxCASModule extends BxBaseModConnectModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }
    
    /**
     * Redirect to remote site login form
     *
     * @return n/a - redirect or HTML page in case of error
     */
    public function actionStart()
    {
        if (isLogged())
            $this->_redirect ($this -> _oConfig -> sDefaultRedirectUrl);

        if (!getParam('bx_cas_path_simplesamlphp')) {
            require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');
            bx_import('BxDolLanguages');
            $sCode =  MsgBox( _t('_bx_cas_profile_error_config') );
            $this->_oTemplate->getPage(_t('_bx_cas'), $sCode);            
        } 
	    else {
            require_once(getParam('bx_cas_path_simplesamlphp') . '/lib/_autoload.php');

            $as = new \SimpleSAML\Auth\Simple('default-sp');

            $as->requireAuth();

            $aAttributes = $as->getAttributes();
			
            // check if user logged in before
            $iLocalProfileId = $this->_oDb->getProfileId($aAttributes['salesforce_id'][0]);

            require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');

            if ($iLocalProfileId && $oProfile = BxDolProfile::getInstance($iLocalProfileId)) {
                // user already exists
                $this->setLogged($oProfile->id());
            }
            else {
                // register new user
                $aAttributes['id'] = $this->_getAttrValue($aAttributes['salesforce_id']);
                $this->_createProfile($aAttributes);
            }
        }
    }

    /**
     * @param $aProfileInfo - remote profile info
     * @param $sAlternativeName - suffix to add to NickName to make it unique
     * @return profile array info, ready for the local database
     */
    protected function _convertRemoteFields($aProfileInfo, $sAlternativeName = '')
    {
        $aProfileFields = [];

        $aProfileFields['id'] = $this->_getAttrValue($aProfileInfo['id']); // TODO: change to actial field
        $aProfileFields['name'] = $this->_getAttrValue($aProfileInfo['name']); // TODO: change to actial field
        $aProfileFields['fullname'] = $this->_getAttrValue($aProfileInfo['first_name']) . ' ' . (isset($aProfileInfo['last_name']) ? $this->_getAttrValue($aProfileInfo['last_name']) : '');

		if (isset($aProfileInfo['first_name']))
            $aProfileFields['first_name'] = $this->_getAttrValue($aProfileInfo['first_name']);

        if (isset($aProfileInfo['last_name']))
            $aProfileFields['last_name'] = $this->_getAttrValue($aProfileInfo['last_name']);

        $aProfileFields['email'] = isset($aProfileInfo['email']) ? $this->_getAttrValue($aProfileInfo['email']) : '';
        $aProfileFields['allow_view_to'] = getParam('bx_cas_privacy');

        return $aProfileFields;
    }

    protected function _getAttrValue($a) 
    {
        if (isset($a[0]))
            return $a[0];
        return false;
    }

    public function serviceAccountAddFormCheck()
    {
        if (!getParam('bx_cas_disable_join_form'))
            return '';

        return '<div></div>';
    }
}

/** @} */

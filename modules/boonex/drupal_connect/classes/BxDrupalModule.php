<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    DrupalConnect Drupal Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDrupalModule extends BxBaseModConnectModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }    

    function actionLoginSubmit()
    {
        require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

        $oForm = BxDolForm::getObjectInstance('bx_drupal_login', 'bx_drupal_login');

        $oForm->initChecker();
        $oForm->setRole(bx_get('role'));
        $bLoginSuccess = $oForm->isSubmittedAndValid();

        $bAjxMode = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ? true : false;
        if ($bAjxMode) {

            header( 'Content-type: text/html; charset=utf-8' );
            echo $bLoginSuccess ? 'OK' : $oForm->getLoginError();
            exit;

        } 
        elseif ($bLoginSuccess) {
            
            $sId = trim($oForm->getCleanValue('ID'));

            $oAccount = BxDolAccount::getInstance($sId);
            $aAccount = bx_login($oAccount->id(), ($oForm->getCleanValue('rememberMe') ? true : false));

            $sUrlRelocate = $oForm->getCleanValue('relocate');
            if (!$sUrlRelocate || 0 !== strncmp($sUrlRelocate, BX_DOL_URL_ROOT, strlen(BX_DOL_URL_ROOT)))
                $sUrlRelocate = BX_DOL_ROLE_ADMIN == $oForm->getRole() ? BX_DOL_URL_STUDIO . 'launcher.php' : BX_DOL_URL_ROOT . 'member.php';

            BxDolTemplate::getInstance()->setPageNameIndex (BX_PAGE_TRANSITION);
            BxDolTemplate::getInstance()->setPageHeader (_t('_Please Wait'));
            BxDolTemplate::getInstance()->setPageContent ('page_main_code', MsgBox(_t('_Please Wait')));
            BxDolTemplate::getInstance()->setPageContent ('url_relocate', bx_html_attribute($sUrlRelocate, BX_ESCAPE_STR_QUOTE));

            BxDolTemplate::getInstance()->getPageCode();
            exit;
        }        

        bx_require_authentication();

        header('Location: ' . BX_DOL_URL_ROOT);
    }

    public function serviceHandleUser($aRemoteProfileInfo)
    {
        if ($aRemoteProfileInfo && isset($aRemoteProfileInfo['user']) && isset($aRemoteProfileInfo['user']['uid'])) {

            // check if user logged in before
            $iLocalProfileId = $this->_oDb->getProfileId($aRemoteProfileInfo['user']['uid']);
            
            if ($iLocalProfileId && $oProfile = BxDolProfile::getInstance($iLocalProfileId)) {
                // user already exists
                bx_alert($this->getName(), 'login', 0, $oProfile->id(), array('remote_profile_info' => $aRemoteProfileInfo));
                $this->setLogged($oProfile->id());
            }             
            else {  
                // register new user
                if (isset($aRemoteProfileInfo['user']) && isset($aRemoteProfileInfo['user']['uid']))
                    $aRemoteProfileInfo['id'] = $aRemoteProfileInfo['user']['uid'];
                $this->_createProfile($aRemoteProfileInfo);
            }
        } 
        else {
            $this->_oTemplate->getPage(_t('_Error'), MsgBox(_t('_sys_connect_profile_error_info')));
        }
    }

    /**
     * @param $aProfileInfo - remote profile info
     * @param $sAlternativeName - suffix to add to NickName to make it unique
     * @return profile array info, ready for the local database
     */
    protected function _convertRemoteFields($aProfileInfo, $sAlternativeName = '')
    {
        $sFirstName = '';
        if (isset($aProfileInfo['user']) && isset($aProfileInfo['user']['field_first_name'])) {
            $a = array_pop($aProfileInfo['user']['field_first_name']['und']);
            $sFirstName = $a['value'];
        }
        
        $sLastName = '';
        if (isset($aProfileInfo['user']) && isset($aProfileInfo['user']['field_last_name'])) {
            $a = array_pop($aProfileInfo['user']['field_last_name']['und']);
            $sLastName = $a['value'];
        }

        if (empty($sFirstName)) {
            if (isset($aProfileInfo['user']['mail']) && false !== ($iPos = mb_strpos($aProfileInfo['user']['mail'], '@')))
                $sFirstName = mb_substr($aProfileInfo['user']['mail'], 0, $iPos);
        }

        $aProfileFields['id'] = $aProfileInfo['user']['uid'];
        $aProfileFields['name'] = empty($sFirstName) ? $aProfileInfo['user']['uid'] : $sFirstName;
        $aProfileFields['fullname'] = $sFirstName . (empty($sLastName) ? '' : ' ' . $sLastName);
        $aProfileFields['email'] = isset($aProfileInfo['user']['mail']) ? $aProfileInfo['user']['mail'] : '';
        $aProfileFields['picture'] = isset($aProfileInfo['user']['picture']) ? $aProfileInfo['user']['picture'] : '';
        $aProfileFields['allow_view_to'] = getParam('bx_drupal_privacy');
        
        return $aProfileFields;
    }
}

/** @} */

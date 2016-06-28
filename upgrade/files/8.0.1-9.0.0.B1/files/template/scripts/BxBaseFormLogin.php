<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Login Form.
 */
class BxBaseFormLogin extends BxTemplFormView
{
    protected $_iRole = BX_DOL_ROLE_MEMBER;

    public function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);

        $aNoRelocatePages = array('forgot-password', 'login', 'create-account', 'logout');

        $sRelocate = bx_process_input(bx_get('relocate'));
        if (!$sRelocate && isset($_SERVER['HTTP_REFERER']) && 0 === mb_stripos($_SERVER['HTTP_REFERER'], BX_DOL_URL_ROOT)) {

            $sRelocate = $_SERVER['HTTP_REFERER'];
    
            foreach ($aNoRelocatePages as $s) {
                if (false !== mb_stripos($_SERVER['HTTP_REFERER'], $s)) {
                    $sRelocate = BX_DOL_URL_ROOT . 'member.php';
                    break;
                }
            }   
        }
        
        $this->aInputs['relocate']['value'] = $sRelocate ? $sRelocate : BX_DOL_URL_ROOT . 'member.php';
    }

    function isValid ()
    {
        if (!parent::isValid ())
            return false;

		$sId = trim($this->getCleanValue('ID'));
		$sPassword = $this->getCleanValue('Password');

        $sErrorString = bx_check_password($sId, $sPassword, $this->getRole());
        $this->_setCustomError ($sErrorString);
        return $sErrorString ? false : true;
    }

    protected function genCustomInputSubmitText ($aInput)
    {
        return '<div><a href="' . BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=forgot-password') . '">' . _t("_sys_txt_forgot_pasword") . '</a></div>';
    }

    public function getRole()
    {
        return $this->_iRole;
    }

    public function setRole ($iRole)
    {
        $this->_iRole = $iRole == BX_DOL_ROLE_ADMIN ? BX_DOL_ROLE_ADMIN : BX_DOL_ROLE_MEMBER;
    }

    public function getLoginError ()
    {
        return isset($this->aInputs['ID']['error']) ? $this->aInputs['ID']['error'] : '';
    }

    protected function _setCustomError ($s)
    {
        $this->aInputs['ID']['error'] = $s;
    }
}

/** @} */

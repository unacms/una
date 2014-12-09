<?php defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentInstall Trident Install
 * @{
 */

class BxDolInstallLang
{
    protected $_aLang;
    protected $_sLang;
    protected $_oModulesTools;

    public function __construct($sLang)
    {
        $this->_oModulesTools = new BxDolInstallModulesTools();
        if (!$sLang)
            $sLang = BX_INSTALL_DEFAULT_LANGUAGE;
        $aModules = $this->_oModulesTools->getModules('language');
        $aModuleConfig = $this->_oModulesTools->getModuleConfigByUri ($sLang, $aModules);
        if (!$aModuleConfig && BX_INSTALL_DEFAULT_LANGUAGE != $sLang) {
            $sLang = BX_INSTALL_DEFAULT_LANGUAGE;
            $aModuleConfig = $this->_oModulesTools->getModuleConfigByUri ($sLang, $aModules);
        }

        $this->_sLang = $sLang;
        $this->_aLang = $this->_oModulesTools->readLanguage($aModuleConfig);
    }

    static function getInstance($sLang = '')
    {
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolInstallLang($sLang);

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public function _t ($sKey)
    {
        $sKey = func_get_arg(0);
        if (isset($this->_aLang[$sKey])) {
            $s = $this->_aLang[$sKey];

            if (($iNumArgs = func_num_args()) > 1)
                for ($i = 1; $i < $iNumArgs; $i++)
                    $s = str_replace('{' . ($i - 1) . '}', func_get_arg($i), $s);

            return $s;
        }

        return $sKey;
    }

    public function getAvailableLanguages ()
    {
        $aRet = array();
        $aModules = $this->_oModulesTools->getModules('language');
        foreach ($aModules as $aModuleConfig)
            $aRet[$aModuleConfig['home_uri']] = array(
                'code' => $aModuleConfig['home_uri'],
                'title' => $aModuleConfig['title'],
                'icon' => BX_INSTALL_URL_MODULES . $aModuleConfig['home_dir'] . 'template/images/icons/std-pi.png',
            );
        return $aRet;
    }

}

if (!function_exists('_t')) {
    $sLang = 'en';
    if (isset($_GET['lang'])) {
        $sLang = $_GET['lang'];
        setcookie( 'lang', '', time() - 60*60*24);
        setcookie( 'lang', $sLang, time() + 60*60*24*365);
    } elseif (isset($_COOKIE['lang'])) {
        $sLang = $_COOKIE['lang'];
    }

    BxDolInstallLang::getInstance($sLang);

    function _t()
    {
        return call_user_func_array(array(BxDolInstallLang::getInstance(), '_t'), func_get_args());
    }
}

/** @} */

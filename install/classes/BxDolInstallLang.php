<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinInstall Dolphin Install
 * @{
 */

class BxDolInstallLang
{
    protected $_aLang;
    protected $_sLang;

    public function __construct($sLang) 
    {
        if (!$sLang)
            $sLang = BX_INSTALL_DEFAULT_LANGUAGE;
        $aModules = $this->getModules('language');
        $aModuleConfig = $this->getModuleConfigByLang ($sLang, $aModules);
        if (!$aModuleConfig && BX_INSTALL_DEFAULT_LANGUAGE != $sLang) {
            $sLang = BX_INSTALL_DEFAULT_LANGUAGE;
            $aModuleConfig = $this->getModuleConfigByLang ($sLang, $aModules);
        }

        $this->_sLang = $sLang;
        $this->_aLang = $this->readLanguage($aModuleConfig);
    }

    static function getInstance($sLang = '') {
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
   
    public function getAvailableLanguages () {
        $aRet = array();
        $aModules = $this->getModules('language');
        foreach ($aModules as $aModuleConfig)
            $aRet[$aModuleConfig['home_uri']] = array(
                'code' => $aModuleConfig['home_uri'],
                'title' => $aModuleConfig['title'],
                'icon' => BX_INSTALL_URL_MODULES . $aModuleConfig['home_dir'] . 'template/images/icons/std-pi.png',
            );
        return $aRet;
    }

    public function getModules ($sType = null) {
    	$aModules = array();

        $sPath = BX_INSTALL_DIR_MODULES;
        if (($rHandleVendor = opendir($sPath)) !== false) {
            while (($sVendor = readdir($rHandleVendor)) !== false) {
                if (substr($sVendor, 0, 1) == '.' || !is_dir($sPath . $sVendor)) 
                    continue;

                if (($rHandleModule = opendir($sPath . $sVendor)) !== false) {
                    while(($sModule = readdir($rHandleModule)) !== false) {
                        if(!is_dir($sPath . $sVendor . '/' . $sModule) || substr($sModule, 0, 1) == '.')
                            continue;

						$sConfigPath = $sPath . $sVendor . '/' . $sModule . '/install/config.php';
						$aModuleConfig = $this->getModuleConfigByConfigPath($sConfigPath);
						if (empty($aModuleConfig) || ($sType && $sType != $aModuleConfig['type']))
							continue;

						$aModules[$aModuleConfig['name']] = $aModuleConfig;
                    }
                    closedir($rHandleModule);
                }
            }
            closedir($rHandleVendor);
        }

        ksort($aModules);
        return $aModules;
    }

    protected function getModuleConfigByConfigPath ($sConfigPath) {
		if (!file_exists($sConfigPath))
			return array();

		include($sConfigPath);

        return $aConfig;
    }

    protected function getModuleConfigByLang ($sLang, $aModules) {
        foreach ($aModules as $aModuleConfig)
            if ($sLang == $aModuleConfig['home_uri'])
                return $aModuleConfig;
        return null;
    }

    protected function readLanguage ($aModuleConfig) {
        $sPath = BX_INSTALL_DIR_MODULES . $aModuleConfig['home_dir'] . 'data/langs/system.xml';

    	if(!file_exists($sPath))
    		return array();

    	$oXmlParser = BxDolXmlParser::getInstance();
    	$sXmlContent = file_get_contents($sPath);

        return $oXmlParser->getValues($sXmlContent, 'string');
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

    function _t() {
        return call_user_func_array(array(BxDolInstallLang::getInstance(), '_t'), func_get_args());
    }
}

/** @} */

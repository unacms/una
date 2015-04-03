<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

define('BX_DOL_LANGUAGE_DEFAULT', 'en');

define('BX_DOL_LANGUAGE_CATEGORY_SYSTEM', 1);
define('BX_DOL_LANGUAGE_CATEGORY_CUSTOM', 2);

class BxDolLanguages extends BxDol implements iBxDolSingleton
{
    protected $oDb;
    protected $sCurrentLanguage;

    function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->oDb = BxDolLanguagesQuery::getInstance();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__])) {
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolLanguages();
            $GLOBALS['bxDolClasses'][__CLASS__]->init();
        }

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    function getCurrentLangName($bSetCookie = true)
    {
        $sLang = '';

        if(!$sLang && !empty($_GET['lang']))
            $sLang = $this->tryToGetLang($_GET['lang'], $bSetCookie);

        if(!$sLang && !empty($_POST['lang']))
            $sLang = $this->tryToGetLang($_POST['lang'], $bSetCookie);

        if(!$sLang && !empty($_COOKIE['lang']))
            $sLang = $this->tryToGetLang($_COOKIE['lang']);

        if(!$sLang && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            $sLang = $this->tryToGetLang($_SERVER['HTTP_ACCEPT_LANGUAGE']);

        if(!$sLang)
            $sLang = $this->tryToGetLang(getParam('lang_default'));

        if(!$sLang)
            $sLang = $this->tryToGetLang(BX_DOL_LANGUAGE_DEFAULT);

        setlocale(LC_TIME, $sLang . '_' . strtoupper($sLang) . '.utf-8', $sLang . '_' . strtoupper($sLang) . '.utf8', $sLang . '.utf-8', $sLang . '.utf8', $sLang);
        return $sLang;
    }

    function getCurrentLangId()
    {
        return $this->getLangId($this->getCurrentLanguage());
    }

    function getLangId($sLang)
    {
        return $this->oDb->getLanguageId($sLang);
    }

    function getLangFlag($sLang = '')
    {
        if (!$sLang)
            $sLang = $this->getCurrentLanguage();
        return $this->oDb->getLanguageFlag($sLang);
    }

    function getDefaultLangName()
    {
        return getParam('lang_default');
    }

    function getLanguages($bIdAsKey = false, $bActiveOnly = false)
    {
        return $this->oDb->getLanguages($bIdAsKey, $bActiveOnly);
    }

    function getLanguageCategory($sName)
    {
        $iId = 0;
        $this->oDb->getCategoriesBy(array('type' => 'id_by_name', 'value' => $sName), $iId);

        return (int)$iId;
    }

    /**
     * It tries to match current language with provided languages array, if nothing is found $sFallbackLanguage is returned.
     * @param $aLangs - array of languages, example: array('ru' => 1, 'kg' => 1);
     * @param $sFallbackLanguage - language to return of nothis is found
     * @return language code string
     */
    function detectLanguageFromArray($aLangs, $sFallbackLanguage = 'en')
    {
        return isset($aLangs[$GLOBALS['sCurrentLanguage']]) ? $GLOBALS['sCurrentLanguage'] : $sFallbackLanguage;
    }

    /**
     * Get current language.
     */
    function getCurrentLanguage()
    {
        return $GLOBALS['sCurrentLanguage'];
    }

    function _t()
    {
        global $LANG;

        $key = func_get_arg(0);
        if(isset($LANG[$key])) {
            $str = $LANG[$key];

            if(($iNumArgs = func_num_args()) > 1)
                for($i = 1; $i < $iNumArgs; $i++)
                    $str = str_replace('{' . ($i - 1) . '}', func_get_arg($i), $str);

            return $str;
        } else
            return $key;
    }

    function _t_err()
    {
        return MsgBox(call_user_func_array(array($this, '_t'), func_get_args()));
    }

    function _t_action()
    {
        return MsgBox(call_user_func_array(array($this, '_t'), func_get_args()));
    }

    function _t_ext($key, $args)
    {
        global $LANG;

        if(isset($LANG[$key])) {
            $str = $LANG[$key];

            if(!is_array($args))
                return str_replace('{0}', $args, $str);

            foreach ($args as $key => $val)
                $str = str_replace('{'.$key.'}', $val, $str);

            return $str;
        } else
            return $key;
    }

    function _t_format_size ($iSize)
    {
        $a = array (
            1024 => '_sys_format_size_b',
            1024*1024 => '_sys_format_size_kb',
            1024*1024*1024 => '_sys_format_size_mb',
            1024*1024*1024*1024 => '_sys_format_size_gb',
            1024*1024*1024*1024*1024 => '_sys_format_size_tb',
        );
        foreach($a as $i => $sKey)
            if($iSize < $i)
                return $this->_t($sKey, round($iSize / ($i / 1024), 1));
        return $this->_t('_sys_format_size_b', 0);
    }

    function _t_format_currency ($fPrice)
    {
        return $this->_t('_sys_currency', getParam('currency_sign'), sprintf("%.2f", (float)$fPrice));
    }

    function _t_format_extensions ($mixedExtensions)
    {
        if (!is_array($mixedExtensions))
            $a = explode(',', $mixedExtensions);
        else
            $a = $mixedExtensions;
        if (!$a)
            return '';
        return '.' . implode(', .', $a);
    }

    protected function init()
    {
        /**
         * Trying to initialize default language.
         */
        $GLOBALS['sCurrentLanguage'] = $GLOBALS['bxDolClasses'][__CLASS__]->getCurrentLangName(false);
        if($GLOBALS['sCurrentLanguage'] != '') {
            $sPath = BX_DIRECTORY_PATH_CACHE . 'lang-' . $GLOBALS['sCurrentLanguage'] . '.php';
            if(!file_exists($sPath))
                BxDolStudioLanguagesUtils::getInstance()->compileLanguage();

            require($sPath);
        }

        $GLOBALS['bxDolClasses'][__CLASS__]->getCurrentLangName(true);
        if(isset($_GET['lang'])) {
            if(BxDolPermalinks::getInstance()->redirectIfNecessary(array('lang')))
                exit;
        }
    }

    protected function tryToGetLang($sLangs, $bSetCookie = false)
    {
        $sLangs = trim($sLangs);
        if(!$sLangs)
            return '';

        $sLangs = preg_replace( '/[^a-zA-Z0-9,;-]/m', '', $sLangs ); // we do not need 'q=0.3'. we are using live queue :)
        $sLangs = strtolower($sLangs);

        if(!$sLangs)
            return '';

        $aLangs = explode(',', $sLangs); // ru,en-us;q=0.7,en;q=0.3 => array( 'ru' , 'en-us;q=0.7' , 'en;q=0.3' );
        foreach($aLangs as $sLang) {
            if(!$sLang)
                continue;

            list($sLang) = explode(';', $sLang, 2); // en-us;q=0.7 => en-us
            if(!$sLang)
                continue;

            // check with country
            if($this->checkLangExists($sLang)) {
                if( $bSetCookie && (!isset($_COOKIE['lang']) || $_COOKIE['lang'] != $sLang) && (!isset($GLOBALS['glLangSet']) || $GLOBALS['glLangSet'] != $sLang)) {
                    $this->setLangCookie( $sLang );
                    $GLOBALS['glLangSet'] = $sLang;
                }
                return $sLang;
            }

            //drop country
            if(strpos($sLang, '-') === false)
                continue;

            list($sLang, $sCntr) = explode('-', $sLang, 2); // en-us => en
            if(!$sLang or !$sCntr)
                continue; //no lang or nothing changed

            //check again. without country
            if($this->checkLangExists($sLang)) {
                if($bSetCookie)
                    $this->setLangCookie($sLang);
                return $sLang;
            }
        }
        return '';
    }
    protected function checkLangExists( $sLang )
    {
        if(!preg_match('/^[A-Za-z0-9_]+$/', $sLang))
            return false;

        $iLangId = $this->oDb->getLanguageId($sLang, false);
        if(!$iLangId)
            return false;

        if(file_exists( BX_DIRECTORY_PATH_CACHE . "lang-{$sLang}.php"))
            return true;

        if(BxDolStudioLanguagesUtils::getInstance()->compileLanguage($iLangId))
            return true;

        return false;
    }

    protected function setLangCookie( $sLang )
    {
        $sLang = bx_process_input($sLang);

        if(isLogged()) {
            $iLangId = $this->oDb->getLanguageId($sLang, false);
            if(!$iLangId)
                $iLangId = 0;

            $iAccountId = getLoggedId();
            $oAccountQuery = BxDolAccountQuery::getInstance();
            $oAccountQuery->updateLanguage($iAccountId, $iLangId);
        }

        setcookie( 'lang', '',     time() - 60*60*24,    '/' );
        setcookie( 'lang', $sLang, time() + 60*60*24*365, '/' );
    }
}

if (!function_exists('_t')) {
    function _t()
    {
        return call_user_func_array(array(BxDolLanguages::getInstance(), '_t'), func_get_args());
    }
}

function _t_err()
{
    return call_user_func_array(array(BxDolLanguages::getInstance(), '_t_err'), func_get_args());
}

function _t_action()
{
    return call_user_func_array(array(BxDolLanguages::getInstance(), '_t_action'), func_get_args());
}

function _t_ext($key, $args)
{
    return BxDolLanguages::getInstance()->_t_ext($key, $args);
}

function _t_format_size($iSize)
{
    return BxDolLanguages::getInstance()->_t_format_size($iSize);
}

function _t_format_currency($fPrice)
{
    return BxDolLanguages::getInstance()->_t_format_currency($fPrice);
}

function _t_format_extensions($mixedExtensions)
{
    return BxDolLanguages::getInstance()->_t_format_extensions($mixedExtensions);
}

function bx_lang_name()
{
    return BxDolLanguages::getInstance()->getCurrentLanguage();
}

/** @} */

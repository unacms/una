<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Nexus Nexus - Mobile Apps and Desktop apps connector
 * @ingroup     UnaModules
 *
 * @{
 */

class BxNexusModule extends BxDolModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    /**
     * Get injection code required for Nexus apps
     * @return string
     */
    public function serviceInjectionHeadBegin()
    {
        $s = '';
        if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'UNAMobileApp/Desktop')) {
            $s .= "<script>if (typeof module === 'object') {window.module = module; module = undefined;}</script>";
        }
        return $s;
    }
    
    /**
     * Get injection code required for Nexus apps
     * @return string with Styles and JS code
     */
    public function serviceInjectionHead()
    {
        if (false === strpos($_SERVER['HTTP_USER_AGENT'], 'UNAMobileApp')) 
            return '';

        $this->redirects();

        $oMenu = BxDolMenu::getObjectInstance('sys_account_notifications'); // sys_toolbar_member
        $a = $oMenu->getMenuItems();
        $iBubbles = 0;
        $aBubbles = array();
        foreach ($a as $r) {
            if (!$r['bx_if:addon']['condition'])
                continue;
            $aBubbles[$r['name']] = $r['bx_if:addon']['content']['addon'];
            $iBubbles += $r['bx_if:addon']['content']['addon'];
        }

        $s = $this->_oTemplate->parseHtmlByName('styles.html', array());
        if ($sCustomStyles = getParam('bx_nexus_option_styles'))
            $s .= "<style>\n" . $sCustomStyles . "\n</style>";

        $s .= $this->_oTemplate->parseHtmlByName('js.html', array(
            'msg' => json_encode(array(
                'loggedin' => isLogged(),                
                'bubbles_num' => $iBubbles ? $iBubbles : '',
                'bubbles' => $aBubbles,
                'push_tags' => array('user' => bx_get_logged_profile_id())
            )),
            'txt_pull_to_refresh' => bx_js_string(_t('_bx_nexus_pull_to_refresh')),
            'txt_release_to_refresh' => bx_js_string(_t('_bx_nexus_release_to_refresh')),
            'txt_refreshing' => bx_js_string(_t('_bx_nexus_refreshing')),
        ));

        //  TODO: enable it back when it will work correctly on Android
        // $this->_oTemplate->addJs('pulltorefresh.min.js');

        if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'Desktop')) {
            $s .= "<script>if (window.module) module = window.module;</script>";
        }

        return $s;
    }

    protected function redirects()
    {
        $bLoggedIn = isLogged() ? 1 : 0;
        $i = bx_get('i');

        // only several pages are available for guest, for other pages user is redirected to login page        
        $aI = array('forgot-password' => 1, 'create-account' => 1, 'terms' => 1, 'privacy' => 1, 'contact' => 1, 'about' => 1, 'home' => 1);
        if (!$bLoggedIn && !preg_match('/searchKeyword.php$/', $_SERVER['PHP_SELF']) && !preg_match('/member.php$/', $_SERVER['PHP_SELF']) && !isset($aI[$i])) {
            header("Location: " . BX_DOL_URL_ROOT);
            exit;
        }

        if ($bLoggedIn) {
            if (false !== strpos($_SERVER['HTTP_USER_AGENT'], 'Desktop')) {
                // desktop: for logged in members show messenger only
                $aI = array('messenger' => 1);
                if (!preg_match('/member.php$/', $_SERVER['PHP_SELF']) && !isset($aI[$i])) {
                    header("Location: " . BX_DOL_URL_ROOT . 'page.php?i=messenger');
                    exit;
                }
            }
            else {        
                // mobile: for logged in members some pages aren't available as well
                $aI = array('forgot-password' => 1, 'create-account' => 1, 'login' => 1);
                if (isset($aI[$i])) {
                    header("Location: " . BX_DOL_URL_ROOT);
                    exit;
                }
            }
        }
    }
}

/** @} */

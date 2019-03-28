<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services for mobile apps.
 */
class BxBaseServiceMobileApps extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get injection code required for mobile apps
     * @return string with Styles and JS code
     */
    public function serviceInjection()
    {
        if (false === strpos($_SERVER['HTTP_USER_AGENT'], 'UNAMobileApp')) 
            return '';

        $this->mobileRedirects();

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

        $s  = BxDolTemplate::getInstance()->parseHtmlByName('mobile_apps_styles.html', array());
        $s .= BxDolTemplate::getInstance()->parseHtmlByName('mobile_apps_js.html', array(
            'msg' => json_encode(array(
                'loggedin' => isLogged(),                
                'bubbles_num' => $iBubbles ? $iBubbles : '',
                'bubbles' => $aBubbles,
                'push_tags' => array('user' => bx_get_logged_profile_id())
            ))
        ));
        return $s;
    }

    protected function mobileRedirects()
    {
        $bLoggedIn = isLogged() ? 1 : 0;
        $i = bx_get('i');

        // only several pages are available for guest, for other pages user is redirected to login page        
        $aI = array('forgot-password' => 1, 'create-account' => 1, 'terms' => 1, 'privacy' => 1, 'contact' => 1, 'about' => 1, 'home' => 1);
        if (!$bLoggedIn && !preg_match('/searchKeyword.php$/', $_SERVER['PHP_SELF']) && !preg_match('/member.php$/', $_SERVER['PHP_SELF']) && !isset($aI[$i])) {
            header("Location: " . BX_DOL_URL_ROOT);
            exit;
        }
        
        // for logged in members some pages aren't available as well
        $aI = array('forgot-password' => 1, 'create-account' => 1, 'login' => 1);
        if ($bLoggedIn && isset($aI[$i])) {
            header("Location: " . BX_DOL_URL_ROOT);
            exit;
        }
    }
}

/** @} */

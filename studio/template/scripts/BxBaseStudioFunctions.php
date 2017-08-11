<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioFunctions extends BxBaseFunctions implements iBxDolSingleton
{
    function __construct($oTemplate = false)
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct($oTemplate ? $oTemplate : BxDolStudioTemplate::getInstance());
    }

    public static function getInstance()
    {
        if (!isset($GLOBALS['bxDolClasses']['BxBaseStudioFunctions']))
            $GLOBALS['bxDolClasses']['BxBaseStudioFunctions'] = new BxTemplStudioFunctions();

        return $GLOBALS['bxDolClasses']['BxBaseStudioFunctions'];
    }

    function getLoginForm()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $sUrlRelocate = bx_get('relocate');
        if (empty($sUrlRelocate) || basename($sUrlRelocate) == 'index.php')
            $sUrlRelocate = '';

        $sHtml = $oTemplate->parseHtmlByName('login_form.html', array (
            'role' => BX_DOL_ROLE_ADMIN,
            'csrf_token' => BxDolForm::genCsrfToken(true),
            'relocate_url' => bx_html_attribute($sUrlRelocate),
            'action_url' => BX_DOL_URL_ROOT . 'member.php',
            'forgot_password_url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=forgot-password'),
        ));
        $sHtml = $oTemplate->parseHtmlByName('login.html', array (
            'form' => $this->transBox('bx-std-login-form-box', $sHtml, true),
        ));

        $oTemplate->setPageNameIndex(BX_PAGE_CLEAR);
        $oTemplate->setPageParams(array(
           'css_name' => array('forms.css', 'login.css'),
           'js_name' => array('jquery-ui/jquery.ui.position.min.js', 'jquery.form.min.js', 'jquery.dolPopup.js', 'login.js'),
           'header' => _t('_adm_page_cpt_login'),
        ));
        $oTemplate->setPageContent ('page_main_code', $sHtml);
        $oTemplate->getPageCode();
    }
}

/** @} */

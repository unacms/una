<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Artificer Artificer template
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import ('BxBaseModTemplateModule');

class BxArtificerModule extends BxBaseModTemplateModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceIncludeCssJs()
    {
        if(BxDolTemplate::getInstance()->getCode() != $this->_oConfig->getUri())
            return '';

        return $this->_oTemplate->getIncludeCssJs();
    }

    public function serviceGetBlockSplash()
    {
        if($this->_oTemplate->getCode() != $this->_oConfig->getUri())
            return '';

        $oPermalink = BxDolPermalinks::getInstance();

        $sJoinForm = $sLoginForm = '';
        if(!isLogged()) {
            $sJoinForm = BxDolService::call('system', 'create_account_form', array(), 'TemplServiceAccount');
            $sLoginForm = BxDolService::call('system', 'login_form', array(), 'TemplServiceLogin');
        }

        $this->_oTemplate->addJs(array('lottie.min.js'));
        return $this->_oTemplate->parseHtmlByName('splash.html', array(
            'join_link' => BX_DOL_URL_ROOT . $oPermalink->permalink('page.php?i=create-account'),
            'join_form' => $sJoinForm,
            'join_form_in_box' => !empty($sJoinForm) ? DesignBoxContent(_t('_sys_txt_splash_join'), $sJoinForm, BX_DB_PADDING_DEF) : '',
            'login_link' => BX_DOL_URL_ROOT . $oPermalink->permalink('page.php?i=login'),
            'login_form' => $sLoginForm,
            'login_form_in_box' => !empty($sLoginForm) ? DesignBoxContent(_t('_sys_txt_splash_login'), $sLoginForm, BX_DB_PADDING_DEF) : ''
        ));
    }
}

/** @} */

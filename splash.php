<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxDolLanguages');
bx_import('BxDolPermalinks');
bx_import('BxDolStudioTemplate');
bx_import('BxTemplStudioMenuTop');
bx_import('BxTemplFormView');

class BxDolSplashMenuTop extends BxTemplStudioMenuTop implements iBxDolSingleton
{
    function __construct()
    {
        parent::__construct();

        $oTemplate = BxDolStudioTemplate::getInstance();

        $this->aVisible[BX_DOL_STUDIO_MT_LEFT] = true;
        $this->aVisible[BX_DOL_STUDIO_MT_RIGHT] = true;

        $this->aItems[BX_DOL_STUDIO_MT_LEFT] = $oTemplate->parseHtmlByName('splash_logo.html', array());
        $this->aItems[BX_DOL_STUDIO_MT_RIGHT] = array(
            'site' => array(
                'name' => 'profile',
                'icon' => 'user',
                'link' => BxDolPermalinks::getInstance()->permalink('page.php?i=create-account'),
                'title' => ''
            )
        );
    }

    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses']['BxDolSplashMenuTop']))
            $GLOBALS['bxDolClasses']['BxDolSplashMenuTop'] = new BxDolSplashMenuTop();

        return $GLOBALS['bxDolClasses']['BxDolSplashMenuTop'];
    }
}

class BxDolSplashForm extends BxTemplFormView
{
    function __construct($oTemplate = false)
    {
        $aInfo = array(
            'form_attrs' => array(
                'id' => 'sys-splash-domain',
            ),
            'params' => array(
                'db' => array(
                    'submit_name' => 'domain_submit'
                )
            ),
            'inputs' => array (
                'domain' => array(
                    'type' => 'domain',
                    'name' => 'domain',
                    'value' => '',
                    'value_postfix' => _t('_sys_splash_inp_postfix'),
                    'value_button' => _t('_sys_splash_btn_start'),
                    'attrs' => array(
                        'placeholder' => _t('_sys_splash_inp_placeholder'),
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'checker' => array (
                        'func' => 'preg',
                        'params' => array('/^[a-z0-9-]{3,}$/'),
                        'error' => _t('_sys_splash_err_domain_incorrect'),
                    ),
                ),
            )
        );
        parent::__construct($aInfo, $oTemplate);
    }

    function isSubmittedAndNotValid ()
    {
        return $this->isSubmitted() && !$this->isValid();
    }

    function getNotValid()
    {
        $aResult = array();
        if(!$this->isSubmitted() || $this->isSubmittedAndValid())
            return $aResult;

        foreach ($this->aInputs as $sKey => $aInput)
            $aResult[$sKey] = $aInput['error'];

        return $aResult;
    }

    function genCustomRowDomain($aInput)
    {
        $sClass = 'bx-form-element-wrapper-' . $aInput['name'] . ' ';
        if(isset($aInput['tr_attrs']['class']) && !empty($aInput['tr_attrs']['class']))
            $aInput['tr_attrs']['class'] .= ' ' . $sClass;
        else
            $aInput['tr_attrs']['class'] = $sClass;

        return $this->genRowStandard($aInput);
    }

    function genCustomInputDomain($aInput)
    {
        $sClass = 'bx-form-input-' . $aInput['name'];
        $sClassesText = $sClassesButton = '';
        if(isset($aInput['attrs']['class']) && !empty($aInput['attrs']['class'])) {
            $sClassesText = $aInput['attrs']['class'] . ' ' . $sClass;
            $sClassesButton = $aInput['attrs']['class'] . ' ' . $sClass . '-btn';
        } else  {
            $sClassesText = $sClass;
            $sClassesButton = $sClass . '-btn';
        }

        //--- Text input field
        $aText = $aInput;
        $aText['type'] = 'text';
        $aText['attrs']['class'] = $sClassesText;
        $sInput = $this->genInputStandard($aText);

        //--- Text value
        if(isset($aInput['value_postfix']))
            $sInput .= '<div class="' . $sClass . '-txt">' . $aInput['value_postfix'] . '</div>';

        //--- Button field
        $aButton = $aInput;
        $aButton['type'] = 'submit';
        $aButton['name'] .= '_submit';
        $aButton['value'] = isset($aButton['value_button']) ? $aButton['value_button'] : '';
        $aButton['attrs']['class'] = $sClassesButton;
        $sInput .= $this->genInputButton($aButton);

        return $sInput;
    }

    function _echoResultJson($a, $isAutoWrapForFormFileSubmit = false)
    {
        header('Content-type: text/html; charset=utf-8');

        $s = json_encode($a);
        if($isAutoWrapForFormFileSubmit && !empty($_FILES))
            $s = '<textarea>' . $s . '</textarea>';

        echo $s;
    }
}

function getPageMainCode()
{
    $oTemplate = BxDolStudioTemplate::getInstance();
    $oTemplate->addJs(array('jquery.form.min.js'));
    $oTemplate->addCss(array('splash.css'));

    $oForm = new BxDolSplashForm($oTemplate);
    $oForm->initChecker();

    if($oForm->isSubmittedAndNotValid()) {
        $oForm->_echoResultJson(array('err' => $oForm->getNotValid()), true);
        exit;
    }

    if($oForm->isSubmittedAndValid()) {
        $sDomainKey = 'domain';
        $sDomainValue = $oForm->getCleanValue($sDomainKey);

        if(!BxDolRequest::serviceExists('bx_sites', 'is_used')) {
            $oForm->_echoResultJson(array('msg' => _t('_sys_splash_err_service_not_available')), true);
            exit;
        }

        if(BxDolService::call('bx_sites', 'is_used', array($sDomainValue))) {
            $oForm->_echoResultJson(array('err' => array($sDomainKey => _t('_sys_splash_err_domain_is_used'))), true);
            exit;
        }

        bx_import('BxDolSession');
        BxDolSession::getInstance()->setValue('bx_sites_domain', $sDomainValue);

        bx_import('BxDolPermalinks');
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=create-account');
        $oForm->_echoResultJson(array('eval' => 'window.open(\'' . $sUrl . '\', \'_self\');'), true);
        exit;
    }

    return $oTemplate->parseHtmlByName('splash.html', array(
        'form_id' => $oForm->aFormAttrs['id'],
        'form' => $oForm->getCode()
    ));
}

check_logged();

$oTemplate = BxDolStudioTemplate::getInstance();
$oTemplate->setPageNameIndex(BX_PAGE_DEFAULT);
$oTemplate->setPageContent ('menu_top', BxDolSplashMenuTop::getInstance()->getCode());
$oTemplate->setPageContent ('page_main_code', getPageMainCode());
$oTemplate->getPageCode();

/** @} */

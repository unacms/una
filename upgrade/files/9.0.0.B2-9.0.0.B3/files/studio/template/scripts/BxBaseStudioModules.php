<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioModules extends BxDolStudioModules
{
    function __construct()
    {
        parent::__construct();
    }
    function getCss()
    {
        return array('modules.css');
    }
    function getJs()
    {
        return array('jquery.anim.js', 'page.js', 'modules.js');
    }
    function getJsCode()
    {
        return BxDolStudioTemplate::getInstance()->parseHtmlByName($this->sTemplPrefix . '_js.html', array(
            'js_object' => $this->sJsObject
        ));
    }
    protected function getPopupConfirm($iWidgetId, &$aModule)
    {
        return BxDolStudioTemplate::getInstance()->parseHtmlByName($this->sTemplPrefix . '_confirm.html', array(
            'content' => _t('_adm_' . $this->sLangPrefix . '_cnf_uninstall', $aModule['title']),
            'click' => $this->sJsObject . ".uninstall(" . $iWidgetId . ", '" . $aModule['name'] . "', 1)"
        ));
    }
    protected function getPopupResult($sMessage)
    {
        return BxDolStudioTemplate::getInstance()->parseHtmlByName($this->sTemplPrefix . '_action_result.html', array(
            'content' => $sMessage)
        );
    }
}

/** @} */

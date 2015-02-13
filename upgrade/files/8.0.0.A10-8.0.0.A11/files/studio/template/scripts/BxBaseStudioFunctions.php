<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
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

    function getDesignBox($sTitle, $sContent, $aOptions = array())
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $sId = isset($aOptions['id']) && $aOptions['id'] != '' ? bx_html_attribute($aOptions['id']) : '';
        $iIndex = isset($aOptions['db']) && !empty($aOptions['db']) ? (int)$aOptions['db'] : BX_DB_DEF;

        $bNote = isset($aOptions['note']) && $aOptions['note'] != '';
        return $oTemplate->parseHtmlByName('designbox_' . (int)$iIndex . '.html', array(
            'id' => $sId != '' ? 'id="bx-db-container-' . $sId . '"' : '',
            'title' => bx_process_output($sTitle),
            'bx_if:note' => array(
                'condition' => $bNote,
                'content' => array(
                    'note' => $bNote ? bx_process_output($aOptions['note']) : ''
                )
            ),
            'caption_item' => isset($aOptions['caption_item']) && $aOptions['caption_item'] != '' ? $this->getDesignBoxMenu('cpt-' . $sId, $aOptions['caption_item']) : '',
            'content' => $sContent,
            'bottom_item' => isset($aOptions['bottom_item']) && $aOptions['bottom_item'] != '' ? $this->getDesignBoxMenu('cpt-' . $sId, $aOptions['bottom_item']) : '',
        ));
    }

    function getDesignBoxMenu($sId, $mixedItems, $iIndex = 1)
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        if(is_array($mixedItems)) {
            $mixedButtons = array();
            foreach($mixedItems as $sId => $aAction) {
                $sClass = isset($aAction['class']) ? ' class="' . bx_html_attribute($aAction['class']) . '"' : '';

                $mixedButtons[] = array(
                    'id' => $sId,
                    'title' => bx_process_output(_t($aAction['title'])),
                    'class' => $sClass,
                    'icon' => isset($aAction['icon']) ? '<img' . $sClass . ' src="' . bx_html_attribute($aAction['icon']) . '" />' : '',
                    'href' => isset($aAction['href']) ? ' href="' . bx_html_attribute($aAction['href']) . '"' : '',
                    'target' => isset($aAction['target'])  ? ' target="' . bx_html_attribute($aAction['target']) . '"' : '',
                    'on_click' => isset($aAction['onclick']) ? ' onclick="' . bx_html_attribute($aAction['onclick']) . '"' : '',
                    'bx_if:hide_active' => array(
                        'condition' => !isset($aAction['active']) || $aAction['active'] != 1,
                        'content' => array()
                    ),
                    'bx_if:hide_inactive' => array(
                        'condition' => isset($aAction['active']) && $aAction['active'] == 1,
                        'content' => array()
                    )
                );
            }
        } else
            $mixedButtons = $mixedItems;

        return $oTemplate->parseHtmlByName('designbox_menu_' . $iIndex . '.html', array('id' => $sId, 'bx_repeat:actions' => $mixedButtons));
    }

    function getLoginForm()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $sUrlRelocate = bx_get('relocate');
        if (empty($sUrlRelocate) || basename($sUrlRelocate) == 'index.php')
            $sUrlRelocate = '';

        $oTemplate->addJsTranslation(array('_adm_txt_login_username', '_adm_txt_login_password'));
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

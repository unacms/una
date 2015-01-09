<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

bx_import('BxTemplPaginate');
bx_import('BxDolStudioDesigner');
bx_import('BxTemplStudioFormView');

class BxBaseStudioDesigner extends BxDolStudioDesigner
{
    protected $sLogoFormId = 'adm-dsg-logo-form';
    protected $sLogoIframeId = 'adm-dsg-logo-iframe';
    protected $sIconFormId = 'adm-dsg-icon-form';
    protected $sIconIframeId = 'adm-dsg-icon-iframe';

    function __construct($sPage = '')
    {
        parent::__construct($sPage);
    }
    function getPageCss()
    {
        return array_merge(parent::getPageCss(), array('forms.css', 'designer.css'));
    }
    function getPageJs()
    {
        return array_merge(parent::getPageJs(), array('settings.js', 'designer.js'));
    }
    function getPageJsObject()
    {
        return 'oBxDolStudioDesigner';
    }
    function getPageMenu($aMenu = array(), $aMarkers = array())
    {
        $sJsObject = $this->getPageJsObject();

        $aMenu = array();
        $aMenuItems = array(
            BX_DOL_STUDIO_DSG_TYPE_GENERAL => array('icon' => 'globe'),
            BX_DOL_STUDIO_DSG_TYPE_LOGO => array('icon' => 'pencil'),
            BX_DOL_STUDIO_DSG_TYPE_ICON => array('icon' => 'picture-o'),
            BX_DOL_STUDIO_DSG_TYPE_SETTINGS => array('icon' => 'cogs'),
        );
        foreach($aMenuItems as $sMenuItem => $aItem)
            $aMenu[] = array(
                'name' => $sMenuItem,
                'icon' => $aItem['icon'],
                'link' => BX_DOL_URL_STUDIO . 'designer.php?page=' . $sMenuItem,
                'title' => _t('_adm_lmi_cpt_' . $sMenuItem),
                'selected' => $sMenuItem == $this->sPage
            );

        return parent::getPageMenu($aMenu);
    }
    function getPageCode($bHidden = false)
    {
        $sMethod = 'get' . ucfirst($this->sPage);
        if(!method_exists($this, $sMethod))
            return '';

        return $this->$sMethod();
    }

    protected function getGeneral()
    {
        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $sResult = '';

        $sTemplate = getParam('template');
        $aTemplates = get_templates_array(true, false);

        $aTmplVarsTemplates = array ();
        foreach($aTemplates as $sUri => $aTemplate) {
        	$sIcon = $this->getModuleIcon($aTemplate, 'store');
	        $bIcon = strpos($sIcon, '.') === false;

            $aTmplVarsTemplates[] = array(
                'uri' => $sUri,
                'title' => htmlspecialchars_adv($aTemplate['title']),
                'version' => htmlspecialchars_adv($aTemplate['version']),
                'vendor' => htmlspecialchars_adv($aTemplate['vendor']),
            	'bx_if:icon' => array (
	                'condition' => $bIcon,
	                'content' => array('icon' => $sIcon),
	            ),
                'bx_if:image' => array (
	                'condition' => !$bIcon,
	                'content' => array('icon_url' => $sIcon),
	            ),
                'bx_if:default' => array (
                    'condition' => $sUri == $sTemplate,
                    'content' => array (),
                ),
                'bx_if:make_default' => array (
                    'condition' => $sUri != $sTemplate,
                    'content' => array(
                        'js_object' => $sJsObject,
                        'uri' => $sUri
                    ),
                )
            );
        }

        $sContent  = $sResult ? MsgBox($sResult, 10) : '';
        $sContent .= $oTemplate->parseHtmlByName('templates.html', array(
            'bx_repeat:templates' => $aTmplVarsTemplates,
        ));

        $aTmplVars = array(
            'js_object' => $this->getPageJsObject(),
            'bx_repeat:blocks' => $sContent,
        );

        return $oTemplate->parseHtmlByName('designer.html', $aTmplVars);
    }

    protected function getLogo()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aForm = array(
            'form_attrs' => array(
                'id' => $this->sLogoFormId,
                'name' => $this->sLogoFormId,
                'action' => BX_DOL_URL_STUDIO . 'designer.php',
                'method' => 'post',
                'enctype' => 'multipart/form-data',
                'target' => $this->sLogoIframeId
            ),
            'params' => array(
                'db' => array(
                    'table' => '',
                    'key' => '',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'save'
                ),
            ),
            'inputs' => array(
                'page' => array(
                    'type' => 'hidden',
                    'name' => 'page',
                    'value' => $this->sPage
                ),
                'image' => array(
                    'type' => 'image_uploader',
                    'name' => 'image',
                    'caption' => _t('_adm_dsg_txt_upload_image'),
                    'caption_preview' => _t('_adm_dsg_txt_upload_image_preview'),
                    'ajax_action_delete' => $this->getPageJsObject() . '.deleteLogo()',
                    'storage_object' => 'sys_images_custom',
                    'transcoder_object' => 'sys_custom_images',
                    'transcoder_image_width' => getParam('sys_site_logo_width'),
                    'transcoder_image_height' => getParam('sys_site_logo_height'),
                    'value' => (int)getParam('sys_site_logo'),
                ),
                'width' => array(
                    'type' => 'text',
                    'name' => 'width',
                    'caption' => _t('_adm_stg_cpt_option_sys_site_logo_width'),
                    'value' => getParam('sys_site_logo_width'),
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'height' => array(
                    'type' => 'text',
                    'name' => 'height',
                    'caption' => _t('_adm_stg_cpt_option_sys_site_logo_height'),
                    'value' => getParam('sys_site_logo_height'),
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'alt' => array(
                    'type' => 'text',
                    'name' => 'alt',
                    'caption' => _t('_adm_dsg_txt_alt_text'),
                    'info' => _t('_adm_dsg_dsc_alt_text'),
                    'value' => getParam('sys_site_logo_alt'),
                    'checker' => array(
                        'func' => '',
                        'params' => array(),
                        'error' => ''
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'save' => array(
                    'type' => 'submit',
                    'name' => 'save',
                    'value' => _t('_adm_btn_designer_submit'),
                )
            )
        );

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            echo $this->submitLogo($oForm);
            exit;
        }

        $aTmplVars = array(
            'js_object' => $this->getPageJsObject(),
            'bx_repeat:blocks' => array(
                array(
                    'caption' => '',
                    'panel_top' => '',
                    'items' => $oTemplate->parseHtmlByName('dsr_logo.html', array('logo_iframe_id' => $this->sLogoIframeId, 'form' => $oForm->getCode())),
                    'panel_bottom' => ''
                )
            )
        );

        return $oTemplate->parseHtmlByName('designer.html', $aTmplVars);
    }

    protected function getIcon()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $sPreview = "";
        $aTmplVars = array('bx_repeat:images' => array());

        if(($iId = (int)getParam('sys_site_icon')) != 0) {
            $aTranscoders = array(
                BX_DOL_TRANSCODER_OBJ_ICON_APPLE => '_adm_dsg_txt_icon_apple',
                BX_DOL_TRANSCODER_OBJ_ICON_FACEBOOK => '_adm_dsg_txt_icon_facebook',
                BX_DOL_TRANSCODER_OBJ_ICON_FAVICON => '_adm_dsg_txt_icon_favicon'
            );

            bx_import('BxDolTranscoderImage');
            foreach($aTranscoders as $sTranscoder => $sTitle) {
                $oTranscoder = BxDolTranscoderImage::getObjectInstance($sTranscoder);

                $sImageUrl = $oTranscoder->getFileUrl($iId);
                if($sImageUrl === false) {
                    setParam('sys_site_icon', 0);
                    break;
                }

                $aTmplVars['bx_repeat:images'][] = array(
                    'caption' => _t($sTitle),
                    'url' => $sImageUrl
                );
            }

            $sPreview = $oTemplate->parseHtmlByName('dsr_icon_preview.html', $aTmplVars);
        }

        $aForm = array(
            'form_attrs' => array(
                'id' => $this->sIconFormId,
                'name' => $this->sIconFormId,
                'action' => BX_DOL_URL_STUDIO . 'designer.php',
                'method' => 'post',
                'enctype' => 'multipart/form-data',
                'target' => $this->sIconIframeId
            ),
            'params' => array(
                'db' => array(
                    'table' => '',
                    'key' => '',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'save'
                ),
            ),
            'inputs' => array(
                'page' => array(
                    'type' => 'hidden',
                    'name' => 'page',
                    'value' => $this->sPage
                ),
                'preview' => array(
                    'type' => 'custom',
                    'name' => 'preview',
                    'content' => $sPreview
                ),
                'image' => array(
                    'type' => 'file',
                    'name' => 'image',
                    'caption' => _t('_adm_dsg_txt_upload_icon')
                ),
                'save' => array(
                    'type' => 'submit',
                    'name' => 'save',
                    'value' => _t('_adm_btn_designer_submit'),
                )
            )
        );

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            echo $this->submitIcon($oForm);
            exit;
        }

        $aTmplVars = array(
            'js_object' => $this->getPageJsObject(),
            'bx_repeat:blocks' => array(
                array(
                    'caption' => '',
                    'panel_top' => '',
                    'items' => $oTemplate->parseHtmlByName('dsr_icon.html', array('icon_iframe_id' => $this->sIconIframeId, 'form' => $oForm->getCode())),
                    'panel_bottom' => ''
                )
            )
        );

        return $oTemplate->parseHtmlByName('designer.html', $aTmplVars);
    }

    protected function getSettings()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        bx_import('BxTemplStudioSettings');
        $oPage = new BxTemplStudioSettings(BX_DOL_STUDIO_STG_TYPE_SYSTEM, BX_DOL_STUDIO_STG_CATEGORY_TEMPLATES);

        $aTmplVars = array(
            'js_object' => $this->getPageJsObject(),
            'bx_repeat:blocks' => $oPage->getPageCode(),
        );

        return $oTemplate->parseHtmlByName('designer.html', $aTmplVars);
    }
}

/** @} */

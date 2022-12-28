<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioDesigner extends BxDolStudioDesigner
{
    protected $oDbSettings;

    protected $sLogoFormId = 'adm-dsg-logo-form';
    protected $sLogoIframeId = 'adm-dsg-logo-iframe';

    protected $sIconFormId = 'adm-dsg-icon-form';
    protected $sIconIframeId = 'adm-dsg-icon-iframe';

    protected $sCoverFormId = 'adm-dsg-cover-form';
    protected $sCoverIframeId = 'adm-dsg-cover-iframe';
    protected $sCoverStorage = BX_DOL_STORAGE_OBJ_IMAGES;
    protected $sCoverTranscoder = 'sys_cover_preview';

    protected $sSplashFormId = 'adm-dsg-splash-form';
    protected $sSplashIframeId = 'adm-dsg-splash-iframe';
    protected $sSplashEditorId = 'adm-dsg-splash-editor';

    protected $sInjectionsFormId = 'adm-dsg-injections-form';
    protected $sInjectionsIframeId = 'adm-dsg-injections-iframe';
    protected $sInjectionsEditorId = 'adm-dsg-injections-editor';

    public function __construct($sPage = '')
    {
        parent::__construct($sPage);

        $this->aPageCss = array_merge($this->aPageCss, ['forms.css', 'designer.css']);
        $this->aPageJs = array_merge($this->aPageJs, ['designer.js']);
        $this->sPageJsClass = 'BxDolStudioDesigner';
        $this->sPageJsObject = 'oBxDolStudioDesigner';

        $this->oDbSettings = new BxDolStudioSettingsQuery();
    }

    public function getPageJsCode($aOptions = array(), $bWrap = true)
    {
    	$aOptions = array_merge([
            'sActionUrl' => $this->sManageUrl,
            'sParamPrefix' => $this->sParamPrefix
    	], $aOptions);

    	return parent::getPageJsCode($aOptions, $bWrap);
    }

    public function getPageMenu($aMenu = array(), $aMarkers = array())
    {
        $sJsObject = $this->getPageJsObject();

        $aMenu = array();
        $aMenuItems = array(
            BX_DOL_STUDIO_DSG_TYPE_GENERAL => array('icon' => 'globe'),
            BX_DOL_STUDIO_DSG_TYPE_LOGO => array('icon' => 'pencil-alt'),
            BX_DOL_STUDIO_DSG_TYPE_ICON => array('icon' => 'far image'),
            BX_DOL_STUDIO_DSG_TYPE_COVER => array('icon' => 'far file-image'),
            BX_DOL_STUDIO_DSG_TYPE_SPLASH => array('icon' => 'far file-image'),
            BX_DOL_STUDIO_DSG_TYPE_INJECTIONS => array('icon' => 'object-group'),
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
	        $bIcon = strpos($sIcon, '.') === false && strcmp(substr($sIcon, 0, 10), 'data:image') != 0;

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

        return $oTemplate->parseHtmlByName('designer.html', array(
            'content' => $sContent,
            'js_content' => $this->getPageJsCode()
        ));
    }

    protected function getLogo()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aForm = array(
            'form_attrs' => [
                'id' => $this->sLogoFormId,
                'name' => $this->sLogoFormId,
                'action' => $this->sManageUrl,
                'method' => 'post',
                'enctype' => 'multipart/form-data',
                'target' => $this->sLogoIframeId
            ],
            'params' => [
                'db' => [
                    'table' => '',
                    'key' => '',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'save'
                ],
            ],
            'inputs' => [
                'page' => [
                    'type' => 'hidden',
                    'name' => 'page',
                    'value' => $this->sPage
                ],
                'alt' => [
                    'type' => 'text',
                    'name' => 'alt',
                    'caption' => _t('_adm_dsg_txt_alt_text'),
                    'info' => _t('_adm_dsg_dsc_alt_text'),
                    'value' => getParam($this->sParamLogoAlt),
                    'checker' => [
                        'func' => '',
                        'params' => [],
                        'error' => ''
                    ],
                    'db' => [
                        'pass' => 'Xss',
                    ],
                ],
                'save' => [
                    'type' => 'submit',
                    'name' => 'save',
                    'value' => _t('_adm_btn_designer_submit'),
                ]
            ]
        );

        $aLogos = $this->aLogos;
        if(empty($this->sParamMark))
            unset($aLogos['mark']);

        $aInputs = [];
        foreach($aLogos as $sLogo => $aLogo)
            $aInputs[$sLogo] = [
                'type' => 'files',
                'name' => $sLogo,
                'storage_object' => $aLogo['storage'],
                'images_transcoder' => $aLogo['transcoder'],
                'uploaders' => ['sys_html5'],
                'multiple' => false,
                'content_id' => $this->getOptionId($this->{$aLogo['param']}),
                'ghost_template' => BxTemplStudioFunctions::getInstance()->getDefaultGhostTemplate($sLogo),
                'caption' => _t('_adm_dsg_txt_upload_' . $sLogo),
            ];

        $aForm['inputs'] = bx_array_insert_after($aInputs, $aForm['inputs'], 'page');

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            echo $this->submitLogo($oForm);
            exit;
        }

        return MsgBox(_t('_adm_dsg_txt_logo_redefinition')) . $oTemplate->parseHtmlByName('designer.html', array(
            'content' => $oTemplate->parseHtmlByName('dsr_logo.html', array('logo_iframe_id' => $this->sLogoIframeId, 'form' => $oForm->getCode())),
            'js_content' => $this->getPageJsCode()
        ));
    }

    protected function getIcon()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();
        $oFunctions = BxTemplStudioFunctions::getInstance();

        $aTmplVarsPreview = ['bx_repeat:images' => []];
        foreach($this->aIcons as $sIcon => $aIcon) {
            $sSetting = 'sys_site_' . $sIcon;
            $iIconValue = (int)getParam($sSetting);

            if(($iOptionId = $this->getOptionId($sSetting)) != 0)
                $this->aIcons[$sIcon]['id'] = $iOptionId;

            if(empty($iIconValue))
                continue;

            $sIconUrl = '';
            $aIconParams = [];
            if(!empty($aIcon['transcoder'])) {
                $oTranscoder = BxDolTranscoderImage::getObjectInstance($aIcon['transcoder']);
                $sIconUrl = $oTranscoder->getFileUrl($iIconValue);
                $aIconParams = $oTranscoder->getFilterParams('Resize');
            }
            else {
                $oStorage = BxDolStorage::getObjectInstance($aIcon['storage']);
                $sIconUrl = $oStorage->getFileUrlById($iIconValue);
            }

            if($sIconUrl === false) {
                setParam($sSetting, 0);
                continue;
            }

            $bIconWidth = !empty($aIconParams['w']);
            $bIconHeight = !empty($aIconParams['h']);

            $aTmplVarsPreview['bx_repeat:images'][] = array(
                'js_object' => $this->getPageJsObject(),
                'id' => $iIconValue,
                'name' => $sIcon,
                'caption' => _t('_adm_dsg_txt_' . $sIcon),
                'url' => $sIconUrl,
                'bx_if:show_width' => array(
                    'condition' => $bIconWidth,
                    'content' => array(
                        'width' => $bIconWidth ? (int)$aIconParams['w'] : 0
                    )
                ),
                'bx_if:show_height' => array(
                    'condition' => $bIconHeight,
                    'content' => array(
                        'height' => $bIconHeight ? (int)$aIconParams['h'] : 0
                    )
                ), 
            );
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
                'icon' => array(
                    'type' => 'file',
                    'name' => 'icon',
                    'caption' => _t('_adm_dsg_txt_upload_icon'),
                    'info' => _t('_adm_dsg_txt_upload_icon_inf'),
                    'attrs' => ['accept' => '.ico']
                ),
                'icon_svg' => array(
                    'type' => 'file',
                    'name' => 'icon_svg',
                    'caption' => _t('_adm_dsg_txt_upload_icon_svg'),
                    'info' => _t('_adm_dsg_txt_upload_icon_svg_inf'),
                    'attrs' => ['accept' => '.svg']
                ),
                'icon_apple' => array(
                    'type' => 'files',
                    'name' => 'icon_apple',
                    'storage_object' => $this->aIcons['icon_apple']['storage'],
                    'images_transcoder' => $this->aIcons['icon_apple']['transcoder'],
                    'uploaders' => ['sys_html5'],
                    'multiple' => false,
                    'content_id' => $this->aIcons['icon_apple']['id'],
                    'ghost_template' => $oFunctions->getDefaultGhostTemplate('icon_apple'),
                    'caption' => _t('_adm_dsg_txt_upload_icon_apple'),
                    'info' => _t('_adm_dsg_txt_upload_icon_apple_inf'),
                ),
                'icon_android' => array(
                    'type' => 'files',
                    'name' => 'icon_android',
                    'storage_object' => $this->aIcons['icon_android']['storage'],
                    'images_transcoder' => $this->aIcons['icon_android']['transcoder'],
                    'uploaders' => ['sys_html5'],
                    'multiple' => false,
                    'content_id' => $this->aIcons['icon_android']['id'],
                    'ghost_template' => $oFunctions->getDefaultGhostTemplate('icon_android'),
                    'caption' => _t('_adm_dsg_txt_upload_icon_android'),
                    'info' => _t('_adm_dsg_txt_upload_icon_android_inf'),
                ),
                'icon_android_splash' => array(
                    'type' => 'files',
                    'name' => 'icon_android_splash',
                    'storage_object' => $this->aIcons['icon_android_splash']['storage'],
                    'images_transcoder' => $this->aIcons['icon_android_splash']['transcoder'],
                    'uploaders' => ['sys_html5'],
                    'multiple' => false,
                    'content_id' => $this->aIcons['icon_android_splash']['id'],
                    'ghost_template' => $oFunctions->getDefaultGhostTemplate('icon_android_splash'),
                    'caption' => _t('_adm_dsg_txt_upload_icon_android_splash'),
                    'info' => _t('_adm_dsg_txt_upload_icon_android_splash_inf'),
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

        return $oTemplate->parseHtmlByName('designer.html', [
            'content' => $oTemplate->parseHtmlByName('dsr_icon.html', [
                'preview' => $oTemplate->parseHtmlByName('dsr_icon_preview.html', $aTmplVarsPreview),
                'icon_iframe_id' => $this->sIconIframeId, 
                'form' => $oForm->getCode()
            ]),
            'js_content' => $this->getPageJsCode()
        ]);
    }

    protected function getCover()
    {
    	$sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $oDbSettings = new BxDolStudioSettingsQuery();

        $aFormInputs = array();
        $aTmplVarsCovers = array();
        foreach($this->aCovers as $sCover => $aCover) {
            $aSetting = array();
            $oDbSettings->getOptions(array('type' => 'by_name', 'value' => $aCover['setting']), $aSetting, false);
            if(empty($aSetting) || !is_array($aSetting))
                continue;

            $aFormInputs[$sCover] = array(
                'type' => 'files',
                    'name' => $sCover,
                    'storage_object' => $this->sCoverStorage,
                    'images_transcoder' => $this->sCoverTranscoder,
                    'uploaders' => array('sys_std_crop_cover'),
                    'multiple' => false,
                    'content_id' => $aSetting['id'],
                    'ghost_template' => BxTemplStudioFunctions::getInstance()->getDefaultGhostTemplate($sCover),
                    'caption' => _t('_adm_dsg_txt_upload_' . $sCover),
                    'db' => array (
                    'pass' => 'Int',
                )
            );

            if(($iImageId = (int)getParam($aCover['setting'])) == 0)
                continue;

            $sImageUrl = BxDolTranscoderImage::getObjectInstance($aCover['transcoder'])->getFileUrl($iImageId);
            if($sImageUrl === false) {
            	setParam($aCover['setting'], 0);
                continue;
            }

            $aTmplVarsCovers[] = array(
                'image_id' => $iImageId,
                'content' => $oTemplate->parseHtmlByName($aCover['template'], array(
                    'js_object' => $sJsObject,
                    'type' => $sCover,
                    'caption' => _t($aCover['title']),
                    'image_id' => $iImageId,
                        'bx_if:show_bg' => array(
                            'condition' => !empty($sImageUrl),
                            'content' => array(
                                'image_url' => $sImageUrl
                            )
                    ),
                ))
            );
        }

        $aForm = array(
            'form_attrs' => array(
                'id' => $this->sCoverFormId,
                'name' => $this->sCoverFormId,
                'action' => BX_DOL_URL_STUDIO . 'designer.php',
                'method' => 'post',
                'enctype' => 'multipart/form-data',
                'target' => $this->sCoverIframeId
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
				'disabled' => array(
                    'type' => 'checkbox',
                    'name' => 'enabled',
                    'caption' => _t('_adm_dsg_txt_cover_disabled'),
                    'value' => 'on',
                    'checked' => getParam('sys_site_cover_disabled') == 'on',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'preview' => array(
                    'type' => 'custom',
                    'name' => 'preview',
                    'content' => $oTemplate->parseHtmlByName('dsr_cover_preview.html', array(
                        'bx_repeat:covers' => $aTmplVarsCovers
                    ))
                ),

                'save' => array(
                    'type' => 'submit',
                    'name' => 'save',
                    'value' => _t('_adm_btn_designer_submit'),
                )
            )
        );

        $aForm['inputs'] = bx_array_insert_after($aFormInputs, $aForm['inputs'], 'preview');

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            echo $this->submitCover($oForm);
            exit;
        }

        $this->aPageCss[] = 'cover.css';
        return $oTemplate->parseHtmlByName('designer.html', array(
            'content' => $oTemplate->parseHtmlByName('dsr_cover.html', array('iframe_id' => $this->sCoverIframeId, 'form' => $oForm->getCode())),
            'js_content' => $this->getPageJsCode()
        ));
    }

    protected function getSplash()
    {
    	$oTemplate = BxDolStudioTemplate::getInstance();

        $aForm = array(
            'form_attrs' => array(
                'id' => $this->sSplashFormId,
                'name' => $this->sSplashFormId,
                'action' => BX_DOL_URL_STUDIO . 'designer.php',
                'method' => 'post',
                'enctype' => 'multipart/form-data',
                'target' => $this->sSplashIframeId
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
                'enabled' => array(
                    'type' => 'checkbox',
                    'name' => 'enabled',
                    'caption' => _t('_adm_dsg_txt_splash_enabled'),
                    'value' => 'on',
                    'checked' => getParam('sys_site_splash_enabled') == 'on',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'code' => array(
                    'type' => 'textarea',
                    'code' => true,
                    'name' => 'code',
                    'caption' => '',
                    'value' => getParam('sys_site_splash_code'),
                ),
                'save' => array(
                    'type' => 'submit',
                    'name' => 'save',
                    'value' => _t('_adm_btn_designer_submit'),
                )
            )
        );

        $oForm = new BxTemplStudioFormView($aForm, $oTemplate);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            echo $this->submitSplash($oForm);
            exit;
        }

        $oTemplate->addJs(array('codemirror/codemirror.min.js'));
        $oTemplate->addCss(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'codemirror/|codemirror.css');
        return $oTemplate->parseHtmlByName('designer.html', array(
            'content' => $oTemplate->parseHtmlByName('dsr_splash.html', array(
                'warning' => MsgBox(_t('_adm_dsg_dsc_splash_warning')),
                'splash_iframe_id' => $this->sSplashIframeId, 
                'form' => $oForm->getCode()
            )),
            'js_content' => $this->getPageJsCode(array(
                'sCodeMirror' => 'textarea[name=code]'
            )),
        ));
    }

    protected function getInjections()
    {
    	$oTemplate = BxDolStudioTemplate::getInstance();

        $aForm = array(
            'form_attrs' => array(
                'id' => $this->sInjectionsFormId,
                'name' => $this->sInjectionsFormId,
                'action' => BX_DOL_URL_STUDIO . 'designer.php',
                'method' => 'post',
                'enctype' => 'multipart/form-data',
                'target' => $this->sInjectionsIframeId
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
                'sys_head' => array(
                    'type' => 'textarea',
                    'code' => true,
                    'name' => 'sys_head',
                    'caption' => _t('_adm_dsg_txt_inj_head'),
                    'info' => _t('_adm_dsg_txt_inj_head_inf'),
                    'value' => $this->oDb->getOne("SELECT `data` FROM `sys_injections` WHERE `name`='sys_head'"),
                ),
                'sys_body' => array(
                    'type' => 'textarea',
                    'code' => true,
                    'name' => 'sys_body',
                    'caption' => _t('_adm_dsg_txt_inj_body'),
                    'info' => _t('_adm_dsg_txt_inj_body_inf'),
                    'value' => $this->oDb->getOne("SELECT `data` FROM `sys_injections` WHERE `name`='sys_body'"),
                ),
                'save' => array(
                'type' => 'submit',
                'name' => 'save',
                'value' => _t('_adm_btn_designer_submit'),
                )
            )
        );

        $oForm = new BxTemplStudioFormView($aForm, $oTemplate);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            echo $this->submitInjections($oForm);
            exit;
        }

        $oTemplate->addJs(array('codemirror/codemirror.min.js'));
        $oTemplate->addCss(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'codemirror/|codemirror.css');

        return $oTemplate->parseHtmlByName('designer.html', array(
            'content' => $oTemplate->parseHtmlByName('dsr_injections.html', array(
                'warning' => '',
                'splash_iframe_id' => $this->sInjectionsIframeId, 
                'form' => $oForm->getCode()
            )),
            'js_content' => $this->getPageJsCode(array(
                'sCodeMirror' => 'textarea[name=sys_head],textarea[name=sys_body]'
            ))
        ));
    }

    protected function getSettings()
    {
        $oOptions = new BxTemplStudioOptions(BX_DOL_STUDIO_STG_TYPE_SYSTEM, BX_DOL_STUDIO_STG_CATEGORY_TEMPLATES);

        $this->aPageCss = array_merge($this->aPageCss, $oOptions->getCss());
        $this->aPageJs = array_merge($this->aPageJs, $oOptions->getJs());
        return BxDolStudioTemplate::getInstance()->parseHtmlByName('designer.html', array(
            'content' => $oOptions->getCode(),
            'js_content' => $this->getPageJsCode()
        ));
    }

    protected function getOptionId($sName)
    {
        $aSetting = [];
        $this->oDbSettings->getOptions(['type' => 'by_name', 'value' => $sName], $aSetting, false);
        if(empty($aSetting) || !is_array($aSetting))
            return 0;

        return (int)$aSetting['id'];
    }
}

/** @} */

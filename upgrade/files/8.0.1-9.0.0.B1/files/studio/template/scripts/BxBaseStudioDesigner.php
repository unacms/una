<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

class BxBaseStudioDesigner extends BxDolStudioDesigner
{
    protected $sLogoFormId = 'adm-dsg-logo-form';
    protected $sLogoIframeId = 'adm-dsg-logo-iframe';
    protected $sIconFormId = 'adm-dsg-icon-form';
    protected $sIconIframeId = 'adm-dsg-icon-iframe';
    protected $sCoverFormId = 'adm-dsg-cover-form';
    protected $sCoverIframeId = 'adm-dsg-cover-iframe';
    protected $sSplashFormId = 'adm-dsg-splash-form';
    protected $sSplashIframeId = 'adm-dsg-splash-iframe';
    protected $sSplashEditorId = 'adm-dsg-splash-editor';

    protected $aPageCss;
    protected $aPageJs;

    function __construct($sPage = '')
    {
        parent::__construct($sPage);

        $this->aPageCss = array('forms.css', 'designer.css');
        $this->aPageJs = array('settings.js', 'designer.js');
    }
    function getPageCss()
    {
        return array_merge(parent::getPageCss(), $this->aPageCss);
    }
    function getPageJs()
    {
        return array_merge(parent::getPageJs(), $this->aPageJs);
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
            BX_DOL_STUDIO_DSG_TYPE_COVER => array('icon' => 'file-image-o'),
            BX_DOL_STUDIO_DSG_TYPE_SPLASH => array('icon' => 'file-image-o'),
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

        return $oTemplate->parseHtmlByName('designer.html', array(
            'js_object' => $this->getPageJsObject(),
        	'action_url' => $this->sManageUrl,
            'content' => $sContent,
        ));
    }

    protected function getLogo()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aForm = array(
            'form_attrs' => array(
                'id' => $this->sLogoFormId,
                'name' => $this->sLogoFormId,
                'action' => $this->sManageUrl,
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
                    'transcoder_image_width' => (int)getParam($this->sParamLogoWidth),
                    'transcoder_image_height' => (int)getParam($this->sParamLogoHeight),
                    'value' => (int)getParam($this->sParamLogo),
                ),
                'width' => array(
                    'type' => 'text',
                    'name' => 'width',
                    'caption' => _t('_adm_stg_cpt_option_sys_site_logo_width'),
                    'value' => (int)getParam($this->sParamLogoWidth),
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'height' => array(
                    'type' => 'text',
                    'name' => 'height',
                    'caption' => _t('_adm_stg_cpt_option_sys_site_logo_height'),
                    'value' => (int)getParam($this->sParamLogoHeight),
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'alt' => array(
                    'type' => 'text',
                    'name' => 'alt',
                    'caption' => _t('_adm_dsg_txt_alt_text'),
                    'info' => _t('_adm_dsg_dsc_alt_text'),
                    'value' => getParam($this->sParamLogoAlt),
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

        return $oTemplate->parseHtmlByName('designer.html', array(
            'js_object' => $this->getPageJsObject(),
        	'action_url' => $this->sManageUrl,
            'content' => $this->getBlockCode(array(
				'items' => $oTemplate->parseHtmlByName('dsr_logo.html', array('logo_iframe_id' => $this->sLogoIframeId, 'form' => $oForm->getCode())),
			))
        ));
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

            foreach($aTranscoders as $sTranscoder => $sTitle) {
                $oTranscoder = BxDolTranscoderImage::getObjectInstance($sTranscoder);

                $sImageUrl = $oTranscoder->getFileUrl($iId);
                if($sImageUrl === false) {
                    setParam('sys_site_icon', 0);
                    break;
                }

                $aFilterParams = $oTranscoder->getFilterParams('Resize');
				$bFilterWidth = !empty($aFilterParams['w']);
				$bFilterHeight = !empty($aFilterParams['h']);

                $aTmplVars['bx_repeat:images'][] = array(
                    'caption' => _t($sTitle),
                    'url' => $sImageUrl,
                	'bx_if:show_width' => array(
                		'condition' => $bFilterWidth,
                		'content' => array(
                			'width' => $bFilterWidth ? (int)$aFilterParams['w'] : 0
                		)
                	),
                	'bx_if:show_height' => array(
                		'condition' => $bFilterHeight,
                		'content' => array(
                			'height' => $bFilterHeight ? (int)$aFilterParams['h'] : 0
                		)
                	), 
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

        return $oTemplate->parseHtmlByName('designer.html', array(
            'js_object' => $this->getPageJsObject(),
        	'action_url' => $this->sManageUrl,
            'content' => $this->getBlockCode(array(
				'items' => $oTemplate->parseHtmlByName('dsr_icon.html', array('icon_iframe_id' => $this->sIconIframeId, 'form' => $oForm->getCode())),
			))
        ));
    }

	protected function getCover()
    {
    	$sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aFormInputs = array();
        $aTmplVarsCovers = array();
        foreach($this->aCovers as $sCover => $aCover) {
        	$aFormInputs[$sCover] = array(
				'type' => 'file',
				'name' => $sCover,
				'caption' => _t('_adm_dsg_txt_upload_' . $sCover)
			);

        	if(($iImageId = (int)getParam($aCover['setting'])) == 0)
        		continue;

			$sImageUrl = BxDolTranscoderImage::getObjectInstance(BX_DOL_TRANSCODER_OBJ_COVER)->getFileUrl($iImageId);
            if($sImageUrl === false) {
            	setParam($aCover['setting'], 0);
                continue;
			}

			$aTmplVarsCovers[] = array(
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
            'js_object' => $this->getPageJsObject(),
        	'action_url' => $this->sManageUrl,
            'content' => $this->getBlockCode(array(
				'items' => $oTemplate->parseHtmlByName('dsr_cover.html', array('iframe_id' => $this->sCoverIframeId, 'form' => $oForm->getCode())),
			))
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

		return $oTemplate->parseHtmlByName('designer_splash.html', array(
			'js_object' => $this->getPageJsObject(),
			'action_url' => $this->sManageUrl,
			'content' => $this->getBlockCode(array(
				'items' => $oTemplate->parseHtmlByName('dsr_splash.html', array(
					'warning' => MsgBox(_t('_adm_dsg_dsc_splash_warning')),
					'splash_iframe_id' => $this->sSplashIframeId, 
					'form' => $oForm->getCode()
				)),
			))
		));
    }

    protected function getSettings()
    {
        $oPage = new BxTemplStudioSettings(BX_DOL_STUDIO_STG_TYPE_SYSTEM, BX_DOL_STUDIO_STG_CATEGORY_TEMPLATES);

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('designer.html', array(
            'js_object' => $this->getPageJsObject(),
        	'action_url' => $this->sManageUrl,
            'content' => $oPage->getPageCode()
        ));
    }
}

/** @} */

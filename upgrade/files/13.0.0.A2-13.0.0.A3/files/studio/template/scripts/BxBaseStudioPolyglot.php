<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioPolyglot extends BxDolStudioPolyglot
{
    protected $sSubpageUrl;
    protected $aMenuItems;
    protected $aGridObjects;

    public function __construct($sPage = '')
    {
        parent::__construct($sPage);

        $this->aPageCss = array_merge($this->aPageCss, ['forms.css', 'paginate.css', 'polyglot.css']);
        $this->aPageJs = array_merge($this->aPageJs, ['polyglot.js']);
        $this->sPageJsClass = 'BxDolStudioPolyglot';
        $this->sPageJsObject = 'oBxDolStudioPolyglot';
        
        $this->sSubpageUrl = BX_DOL_URL_STUDIO . 'polyglot.php?page=';

        $this->aMenuItems = array(
            BX_DOL_STUDIO_PGT_TYPE_SETTINGS => array('icon' => 'cogs'),
            BX_DOL_STUDIO_PGT_TYPE_KEYS => array('icon' => 'key'),
            BX_DOL_STUDIO_PGT_TYPE_ETEMPLATES => array('icon' => 'far envelope'),
            BX_DOL_STUDIO_PGT_TYPE_ETEMPLATES_HF => array('icon' => 'far sticky-note')
        );

        $this->aGridObjects = array(
            'keys' => 'sys_studio_lang_keys',
            'etemplates' => 'sys_studio_lang_etemplates',
        );
    }

    public function getPageJsCode($aOptions = array(), $bWrap = true)
    {
        $aOptions = array_merge($aOptions, array(
            'sActionUrl' => BX_DOL_URL_STUDIO . 'polyglot.php'
        ));

        return parent::getPageJsCode($aOptions, $bWrap);
    }

    public function getPageMenu($aMenu = array(), $aMarkers = array())
    {
        $sJsObject = $this->getPageJsObject();

        $aMenu = array();
        foreach($this->aMenuItems as $sMenuItem => $aItem)
            $aMenu[] = array(
                'name' => $sMenuItem,
                'icon' => $aItem['icon'],
                'link' => $this->sSubpageUrl . $sMenuItem,
                'title' => _t('_adm_lmi_cpt_' . $sMenuItem),
                'selected' => $sMenuItem == $this->sPage
            );

        return parent::getPageMenu($aMenu);
    }

    protected function getSettings()
    {
        $oOptions = new BxTemplStudioOptions(BX_DOL_STUDIO_STG_TYPE_DEFAULT, BX_DOL_STUDIO_STG_CATEGORY_LANGUAGES);

        $this->aPageCss = array_merge($this->aPageCss, $oOptions->getCss());
        $this->aPageJs = array_merge($this->aPageJs, $oOptions->getJs());
        return BxDolStudioTemplate::getInstance()->parseHtmlByName('polyglot.html', array(
            'content' => $oOptions->getCode(),
            'js_content' => ''
        ));
    }

    protected function getKeys()
    {
        return $this->getGrid($this->aGridObjects['keys']);
    }

    protected function getEtemplates()
    {
        return $this->getGrid($this->aGridObjects['etemplates']);
    }

    protected function getEtemplatesHf()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $sFormId = 'adm-dsg-et-hf-form';
        $sIFrameId = 'adm-dsg-et-hf-iframe';

        $aForm = array(
            'form_attrs' => array(
                'id' => $sFormId,
                'name' => $sFormId,
                'action' => BX_DOL_URL_STUDIO . 'polyglot.php',
                'method' => 'post',
                'enctype' => 'multipart/form-data',
                'target' => $sIFrameId
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
                'et_hf_header' => array(
                    'type' => 'textarea',
                    'code' => true,
                    'name' => 'et_hf_header',
                    'caption' => _t('_adm_stg_cpt_option_site_email_html_template_header'),
                    'info' => _t('_adm_pgt_txt_et_hf_inf'),
                    'value' => getParam('site_email_html_template_header'),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'et_hf_footer' => array(
                    'type' => 'textarea',
                    'code' => true,
                    'name' => 'et_hf_footer',
                    'caption' => _t('_adm_stg_cpt_option_site_email_html_template_footer'),
                    'info' => _t('_adm_pgt_txt_et_hf_inf'),
                    'value' => getParam('site_email_html_template_footer'),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'save' => array(
                    'type' => 'submit',
                    'name' => 'save',
                    'value' => _t('_adm_pgt_btn_et_hf_submit'),
                )
            )
        );

        $oForm = new BxTemplStudioFormView($aForm, $oTemplate);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            echo $this->submitEtemplatesHf($oForm);
            exit;
        }

        $oTemplate->addJs(array('codemirror/codemirror.min.js'));
        $oTemplate->addCss(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'codemirror/|codemirror.css');

        return $oTemplate->parseHtmlByName('polyglot.html', array(
            'content' => $oTemplate->parseHtmlByName('pgt_etemplates_hf.html', array(
                'warning' => '',
                'iframe_id' => $sIFrameId, 
                'form' => $oForm->getCode()
            )),
            'js_content' => $this->getPageJsCode(array(
                'sCodeMirror' => 'textarea[name=et_hf_header],textarea[name=et_hf_footer]'
            ))
        ));
    }

    protected function getGrid($sObjectName)
    {
        $oGrid = BxDolGrid::getObjectInstance($sObjectName);
        if(!$oGrid)
            return '';

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('polyglot.html', array(
            'content' => $oGrid->getCode(),
            'js_content' => ''
        ));
    }
}

/** @} */

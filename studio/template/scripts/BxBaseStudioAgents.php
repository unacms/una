<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioAgents extends BxDolStudioAgents
{
    protected $sSubpageUrl;
    protected $aPageJsOptions;
    protected $aMenuItems;
    protected $aGridObjects;

    public function __construct($sPage = '')
    {
        parent::__construct($sPage);

        $this->sSubpageUrl = BX_DOL_URL_STUDIO . 'agents.php?page=';

        $this->aPageJs = array_merge($this->aPageJs, ['agents.js']);
        $this->aPageCss = array_merge($this->aPageCss, ['cmts.css', 'agents.css']);

        $this->sPageJsClass = 'BxDolStudioPageAgents';
        $this->sPageJsObject = 'oBxDolStudioPageAgents';
        $this->aPageJsOptions = [
            'sActionUrl' => BX_DOL_URL_STUDIO . 'agents.php',
            'sPageUrl' => $this->sSubpageUrl
        ];

        $this->aMenuItems = [
            BX_DOL_STUDIO_AGENTS_TYPE_SETTINGS => ['icon' => 'cogs'],
            BX_DOL_STUDIO_AGENTS_TYPE_AUTOMATORS => ['icon' => 'cogs'],
            BX_DOL_STUDIO_AGENTS_TYPE_PROVIDERS => ['icon' => 'cogs'],
        ];

        $this->aGridObjects = [
            BX_DOL_STUDIO_AGENTS_TYPE_AUTOMATORS => 'sys_studio_agents_automators',
            BX_DOL_STUDIO_AGENTS_TYPE_PROVIDERS => 'sys_studio_agents_providers',
        ];
    }

    public function getPageJsCode($aOptions = [], $bWrap = true)
    {
        return parent::getPageJsCode(array_merge($aOptions, $this->aPageJsOptions), $bWrap);
    }

    public function getPageMenu($aMenu = [], $aMarkers = [])
    {
        $sJsObject = $this->getPageJsObject();

        $aMenu = [];
        foreach($this->aMenuItems as $sMenuItem => $aItem)
            $aMenu[] = [
                'name' => $sMenuItem,
                'icon' => $aItem['icon'],
                'link' => $this->sSubpageUrl . $sMenuItem,
                'title' => _t('_adm_lmi_cpt_' . $sMenuItem),
                'selected' => $sMenuItem == $this->sPage
            ];

        return parent::getPageMenu($aMenu);
    }

    protected function getSettings()
    {
        $oOptions = new BxTemplStudioOptions(BX_DOL_STUDIO_STG_TYPE_DEFAULT, [
            'agents_general',
        ]);

        $this->aPageCss = array_merge($this->aPageCss, $oOptions->getCss());
        $this->aPageJs = array_merge($this->aPageJs, $oOptions->getJs());

        return $oOptions->getCode();
    }

    protected function getAutomators()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();
        
        $this->aPageJsOptions['sPageUrl'] .= 'automators';

        if(($iId = bx_get('id')) !== false) {
            $sCmts = 'sys_agents_automators';

            $this->aPageJsOptions = array_merge($this->aPageJsOptions, [
                'sPageUrl' => $this->sSubpageUrl . 'automators&id=' . $iId,
                'sActionUrlCmts' => bx_append_url_params(BX_DOL_URL_ROOT . 'cmts.php', [
                    'sys' => $sCmts,
                    'id' => $iId
                ])
            ]);

            $oCmts = BxDolCmts::getObjectInstance($sCmts, (int)$iId, true, $oTemplate);
            return $oCmts->getCommentsBlock();
        }

        return $this->getGrid($this->aGridObjects[BX_DOL_STUDIO_AGENTS_TYPE_AUTOMATORS]);
    }

    protected function getProviders()
    {
        $this->aPageJsOptions = array_merge($this->aPageJsOptions, [
            'sPageUrl' => $this->sSubpageUrl . 'providers',
            'sActionUrlGrid' => bx_append_url_params(BX_DOL_URL_ROOT . 'grid.php', [
                'o' => 'sys_studio_agents_providers'
            ])
        ]);

        return $this->getGrid($this->aGridObjects[BX_DOL_STUDIO_AGENTS_TYPE_PROVIDERS]);
    }

    protected function getGrid($sObjectName)
    {
        $oGrid = BxDolGrid::getObjectInstance($sObjectName);
        if(!$oGrid)
            return '';

        return $oGrid->getCode();
    }
}

/** @} */

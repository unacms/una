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
    protected $aMenuItems;
    protected $aGridObjects;

    public function __construct($sPage = '')
    {
        parent::__construct($sPage);

        $this->aPageCss = array_merge($this->aPageCss, ['cmts.css', 'agents_automators.css']);

        $this->sSubpageUrl = BX_DOL_URL_STUDIO . 'agents.php?page=';

        $this->aMenuItems = [
            BX_DOL_STUDIO_AGENTS_TYPE_SETTINGS => ['icon' => 'cogs'],
            BX_DOL_STUDIO_AGENTS_TYPE_AUTOMATORS => ['icon' => 'cogs'],
        ];

        $this->aGridObjects = [
            BX_DOL_STUDIO_AGENTS_TYPE_AUTOMATORS => 'sys_studio_agents_automators',
        ];
    }

    public function getPageJsCode($aOptions = [], $bWrap = true)
    {
        $aOptions = array_merge($aOptions, [
            'sActionUrl' => BX_DOL_URL_STUDIO . 'agents.php'
        ]);

        return parent::getPageJsCode($aOptions, $bWrap);
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

        if(($iId = bx_get('id')) !== false) {
            $oCmts = BxDolCmts::getObjectInstance('sys_agents_automators', (int)$iId, true, $oTemplate);
            return $oCmts->getCommentsBlock();
        }

        return $this->getGrid($this->aGridObjects[BX_DOL_STUDIO_AGENTS_TYPE_AUTOMATORS]);
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

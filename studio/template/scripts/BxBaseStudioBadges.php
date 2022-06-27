<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioBadges extends BxDolStudioBadges
{
    protected $sSubpageUrl;
    protected $aMenuItems;
    protected $aGridObjects;

    function __construct($sPage = '')
    {
        parent::__construct($sPage);

        $this->sSubpageUrl = BX_DOL_URL_STUDIO . 'badges.php?page=';

        $this->aMenuItems = array(
            BX_DOL_STUDIO_BADGES_TYPE_GENERAL => array('icon' => 'user-tag'),
        );
    }
	
    function getPageCss()
    {
        return array_merge(parent::getPageCss(), array('forms.css', 'paginate.css', 'badges.css'));
    }

    function getPageJsCode($aOptions = array(), $bWrap = true)
    {
        $aOptions = array_merge($aOptions, array(
            'sActionUrl' => BX_DOL_URL_STUDIO . 'badges.php'
        ));

        return parent::getPageJsCode($aOptions, $bWrap);
    }
    
    function getPageMenu($aMenu = array(), $aMarkers = array())
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

    protected function getBadges()
    {
        return $this->getGrid();
    }
    
    protected function getGrid()
    {
        $oGrid = BxDolGrid::getObjectInstance('sys_badges_administration');
        if(!$oGrid)
            return '';

        $oTemplate = BxDolStudioTemplate::getInstance();
        $oTemplate->addJs(array('BxDolGrid.js', 'jquery.form.min.js', 'jquery-ui/jquery-ui.min.js'));
        $oForm = new BxTemplStudioFormView(array());
        $oTemplate->addCss('grid.css');
        $oTemplate->addJsTranslation(array('_sys_grid_search'));

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('badges.html', array(
            'content' => $oGrid->getCode(),
            'js_content' => ''
        ));
    }
}

/** @} */

<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioStorages extends BxDolStudioStorages
{
    protected $aStorages;
    protected $sSubpageUrl;

    function __construct($sPage = '')
    {
        parent::__construct($sPage);

        $this->aStorages = [
            BX_DOL_STUDIO_STRG_TYPE_FILES => ['icon' => 'mi-stg-files.svg'],
            BX_DOL_STUDIO_STRG_TYPE_IMAGES => ['icon' => 'mi-stg-images.svg']
        ];

        $this->sSubpageUrl = BX_DOL_URL_STUDIO . 'storages.php?page=';
    }

    function getPageCss()
    {
        return array_merge(parent::getPageCss(), array());
    }

    function getPageJs()
    {
        return array_merge(parent::getPageJs(), array());
    }

    function getPageJsObject()
    {
        return '';
    }

    function getPageMenu($aMenu = [], $aMarkers = [])
    {
        $aMenu = [];
        foreach($this->aStorages as $sName => $aStorage)
            $aMenu[] = [
                'name' => $sName,
                'icon' => $aStorage['icon'],
                'icon_bg' => true,
                'link' => $this->sSubpageUrl . $sName,
                'title' => _t('_adm_lmi_cpt_' . $sName),
                'selected' => $sName == $this->sPage
            ];

        return parent::getPageMenu($aMenu);
    }

    protected function getFiles()
    {
        return $this->getGrid(BX_DOL_STUDIO_STRG_TYPE_FILES);
    }

    protected function getImages()
    {
        return $this->getGrid(BX_DOL_STUDIO_STRG_TYPE_IMAGES);
    }

    protected function getGrid($sName)
    {
        $oGrid = BxDolGrid::getObjectInstance('sys_studio_strg_' . $sName);
        if(!$oGrid)
            return '';

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('storages.html', array(
            'js_object' => $this->getPageJsObject(),
            'content' => $oGrid->getCode()
        ));
    }
}

/** @} */

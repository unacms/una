<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioSettings extends BxDolStudioSettings
{
    public function __construct($sType = '', $mixedCategory = '')
    {
        parent::__construct($sType, $mixedCategory);
    }

    public function getPageCss()
    {
        return array_merge(parent::getPageCss(), $this->oOptions->getCss());
    }

    public function getPageJs()
    {
        return array_merge(parent::getPageJs(), $this->oOptions->getJs());
    }

    public function getPageJsObject()
    {
        return 'oBxDolStudioSettings';
    }

    public function getPageMenu($aMenu = array(), $aMarkers = array())
    {
        $sType = $this->oOptions->getType();

        $aTypes = $aMenu = array();
        if($this->oDb->getTypes(array('type' => 'all', 'not_in_group' => array(BX_DOL_STUDIO_STG_GROUP_TEMPLATES)), $aTypes) > 0 ) {
            $aTypesGrouped = array();
            foreach($aTypes as $aType)
                $aTypesGrouped[$aType['group']][] = $aType;

            foreach($aTypesGrouped as $sGroup => $aTypes)
                foreach($aTypes as $aType)
                    $aMenu[] = array(
                        'name' => $aType['name'],
                        'icon' => $this->getMenuIcon($sGroup, $aType),
                        'link' => BX_DOL_URL_STUDIO . 'settings.php?page=' . $aType['name'],
                        'title' => $aType['caption'],
                        'selected' => $aType['name'] == $sType
                    );
        }

        return parent::getPageMenu($aMenu);
    }

    public function getPageCode($sPage = '', $bWrap = true)
    {
        $sResult = parent::getPageCode($sPage, $bWrap);
        if($sResult === false)
            return false;

        return $sResult . $this->getBlockCode(array(
            'content' => $this->oOptions->getCode()
        ));
    }

    protected function getMenuIcon($sGroup, &$aType)
    {
        if(empty($aType['icon']) || ($sUrl = BxDolStudioTemplate::getInstance()->getIconUrl($aType['icon'])) == "")
            switch($sGroup) {
                case BX_DOL_STUDIO_STG_GROUP_MODULES:
                    $aType['icon'] = BxDolStudioUtils::getIconDefault(BX_DOL_MODULE_TYPE_MODULE);
                    break;

                case BX_DOL_STUDIO_STG_GROUP_LANGUAGES:
                    $aType['icon'] = BxDolStudioUtils::getIconDefault(BX_DOL_MODULE_TYPE_LANGUAGE);
                    break;

                case BX_DOL_STUDIO_STG_GROUP_TEMPLATES:
                    $aType['icon'] = BxDolStudioUtils::getIconDefault(BX_DOL_MODULE_TYPE_TEMPLATE);
                    break;
            }

        return $aType['icon'];
    }
}

/** @} */

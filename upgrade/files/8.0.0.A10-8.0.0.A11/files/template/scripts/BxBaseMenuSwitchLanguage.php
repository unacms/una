<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Site main menu representation.
 */
class BxBaseMenuSwitchLanguage extends BxTemplMenu
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function getMenuItems ()
    {
        $this->loadData();

        return parent::getMenuItems();
    }

    protected function loadData()
    {
        $sLanguage = BxDolLanguages::getInstance()->getCurrentLangName();

        $this->setSelected('', $sLanguage);

        $aPage = explode('?', $_SERVER['HTTP_REFERER']);

        $aPageParams = array();
        if(!empty($aPage[1]))
            parse_str($aPage[1], $aPageParams);

        $aLanguages = BxDolLanguagesQuery::getInstance()->getLanguages(false, true);

        $aItems = array();
        foreach( $aLanguages as $sName => $sLang ) {
            $aPageParams['lang'] = $sName;

            $aItems[] = array(
                'id' => $sName,
                'name' => $sName,
                'class' => '',
                'title' => $sLang,
                'target' => '_self',
                'icon' => 'sys_fl_' . $sName . '.gif',
                'link' => bx_html_attribute(bx_append_url_params($aPage[0], $aPageParams)),
                'onclick' => ''
            );
        }

        $this->_aObject['menu_items'] = $aItems;
    }
}

/** @} */

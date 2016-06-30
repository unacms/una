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
class BxBaseMenuSwitchTemplate extends BxTemplMenu
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
        $this->setSelected('', $this->_oTemplate->getCode());

        $aPage = explode('?', $_SERVER['HTTP_REFERER']);

        $aPageParams = array();
        if(!empty($aPage[1]))
            parse_str($aPage[1], $aPageParams);

        $aTemplates = get_templates_array(true, false);

        $aItems = array();
        foreach( $aTemplates as $sName => $aTemplate ) {
            $aPageParams['skin'] = $sName;

            $aItems[] = array(
                'id' => $sName,
                'name' => $sName,
                'class' => '',
                'title' => $this->getItemTitle($sName, $aTemplate),
                'target' => '_self',
                'icon' => '',
                'link' => bx_html_attribute(bx_append_url_params($aPage[0], $aPageParams)),
                'onclick' => ''
            );
        }

        $this->_aObject['menu_items'] = $aItems;
    }

	protected function getItemTitle($sName, $aTemplate)
    {
    	$sTitle = getParam($aTemplate['name'] . '_switcher_title');
    	if(!empty($sTitle))
    		return $sTitle;

    	return $aTemplate['title'];
    }
}

/** @} */

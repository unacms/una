<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Site main menu representation.
 */
class BxBaseMenuSwitchLanguage extends BxTemplMenu
{
    protected $_sType;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        $this->_sType = str_replace('sys_switch_language_', '', $this->_sObject);
    }

    public function getMenuItems ()
    {
        $this->loadData();

        return parent::getMenuItems();
    }

    protected function loadData()
    {
        $aLanguages = BxDolLanguagesQuery::getInstance()->getLanguages(false, true);
        if(empty($aLanguages) || !is_array($aLanguages) || count($aLanguages) < 2)
            return;

        $sLanguage = BxDolLanguages::getInstance()->getCurrentLangName();
        $this->setSelected('', $sLanguage);

        list($sPageLink, $aPageParams) = $this->{'getBaseUrl' . bx_gen_method_name($this->_sType)}();

        $oPermalink = BxDolPermalinks::getInstance();
		$sMethod = 'getItemTitle' . bx_gen_method_name($this->_sType);

        $aItems = array();
        foreach( $aLanguages as $sName => $sLang ) {
            $aPageParams['lang'] = $sName;

            $aItems[] = array(
                'id' => $sName,
                'name' => $sName,
                'class' => '',
                'title' => $this->$sMethod($sName, $sLang),
                'target' => '_self',
                'icon' => '',
                'link' => bx_html_attribute(bx_append_url_params($oPermalink->permalink($sPageLink), $aPageParams)),
                'onclick' => ''
            );
        }

        $this->_aObject['menu_items'] = $aItems;
    }

    protected function getBaseUrlPopup()
    {
        return $this->getBaseUrl($_SERVER['HTTP_REFERER']);
    }

    protected function getBaseUrlInline()
    {
        $aBaseLink = parse_url(BX_DOL_URL_ROOT);
        $sPageLink = (!empty($aBaseLink['scheme']) ? $aBaseLink['scheme'] : 'http') . '://' . $aBaseLink['host'] . $_SERVER['REQUEST_URI'];

        list($sPageLink, $aPageParams) = $this->getBaseUrl($sPageLink);

        $aPageParamsAdd = array();
		if(!empty($_SERVER['QUERY_STRING'])) {
			parse_str($_SERVER['QUERY_STRING'], $aPageParamsAdd);
			if(!empty($aPageParamsAdd) && is_array($aPageParamsAdd))
				$aPageParams = array_merge($aPageParams, $aPageParamsAdd);
		}

		return array($sPageLink, $aPageParams);
    }

    protected function getBaseUrl($sPageLink)
    {
        $sPageLink = BxDolPermalinks::getInstance()->unpermalink($sPageLink);

        $sPageParams = '';
        if(strpos($sPageLink, '?') !== false)
        	list($sPageLink, $sPageParams) = explode('?', $sPageLink);

        $aPageParams = array();
        if(!empty($sPageParams))
        	parse_str($sPageParams, $aPageParams);

        return array($sPageLink, $aPageParams);
    }

    protected function getItemTitlePopup($sName, $sTitle)
    {
    	return genFlag($sName) . ' ' . $sTitle;
    }

    protected function getItemTitleInline($sName, $sTitle)
    {
    	return genFlag($sName);
    }
}

/** @} */

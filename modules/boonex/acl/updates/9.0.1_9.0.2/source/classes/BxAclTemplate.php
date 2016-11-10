<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    PaidLevels Paid Levels
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAclTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

	public function displayEmptyOwner()
    {
    	return MsgBox(_t('_bx_acl_msg_empty_owner'));
    }

    public function displayMembershipActions($iProfileId)
    {
		$this->addCss(array('pm_actions.css'));
    	return $this->parseHtmlByName('pm_actions.html', array(
    		'url_upgrade' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($this->_oConfig->CNF['URL_VIEW'])
    	));
    }

	public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
        $aParams = array_merge(array(
            'aHtmlIds' => $this->_oConfig->getHtmlIds()
        ), $aParams);

        return parent::getJsCode($sType, $aParams, $bWrap);
    }
}

/** @} */

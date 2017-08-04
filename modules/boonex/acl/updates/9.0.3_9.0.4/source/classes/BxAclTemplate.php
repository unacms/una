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

    public function displayLevelIcon($mixedValue)
    {
        $bTmplVarsImage = $bTmplVarsIcon = false;
        $aTmplVarsImage = $aTmplVarsIcon = array();
        if(is_numeric($mixedValue) && (int)$mixedValue != 0) {
            $oStorage = BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES);

            $bTmplVarsImage = true;
            $aTmplVarsImage = array(
                'src' => $oStorage->getFileUrlById((int)$mixedValue),
            );
        }
        else {
            $bTmplVarsIcon = true;
            $aTmplVarsIcon = array(
                'name' => $mixedValue
            );
        }

    	return $this->parseHtmlByName('level_icon.html', array(
    	    'bx_if:show_image' => array(
    	        'condition' => $bTmplVarsImage,
    	        'content' => $aTmplVarsImage
    	    ),
    	    'bx_if:show_icon' => array(
    	        'condition' => $bTmplVarsIcon,
    	        'content' => $aTmplVarsIcon
    	    )
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

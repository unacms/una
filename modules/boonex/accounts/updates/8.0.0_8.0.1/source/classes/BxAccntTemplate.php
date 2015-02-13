<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Accounts Accounts
 * @ingroup     DolphinModules
 *
 * @{
 */

class BxAccntTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
    	$this->MODULE = 'bx_accounts';
        parent::__construct($oConfig, $oDb);
    }

    public function getProfilesByAccount($aContentInfo, $iMaxVisible = 2)
    {
        $oProfilesQuery = BxDolProfileQuery::getInstance();

        $aProfiles = $oProfilesQuery->getProfilesByAccount($aContentInfo['id']);
        $iProfiles = count($aProfiles);

        $aTmplVars = array (
        	'class_cnt' => '',
            'bx_repeat:profiles' => array(),
            'bx_if:profiles_more' => array(
                'condition' => $iProfiles > $iMaxVisible,
                'content' => array(
        			'html_id' => $this->_oConfig->getHtmlIds('profile_more_popup') . $aContentInfo['id'],
                    'more' => _t('_bx_accnt_txt_more', $iProfiles - $iMaxVisible),
        			'more_attr' => bx_html_attribute(_t('_bx_accnt_txt_see_more')),
                    'popup' => '',
                ),
            ),
        );

        $aTmplVarsPopup = array (
        	'class_cnt' => ' bx-def-padding',
            'bx_repeat:profiles' => array(),
            'bx_if:profiles_more' => array(
                'condition' => false,
                'content' => array(),
            ),
        );

        $i = 0;
        foreach ($aProfiles as $iProfileId => $aProfile) {
            $oProfile = BxDolProfile::getInstance($iProfileId);
            if(!$oProfile)
				continue;

			$sName = $oProfile->getDisplayName();
            $aTmplVarsProfile = array (
            	'html_id' => $this->_oConfig->getHtmlIds('profile') . $aProfile['id'],
                'id' => $oProfile->id(),
                'url' => $oProfile->getUrl(),
                'name' => $sName,
                'name_attr' =>  bx_html_attribute($sName)
            );

            if($i < $iMaxVisible)
				$aTmplVars['bx_repeat:profiles'][] = $aTmplVarsProfile;
            if($i >= $iMaxVisible)
                $aTmplVarsPopup['bx_repeat:profiles'][] = $aTmplVarsProfile;

            ++$i;
        }

        if($aTmplVarsPopup['bx_repeat:profiles']) {
            $aTmplVars['bx_if:profiles_more']['content']['popup'] = BxTemplFunctions::getInstance()->transBox('', $this->parseHtmlByName('profiles.html', $aTmplVarsPopup));
        }

        return $this->parseHtmlByName('profiles.html', $aTmplVars);
    }
}

/** @} */

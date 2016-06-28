<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Acl representation.
 * @see BxDolAcl
 */
class BxBaseAcl extends BxDolAcl
{
    public function __construct ()
    {
        parent::__construct ();
    }

	public function getProfileMembership ($iProfileId)
    {
    	$aLevel = $this->getMemberMembershipInfo($iProfileId);
    	if(empty($aLevel) || !is_array($aLevel))
    		return '';

		$iUserId = bx_get_logged_profile_id();
		$aLevelInfo = $this->getMembershipInfo($aLevel['id']);

		$aTmplVarsPrivateInfo = array();
		$bTmplVarsPrivateInfo = $iProfileId == $iUserId && !empty($aLevel['date_starts']);
		if($bTmplVarsPrivateInfo)
			$aTmplVarsPrivateInfo = array(
				'date_start' => bx_time_js($aLevel['date_starts']),
				'date_expire' => (int)$aLevel['date_expires'] > 0 ? bx_time_js($aLevel['date_expires']) : _t('_sys_acl_expire_never')
			);

   		$oTemplate = BxDolTemplate::getInstance();
        $sContent = $oTemplate->parseHtmlByName('acl_membership.html', array(
    		'level' => _t($aLevel['name']),
    		'thumbnail' => $oTemplate->getImage($aLevelInfo['icon'], array('class' => 'bx-acl-m-thumbnail bx-def-border')),
    		'bx_if:show_private_info' => array(
    			'condition' => $bTmplVarsPrivateInfo,
    			'content' => $aTmplVarsPrivateInfo
    		)
    	));

        bx_alert('system', 'page_output_block_acl_level', 0, false, array(
        	'block_owner' => $iProfileId,
			'block_code' => &$sContent
		));

        $oTemplate->addCss(array('acl.css'));
    	return $sContent;
    }

    /**
     * Print code for membership status
     * $iProfileId - ID of profile
     * $offer_upgrade - will this code be printed at [c]ontrol [p]anel
     */
    function GetMembershipStatus($iProfileId, $bOfferUpgrade = true)
    {
        $aMembershipInfo = $this->getMemberMembershipInfo($iProfileId);

        $sViewMembershipActions = "<br />(<a onclick=\"javascript:window.open('explanation.php?explain=membership&amp;type=".$aMembershipInfo['ID']."', '', 'width=660, height=500, menubar=no, status=no, resizable=no, scrollbars=yes, toolbar=no, location=no');\" href=\"javascript:void(0);\">"._t("_VIEW_MEMBERSHIP_ACTIONS")."</a>)<br />";

        // Show colored membership name
        $ret = '';
        if ( $aMembershipInfo['ID'] == MEMBERSHIP_ID_STANDARD || $aMembershipInfo['ID'] == MEMBERSHIP_ID_AUTHENTICATED) {
            $ret .= _t( "_MEMBERSHIP_STANDARD" ). $sViewMembershipActions;
            if ( $bOfferUpgrade )
                $ret .= " ". _t( "_MEMBERSHIP_UPGRADE_FROM_STANDARD" );
        } else {
            $ret .= "<font color=\"red\">{$aMembershipInfo['Name']}</font>$sViewMembershipActions";

            $days_left = (int)( ($aMembershipInfo['DateExpires'] - time()) / (24 * 3600) );

            if(!is_null($aMembershipInfo['DateExpires'])) {
                $ret .= ( $days_left > 0 ) ? _t( "_MEMBERSHIP_EXPIRES_IN_DAYS", $days_left ) : _t( "_MEMBERSHIP_EXPIRES_TODAY", date( "H:i", $aMembershipInfo['DateExpires'] ), date( "H:i" ) );
            } else {
                $ret.= _t("_MEMBERSHIP_EXPIRES_NEVER");
            }
        }
        return $ret;
    }
}
/** @} */

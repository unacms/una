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

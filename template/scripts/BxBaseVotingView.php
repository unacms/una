<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolVoting');
bx_import('BxDolTemplate');

/**
 * @see BxDolVoting
 */
class BxBaseVotingView extends BxDolVoting
{
    var $_iSizeStarBigX = 18;
    var $_iSizeStarBigY = 16;
    var $_iSizeStarSmallX = 11;
    var $_iSizeStarSmallY = 15;

    function BxBaseVotingView( $sSystem, $iId, $iInit = 1 )
    {
        BxDolVoting::BxDolVoting( $sSystem, $iId, $iInit );
    }

    function getSmallVoting ($iCanRate = 1, $iVoteRateOverride = false)
    {
        if ($iCanRate != 0) {
            if (!$this->checkAction()) $iCanRate = 0;
        }
        return $this->getVoting($iCanRate, $this->_iSizeStarSmallX, $this->_iSizeStarSmallY, 'small', 0, true, $iVoteRateOverride);
    }

    function getManySmallVoting($iCanRate = 1, $iID = 0, $isShowCount = true, $iVoteRateOverride = false)
    {
        if ($iCanRate != 0) {
            if (!$this->checkAction()) $iCanRate = 0;
        }
        return $this->getVoting($iCanRate, $this->_iSizeStarSmallX, $this->_iSizeStarSmallY, 'small', $iID, $isShowCount, $iVoteRateOverride);
    }

    function getBigVoting ($iCanRate = 1, $iVoteRateOverride = false)
    {
        if ($iCanRate != 0) {
            if (!$this->checkAction()) $iCanRate = 0;
        }
        return $this->getVoting($iCanRate, $this->_iSizeStarBigX, $this->_iSizeStarBigY, 'big', 0, true, $iVoteRateOverride);
    }

    function getJustVotingElement($iCanRate, $iPossibleID = 0, $iVoteRateOverride = false)
    {
        return $this->getManySmallVoting($iCanRate, $iPossibleID, false, $iVoteRateOverride);
    }

    function getVoting($iCanRate, $iSizeX, $iSizeY, $sName, $iPossibleID = 0, $isShowCount = true, $iVoteRateOverride = false) {
        $oSysTemplate = BxDolTemplate::getInstance();

        $sSiteUrl = BX_DOL_URL_ROOT;
        $iMax = $this->getMaxVote();
        $iWidth = $iSizeX*$iMax;
        $sSystemName = $this->getSystemName();
        $iObjId = $iPossibleID ? $iPossibleID : $this->getId();
        $sDivId = $this->getSystemName() . $sName;
        if ($iPossibleID>0) {
            $sDivId .= $iPossibleID;
        }

        $sRet = '<div class="votes_'.$sName.'" id="' . $sDivId . '">';

        if ($iCanRate)
            $sRet .= <<<EOF
<script language="javascript">
    var oVoting{$sDivId} = new BxDolVoting('{$sSiteUrl}', '{$sSystemName}', '{$iObjId}', '{$sDivId}', '{$sDivId}Slider', {$iSizeX}, {$iMax});
</script>
EOF;

        $sRet .= '<div class="votes_gray_'.$sName.'" style="width:'.$iWidth.'px;">';

        if ($iCanRate)
        {
            $sRet .= '<div class="votes_buttons">';
            for ($i=1 ; $i<=$iMax ; ++$i)
            {
                $sRet .= '<a href="javascript:'.$i.';void(0);" onmouseover="oVoting'.$sDivId.'.over('.$i.');" onmouseout="oVoting'.$sDivId.'.out();" onclick="oVoting'.$sDivId.'.vote('.$i.')"><img class="votes_button_' . $sName . '" src="' . $oSysTemplate->getImageUrl('vote_star_null.gif') . '" alt="" /></a>';
            }
            $sRet .= '</div>';
        }
        $iVoteRate = (false === $iVoteRateOverride ? $this->getVoteRate() : $iVoteRateOverride);
        $sRet .= '<div id="'.$sDivId.'Slider" class="votes_active_'.$sName.'" style="width:'.round($iVoteRate*($iMax ? $iWidth/$iMax : 0)).'px;"></div>';
        $sRet .= '</div>';
        if ($isShowCount)
            $sRet .= '<b>'.$this->getVoteCount(). ' ' . _t('_votes') . '</b>';
        $sRet .= '<div class="clear_both"></div>';
        $sRet .= '</div>';

        return $sRet;
    }

    function getExtraJs () {
        BxDolTemplate::getInstance()->addJs('BxDolVoting.js');
    }
}


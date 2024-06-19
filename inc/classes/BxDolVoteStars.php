<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Star based votes for any content
 */
class BxDolVoteStars extends BxTemplVote
{
    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        $this->_oQuery = new BxDolVoteStarsQuery($this);
        $this->_sType = BX_DOL_VOTE_TYPE_STARS;
    }

    /**
     * Interface functions for outer usage
     */
    public function getValue($bVoted)
    {
        $iObjectId = $this->getId();
        $iAuthorId = $this->_getAuthorId();

        $iValue = 0;
        if($bVoted && ($aTrack = $this->_getTrack($iObjectId, $iAuthorId)) && is_array($aTrack))
            $iValue = (int)$aTrack['value'];

        return $iValue;
    }

    /**
     * Internal functions
     */
    protected function _isDuplicate($iObjectId, $iAuthorId, $iAuthorIp, $bVoted)
    {
        return !$this->_oQuery->isPostTimeoutEnded($iObjectId, $iAuthorId, $iAuthorIp);
    }
}

/** @} */

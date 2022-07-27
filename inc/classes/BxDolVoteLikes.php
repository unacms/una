<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Like based votes for any content
 */
class BxDolVoteLikes extends BxTemplVote
{
    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        $this->_oQuery = new BxDolVoteLikesQuery($this);
        $this->_sType = BX_DOL_VOTE_TYPE_LIKES;
    }

    /**
     * Interface functions for outer usage
     */
    public function getValue()
    {
        return (int)$this->_aSystem['min_value'];
    }

    /**
     * Internal functions
     */
    protected function _isDuplicate($iObjectId, $iAuthorId, $iAuthorIp, $bVoted)
    {
        return $bVoted && !$this->isUndo();
    }

    protected function _getIconDo($bVoted)
    {
    	return $bVoted && $this->isUndo() ? 'thumbs-up' : 'thumbs-up';
    }

    protected function _getTitleDo($bVoted)
    {
    	return $bVoted && $this->isUndo() ? '_vote_do_unlike' : '_vote_do_like';
    }
}

/** @} */

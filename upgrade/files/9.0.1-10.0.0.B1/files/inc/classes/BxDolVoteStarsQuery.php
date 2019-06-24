<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolVote
 */
class BxDolVoteStarsQuery extends BxDolVoteQuery
{
    public function __construct(&$oModule)
    {
        parent::__construct($oModule);
    }

    public function getLegend($iObjectId)
    {
    	$sQuery = $this->prepare("SELECT `value` AS `value`, COUNT(`value`) AS `count` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? GROUP BY `value`", $iObjectId);

    	return $this->getAllWithKey($sQuery, 'value');
    }
}

/** @} */

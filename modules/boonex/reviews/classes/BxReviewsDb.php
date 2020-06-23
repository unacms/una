<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Reviews Reviews
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxReviewsDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getVotingOptionLKey($iOptionId) {
        return $this->getOne("SELECT `lkey` FROM `bx_reviews_voting_options` WHERE `id` = :id", [
            'id' => $iOptionId,
        ]);
    }

    public function getVotingOptions() {
        return $this->getAll("SELECT `id`, `lkey` FROM `bx_reviews_voting_options` ORDER BY `order`");
    }
}

/** @} */

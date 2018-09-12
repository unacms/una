<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services related to ACL.
 */
class BxBaseAclServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceGetMemberships($bPurchasableOnly = false, $bActiveOnly = false, $isTranslate = true, $bFilterOutSystemAutomaticLevels = false)
    {
        return BxDolAcl::getInstance()->getMemberships($bPurchasableOnly, $bActiveOnly, $isTranslate, $bFilterOutSystemAutomaticLevels);
    }
}

/** @} */

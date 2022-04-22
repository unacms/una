<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Site main menu representation.
 */
class BxBaseMenuProfileAdd extends BxTemplMenu
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function getCode ()
    {
        $oProfile = BxDolProfile::getInstance();
        if($oProfile && !BxDolAccount::isAllowedCreateMultiple($oProfile->id()))
            return '';

        $oAccount = BxDolAccount::getInstance();
        if($oAccount && $oAccount->isProfilesLimitReached())
            return '';

        return parent::getCode ();
    }
}

/** @} */

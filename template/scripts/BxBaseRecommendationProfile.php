<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Profile recommendation representation.
 * @see BxDolRecommendation
 */
class BxBaseRecommendationProfile extends BxTemplRecommendation
{
    public function __construct ($aOptions, $oTemplate)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sClassWrapper = 'bx-base-pofile-units-wrapper bx-base-puw-gallery';
    }

    public function getCodeItem($iId, $iCount)
    {
        $oProfile = BxDolProfile::getInstance($iId);
        if(!$oProfile)
            return '';

        return $oProfile->{'getUnit' . ($this->_bIsApi ? 'API' : '')}(0, [
            'template' => 'unit_with_cover', 
            'context' => $this->_getContextName()
        ]);
    }
}

/** @} */

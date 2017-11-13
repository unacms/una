<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Videos Videos
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Videos module
 */
class BxVideosModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceEntityVideoBlock ($iContentId = 0)
    {
        $mixedContent = $this->_getContent($iContentId);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

        return $this->_oTemplate->entryVideo($aContentInfo);
    }
}

/** @} */

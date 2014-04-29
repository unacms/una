<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModGeneralSearchResult');

class BxBaseModProfileSearchResult extends BxBaseModGeneralSearchResult 
{
    function __construct($sMode = '', $aParams = array()) 
    {
        parent::__construct($sMode, $aParams);
        $this->sCenterContentUnitSelector = '.bx-base-pofile-unit';
    }
}

/** @} */


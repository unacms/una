<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Organizations Organizations
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Organizations module representation.
 */
class BxOrgsTemplate extends BxBaseModGroupsTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_organizations';

        parent::__construct($oConfig, $oDb);

        $this->_sUnitClass = 'bx-base-pofile-unit';
        $this->_sUnitClassWithCover = 'bx-base-pofile-unit-with-cover';
        $this->_sUnitClassWoInfo = 'bx-base-pofile-unit-wo-info';
    }

    protected function _getUnitClass($aData, $sTemplateName = 'unit.html')
    {
        return BxBaseModProfileTemplate::_getUnitClass($aData, $sTemplateName);
    }
}

/** @} */

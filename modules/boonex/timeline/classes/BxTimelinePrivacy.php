<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTimelinePrivacy extends BxBaseModNotificationsPrivacy
{
    protected $_oModule;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_timeline');
    }

    public function addSpaces($aValues, $iOwnerId, $aParams)
    {
        if(!empty($aParams['display']) && $aParams['display'] != $this->_oModule->_oConfig->getObject('form_display_post_add'))
            return $aValues;

        return parent::addSpaces($aValues, $iOwnerId, $aParams);
    }

    public function getContentByGroupAsSQLPart($mixedGroupId)
    {
        $aResult = parent::getContentByGroupAsSQLPart($mixedGroupId);

        if($this->_oModule->_oDb->isTableAlias()) {
            $sTable = $this->_oModule->_oDb->getTable();
            $sTableAlias = $this->_oModule->_oDb->getTableAlias();
            foreach($aResult as $sKey => $sValue)
                $aResult[$sKey] = str_replace($sTable, $sTableAlias, $sValue);
        }

        return $aResult;
    }
}

/** @} */

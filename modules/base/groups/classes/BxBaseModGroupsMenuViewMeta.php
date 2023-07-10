<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry meta menu
 */
class BxBaseModGroupsMenuViewMeta extends BxBaseModProfileMenuViewMeta
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemMembers($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!$this->_bContentPublic || !$this->_oContentProfile || empty($CNF['OBJECT_CONNECTIONS']))
            return false;

        $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS']);
        if(!$oConnection)
            return false;

        $iContentProfileId = $this->_oContentProfile->id();

        if($this->_bIsApi) {
            $aCounter = $oConnection->getCounterAPI($iContentProfileId, false, ['caption' => $aItem['title']], BX_CONNECTIONS_CONTENT_TYPE_INITIATORS);

            return $this->_getMenuItemAPI($aItem, ['display' => 'button'], [
                'title' => $aCounter['countf'],
            ]);
        }

        $sIcon = BxTemplFunctions::getInstanceWithTemplate($this->_oTemplate)->getIconAsHtml(!empty($aItem['icon']) ? $aItem['icon'] : '');
        return $oConnection->getCounter($iContentProfileId, true, ['caption' => $aItem['title'], 'custom_icon' => $sIcon], BX_CONNECTIONS_CONTENT_TYPE_INITIATORS);
    }
}

/** @} */

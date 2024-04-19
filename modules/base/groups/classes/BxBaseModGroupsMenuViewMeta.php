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
            $aCounter = $oConnection->getCounterAPI($iContentProfileId, true, ['caption' => $aItem['title']], BX_CONNECTIONS_CONTENT_TYPE_INITIATORS);

            $sUrl = $this->_oContentProfile->getUrl();
            if(!empty($CNF['URI_VIEW_MEMBERS']))
                $sUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_MEMBERS'] . '&profile_id=' . $iContentProfileId));

            return $this->_getMenuItemAPI($aItem, ['display' => 'button'], [
                'title' => $aCounter['countf'],
                'link' => bx_api_get_relative_url($sUrl),
                'list' => $oConnection->getConnectedListAPI($iContentProfileId, true, BX_CONNECTIONS_CONTENT_TYPE_INITIATORS)
            ]);
        }

        $sIcon = BxTemplFunctions::getInstanceWithTemplate($this->_oTemplate)->getIconAsHtml(!empty($aItem['icon']) ? $aItem['icon'] : '');
        return $oConnection->getCounter($iContentProfileId, true, ['caption' => $aItem['title'], 'custom_icon' => $sIcon], BX_CONNECTIONS_CONTENT_TYPE_INITIATORS);
    }
}

/** @} */

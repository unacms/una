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
 * 'View group' actions menu.
 */
class BxBaseModGroupsMenuViewActions extends BxBaseModProfileMenuViewActions
{

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
    }
    
    public function setContentId($iContentId)
    {
        parent::setContentId($iContentId);

        if(!$this->_oProfile || !isLogged())
            return;

        $CNF = &$this->_oModule->_oConfig->CNF;
        
        if(isset($CNF['OBJECT_CONNECTIONS']) && ($oConn = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])) !== false) {
            $iProfileId = bx_get_logged_profile_id();
            $iGroupProfileId = $this->_oProfile->id();

            $aTitles = $this->_oModule->getMenuItemTitleByConnection($CNF['OBJECT_CONNECTIONS'], '', $iGroupProfileId, $iProfileId);
            $aMarkers = [
                'title_add_fan' => $aTitles['add'],
                'onclick_add_fan' => "bx_conn_action(this, 'bx_events_fans', 'add', '" . $iGroupProfileId . "')",
                'title_remove_fan' => $aTitles['remove'],
            ];
            if(!$oConn->isConnectedNotMutual($iProfileId, $iGroupProfileId) && $oConn->hasQuestionnaire($iGroupProfileId))
                $aMarkers['onclick_add_fan'] = $this->_oModule->_oConfig->getJsObject('entry') . ".connAction(this, 'bx_events_fans', 'add', '" . $iGroupProfileId . "')";

            if ($this->_oModule->isFan($this->_aContentInfo[$CNF['FIELD_ID']])) {
                $a = $oConn->getConnectedInitiators($iGroupProfileId);
                $aMarkers['recipients'] = implode(',', $a);
            }

            $this->addMarkers($aMarkers);
        }
    }
}

/** @} */

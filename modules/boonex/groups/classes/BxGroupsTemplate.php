<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Groups Groups
 * @ingroup     TridentModules
 *
 * @{
 */

/*
 * Groups module representation.
 */
class BxGroupsTemplate extends BxBaseModProfileTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_groups';
        parent::__construct($oConfig, $oDb);
    }

    function unitVars ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html')
    {
        $CNF = &$this->_oConfig->CNF;

        $aVars = parent::unitVars ($aData, $isCheckPrivateContent, $sTemplateName);

        $oConn = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS']);

        $aVars['cover_url'] = $this->urlCover ($aData, true);
        $aVars['members'] = _t('_bx_groups_txt_N_fans', $oConn ? $oConn->getConnectedInitiatorsCount($aData[$CNF['FIELD_ID']], true) : 0);
        $aVars['bx_if:btn'] = array (
            'condition' => isLogged() && !$oConn->isConnected(bx_get_logged_profile_id(), $aData[$CNF['FIELD_ID']], true),
            'content' => array (
                'id' => $aData[$CNF['FIELD_ID']],
                'title' => $oConn->isConnectedNotMutual(bx_get_logged_profile_id(), $aData[$CNF['FIELD_ID']]) ? _t('_bx_groups_menu_item_title_become_fan_sent') : _t('_bx_groups_menu_item_title_become_fan'),
                'object' => $CNF['OBJECT_CONNECTIONS'],
            ),
        );

        return $aVars;
    }

    function setCover ($aData, $sTemplateName = 'cover.html')
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
    
        if ('c' != $aData['allow_view_to'] && CHECK_ACTION_RESULT_ALLOWED !== $oModule->checkAllowedView($aData)) {
            $CNF = &$this->_oConfig->CNF;
            $aData[$CNF['FIELD_COVER']] = 0;
            $aData[$CNF['FIELD_PICTURE']] = 0;
        }

        parent::setCover ($aData, $sTemplateName);
    }
}

/** @} */

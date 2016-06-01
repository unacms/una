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

        $isPublic = CHECK_ACTION_RESULT_ALLOWED === $this->getModule()->checkAllowedView($aData) || 'c' == $aData[$CNF['FIELD_ALLOW_VIEW_TO']];

        $aVars = parent::unitVars ($aData, $isCheckPrivateContent, $sTemplateName);

        $oConn = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS']);

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aData[$CNF['FIELD_ID']], $this->MODULE);

        if (!$isPublic) {
            $aVars['thumb_url'] = $this->getImageUrl('no-picture-thumb.png');
            $aVars['content_url'] = 'javascript:void(0);';
            $aVars['title'] = _t('_bx_groups_txt_private_group');
        }
        $aVars['cover_url'] = $isPublic ? $this->urlCover ($aData, true) : $this->getImageUrl('cover.jpg');
        $aVars['members'] = $isPublic ? _t('_bx_groups_txt_N_fans', $oConn ? $oConn->getConnectedInitiatorsCount($oGroupProfile->id(), true) : 0) : '&nbsp;';
        $aVars['bx_if:btn'] = array (
            'condition' => isLogged() && !$oConn->isConnected(bx_get_logged_profile_id(), $oGroupProfile->id(), true),
            'content' => array (
                'id' => $oGroupProfile->id(),
                'title' => $oConn->isConnectedNotMutual(bx_get_logged_profile_id(), $oGroupProfile->id()) ? _t('_bx_groups_menu_item_title_become_fan_sent') : _t('_bx_groups_menu_item_title_become_fan'),
                'object' => $CNF['OBJECT_CONNECTIONS'],
            ),
        );

        return $aVars;
    }

    function setCover ($aData, $sTemplateName = 'cover.html')
    {    
        if ('c' != $aData['allow_view_to'] && CHECK_ACTION_RESULT_ALLOWED !== $this->getModule()->checkAllowedView($aData)) {
            $CNF = &$this->_oConfig->CNF;
            $aData[$CNF['FIELD_COVER']] = 0;
            $aData[$CNF['FIELD_PICTURE']] = 0;
        }

        parent::setCover ($aData, $sTemplateName);
    }
}

/** @} */

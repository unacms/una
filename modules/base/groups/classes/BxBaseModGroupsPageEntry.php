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
 * Profile create/edit/delete pages.
 */
class BxBaseModGroupsPageEntry extends BxBaseModProfilePageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_sCoverClass = 'bx-base-group-cover-wrapper ' . $this->_oModule->getName() . '_cover';
    }

    protected function _processPermissionsCheck ()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);
        
        $mixedAllowView = $this->_oModule->checkAllowedView($this->_aContentInfo);
        if (!$oPrivacy->isPartiallyVisible($this->_aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]) && CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $mixedAllowView)) {
            $this->_oTemplate->displayAccessDenied($sMsg);
            exit;
        }
        elseif ($oPrivacy->isPartiallyVisible($this->_aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]) && $CNF['OBJECT_PAGE_VIEW_ENTRY'] == $this->_sObject && CHECK_ACTION_RESULT_ALLOWED !== $mixedAllowView) {
            // replace current page with different set of blocks
            $aObject = BxDolPageQuery::getPageObject($CNF['OBJECT_PAGE_VIEW_ENTRY_CLOSED']);
            $this->_sObject = $aObject['object'];
            $this->_aObject = $aObject;
            $this->_oQuery = new BxDolPageQuery($this->_aObject);
        }

        $this->_oModule->checkAllowedView($this->_aContentInfo, true);
    }
    
    public function getCode ()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sRv = '';
        $sKey = '';
        if(!empty($CNF['TABLE_INVITES']) && ($sKey = bx_get('key')) !== false) {
            $sId = $this->_oModule->getName() . '_popup_invite';

            $mixedInvited = $this->_oModule->isInvited($sKey, $this->_oProfile->id());
            if ($mixedInvited === true) {
                $sRv = $this->_oModule->_oTemplate->parseHtmlByName('popup_invite.html', array(
                    'popup_id' => $sId,
                    'text' => _t($CNF['T']['txt_invitation_popup_text']),
                    'button_accept' => _t($CNF['T']['txt_invitation_popup_accept_button']),
                    'button_decline' => _t($CNF['T']['txt_invitation_popup_decline_button']),
                ));
            }
            else
                $sRv = $mixedInvited;
        }

        $this->_oTemplate->addJs(array('invite_popup.js'));
        return ($sRv != '' ? $this->_oModule->_oTemplate->getJsCode('invite_popup', array(
            'sPopupId' => $sId,
            'sKey' => $sKey,
            'sAcceptUrl' =>  BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $this->_aProfileInfo['content_id']),
            'sDeclineUrl' => BX_DOL_URL_ROOT,
            'iGroupProfileId' => $this->_oProfile->id(),
        )) .  BxTemplFunctions::getInstance()->popupBox($sId, _t($CNF['T']['txt_invitation_popup_title']), $sRv, true) : '') . parent::getCode();
    }
}

/** @} */

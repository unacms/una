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

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_sCoverClass = 'bx-base-group-cover-wrapper ' . $this->_oModule->getName() . '_cover';

        $bLoggedOwner = isset($this->_aContentInfo[$CNF['FIELD_AUTHOR']]) && $this->_aContentInfo[$CNF['FIELD_AUTHOR']] == bx_get_logged_profile_id();
        $bLoggedModerator = $this->_oModule->checkAllowedEditAnyEntry() === CHECK_ACTION_RESULT_ALLOWED;

        $aInformers = array ();
        $oInformer = BxDolInformer::getInstance($this->_oTemplate);
        if($oInformer && ($bLoggedOwner || $bLoggedModerator)) {
            $sStatus = isset($CNF['FIELD_STATUS']) && isset($this->_aContentInfo[$CNF['FIELD_STATUS']]) ? $this->_aContentInfo[$CNF['FIELD_STATUS']] : '';

            //--- Display 'scheduled' informer if an item wasn't published yet.
            if(isset($CNF['FIELD_PUBLISHED'])) {
                if(!empty($CNF['INFORMERS']['scheduled']) && isset($CNF['INFORMERS']['scheduled']['map'][$sStatus])) {
                    $this->addMarkers(array(
                        'date_publish_uf' => bx_time_js((int)$this->_aContentInfo[$CNF['FIELD_PUBLISHED']], BX_FORMAT_DATE, true)
                    ));

                    $aInformer = $CNF['INFORMERS']['scheduled'];
                    $aInformers[] = array ('name' => $aInformer['name'], 'msg' => _t($aInformer['map'][$sStatus]['msg']), 'type' => $aInformer['map'][$sStatus]['type']);
                }
            }

            if($aInformers)
                foreach($aInformers as $aInformer)
                    $oInformer->add($aInformer['name'], $this->_replaceMarkers($aInformer['msg']), $aInformer['type']);
        }
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
    
    protected function _getInvitationCode($aParams = [])
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['TABLE_INVITES']))
            return '';

        $iProfileId = bx_get_logged_profile_id();
        $iGroupProfileId = $this->_oProfile->id();
        if(!$this->_oModule->serviceIsInvited($iGroupProfileId, $iProfileId)) 
            return '';

        $sId = $this->_oModule->getName() . '_popup_invite';
        $sKey = $this->_oModule->serviceGetInvitedKey($iGroupProfileId, $iProfileId);

        $sCode = $this->_oModule->_oTemplate->getJsCode('invite_popup', array(
            'sPopupId' => $sId,
            'sKey' => $sKey,
            'sAcceptUrl' =>  BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $this->_aProfileInfo['content_id']),
            'sDeclineUrl' => BX_DOL_URL_ROOT,
            'iGroupProfileId' => $this->_oProfile->id(),
        ));

        if(!isset($aParams['use_invitation_popup']) || $aParams['use_invitation_popup'] === true)
            $sCode .= BxTemplFunctions::getInstance()->popupBox($sId, _t($CNF['T']['txt_invitation_popup_title']), $this->_oModule->_oTemplate->parseHtmlByName('popup_invite.html', array(
                'popup_id' => $sId,
                'text' => _t($CNF['T']['txt_invitation_popup_text']),
                'button_accept' => _t($CNF['T']['txt_invitation_popup_accept_button']),
                'button_decline' => _t($CNF['T']['txt_invitation_popup_decline_button']),
            )), true);

        $this->_oTemplate->addJs(array('invite_popup.js'));        
        return $sCode;
    }

    public function getCode ()
    {
        return $this->_getInvitationCode() . parent::getCode();
    }
}

/** @} */

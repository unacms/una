<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Invites Invites
 * @ingroup     UnaModules
 *
 * @{
 */

class BxInvTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    public function getCssJs()
    {
        $this->addCss(array(
            'main.css',
        ));

        $this->addJs(array(
            'clipboard.min.js',
            'jquery.form.min.js',
            'jquery.anim.js',
            'main.js'
        ));
    }

    public function getInclude()
    {
        $this->getCssJs();
        return $this->getJsCode('main');
    }

    public function getBlockRequestText($aRequest)
    {
        return $this->parseHtmlByName('request_text.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'text' => bx_process_output(nl2br($aRequest['text']), BX_DATA_TEXT_MULTILINE),
        ));
    }
    
    public function getBlockInviteInfo($aRequest)
    {
        $aVars = array(
            'date_sent_title' => _t('_bx_invites_title_date_invite_sent'),
            'date_seen_title' => _t('_bx_invites_title_date_invite_seen'),
            'bx_repeat:items' => array()
        );
        foreach ($aRequest as $r) {
            $aVars['bx_repeat:items'][] = array (
                 'date_sent' => bx_time_js($r['date'], BX_FORMAT_DATE_TIME, true),
                 'date_seen' => ($r['date_seen'] != '' ? bx_time_js($r['date_seen'], BX_FORMAT_DATE_TIME, true) : ' - ')
            );
        }
        return $this->parseHtmlByName('invite_info.html', $aVars);
    }
    
    public function getBlockInvite($iAccountId, $iProfileId, $bRedirect = false)
    {
        $mInvitesRemain = $this->_oConfig->getCountPerUser();
        if($mInvitesRemain === true)
            $mInvitesRemain = _t('_bx_invites_txt_unlimited');

        $sUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink($this->_oConfig->CNF['URL_INVITE']));

        if($bRedirect) {
            $sRedirectCode = $this->_oConfig->getRedirectCode();

            list($sPageLink, $aPageParams) = bx_get_base_url_inline();
            $sRedirectValue = bx_append_url_params($sPageLink, $aPageParams);

            $oSession = BxDolSession::getInstance();
            if($oSession->isValue($sRedirectCode))
                $oSession->unsetValue($sRedirectCode);
            $oSession->setValue($sRedirectCode, $sRedirectValue);
        }

        $this->getCssJs();
        return $this->parseHtmlByName('block_invite.html', [
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'js_object' => $this->_oConfig->getJsObject('main'),
            'text' => _t('_bx_invites_txt_invite_block_text', $mInvitesRemain),
            'url' => $sUrl,
            'js_code' => $this->getJsCode('main')
        ]);
    }

    public function getBlockRequest()
    {
        $sUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink($this->_oConfig->CNF['URL_REQUEST']));

        $this->getCssJs();
        return $this->parseHtmlByName('block_request.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'text' => _t('_bx_invites_txt_request_block_text'),
            'bx_if:show_button_request' => array(
                'condition' => $this->_oConfig->isRequestInvite(),
                'content' => array(
                    'url' => $sUrl
                )
            )
        ));
    }
    public function getBlockFormRequest()
    {
        if(!$this->_oConfig->isRequestInvite())
            return MsgBox(_t('_bx_invites_err_not_available'));

        $mixedAllowed = $this->getModule()->isAllowedRequest(0);
        if($mixedAllowed !== true)
            return MsgBox($mixedAllowed);

        $CNF = &$this->_oConfig->CNF;

        $sJsObject = $this->_oConfig->getJsObject('main');
        $oPermalink = BxDolPermalinks::getInstance();

        $oForm = BxDolForm::getObjectInstance($this->_oConfig->getObject('form_request'), $this->_oConfig->getObject('form_display_request_send'));
        $oForm->aFormAttrs['action'] = bx_absolute_url($oPermalink->permalink($CNF['URL_REQUEST']));

        $sFormId = $oForm->getId();
        $sEval = $sJsObject . '.onRequestFormSubmit(oData)';

        $oForm->initChecker();
        if(!$oForm->isSubmittedAndValid()) {
            $sForm = $oForm->getCode();
            if(!$oForm->isSubmitted()) {
                $this->getCssJs();
                return $this->parseHtmlByName('block_request_form.html', array(
                    'style_prefix' => $this->_oConfig->getPrefix('style'),
                    'js_object' => $sJsObject,
                    'js_code' => $this->getJsCode('main'),
                    'form' => $sForm,
                    'form_id' => $sFormId,
                ));
            }

            if(!$oForm->isValid())
                return array('content' => $sForm, 'content_id' => $sFormId, 'eval' => $sEval);
        }
        
        $sEmail = $oForm->getCleanValue('email');
        $oAccountQuery = BxDolAccountQuery::getInstance();
        $iAccount = $oAccountQuery->getIdByEmail($sEmail);
        if ($iAccount > 0)
            return array('content' => MsgBox(_t('_bx_invites_err_already_registed', bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=forgot-password')))), 'content_id' => $sFormId, 'eval' => $sEval);
        $iCountByEmail = $this->_oDb->getRequests(array('type' => 'count_by_email', 'value' => $sEmail));
        if ($iCountByEmail > 0)
            return array('content' => MsgBox(_t('_bx_invites_err_already_send')), 'content_id' => $sFormId, 'eval' => $sEval);
       
        $sIp = getVisitorIP();
        $iId = (int)$oForm->insert(array('nip' => ip2long($sIp),'date' => time()));
        if(!$iId)
            return array('content' => MsgBox(_t('_bx_invites_err_cannot_perform')), 'content_id' => $sFormId, 'eval' => $sEval);
        $this->getModule()->onRequest($iId);
        $sRequestsEmail = $this->_oConfig->getRequestsEmail();
        if(!empty($sRequestsEmail)) {
            $sManageUrl = bx_absolute_url($oPermalink->permalink($CNF['URL_REQUESTS']));

            $aMessage = BxDolEmailTemplates::getInstance()->parseTemplate('bx_invites_request_form_message', array(
                'sender_name' => bx_process_output($oForm->getCleanValue('name')),
                'sender_email' => bx_process_output($oForm->getCleanValue('email')),
                'sender_ip' => $sIp,
                'manage_url' => $sManageUrl
            ));
            
            $aRequestsEmails = explode(',', $sRequestsEmail);
            foreach ($aRequestsEmails as $s) {
                sendMail(trim($s), $aMessage['Subject'], $aMessage['Body'], 0, array(), BX_EMAIL_SYSTEM);
            }
        }

        return array('content' => MsgBox(_t('_bx_invites_msg_request_sent')), 'content_id' => $sFormId, 'eval' => $sEval);
    }

    public function getLinkPopup($sLink)
    {
        $sId = $this->_oConfig->getHtmlIds('link_popup');
        $sTitle = _t('_bx_invites_txt_link_popup_title');
        $sContent = $this->parseHtmlByName('popup_link.html', array(
            'style_prefix' => $this->_oConfig->getPrefix('style'),
            'html_id_link' => $this->_oConfig->getHtmlIds('link_input'),
            'link' => $sLink
        ));

        return BxTemplFunctions::getInstance()->popupBox($sId, $sTitle, $sContent, true);
    }
    
    public function getJsCode($sType, $aParams = array(), $mixedWrap = true)
    {
        $sGrid = 'requests';
        if(!empty($aParams['grid'])) {
            $sGrid = $aParams['grid'];
            unset($aParams['grid']);
        }

        $aParams = array_merge(array(
            'aHtmlIds' => $this->_oConfig->getHtmlIds(),
            'sObjNameGrid' => $this->_oConfig->getObject('grid_' . $sGrid),
        ), $aParams);

        return parent::getJsCode($sType, $aParams);
    }

    public function getMenuForManageBlocks($sType)
    {
        $CNF = &$this->_oConfig->CNF;
        
        $sMenu = '';
		$oPermalink = BxDolPermalinks::getInstance();

		$aMenuItems = array();
		$aMenuItems[] = array('id' => 'invites-requests', 'name' => 'invites-requests', 'class' => '', 'link' => $oPermalink->permalink($CNF['URL_REQUESTS']), 'target' => '_self', 'title' => _t('_bx_invites_menu_item_title_manage_requests'), 'active' => 1);
		$aMenuItems[] = array('id' => 'invites-invites', 'name' => 'invites-invites', 'class' => '', 'link' => $oPermalink->permalink($CNF['URL_INVITES']), 'target' => '_self', 'title' => _t('_bx_invites_menu_item_title_manage_invites'), 'active' => 1);

		if(count($aMenuItems) > 1) {
	        $oMenu = new BxTemplMenu(array(
	            'template' => 'menu_vertical.html', 
	            'menu_items' => $aMenuItems
	        ), $this);
	        $oMenu->setSelected($this->getModule()->_aModule['name'], 'invites-' . $sType);
	        $sMenu = $oMenu->getCode();
		}
        return $sMenu;
    }
    
    public function getProfilesByAccount($iAccountId, $iMaxVisible = 2)
    {
        $aProfiles = BxDolAccount::getInstance($iAccountId)->getProfiles();
        $iProfiles = count($aProfiles);

        $aTmplVars = array (
            'class_cnt' => '',
            'bx_repeat:profiles' => array(),
            'bx_if:profiles_more' => array(
                'condition' => $iProfiles > $iMaxVisible,
                'content' => array(
                    'html_id' => $this->_oConfig->getHtmlIds('profile_more_popup') . $iAccountId,
                    'more' => _t('_bx_accnt_txt_more', $iProfiles - $iMaxVisible),
                    'more_attr' => bx_html_attribute(_t('_bx_accnt_txt_see_more')),
                    'popup' => '',
                ),
            ),
        );

        $aTmplVarsPopup = array (
            'class_cnt' => ' bx-def-padding',
            'bx_repeat:profiles' => array(),
            'bx_if:profiles_more' => array(
                'condition' => false,
                'content' => array(),
            ),
        );

        $i = 0;
        foreach ($aProfiles as $iProfileId => $aProfile) {
            $oProfile = BxDolProfile::getInstance($iProfileId);
            if(!$oProfile)
                continue;

            $sName = $oProfile->getDisplayName();
            $aTmplVarsProfile = array (
                'html_id' => $this->_oConfig->getHtmlIds('profile') . $aProfile['id'],
                'id' => $oProfile->id(),
                'url' => $oProfile->getUrl(),
                'name' => $sName,
                'name_attr' => bx_html_attribute($sName)
            );

            if($i < $iMaxVisible)
                $aTmplVars['bx_repeat:profiles'][] = $aTmplVarsProfile;
            if($i >= $iMaxVisible)
                $aTmplVarsPopup['bx_repeat:profiles'][] = $aTmplVarsProfile;

            ++$i;
        }

        if($aTmplVarsPopup['bx_repeat:profiles']) {
            $aTmplVars['bx_if:profiles_more']['content']['popup'] = BxTemplFunctions::getInstance()->transBox('', $this->parseHtmlByName('profiles.html', $aTmplVarsPopup));
        }

        return $this->parseHtmlByName('profiles.html', $aTmplVars);
    }
}

/** @} */

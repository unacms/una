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

    public function getBlockInvite($iAccountId, $iProfileId)
    {
    	$sInvitesRemain = '';
    	if(!isAdmin($iAccountId)) {
	    	$iInvites = $this->_oConfig->getCountPerUser();
	        $iInvited = $this->_oDb->getInvites(array('type' => 'count_by_account', 'value' => $iAccountId));

	        $sInvitesRemain = $iInvites - $iInvited;
    	}
    	else 
    		$sInvitesRemain = _t('_bx_invites_txt_unlimited');

        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($this->_oConfig->CNF['URL_INVITE']);

        $this->getCssJs();
    	return $this->parseHtmlByName('block_invite.html', array(
    		'style_prefix' => $this->_oConfig->getPrefix('style'),
    		'js_object' => $this->_oConfig->getJsObject('main'),
    		'text' => _t('_bx_invites_txt_invite_block_text', $sInvitesRemain),
    		'url' => $sUrl,
    		'js_code' => $this->getJsCode('main')
    	));
    }

    public function getBlockRequest()
    {
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($this->_oConfig->CNF['URL_REQUEST']);

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
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . $oPermalink->permalink($CNF['URL_REQUEST']);

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

    	$sIp = getVisitorIP();

    	$iId = (int)$oForm->insert(array('nip' => ip2long($sIp),'date' => time()));
		if(!$iId)
		    return array('content' => MsgBox(_t('_bx_invites_err_cannot_perform')), 'content_id' => $sFormId, 'eval' => $sEval);

		$sRequestsEmail = $this->_oConfig->getRequestsEmail();
		if(!empty($sRequestsEmail)) {
			$sManageUrl = BX_DOL_URL_ROOT . $oPermalink->permalink($CNF['URL_REQUESTS']);

			$aMessage = BxDolEmailTemplates::getInstance()->parseTemplate('bx_invites_request_form_message', array(
				'sender_name' => bx_process_output($oForm->getCleanValue('name')),
				'sender_email' => bx_process_output($oForm->getCleanValue('email')),
				'sender_ip' => $sIp,
				'manage_url' => $sManageUrl
			));

			sendMail($sRequestsEmail, $aMessage['Subject'], $aMessage['Body'], 0, array(), BX_EMAIL_SYSTEM);
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

	public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
        $aParams = array_merge(array(
            'aHtmlIds' => $this->_oConfig->getHtmlIds()
        ), $aParams);

        return parent::getJsCode($sType, $aParams, $bWrap);
    }
}

/** @} */

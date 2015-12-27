<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Invites Invites
 * @ingroup     TridentModules
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
            'main.js'
        ));
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

<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Polls Polls
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxPollsTemplate extends BxBaseModTextTemplate
{
    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_polls';
        parent::__construct($oConfig, $oDb);
    }

    public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
        $aParams = array_merge(array(
            'aHtmlIds' => $this->_oConfig->getHtmlIds()
        ), $aParams);

        return parent::getJsCode($sType, $aParams, $bWrap);
    }

    public function entrySubentries ($aData, $bDynamic = false)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aSubentries = $this->_oDb->getSubentries(array('type' => 'entry_id', 'entry_id' => $aData[$CNF['FIELD_ID']]));
        if(empty($aSubentries) || !is_array($aSubentries))
            return '';

        $aTmplVarsSubentries = array();
        foreach($aSubentries as $aSubentry) {
            $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_SUBENTRIES'], $aSubentry['id']);

            $aTmplVarsSubentries[] = array(
                'subentry' => $oVotes->getElementBlock(array(
                    'dynamic_mode' => $bDynamic
                ))
            );
        }

    	return array(
            'content' => $this->parseHtmlByName('subentries.html', array(
    			'html_id' => $this->_oConfig->getHtmlIds('content') . $aData[$CNF['FIELD_ID']],
                'bx_repeat:subentries' => $aTmplVarsSubentries,
    	        'bx_if:show_public' => array(
    	            'condition' => (int)$aData[$CNF['FIELD_ANONYMOUS']] == 0,
    	            'content' => array()
    	        )
            )),
            'menu' => $this->_getGetBlockMenu($aData, 'subentries')
        );
    }

    public function entryResults($aData, $bDynamic = false)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $bAnonymous = (int)$aData[$CNF['FIELD_ANONYMOUS']] == 1;

        $aSubentries = $this->_oDb->getSubentries(array('type' => 'entry_id', 'entry_id' => $aData[$CNF['FIELD_ID']]));
        if(empty($aSubentries) || !is_array($aSubentries))
            return '';

        $iTotal = 0;
        foreach($aSubentries as $aSubentry)
            $iTotal += $aSubentry['votes'];

        $aTmplVarsSubentries = array();
        foreach($aSubentries as $aSubentry) {
            $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_SUBENTRIES'], $aSubentry['id']);

            $fPercent = $iTotal > 0 ? 100 * (float)$aSubentry['votes']/$iTotal : 0;
            $aTmplVarsSubentries[] = array(
                'title' => bx_process_output($aSubentry['title']),
                'width' => (int)round($fPercent) . '%',
                'votes' => $oVotes->getCounter(array('show_counter_empty' => true, 'show_counter_in_brackets' => false)),
                'percent' => _t('_bx_polls_txt_subentry_vote_percent', $iTotal > 0 ? round($fPercent, 2) : 0),
                'js_code' => $oVotes->getJsScript($bDynamic)
            );
        }

        return array(
            'content' => $this->parseHtmlByName('subentries_results.html', array(
            	'html_id' => $this->_oConfig->getHtmlIds('content') . $aData[$CNF['FIELD_ID']],
                'bx_repeat:subentries' => $aTmplVarsSubentries,
            )),
            'menu' => $this->_getGetBlockMenu($aData, 'results')
        );
    }

    protected function getUnit ($aData, $aParams = array())
    {
        $CNF = &$this->getModule()->_oConfig->CNF;
        $sJsObject = $this->_oConfig->getJsObject('entry');

        $iContentId = $aData[$CNF['FIELD_ID']];
        $bPerformed = $this->_oDb->isPerformed($iContentId, bx_get_logged_profile_id());

        $oComments = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $iContentId);

        $bTmplVarsComments = $oComments && $oComments->isEnabled();
        $aTmplVarsComments = array();
        if($bTmplVarsComments)
            $aTmplVarsComments = array(
            	'entry_comments' => _t('_bx_polls_txt_entry_comment_counter', $aData[$CNF['FIELD_COMMENTS']]),
                'entry_comments_url' => $oComments->getListUrl()
            );

        $bTmplVarsSwitcher = (int)$aData[$CNF['FIELD_HIDDEN_RESULTS']] == 0 || $bPerformed;
        $aTmplVarsSwitcher = array();
        if($bTmplVarsSwitcher)
            $aTmplVarsSwitcher = array(
                'js_object' => $sJsObject,
                'id' => $iContentId,
                'bx_if:hide_subentries' => array(
                    'condition' => !$bPerformed,
                    'content' => array()
                ),
                'bx_if:hide_results' => array(
                    'condition' => $bPerformed,
                    'content' => array()
                ),
            );

        $aResult = parent::getUnit ($aData, $aParams);
        $aResult = array_merge($aResult, array(
            'bx_if:show_comments' => array(
                'condition' => $bTmplVarsComments, 
                'content' => $aTmplVarsComments
            ),
            'bx_if:show_switcher' => array(
                'condition' => $bTmplVarsSwitcher, 
                'content' => $aTmplVarsSwitcher
            ),
        )); 

        return $aResult;
    }

    protected function getTitle($aData)
    {
        return $this->_oConfig->getTitle($aData);
    }

    protected function getSummary($aData, $sTitle = '', $sText = '', $sUrl = '')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aBlock = $this->{$this->_oDb->isPerformed($aData[$CNF['FIELD_ID']], bx_get_logged_profile_id()) ? 'entryResults' : 'entrySubentries'}($aData);

        return $aBlock['content'];
    }

    protected function getUnitThumbAndGallery ($aData)
    {
        return array('', '');
    }

    protected function _getGetBlockMenu($aData, $sSelected = '')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $sJsObject = $this->_oConfig->getJsObject('entry');
        $iContentId = $aData[$CNF['FIELD_ID']];

        $aBlocks = array(
        	'subentries' => true, 
        	'results' => (int)$aData[$CNF['FIELD_HIDDEN_RESULTS']] == 0 || $this->_oDb->isPerformed($iContentId, bx_get_logged_profile_id())
        );

        $aMenu = array();
        foreach($aBlocks as $sBlock => $bActive)
            if($bActive) {
                $sId = $this->_oConfig->getHtmlIds('block_link_' . $sBlock);
                $aMenu[] = array('id' => $sId, 'name' => $sId, 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.changeBlock(this, \'' . $sBlock . '\', ' . $iContentId . ')', 'target' => '_self', 'title' => _t('_bx_polls_menu_item_view_' . $sBlock));
            }

        if(count($aMenu) <= 1)
            return '';

        $oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive_vertical.html', 'menu_id'=> $this->_oConfig->getHtmlIds('block_menu'), 'menu_items' => $aMenu));
        if(!empty($sSelected))
            $oMenu->setSelected('', $this->_oConfig->getHtmlIds('block_link_' . $sSelected));

        return $oMenu;
    }
}

/** @} */

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
    public function entrySubentries ($aData)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aSubentries = $this->_oDb->getSubentries(array('type' => 'entry_id', 'entry_id' => $aData[$CNF['FIELD_ID']]));
        if(empty($aSubentries) || !is_array($aSubentries))
            return '';

        $aTmplVarsSubentries = array();
        foreach($aSubentries as $aSubentry) {
            $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_SUBENTRIES'], $aSubentry['id']);

            $aTmplVarsSubentries[] = array(
                'subentry' => $oVotes->getElementBlock()
            );
        }

    	return array(
            'content' => $this->parseHtmlByName('subentries.html', array(
                'bx_repeat:subentries' => $aTmplVarsSubentries,
            )),
            'menu' => $this->_getGetBlockMenu($aData, 'subentries')
        );
    }

    public function entryResults($aData)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aSubentries = $this->_oDb->getSubentries(array('type' => 'entry_id', 'entry_id' => $aData[$CNF['FIELD_ID']]));
        if(empty($aSubentries) || !is_array($aSubentries))
            return '';

        $iTotal = 0;
        foreach($aSubentries as $aSubentry)
            $iTotal += $aSubentry['votes'];

        $aTmplVarsSubentries = array();
        foreach($aSubentries as $aSubentry) {
            $fPercent = 100 * (float)$aSubentry['votes']/$iTotal;
            $aTmplVarsSubentries[] = array(
                'title' => $aSubentry['title'],
                'width' => (int)round($fPercent) . '%',
                'results' => _t('_bx_polls_txt_subentry_n_votes_m_percent', $aSubentry['votes'], $iTotal > 0 ? round($fPercent, 2) : 0)
            );
        }

        return array(
            'content' => $this->parseHtmlByName('subentries_results.html', array(
                'bx_repeat:subentries' => $aTmplVarsSubentries,
            )),
            'menu' => $this->_getGetBlockMenu($aData, 'results')
        );
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

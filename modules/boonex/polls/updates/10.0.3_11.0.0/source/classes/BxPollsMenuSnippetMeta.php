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

class BxPollsMenuSnippetMeta extends BxBaseModTextMenuSnippetMeta
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_polls';

        parent::__construct($aObject, $oTemplate);
    }

    public function setContent($aContentInfo)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_aContentInfo = !empty($this->_aContentInfo) && is_array($this->_aContentInfo) ? array_merge($this->_aContentInfo, $aContentInfo) : $aContentInfo;

        if($this->_aContentInfo) {
            $this->_iContentId = (int)$aContentInfo[$CNF['FIELD_ID']];

            $this->addMarkers(array('content_id' => $this->_iContentId));
        }
    }

    protected function _getMenuItemActions($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iContentId = (int)$this->_aContentInfo[$CNF['FIELD_ID']];
        $bPerformed = $this->_oModule->isPerformed($iContentId);
        if((int)$this->_aContentInfo[$CNF['FIELD_HIDDEN_RESULTS']] != 0 && !$bPerformed)
            return false;

        $aTmplVars = array(
            'js_object' => $this->_oModule->_oConfig->getJsObject('entry'),
            'html_id_subentries' => '',
            'html_id_results' => '',
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
        
        if(!empty($this->_aContentInfo['salt'])) {
            $sSalt = $this->_aContentInfo['salt'];

            $aTmplVars = array_merge($aTmplVars, array(
                'html_id_subentries' => $this->_oModule->_oConfig->getHtmlIds('snippet_link_subentries') . $sSalt,
                'html_id_results' => $this->_oModule->_oConfig->getHtmlIds('snippet_link_results') . $sSalt,
                'id' => bx_js_string(json_encode(array('content_id' => $iContentId, 'salt' => $sSalt)))
            ));
        }

        return $this->getUnitMetaItemCustom($this->_oTemplate->parseHtmlByName('unit_meta_actions.html', $aTmplVars));
    }
}

/** @} */

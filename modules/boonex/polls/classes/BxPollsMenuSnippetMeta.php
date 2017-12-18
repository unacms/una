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

    protected function _getMenuItemActions($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iContentId = (int)$this->_aContentInfo[$CNF['FIELD_ID']];
        $bPerformed = $this->_oModule->_oDb->isPerformed($iContentId, bx_get_logged_profile_id());
        if((int)$this->_aContentInfo[$CNF['FIELD_HIDDEN_RESULTS']] != 0 && !$bPerformed)
            return false;

        $sContent = $this->_oTemplate->parseHtmlByName('unit_meta_actions.html', array(
            'js_object' => $this->_oModule->_oConfig->getJsObject('entry'),
            'id' => $iContentId,
            'bx_if:hide_subentries' => array(
                'condition' => !$bPerformed,
                'content' => array()
            ),
            'bx_if:hide_results' => array(
                'condition' => $bPerformed,
                'content' => array()
            ),
        ));

        return $this->_oTemplate->getUnitMetaItemCustom($sContent);
    }
}

/** @} */

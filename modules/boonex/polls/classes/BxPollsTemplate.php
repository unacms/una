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

    	return $this->parseHtmlByName('subentries.html', array(
            'bx_repeat:subentries' => $aTmplVarsSubentries,
        ));
    }
}

/** @} */

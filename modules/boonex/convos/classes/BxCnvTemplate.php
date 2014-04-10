<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Convos Convos
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModTextTemplate');

/*
 * Module representation.
 */
class BxCnvTemplate extends BxBaseModTextTemplate 
{
    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb) 
    {
        $this->MODULE = 'bx_convos';
        parent::__construct($oConfig, $oDb);
    }

    public function entryCollaborators ($aContentInfo, $iMaxVisible = 2, $sFloat = 'left')
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        bx_import('BxDolProfile');
    
        $aCollaborators = $this->_oDb->getCollaborators($aContentInfo[$CNF['FIELD_ID']]);
        //unset($aCollaborators[$aContentInfo[$CNF['FIELD_AUTHOR']]]);

        // sort collaborators: first - current user, second - last replier, third - author, all others sorted by max number of posts
        $aCollaborators = $oModule->sortCollaborators($aCollaborators, $aContentInfo['last_reply_profile_id'], $aContentInfo[$CNF['FIELD_AUTHOR']]);
        $iCollaboratorsNum = count($aCollaborators);

        // prepare template variables
        $aVarsPopup = array (
            'bx_repeat:collaborators' => array(),
            'bx_if:collaborators_more' => array(
                'condition' => false,
                'content' => array(),
            ),
        );
        $aVars = array (
            'bx_repeat:collaborators' => array(),
            'bx_if:collaborators_more' => array(
                'condition' => $iCollaboratorsNum > $iMaxVisible,
                'content' => array(
                    'popup' => '',
                    'title_more' => _t('_bx_cnv_more', $iCollaboratorsNum - $iMaxVisible),
                    'float' => $sFloat,
                    'id' => $this->MODULE . '-popup-' . $aContentInfo[$CNF['FIELD_ID']],
                ),
            ),
        );
        $i = 0;
        foreach ($aCollaborators as $iProfileId => $iReadComments) {
            $oProfile = BxDolProfile::getInstance($iProfileId);
            if (!$oProfile)
                continue;

            $sInfo = '';
            if ($aContentInfo[$CNF['FIELD_AUTHOR']] == $iProfileId)
                $sInfo = _t('_bx_cnv_collaborator_author');
            if ($aContentInfo['last_reply_profile_id'] == $iProfileId)
                $sInfo .= ', ' . _t('_bx_cnv_collaborator_last_replier');
            $sInfo = trim($sInfo, ', ');
            $sInfo = $sInfo ? _t('_bx_cnv_collaborator_info', $oProfile->getDisplayName(), $sInfo) : $oProfile->getDisplayName();

            $aCollaborator = array (
                'id' => $oProfile->id(),
                'url' => $oProfile->getUrl(),
                'thumb_url' => $oProfile->getThumb(),
                'title' => $oProfile->getDisplayName(),
                'title_attr' =>  bx_html_attribute($sInfo),
                'float' => $sFloat,
                'bx_if:last_replier' => array (
                    'condition' => ($aContentInfo['last_reply_profile_id'] == $iProfileId),
                    'content' => array (
                        'id' => $oProfile->id(),
                    ),
                ),
                'bx_if:author'  => array (
                    'condition' => $aContentInfo[$CNF['FIELD_AUTHOR']] == $iProfileId,
                    'content' => array (
                        'id' => $oProfile->id(),
                    ),
                ),
            );

            if ($i < $iMaxVisible)
                $aVars['bx_repeat:collaborators'][] = $aCollaborator;
            if ($i >= $iMaxVisible)
                $aVarsPopup['bx_repeat:collaborators'][] = $aCollaborator;

            ++$i;
        }

        if ($aVarsPopup['bx_repeat:collaborators']) {
            bx_import('BxTemplFunctions');
            $aVars['bx_if:collaborators_more']['content']['popup'] = BxTemplFunctions::getInstance()->transBox('', '<div class="bx-def-padding">' . $this->parseHtmlByName('collaborators.html', $aVarsPopup) . '</div>');
        }

        return $this->parseHtmlByName('collaborators.html', $aVars);
    }

    function getAuthorDesc ($aData) 
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        if ($aData['last_reply_timestamp'] == $aData[$oModule->_oConfig->CNF['FIELD_ADDED']])
            return bx_time_js($aData[$oModule->_oConfig->CNF['FIELD_ADDED']], BX_FORMAT_DATE);
        
        return _t('_bx_cnv_author_desc', bx_time_js($aData[$oModule->_oConfig->CNF['FIELD_ADDED']], BX_FORMAT_DATE), bx_time_js($aData['last_reply_timestamp'], BX_FORMAT_DATE));
    }
}

/** @} */ 


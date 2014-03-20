<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Messages Messages
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModTextTemplate');

/*
 * Module representation.
 */
class BxMsgTemplate extends BxBaseModTextTemplate 
{
    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb) 
    {
        self::$MODULE = 'bx_messages';
        parent::__construct($oConfig, $oDb);
    }

    public function entryCollaborators ($aContentInfo, $aCollaborators)
    {
        $oModule = BxDolModule::getInstance(self::$MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        bx_import('BxDolProfile');
    
        $aVars = array (
            'bx_repeat:collaborators' => array(),
        );
        foreach ($aCollaborators as $iProfileId => $iReadComments) {
            $oProfile = BxDolProfile::getInstance($iProfileId);
            if (!$oProfile)
                continue;
            $aVars['bx_repeat:collaborators'][] = array (
                'url' => $oProfile->getUrl(),
                'thumb_url' => $oProfile->getThumb(),
                'title' => $oProfile->getDisplayName(),
                'unread_messages_title_attr' =>  bx_html_attribute( _t('_bx_msg_unread_messages', $aContentInfo['comments'] - $iReadComments)),
                'bx_if:unread_messages' => array (
                    'condition' => ($aContentInfo['comments'] - $iReadComments) > 0,
                    'content' => array (
                        'unread_messages' =>  $aContentInfo['comments'] - $iReadComments,
                    ),
                ),
            );
        }

        return $this->parseHtmlByName('collaborators.html', $aVars);
    }
}

/** @} */ 


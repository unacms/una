<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxForumTemplate extends BxBaseModTextTemplate
{
    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_forum';
        parent::__construct($oConfig, $oDb);
    }

    public function entryBreadcrumb($aContentInfo, $aTmplVarsItems = array())
    {
    	$CNF = &BxDolModule::getInstance($this->MODULE)->_oConfig->CNF;

        $oPermalink = BxDolPermalinks::getInstance();
        $oCategory = BxDolCategory::getObjectInstance($CNF['OBJECT_CATEGORY']);

    	$aTmplVarsItems = array(array(
        	'url' => bx_append_url_params($oPermalink->permalink('page.php?i=' . $CNF['URI_CATEGORY_ENTRIES']), array(
        		'category' => $aContentInfo[$CNF['FIELD_CATEGORY']]
        	)),
        	'title' => $oCategory->getCategoryTitle($aContentInfo[$CNF['FIELD_CATEGORY']])
        ), array(
        	'url' => $oPermalink->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]),
        	'title' => bx_process_output($aContentInfo[$CNF['FIELD_TITLE']])
        ));

    	return parent::entryBreadcrumb($aContentInfo, $aTmplVarsItems);
    }

    public function entryParticipants($aContentInfo, $iMaxVisible = 2, $sFloat = 'left')
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        $iAuthorId = $aContentInfo[$CNF['FIELD_AUTHOR']];
        $aParticipants = $this->_oDb->getComments(array('type' => 'author_comments', 'object_id' => $aContentInfo[$CNF['FIELD_ID']]));
        $aParticipants[$iAuthorId] = !empty($aParticipants[$iAuthorId]) ? $aParticipants[$iAuthorId] + 1 : 1;

        // sort participants: first - current user, second - last replier, third - author, all others sorted by max number of posts
        $aParticipants = $oModule->sortParticipants($aParticipants, $aContentInfo['lr_profile_id'], $iAuthorId);
        $iParticipantsNum = count($aParticipants);

        // prepare template variables
        $aVarsPopup = array (
            'float' => 'none',
            'bx_repeat:participants' => array(),
            'bx_if:participants_more' => array(
                'condition' => false,
                'content' => array(),
            ),
        );
        $aVars = array (
            'float' => $sFloat,
            'bx_repeat:participants' => array(),
            'bx_if:participants_more' => array(
                'condition' => $iParticipantsNum > $iMaxVisible,
                'content' => array(
                    'popup' => '',
                    'title_more' => _t('_bx_forum_more', $iParticipantsNum - $iMaxVisible),
                    'float' => $sFloat,
                    'id' => $this->MODULE . '-popup-' . $aContentInfo[$CNF['FIELD_ID']],
                ),
            ),
        );
        $i = 0;
        foreach ($aParticipants as $iProfileId => $iComments) {
            $oProfile = BxDolProfile::getInstanceMagic($iProfileId);

            $aParticipant = array (
                'id' => $oProfile->id(),
                'unit' => $oProfile->getUnit(0, array('template' => 'unit_wo_info')),
                'float' => $sFloat,
                'class' => $iAuthorId == $iProfileId ? 'bx-forum-participant-author' : '',
            	'bx_if:replies_count' => array(
            		'condition' => $iComments > 0,
            		'content' => array(
            			'count' => $iComments
            		)
            	),
                'bx_if:last_replier' => array (
                    'condition' => ($aContentInfo['lr_profile_id'] == $iProfileId),
                    'content' => array (
                        'id' => $oProfile->id(),
                        'title' => bx_html_attribute(_t('_bx_forum_participant_last_replier')),
                    ),
                ),
                'bx_if:author'  => array (
                    'condition' => $iAuthorId == $iProfileId,
                    'content' => array (
                        'id' => $oProfile->id(),
                        'title' => bx_html_attribute(_t('_bx_forum_participant_author')),
                    ),
                ),
            );

            if ($i < $iMaxVisible)
                $aVars['bx_repeat:participants'][] = $aParticipant;
            if ($i >= $iMaxVisible)
                $aVarsPopup['bx_repeat:participants'][] = $aParticipant;

            ++$i;
        }

        if ($aVarsPopup['bx_repeat:participants']) {
            $aVars['bx_if:participants_more']['content']['popup'] = BxTemplFunctions::getInstance()->transBox('', '<div class="bx-def-padding">' . $this->parseHtmlByName('participants.html', $aVarsPopup) . '</div>');
        }

        return $this->parseHtmlByName('participants.html', $aVars);
    }

	function getEntryAuthor($aRow)
    {
    	$oProfileAuthor = BxDolProfile::getInstanceMagic($aRow['author']);

    	return $this->parseHtmlByName('entry-author.html', array(
			'unit' => $oProfileAuthor->getUnit(0, array('template' => 'unit_wo_info')),
    	));
    }

    function getEntryPreview($aRow, $aParams = [])
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        $oProfileLast = BxDolProfile::getInstanceMagic($aRow['lr_profile_id']);

        $sTitle = strmaxtextlen($aRow[$CNF['FIELD_TITLE']], 100);
        $sText = strmaxtextlen($aRow[$CNF['FIELD_TEXT']], 100);

        $aMetas = array('main' => false, 'counters' => false, 'reply' => false);
        foreach(array_keys($aMetas) as $sMeta) {
            $sKey = 'OBJECT_MENU_SNIPPET_META_' . strtoupper($sMeta);
            if(empty($CNF[$sKey]))
                continue;

            $oMenuMeta = BxDolMenu::getObjectInstance($CNF[$sKey], $this);
            if(!$oMenuMeta) 
                continue;

            $oMenuMeta->setContentId($aRow[$CNF['FIELD_ID']]);
            $sMenuMeta = $oMenuMeta->getCode();
            if(empty($sMenuMeta))
                continue;

            $aMetas[$sMeta] = array(
                'content' => $sMenuMeta
            );
        }

        return $this->parseHtmlByName('entry-preview.html', array(
            'bx_if:show_stick' => array(
                'condition' => (int)$aRow[$CNF['FIELD_STICK']] == 1,
                'content' => array()
            ),
            'bx_if:show_lock' => array(
                'condition' => (int)$aRow[$CNF['FIELD_LOCK']] == 1,
                'content' => array()
            ),
            'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $oModule->_oConfig->CNF['URI_VIEW_ENTRY'] . '&id=' . $aRow[$CNF['FIELD_ID']]),
            'title' => $sTitle ? $sTitle : _t('_Empty'),
            'badges' => $oModule->serviceGetBadges($aRow[$CNF['FIELD_ID']], false, true),
            'text' => $sText,
            'bx_if:meta_main' => array(
                'condition' => $aMetas['main'] !== false && (!isset($aParams['show_meta_main']) || $aParams['show_meta_main']),
                'content' => $aMetas['main']
            ),
            'bx_if:meta_counters' => array(
                'condition' => $aMetas['counters'] !== false && (!isset($aParams['show_meta_counters']) || $aParams['show_meta_counters']),
                'content' => $aMetas['counters']
            ),
            'bx_if:meta_reply' => array(
                'condition' => $aMetas['reply'] !== false && (!isset($aParams['show_meta_reply']) || $aParams['show_meta_reply']),
                'content' => $aMetas['reply']
            )
        ));
    }
    
    public function getJsCode($sType, $aParams = array(), $mixedWrap = true)
    {
        $CNF = $this->_oModule->_oConfig->CNF;
        if ($sType == 'main'){
            $aParams = array_merge(array(
                'sObjNameGrid' => $CNF['OBJECT_GRID'],
            ), $aParams);
        }
        return parent::getJsCode($sType, $aParams);
    }

    function getAuthorDesc ($aData, $oProfile)
    {
        return '';
    }

    function getAuthorAddon ($aData, $oProfile)
    {
        return '';
    }
}

/** @} */

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
            $oProfile = BxDolProfile::getInstance($iProfileId);
            if (!$oProfile)
                continue;

            $sInfo = '';
            if ($iAuthorId == $iProfileId)
                $sInfo = _t('_bx_forum_participant_author');
            if ($aContentInfo['lr_profile_id'] == $iProfileId)
                $sInfo .= ', ' . _t('_bx_forum_participant_last_replier');
            $sInfo = trim($sInfo, ', ');
            $sInfo = $sInfo ? _t('_bx_forum_participant_info', $oProfile->getDisplayName(), $sInfo) : $oProfile->getDisplayName();

            $aParticipant = array (
                'id' => $oProfile->id(),
                'url' => $oProfile->getUrl(),
                'thumb_url' => $oProfile->getThumb(),
                'title' => $oProfile->getDisplayName(),
                'title_attr' =>  bx_html_attribute($sInfo),
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
                    ),
                ),
                'bx_if:author'  => array (
                    'condition' => $iAuthorId == $iProfileId,
                    'content' => array (
                        'id' => $oProfile->id(),
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
    	$oProfileAuthor = BxDolProfile::getInstance($aRow['author']);
		if(!$oProfileAuthor)
			$oProfileAuthor = BxDolProfileUndefined::getInstance();

    	return $this->parseHtmlByName('entry-author.html', array(
			'unit' => $oProfileAuthor->getUnit(0, array('template' => 'unit_wo_info')),
    	));
    }

	function getEntryLabel($aRow, $aParams = array())
    {
    	$bShowCount = isset($aParams['show_count']) ? (int)$aParams['show_count'] == 1 : false;

		$oProfileLast = BxDolProfile::getInstance($aRow['lr_profile_id']);
		if(!$oProfileLast)
			$oProfileLast = BxDolProfileUndefined::getInstance();

        return $this->parseHtmlByName('entry-label.html', array(
        	'bx_if:show_count' => array(
        		'condition' => $bShowCount,
        		'content' => array(
        			'count' => (int)$aRow['comments'] + 1
				)
        	),
            'bx_if:show_viewer_is_last_replier' => array(
                'condition' => $oProfileLast->id() == bx_get_logged_profile_id(),
                'content' => array (),
            )
        ));
    }

    function getEntryPreview($aRow)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        $oProfileLast = BxDolProfile::getInstance($aRow['lr_profile_id']);
        if(!$oProfileLast)
            $oProfileLast = BxDolProfileUndefined::getInstance();

		$sTitle = strmaxtextlen($aRow['title'], 100);
        $sText = strmaxtextlen(!empty($aRow['cmt_text']) ? $aRow['cmt_text'] : $aRow['text'], 100);

        return $this->parseHtmlByName('entry-preview.html', array(
        	'bx_if:show_stick' => array(
        		'condition' => (int)$aRow[$CNF['FIELD_STICK']] == 1,
        		'content' => array()
        	),
        	'bx_if:show_lock' => array(
        		'condition' => (int)$aRow[$CNF['FIELD_LOCK']] == 1,
        		'content' => array()
        	),
            'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $oModule->_oConfig->CNF['URI_VIEW_ENTRY'] . '&id=' . $aRow['id']),
            'title' => $sTitle ? $sTitle : _t('_Empty'),
            'text' => $sText,
            'lr_time_and_replier' => _t('_bx_forum_x_date_by_x_replier', bx_time_js($aRow['lr_timestamp'], BX_FORMAT_DATE), $oProfileLast->getDisplayName()),
        ));
    }

    function getAuthorDesc ($aData)
    {
        $CNF = &$this->_oConfig->CNF;

        return bx_time_js($aData[$CNF['FIELD_ADDED']]);
    }

    function getAuthorAddon ($aData, $oProfile)
    {
        return '';
    }

    protected function getUnit ($aData, $aParams = array())
    {
    	$aUnit = parent::getUnit($aData, $aParams);
		$aUnit['label'] = $this->getEntryLabel($aData, array('show_count' => 0));
    	return $aUnit;
    }
}

/** @} */

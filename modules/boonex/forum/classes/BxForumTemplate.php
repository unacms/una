<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Forum Forum
 * @ingroup     TridentModules
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

    public function entryParticipants ($aContentInfo, $iMaxVisible = 2, $sFloat = 'left')
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        $aParticipants = $this->_oDb->getComments(array('type' => 'author_comments', 'object_id' => $aContentInfo[$CNF['FIELD_ID']]));

        // sort participants: first - current user, second - last replier, third - author, all others sorted by max number of posts
        $aParticipants = $oModule->sortParticipants($aParticipants, $aContentInfo['last_reply_profile_id'], $aContentInfo[$CNF['FIELD_AUTHOR']]);
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
            if ($aContentInfo[$CNF['FIELD_AUTHOR']] == $iProfileId)
                $sInfo = _t('_bx_forum_participant_author');
            if ($aContentInfo['last_reply_profile_id'] == $iProfileId)
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
                'class' => $aContentInfo[$CNF['FIELD_AUTHOR']] == $iProfileId ? 'bx-forum-participant-author' : '',
            	'bx_if:replies_count' => array(
            		'condition' => $iComments > 0,
            		'content' => array(
            			'count' => $iComments
            		)
            	),
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

    function getMessagesPreviews ($a)
    {
        if (empty($a))
            return MsgBox(_t('_Empty'));

        $oModule = BxDolModule::getInstance($this->MODULE);

        $aVars = array(
            'see_all_url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($oModule->_oConfig->CNF['URL_HOME']),
            'bx_repeat:messages' => array(),
        );
        foreach ($a as $r) {

            $oProfileAuthor = BxDolProfile::getInstance($r['author']);
            if (!$oProfileAuthor)
                $oProfileAuthor = BxDolProfileUndefined::getInstance();

            $oProfileLast = BxDolProfile::getInstance($r['last_reply_profile_id']);
            if (!$oProfileLast)
                $oProfileLast = BxDolProfileUndefined::getInstance();

            $sText = strmaxtextlen($r['text'], 90);
            $sTextCmt = strmaxtextlen($r['cmt_text'], 50);

            $aVars['bx_repeat:messages'][] = array (
                'id' => $r['id'],
                'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $oModule->_oConfig->CNF['URI_VIEW_ENTRY'] . '&id=' . $r['id']),
                'text' => $sText ? $sText : $oProfileAuthor->getDisplayName(),
                'cmt_text' => $sTextCmt,
                'unread_messages' => $r['unread_messages'],
                'last_reply_time_and_replier' => _t('_bx_forum_x_date_by_x_replier', bx_time_js($r['last_reply_timestamp'], BX_FORMAT_DATE), $oProfileLast->getDisplayName()),
                'font_weight' => $r['unread_messages'] > 0 ? 'bold' : 'normal',
                'label' => $this->getEntryLabel($r, array('show_count' => 0), $oProfileLast),

                'author_id' => $oProfileAuthor->id(),
                'author_url' => $oProfileAuthor->getUrl(),
                'author_title' => $oProfileAuthor->getDisplayName(),
                'author_title_attr' => bx_html_attribute($oProfileAuthor->getDisplayName()),
                'author_thumb_url' => $oProfileAuthor->getThumb(),

                'last_replier_id' => $oProfileLast->id(),
                'last_replier_url' => $oProfileLast->getUrl(),
                'last_replier_title' => $oProfileLast->getDisplayName(),
                'last_replier_title_attr' => bx_html_attribute($oProfileLast->getDisplayName()),
                'last_replier_thumb_url' => $oProfileLast->getThumb(),
            );
        }
        return $this->parseHtmlByName('messages_previews.html', $aVars);
    }

	function getEntryAuthor($aRow)
    {
    	$oProfileAuthor = BxDolProfile::getInstance($aRow['author']);
		if(!$oProfileAuthor)
			$oProfileAuthor = BxDolProfileUndefined::getInstance();

    	return $this->parseHtmlByName('entry-author.html', array(
			'id' => $oProfileAuthor->id(),
			'url' => $oProfileAuthor->getUrl(),
			'title' => $oProfileAuthor->getDisplayName(),
			'title_attr' => bx_html_attribute($oProfileAuthor->getDisplayName()),
			'thumb_url' => $oProfileAuthor->getThumb(),
    	));
    }

	function getEntryLabel($aRow, $aParams = array(), $oProfileLast = null)
    {
    	$bShowCount = isset($aParams['show_count']) ? (int)$aParams['show_count'] == 1 : false;

        if(!$oProfileLast) {
            $oProfileLast = BxDolProfile::getInstance($aRow['last_reply_profile_id']);
            if(!$oProfileLast)
				$oProfileLast = BxDolProfileUndefined::getInstance();
        }

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

    function getEntryPreviewGrid($aRow)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);

        $oProfileLast = BxDolProfile::getInstance($aRow['last_reply_profile_id']);
        if (!$oProfileLast)
            $oProfileLast = BxDolProfileUndefined::getInstance();

        $sText = strmaxtextlen($aRow['text'], 100);
        $sTextCmt = strmaxtextlen($aRow['cmt_text'], 100);

        return $this->parseHtmlByName('entry-preview-grid.html', array(
            'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $oModule->_oConfig->CNF['URI_VIEW_ENTRY'] . '&id=' . $aRow['id']),
            'text' => $sText ? $sText : _t('_Empty'),
            'cmt_text' => $sTextCmt,
            'last_reply_time_and_replier' => _t('_bx_forum_x_date_by_x_replier', bx_time_js($aRow['last_reply_timestamp'], BX_FORMAT_DATE), $oProfileLast->getDisplayName()),
        ));
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

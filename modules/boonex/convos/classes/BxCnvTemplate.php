<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Convos Convos
 * @ingroup     TridentModules
 *
 * @{
 */

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

        $aCollaborators = $this->_oDb->getCollaborators($aContentInfo[$CNF['FIELD_ID']]);
        //unset($aCollaborators[$aContentInfo[$CNF['FIELD_AUTHOR']]]);

        // sort collaborators: first - current user, second - last replier, third - author, all others sorted by max number of posts
        $aCollaborators = $oModule->sortCollaborators($aCollaborators, $aContentInfo['last_reply_profile_id'], $aContentInfo[$CNF['FIELD_AUTHOR']]);
        $iCollaboratorsNum = count($aCollaborators);

        // prepare template variables
        $aVarsPopup = array (
            'float' => 'none',
            'bx_repeat:collaborators' => array(),
            'bx_if:collaborators_more' => array(
                'condition' => false,
                'content' => array(),
            ),
        );
        $aVars = array (
            'float' => $sFloat,
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
                'class' => $aContentInfo[$CNF['FIELD_AUTHOR']] == $iProfileId ? 'bx-cnv-collaborator-author' : '',
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

    function getMessageLabel ($r, $oProfileLast = null)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);

        if (!$oProfileLast) {
            $oProfileLast = BxDolProfile::getInstance($r['last_reply_profile_id']);
            if (!$oProfileLast)
                $oProfileLast = BxDolProfileUndefined::getInstance();
        }

        if (!isset($r['unread_messages']))
            $r['unread_messages'] = $r['comments'] - $r['read_comments'];

        $bReadByAll = true;
        $aCollaborators = $oModule->_oDb->getCollaborators($r['id']);
        foreach ($aCollaborators as $iReadComments) {
            if ($r['comments'] - $iReadComments) {
                $bReadByAll = false;
                break;
            }
        }

        if (!isset($r['unread_messages']))
            $r['unread_messages'] = $r['comments'] - $r['read_comments'];

        $aVars = array (
            'bx_if:unread_messages' => array (
                'condition' => $r['unread_messages'] > 0,
                'content' => array (
                    'unread_messages' => $r['unread_messages'],
                ),
            ),
            'bx_if:viewer_is_last_replier' => array (
                'condition' => !$bReadByAll && $oProfileLast->id() == bx_get_logged_profile_id(),
                'content' => array (),
            ),
            'bx_if:is_read_by_all' => array (
                'condition' => $bReadByAll,
                'content' => array (),
            ),
        );

        return $this->parseHtmlByName('message_label.html', $aVars);
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
                'last_reply_time_and_replier' => _t('_bx_cnv_x_date_by_x_replier', bx_time_js($r['last_reply_timestamp'], BX_FORMAT_DATE), $oProfileLast->getDisplayName()),
                'font_weight' => $r['unread_messages'] > 0 ? 'bold' : 'normal',
                'label' => $this->getMessageLabel($r, $oProfileLast),

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

    function entryMessagePreviewInGrid ($r)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);

        $oProfileLast = BxDolProfile::getInstance($r['last_reply_profile_id']);
        if (!$oProfileLast)
            $oProfileLast = BxDolProfileUndefined::getInstance();

        $sText = strmaxtextlen($r['text'], 100);
        $sTextCmt = strmaxtextlen($r['cmt_text'], 100);

        if (!isset($r['unread_messages']))
            $r['unread_messages'] = $r['comments'] - $r['read_comments'];

        $aVars = array (
            'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $oModule->_oConfig->CNF['URI_VIEW_ENTRY'] . '&id=' . $r['id']),
            'text' => $sText,
            'cmt_text' => $sTextCmt,
            'last_reply_time_and_replier' => _t('_bx_cnv_x_date_by_x_replier', bx_time_js($r['last_reply_timestamp'], BX_FORMAT_DATE), $oProfileLast->getDisplayName()),
            'bx_if:unread_messages' => array (
                'condition' => $r['unread_messages'] > 0,
                'content' => array (),
            ),
        );
        $aVars['bx_if:unread_messages2'] = $aVars['bx_if:unread_messages'];
        return $this->parseHtmlByName('message_preview_in_grid.html', $aVars);
    }

    function getAuthorAddon ($aData, $oProfile)
    {
        return '';
    }
}

/** @} */

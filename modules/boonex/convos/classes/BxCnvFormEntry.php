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

/**
 * Create/Edit entry form
 */
class BxCnvFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_convos';
        parent::__construct($aInfo, $oTemplate);

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addJs(array (
            'jquery-ui/jquery-ui.custom.min.js',
            'jquery.form.min.js',
        ));
        $oTemplate->addJsTranslation(array(
            '_bx_cnv_draft_saving_error',
            '_bx_cnv_draft_saved_success',
        ));
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $aValsToAdd['last_reply_timestamp'] = time();
        $aValsToAdd['last_reply_profile_id'] = bx_get_logged_profile_id();

        $bSaveToDrafts = bx_get('draft_save');
        $iContentId = bx_get('draft_id');
        $bDraft = $iContentId ? BX_CNV_FOLDER_DRAFTS == $this->_oModule->_oDb->getConversationFolder($iContentId, bx_get_logged_profile_id()) : false;

        if ($iContentId) {

            if (!$bDraft)
                return 0;

            if (!parent::update ($iContentId, $aValsToAdd, $isIgnore))
                return 0;

        } else {
            $iContentId = parent::insert ($aValsToAdd, $isIgnore);
            if (!$iContentId)
                return 0;
        }

        if ($bSaveToDrafts) {

            if (!$bDraft)
                $this->_oModule->_oDb->conversationToFolder($iContentId, BX_CNV_FOLDER_DRAFTS, bx_get_logged_profile_id(), 0);

            // draft is saved via ajax call only, upon successfull draft saving content id is returned
            echo $iContentId;
            exit;

        } else {

            // check for spam
            $bSpam = false;
            bx_alert('system', 'check_spam', 0, getLoggedId(), array('is_spam' => &$bSpam, 'content' => $this->getCleanValue('text'), 'where' => $this->MODULE));
            $iFolder = $bSpam ? BX_CNV_FOLDER_SPAM : BX_CNV_FOLDER_INBOX;

            // place conversation to "inbox" (or "spam" - in case of spam) folder
            $aRecipients = array_unique(array_merge($this->getCleanValue('recipients'), array(bx_get_logged_profile_id())), SORT_NUMERIC);
            foreach ($aRecipients as $iProfile) {
                $oProfile = BxDolProfile::getInstance($iProfile);
                if (!$oProfile)
                    continue;

                if ($bDraft && $oProfile->id() == bx_get_logged_profile_id())
                    $this->_oModule->_oDb->moveConvo($iContentId, $oProfile->id(), $iFolder);
                else
                    $this->_oModule->_oDb->conversationToFolder($iContentId, $iFolder, $oProfile->id(), $oProfile->id() == bx_get_logged_profile_id() ? 0 : -1);
            }
        }

        return $iContentId;
    }

    protected function genCustomInputRecipients ($aInput)
    {
        $sVals = '';
        if (!empty($aInput['value']) && is_array($aInput['value'])) {
            foreach ($aInput['value'] as $sVal) {
                if (!$sVal || !($oProfile = BxDolProfile::getInstance($sVal)))
                    continue;
               $sVals .= '<b class="val bx-def-color-bg-hl bx-def-round-corners">' . $oProfile->getDisplayName() . '<input type="hidden" name="' . $aInput['name'] . '[]" value="' . $sVal . '" /></b>';
            }
            $sVals = trim($sVals, ',');
        }
        $sId = $aInput['name'] . time();
        $sPlaceholderText = bx_html_attribute(_t('_bx_cnv_form_entry_input_recipients_placeholder'), BX_ESCAPE_STR_QUOTE);
        $sUrlGetRecipients = BX_DOL_URL_ROOT . "modules/?r=convos/ajax_get_recipients";
        return <<<EOS
<script>
    $(function() {

        $('#{$sId} input[type=text]').autocomplete({
            source: "{$sUrlGetRecipients}",
            select: function(e, ui) {
                $(this).val(ui.item.label);
                $(this).trigger('superselect', ui.item);
                e.preventDefault();
            }
        });

        $('#{$sId} input[type=text]').on('superselect', function(e, item) {
            $(this).hide();
            if ('undefined' != typeof(item))
                $(this).before('<b class="bx-def-color-bg-hl bx-def-round-corners">'+ item.label +'<input type="hidden" name="{$aInput['name']}[]" value="'+ item.value +'" /></b>');
            fAutoShrinkInput();
            $(this).show();
            this.value = '';

        }).on('keydown', function(e) {

            // if: comma,enter (delimit more keyCodes with | pipe)
            if (/(13)/.test(e.which))
                e.preventDefault();

        });

        $('#{$sId}').on('click', 'b', function() {
            $(this).remove();
            fAutoShrinkInput();
        });

        fAutoShrinkInput = function () {
            var iWidthCont = $('#{$sId}.bx-form-input-autotoken').innerWidth();
            var iWidthExisting = 0;
            $('#{$sId}.bx-form-input-autotoken b').each(function () {
                iWidthExisting += $(this).outerWidth(true);
            });
            $('#{$sId}.bx-form-input-autotoken input').width(parseInt(iWidthCont - iWidthExisting > 180 ? iWidthCont - iWidthExisting : 180) - 5);
        };

        fAutoShrinkInput();
    });
</script>
<style>
    .bx-form-input-autotoken {
        float:left;
        padding:0px;
        height:auto;
    }
    .bx-form-input-autotoken b {
        cursor:pointer;
        display:block;
        float:left;
        padding:0.5em;
        padding-right:1.5em;
        margin:0.1em;
        font-weight:normal;
    }
    .bx-form-input-autotoken b:hover {
        opacity:0.7;
    }
    .bx-form-input-autotoken b:after {
        position:absolute;
        content:"x";
        padding:0 0.5em;
        margin:0.2em 0 0.7em 0.5em;
        font-size:0.7em;
        font-weight:bold;
    }
    .bx-form-input-autotoken input {
        border:0;
        margin:0;
        padding:0 0 0 5px;
        width:auto;
    }
</style>
<div id="{$sId}" class="bx-form-input-autotoken bx-def-font-inputs bx-form-input-text">
    {$sVals}
    <input type="text" value="" placeholder="{$sPlaceholderText}" class="bx-def-font-inputs bx-form-input-text" />
</div>
EOS;
    }

    protected function genCustomInputSubmitText ($aInput)
    {
        $aVars = array();
        return $this->_oModule->_oTemplate->parseHtmlByName('form_submit_text.html', $aVars);
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        if ($iContentId = bx_get('draft_id')) { // if adding from draft, fill in existing fields info
            $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
            if ($aContentInfo)
                $aValues = array_merge($aContentInfo, $aValues);
        }

        if ($sProfilesIds = bx_get('profiles')) { // if writing directly to some recipients, pre-fill them
            $a = explode(',', $sProfilesIds);
            $a = array_unique(BxDolFormCheckerHelper::passInt($a));
            if ($a)
                $aValues['recipients'] = array_merge(empty($aValues['recipients']) ? array() : $aValues['recipients'], $a);
            
        }
        return parent::initChecker ($aValues, $aSpecificValues);
    }

    function isValid ()
    {
        if (bx_get('draft_save')) // form is always valid when saving to drafts
            return true;
        return parent::isValid ();
    }
}

/** @} */

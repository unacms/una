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

bx_import('BxBaseModTextFormEntry');

/**
 * Create/Edit entry form
 */
class BxMsgFormEntry extends BxBaseModTextFormEntry 
{
    public function __construct($aInfo, $oTemplate = false) 
    {
        $this->MODULE = 'bx_messages';
        parent::__construct($aInfo, $oTemplate);

        $aJs = array (
            'jquery.ui.all.min.js', // TODO: remake by adding individual files, instead of all
        );
        BxDolTemplate::getInstance()->addJs($aJs);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false) 
    {
        $aValsToAdd['last_reply_timestamp'] = time();
        $aValsToAdd['last_reply_profile_id'] = bx_get_logged_profile_id();
        $iContentId = parent::insert ($aValsToAdd, $isIgnore);
        if (!$iContentId)
            return 0;

        bx_import('BxDolProfile');

        // check for spam
        $bSpam = false;
        bx_alert('system', 'check_spam', 0, getLoggedId(), array('is_spam' => &$bSpam, 'content' => $this->getCleanValue('text'), 'where' => 'messages'));
        $iFolder = $bSpam ? BX_MSG_FOLDER_SPAM : BX_MSG_FOLDER_PRIMARY;

        // place conversation to "primary" (or "spam" - in case of spam) folder 
        $aRecipients = array_unique(array_merge($this->getCleanValue('recipients'), array(bx_get_logged_profile_id())), SORT_NUMERIC);
        foreach ($aRecipients as $iProfile) {
            $oProfile = BxDolProfile::getInstance($iProfile);
            if ($oProfile)
                $this->_oModule->_oDb->conversationToFolder($iContentId, $iFolder, $oProfile->id());
        }

        return $iContentId;
    }

    protected function genCustomInputRecipients ($aInput) {
        $sVals = '';
        if (!empty($aInput['value']) && is_array($aInput['value'])) {
            foreach ($aInput['value'] as $sVal) {
               $sVals .= '<span class="bx-def-color-bg-hl bx-def-round-corners">' . BxDolProfile::getInstance($sVal)->getDisplayName() . '<input type="hidden" name="' . $aInput['name'] . '[]" value="' . $sVal . '" /></span>';
            }
            $sVals = trim($sVals, ',');
        }
        $sId = $aInput['name'] . time();
        $sPlaceholderText = bx_html_attribute("Type name here...", BX_ESCAPE_STR_QUOTE); // TODO: lang key
        $sUrlGetRecipients = BX_DOL_URL_ROOT . "modules/?r=messages/ajax_get_recipients";
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
            if ('undefined' != typeof(item))
                $(this).before('<span class="bx-def-color-bg-hl bx-def-round-corners">'+ item.label +'<input type="hidden" name="{$aInput['name']}[]" value="'+ item.value +'" /></span>');
            this.value = '';

        }).on('keydown', function(e) {

            // if: comma,enter (delimit more keyCodes with | pipe)
            if (/(13)/.test(e.which))
                e.preventDefault();

        });

        $('#{$sId}').on('click', 'span', function() {
            $(this).remove(); 
        });

    });
</script>
<style>
    .bx-form-input-autotoken {
      float:left;
      padding:0px;
      height:auto;
    }
    .bx-form-input-autotoken span {
      cursor:pointer;
      display:block;
      float:left;
      padding:0.5em;
      padding-right:1.5em;
      margin:0.1em;
    }
    .bx-form-input-autotoken span:hover{
      opacity:0.7;
    }
    .bx-form-input-autotoken span:after{
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
}

/** @} */

<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Antispam Antispam
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Profanity Filter based on https://github.com/snipe/banbuilder
 * Need change 
 * str_ireplace(array_keys($leet_replace), array_values($leet_replace), $badwords[$x]) . '\b/i'
 * to 
 * str_ireplace(array_keys($leet_replace), array_values($leet_replace), $badwords[$x]) . '\b/iu'
 * in CensorWords.php
 */
class BxAntispamProfanityFilter extends BxDol
{
    protected $oProfanityFilter = null;
    protected $sPluginPath = BX_DIRECTORY_PATH_PLUGINS . 'snipe/banbuilder/src/';
    
    public function __construct()
    {
        parent::__construct();
        
        $sClassName = 'Snipe\BanBuilder\CensorWords';
        $this->oProfanityFilter = new $sClassName;
        
        $aTmp = explode(',', getParam('bx_antispam_profanity_filter_dicts'));
        foreach ($aTmp as $sLng) {
            if ($sLng != '')
                $this->oProfanityFilter->addDictionary($sLng);
        }
        
        $Char = trim(getParam('bx_antispam_profanity_filter_char_replace'));
        if ($Char != '')
            $this->oProfanityFilter->setReplaceChar($Char);
        
        $sBadWords = trim(getParam('bx_antispam_profanity_filter_bad_words_list'));
        if ($sBadWords != '')
            $this->oProfanityFilter->addFromArray(array_map('trim', explode(',', $sBadWords)));

        $sWhiteWords = getParam('bx_antispam_profanity_filter_white_words_list');
        if ($sWhiteWords != '')
            $this->oProfanityFilter->addWhiteList(array_map('trim', explode(',', $sWhiteWords)));
    }

    public function censor ($mValue)
    {
        $bFullWord = getParam('bx_antispam_profanity_filter_full_words_only') =='on' ? true : false;
        if (is_array($mValue)){
            for ($i = 0; $i < count($mValue); $i++) {
                if (is_string($mValue[$i])){
                    $aTmp = $this->oProfanityFilter->censorString($mValue[$i], $bFullWord);
                    $mValue[$i] = $aTmp['clean'];
                }
            }
        }
        else{
            $aTmp = $this->oProfanityFilter->censorString($mValue, $bFullWord);
            $mValue = $aTmp['clean'];
        }
        return $mValue;
    } 
    
    public function getDicts ()
    {
        $aResult = array();
        if ($oHandle = opendir($this->sPluginPath . 'dict')) {
            while (false !== ($oEntry = readdir($oHandle))) {
                if (!is_dir($oEntry)){
                    $sTmp = str_replace('.php', '', $oEntry);
                    $aResult[$sTmp] = $sTmp;
                }
            }
            closedir($oHandle);
        }
        return $aResult;
    } 
}

/** @} */

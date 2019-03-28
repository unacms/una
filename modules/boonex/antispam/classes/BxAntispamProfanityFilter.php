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
 */
class BxAntispamProfanityFilter extends BxDol
{
    protected $oProfanityFilter = null;
    protected $sPluginPath = BX_DIRECTORY_PATH_PLUGINS . 'banbuilder/src/';
    protected $bIsFullWord = false;
    
    public function __construct()
    {
        parent::__construct();
        
        require_once ($this->sPluginPath .'CensorWords.php');
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
            $this->oProfanityFilter->addFromArray(explode(',', $sBadWords));
        
        $sWhiteWords = getParam('bx_antispam_profanity_filter_white_words_list');
        if ($sWhiteWords != '')
            $this->oProfanityFilter->addWhiteList(explode(',', $sWhiteWords));
    }

    public function censorString ($s)
    {
        $aTmp = $this->oProfanityFilter->censorString($s, $this->bIsFullWord);
        return $aTmp['clean'];
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

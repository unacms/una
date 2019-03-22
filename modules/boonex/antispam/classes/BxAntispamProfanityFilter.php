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
 * Profanity Filter based on https://github.com/developerdino/ProfanityFilter
 */
class BxAntispamProfanityFilter extends BxDol
{
    protected $oProfanityFilter = null;

    public function __construct()
    {
        parent::__construct();
        
        require_once (BX_DIRECTORY_PATH_PLUGINS . 'banbuilder/src/CensorWords.php');
        $sClassName = 'Snipe\BanBuilder\CensorWords';
        $this->oProfanityFilter = new $sClassName;
        $this->oProfanityFilter->addDictionary('en-base');
        $this->oProfanityFilter->addDictionary('en-uk');
        
        $aLng = BxDolLanguages::getInstance()->getLanguages();
        try{
            foreach ($aLng as $sKey => $sVal){
                if ($sKey != 'en')
                    $this->oProfanityFilter->addDictionary($sKey);
            }
        }
        catch (Exception $oException) {
        }
        
        $this->oProfanityFilter->setReplaceChar(getParam('bx_antispam_profanity_filter_char_replace'));
        
        $sBadWords = trim(getParam('bx_antispam_profanity_filter_bad_words_list'));
        if ($sBadWords != '')
            $this->oProfanityFilter->addFromArray(explode(',', $sBadWords));
        
        $sWhiteWords = getParam('bx_antispam_profanity_filter_white_words_list');
        if ($sWhiteWords != '')
            $this->oProfanityFilter->addWhiteList(explode(',', $sWhiteWords));
    }

    public function censorString ($s)
    {
        $aTmp = $this->oProfanityFilter->censorString($s, false);
        return $aTmp['clean'];
    } 
}

/** @} */

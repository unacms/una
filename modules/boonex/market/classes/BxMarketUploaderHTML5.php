<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMarketUploaderHTML5 extends BxTemplUploaderHTML5
{
    protected $_oModule;

    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_market');
    }

    public function getGhostsWithOrder($iProfileId, $sFormat, $sImagesTranscoder = false, $iContentId = false, $isLatestOnly = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $a = $this->getGhosts($iProfileId, 'array', $sImagesTranscoder, $iContentId);
        if(!$isLatestOnly) {
            if($iContentId && ($aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId))) {
                if(!empty($aContentInfo[$CNF['FIELD_THUMB']]))
                    unset($a[$aContentInfo[$CNF['FIELD_THUMB']]]);

                if(!empty($aContentInfo[$CNF['FIELD_COVER']]))
                    unset($a[$aContentInfo[$CNF['FIELD_COVER']]]);
            }
        }
        else 
            $a = array_slice($a, 0, 1, true);

        if(!empty($a) && is_array($a))
            $a = ['g' => $a, 'o' => array_keys($a)];

        return $sFormat == 'json' ? json_encode($a) : $a;
    }

    protected function getGhostTemplateVars($aFile, $iProfileId, $iContentId, $oStorage, $oImagesTranscoder)
    {
    	return $this->_oModule->getGhostTemplateVars($aFile, $iProfileId, $iContentId, $oStorage, $oImagesTranscoder);
    }
}

/** @} */

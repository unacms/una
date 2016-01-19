<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Air Air
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModGeneralTemplate');

/*
 * Module representation.
 */
class BxAirTemplate extends BxBaseModGeneralTemplate
{
    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_air';
        parent::__construct($oConfig, $oDb);
    }

    public function getSplash()
    {
		if($this->_oConfig->getUri() != $this->getCode())
			return '';

		$sDownloadUrl = 'http://ci.boonex.com/';
		$sVersion = '8.0';
        $sVersionFull = '8.0.0';
		$sBuild = '';

		$mixedResponse = bx_file_get_contents($sDownloadUrl . "builds/latest-release-file.txt");
		if($mixedResponse !== false) {
			$sFile = trim(bx_process_input($mixedResponse));
			
			$aMatches = array();
			if((int)preg_match("/([0-9]\.[0-9])\.([0-9])-?([A-Z]*[a-z]*[0-9]{1,3})/", $sFile, $aMatches) > 0 && !empty($aMatches[1]) && !empty($aMatches[3])) {
				$sDownloadUrl .= 'builds/' . $sFile;
				$sVersion = $aMatches[1];
				$sBuild = $aMatches[3];
			}

            if((int)preg_match("/Trident-v\.([0-9A-Za-z\.-]+)\.zip/", $sFile, $aMatches) > 0)
                $sVersionFull = $aMatches[1];
		}

		$this->addCss(array('splash-phone.css', 'splash-tablet.css', 'splash-desktop.css'));
    	return $this->parseHtmlByName('splash.html', array(
    		'download_url' => $sDownloadUrl,
            'version_full' => $sVersionFull,
    		'version' => $sVersion,
    		'build' => $sBuild
    	));
    }
}

/** @} */

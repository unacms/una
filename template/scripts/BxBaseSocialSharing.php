<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolSocialSharing');

/**
 * @see BxDolSocialSharing
 */ 
class BxBaseSocialSharing extends BxDolSocialSharing
{
    protected $_oTemplate;

	/**
	 * Constructor
	 */
	protected function __construct($oTemplate) {
        parent::__construct();
        $this->_oTemplate = $oTemplate ? $oTemplate : BxDolTemplate::getInstance();
	}

    public function getCode ($sUrl, $sTitle, $aCustomVars = false) {

        // define markers for replacments
        bx_import('BxDolLanguages');
        $sLang = BxDolLanguages::getInstance()->getCurrentLanguage();
        $aMarkers = array (
            'url' => $sUrl,
            'url_encoded' => rawurlencode($sUrl),
            'lang' => $sLang, 
            'locale' => $this->_getLocaleFacebook($sLang),
            'title' => $sTitle,
            'title_encoded' => rawurlencode($sTitle),
        );

        if (!empty($aCustomVars) && is_array($aCustomVars))
            $aMarkers = array_merge($aMarkers, $aCustomVars);
        
        // alert
        $sOverrideOutput = null;
        bx_import('BxDolAlerts');
        $oAlert = new BxDolAlerts('system', 'social_sharing_display', '', '', array (
            'buttons' => &$this->_aSocialButtons,
            'markers' => &$aMarkers,
            'override_output' => &$sOverrideOutput,
        ));
        $oAlert->alert();

        // return custom code if there is one
        if ($sOverrideOutput)
            return $sOverrideOutput;

        // return empty string of there is no buttons
        if (empty($this->_aSocialButtons))
            return '';

        // prepare buttons
        $aButtons = array();
        foreach ($this->_aSocialButtons as $aButton) {
            $sButton = $this->_replaceMarkers($aButton['content'], $aMarkers);
            if (preg_match('/{[A-Za-z0-9_]+}/', $sButton)) // if not all markers are replaced skip it
                continue;
            $aButtons[] = array ('button' => $sButton);
        }

        // output
        $aTemplateVars = array (
            'bx_repeat:buttons' => $aButtons,
        );
        return $this->_oTemplate->parseHtmlByName('social_sharing.html', $aTemplateVars);
    }

}

/** @} */

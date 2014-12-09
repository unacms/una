<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
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
    protected function __construct($oTemplate)
    {
        parent::__construct();
        $this->_oTemplate = $oTemplate ? $oTemplate : BxDolTemplate::getInstance();
    }

    public function getCode ($sContentId, $sModuleName, $sUrl, $sTitle, $aCustomVars = false)
    {
        // define markers for replacments
        bx_import('BxDolLanguages');
        $sLang = BxDolLanguages::getInstance()->getCurrentLanguage();
        $aMarkers = array (
            'id' => $sContentId,
            'module' => $sModuleName,
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

            switch ($aButton['type']) {
                case 'html':
                    $sButton = $this->_replaceMarkers($aButton['content'], $aMarkers);
                    break;
                case 'service':
                    $a = @unserialize($aButton['content']);
                    if (false === $a || !is_array($a))
                        break;
                    $a = $this->_replaceMarkers($a, $aMarkers);
                    $sButton = BxDolService::call($a['module'], $a['method'], isset($a['params']) ? $a['params'] : array(), isset($a['class']) ? $a['class'] : 'Module');
                    break;
            }

            if (!isset($sButton) || preg_match('/{[A-Za-z0-9_]+}/', $sButton)) // if not all markers are replaced skip it
                continue;
            $aButtons[] = array ('button' => $sButton, 'object' => $aButton['object']);
        }

        // output
        $aTemplateVars = array (
            'bx_repeat:buttons' => $aButtons,
        );
        return $this->_oTemplate->parseHtmlByName('social_sharing.html', $aTemplateVars);
    }

}

/** @} */

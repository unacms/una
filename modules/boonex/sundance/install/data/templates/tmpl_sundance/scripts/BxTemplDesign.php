<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxTemplStudioDesign');

class BxTemplDesign extends BxTemplStudioDesign { 
    function BxTemplDesign($sTemplate = "", $sPage = "") {
        parent::BxTemplStudioDesign($sTemplate, $sPage);

        $this->aMenuItems = array(
            'general' => '_bx_snd_lmi_cpt_general',
            'colors' => '_bx_snd_lmi_cpt_colors',
            'fonts' => '_bx_snd_lmi_cpt_fonts'
        );
    }

    protected function getColors() {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aTmplVars = array(
        	'bx_repeat:blocks' => 'Custom color changer can be here.',
        );
        return $oTemplate->parseHtmlByName('design.html', $aTmplVars);
    }

    protected function getFonts() {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aTmplVars = array(
        	'bx_repeat:blocks' => 'Custom font changer can be here.',
        );
        return $oTemplate->parseHtmlByName('design.html', $aTmplVars);
    }
}
/** @} */
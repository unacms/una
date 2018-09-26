<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ribbons Ribbons
 * @ingroup     UnaModules
 *
 * @{
 */

class BxRibbonsTemplate extends BxBaseModTextTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_ribbons';
        parent::__construct($oConfig, $oDb);
    }
    
    function getRibbonsForSnippet($iProfileId)
    {
        $this->addCss('main.css');
        return $this->parseHtmlByName('snippet_view.html', $this->getRibbonsData($iProfileId));
    }
    
    function getRibbonsForBlock($iProfileId)
    {
        $this->addCss('main.css');
        return $this->parseHtmlByName('block_view.html', $this->getRibbonsData($iProfileId));
    }
    
    function getRibbonsForSelector($iProfileId)
    {
        $aData = $this->getRibbonsData($iProfileId, true);
        $CNF = &$this->_oModule->_oConfig->CNF;
        $aData['js_object'] = $this->_oConfig->getJsObject('ribbons');
        $aData['button_title'] = _t('_bx_ribbons_txt_button_set_ribbons');
        return $this->parseHtmlByName('selector_view.html', $aData);
    }
   
    private function getRibbonsData($iProfileId, $bIsForSelect = false)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;
        
        $aRibbons = array();
        $aRibbonsProfile = $this->_oModule->_oDb->getRibbonsForProfile($iProfileId);
        $aRibbonsTmp = array();
        if ($bIsForSelect){
            $aRibbons = $this->_oModule->_oDb->getAllActive(array('type' => 'all'));
            foreach($aRibbonsProfile as $aRibbon){
                array_push($aRibbonsTmp, $aRibbon[$CNF['FIELD_ID']]);
            }
        }
        else{
            $aRibbons = $aRibbonsProfile;
        }
        
        $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
        $aRibbonsList = array();
        foreach($aRibbons as $aRibbon){
            $sPhotoThumb = '';
            if ($oImagesTranscoder && $aRibbon[$CNF['FIELD_THUMB']] != '0')
                $sPhotoThumb = $oImagesTranscoder->getFileUrl($aRibbon[$CNF['FIELD_THUMB']]);
            $aRibbonsList[] = array(
                'id' => $aRibbon[$CNF['FIELD_ID']],
                'selected' => in_array($aRibbon[$CNF['FIELD_ID']], $aRibbonsTmp) ? 'checked' : '',
                'bx_if:title' => array(
                    'condition' => trim($aRibbon[$CNF['FIELD_TITLE']]) != '',
                    'content' => array(
                        'title' => $aRibbon[$CNF['FIELD_TITLE']],
                    )
                ),
                'bx_if:text' => array(
                    'condition' => trim($aRibbon[$CNF['FIELD_TEXT']]) != '',
                    'content' => array(
                        'text' => $aRibbon[$CNF['FIELD_TEXT']],
                    )
                ),
                'bx_if:image' => array(
                    'condition' => $sPhotoThumb != '',
                    'content' => array(
                        'thumb_url' => $sPhotoThumb,
                        'title' => $aRibbon[$CNF['FIELD_TITLE']],
                    )
                )
             );
        }
        return array('bx_repeat:items' => $aRibbonsList);
    }
}

/** @} */
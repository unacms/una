<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Cover representation.
 * @see BxDolCover
 */
class BxBaseCover extends BxDolCover
{
    protected $_bJsCssAdded = false;

    protected $_oTemplate;

    public function __construct ($oTemplate)
    {
        parent::__construct ();

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    /**
     * Display Cover.
     */
    public function display ()
    {
        $this->_addJsCss();

        $sResult = null;
        $aParams = [];
        if(!empty($this->_aOptions) && is_array($this->_aOptions)){
            $aParams = array_merge($this->_aOptiondDefault, $this->_aOptions);
        }
        else{
            $oPage = BxDolPage::getObjectInstanceByURI();
            if(empty($oPage) || !is_a($oPage, 'BxDolPage'))
                return $this->displayEmpty();

            $mixedOptions = $oPage->getPageCoverParams();
            if(empty($mixedOptions) || !is_array($mixedOptions))
                return $this->displayEmpty();

            if(!$this->_sCoverImageUrl) {
                $iId = (int)getParam('sys_site_cover_common');
                if($iId != 0)
                    $this->setCoverImageUrl(array('id' => $iId, 'transcoder' => BX_DOL_TRANSCODER_OBJ_COVER));
            }

            if($this->_sCoverImageUrl)
                $mixedOptions['bx_if:bg'] = array (
                    'condition' => true,
                    'content' => array('image_url' => $this->_sCoverImageUrl),
                );

            $mixedOptions['bx_if:empty_cover_class'] = array (
                'condition' => empty($this->_sCoverImageUrl),
                'content' => array(),
            );
        
            $aParams = array_merge($this->_aOptiondDefault, $mixedOptions);
        }
        
        bx_alert('system', 'display_cover', 0, 0, array(
            'res' => &$sResult, 
            'template_name' => &$this->_sTemplateName, 
            'params' => &$aParams, 
        ));
        
        if ($sResult  != null)
            return $sResult;
        
        return $this->_oTemplate->parseHtmlByName($this->_sTemplateName, $aParams); 
    }
    
    /**
     * Display Empty Cover.
     */
    public function displayEmpty ()
    {
    	return $this->_oTemplate->parseHtmlByName($this->_sTemplateNameEmpty, array());
    }

    /**
     * Add css/js files which are needed for display and functionality.
     */
    protected function _addJsCss()
    {
        if ($this->_bJsCssAdded)
            return;

        $this->_oTemplate->addCss(array('cover.css'));
        $this->_bJsCssAdded = true;
    }
}

/** @} */

<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
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

        if(!empty($this->_aOptions) && is_array($this->_aOptions))
        	return $this->_oTemplate->parseHtmlByName($this->_sTemplateName, array_merge($this->_aOptiondDefault, $this->_aOptions));

		$mixedOptions = BxDolPage::getObjectInstanceByURI()->getPageCoverParams();
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

		return $this->_oTemplate->parseHtmlByName($this->_sTemplateName, array_merge($this->_aOptiondDefault, $mixedOptions));        
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

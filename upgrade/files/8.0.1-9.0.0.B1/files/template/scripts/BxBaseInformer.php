<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Informer representation.
 * @see BxDolInformer
 */
class BxBaseInformer extends BxDolInformer
{
    protected $_bJsCssAdded = false;

    protected $_oTemplate;

    protected $_aMapType2Class = array(
        BX_INFORMER_ALERT => 'bx-informer-msg-alert',
        BX_INFORMER_INFO => 'bx-informer-msg-info',
        BX_INFORMER_ERROR => 'bx-informer-msg-error',
    );

    public function __construct ($oTemplate)
    {
        parent::__construct ();

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    /**
     * Display Informer.
     */
    public function display ()
    {
    	if(!$this->_bEnabled)
    		return '';

        $this->_addPermanentMessages();

        if (!$this->_aMessages)
            return '';

		$aTmplVarsMessages = array();
        foreach ($this->_aMessages as $sId => $a) {
        	$a['class'] = $this->_aMapType2Class[$a['type']];

        	$aTmplVarsMessages[] = $a;
        }

        $this->_addJsCss();
        return $this->_oTemplate->parseHtmlByName('informer.html', array(
            'bx_repeat:messages' => $aTmplVarsMessages,
        ));
    }

    /**
     * Add css/js files which are needed for display and functionality.
     */
    protected function _addJsCss()
    {
        if ($this->_bJsCssAdded)
            return;
        $this->_oTemplate->addCss(array('informer.css'));
        $this->_bJsCssAdded = true;
    }
}

/** @} */

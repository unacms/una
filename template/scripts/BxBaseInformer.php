<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolInformer');

/**
 * Informer representation.
 * @see BxDolInformer
 */
class BxBaseInformer extends BxDolInformer {

    protected $_bJsCssAdded = false;

    protected $_oTemplate;

    protected $_aMapType2Icon = array(
        BX_INFORMER_ALERT => 'exclamation-red-frame.png',
        BX_INFORMER_INFO => 'information-frame.png',
    );
    protected $_aMapType2Class = array(
        BX_INFORMER_ALERT => 'bx-informer-msg-alert',
        BX_INFORMER_INFO => 'bx-informer-msg-info',
    );

    public function __construct ($oTemplate) {
        parent::__construct ();

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    /**
     * Display Informer.
     */
    public function display () {

        $this->_addPermanentMessages();

        if (!$this->_aMessages)
            return '';

        foreach ($this->_aMessages as $sId => $a) {
            $this->_aMessages[$sId]['class'] = $this->_aMapType2Class[$a['type']];
            $this->_aMessages[$sId]['icon_url'] = $this->_oTemplate->getIconUrl($this->_aMapType2Icon[$a['type']]);
        }

        $this->_addJsCss();
        return $this->_oTemplate->parseHtmlByName('informer.html', array(
            'bx_repeat:messages' => $this->_aMessages,
        ));
    }

    /**
     * Add css/js files which are needed for display and functionality.
     */
    protected function _addJsCss() {
        if ($this->_bJsCssAdded)
            return;
        $this->_oTemplate->addCss('informer.css');
        $this->_bJsCssAdded = true;
    }
}

/** @} */

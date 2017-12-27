<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry create/edit pages
 */
class BxPhotosPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_photos';
        parent::__construct($aObject, $oTemplate);
    }

    public function getCode()
    {
        $sResult = parent::getCode();

        //--- Don't use uploaded image as page cover.
        BxDolCover::getInstance($this->_oModule->_oTemplate)->setCoverImageUrl('');

        return $sResult;
    }

    protected function _setSubmenu($aParams)
    {
    	parent::_setSubmenu(array_merge($aParams, array(
    		'icon' => ''
    	)));
    }

    protected function _getThumbForMetaObject ()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!empty($CNF['FIELD_THUMB']) && !empty($this->_aContentInfo[$CNF['FIELD_THUMB']]))
            return array('id' => $this->_aContentInfo[$CNF['FIELD_THUMB']], 'transcoder' => $CNF['OBJECT_IMAGES_TRANSCODER_COVER']);

        return false;
    }
}

/** @} */

<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Videos Videos
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry create/edit pages
 */
class BxVideosPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_videos';
        parent::__construct($aObject, $oTemplate);
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

		$sPosterSrc = !empty($CNF['FIELD_POSTER']) ? $CNF['FIELD_POSTER'] : $CNF['FIELD_THUMB'];
        if(!empty($sPosterSrc) && !empty($this->_aContentInfo[$sPosterSrc]))
            return array('id' => $this->_aContentInfo[$sPosterSrc], 'transcoder' => $CNF['OBJECT_IMAGES_TRANSCODER_POSTER']);

        if(!empty($CNF['FIELD_VIDEO']) && !empty($this->_aContentInfo[$CNF['FIELD_VIDEO']]))
            return array('id' => $this->_aContentInfo[$CNF['FIELD_VIDEO']], 'transcoder' => $CNF['OBJECT_VIDEOS_TRANSCODERS']['poster']);

        return false;
    }
}

/** @} */

<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

bx_import('BxDolStudioStorages');

class BxDolStudioGridStorages extends BxTemplStudioGrid
{
	protected $_sType;
	protected $_aT;

	protected $_sStorage;
	protected $_oStorage;
	protected $_sTranscoder;
	protected $_aUploaders;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate ? $oTemplate : BxDolStudioTemplate::getInstance());

        $this->oDb = new BxDolStudioStoragesQuery();

        $this->_oStorage = BxDolStorage::getObjectInstance($this->_sStorage);
        $this->_sTranscoder = '';
        $this->_aUploaders = array('sys_html5');
    }
}
/** @} */

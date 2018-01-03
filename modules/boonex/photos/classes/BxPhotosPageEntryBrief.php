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
 * View Entry (brief)
 */
class BxPhotosPageEntryBrief extends BxTemplPage
{
    protected $_sModule;
    protected $_oModule;

    protected $_aContentInfo;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_sModule = 'bx_photos';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        $sMode = '';
        if(bx_get('mode') !== false)
            $sMode = bx_process_input(bx_get('mode'));

        if($iContentId)
            $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
    }
}

/** @} */

<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAlbumsVoteLikesMedia extends BxTemplVoteLikes
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($sSystem, $iId, $iInit = 1)
    {
        parent::__construct($sSystem, $iId, $iInit);

        $this->_sModule = 'bx_albums';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    protected function _isAllowedVoteByObject($aObject)
    {
        return parent::_isAllowedVoteByObject($this->_oModule->_oDb->getContentInfoById($aObject['content_id']));
    }
}

/** @} */

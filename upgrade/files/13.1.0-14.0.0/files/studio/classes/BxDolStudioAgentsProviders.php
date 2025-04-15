<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

require_once(BX_DOL_DIR_STUDIO_INC . 'utils.inc.php');

class BxDolStudioAgentsProviders extends BxTemplStudioGrid
{
    protected $_oDb;

    protected $_iProfileIdAi;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sDefaultSortingOrder = 'DESC';

        $this->_oDb = new BxDolStudioAgentsQuery();

        $this->_iProfileIdAi = BxDolAI::getInstance()->getProfileId();
    }
}

/** @} */

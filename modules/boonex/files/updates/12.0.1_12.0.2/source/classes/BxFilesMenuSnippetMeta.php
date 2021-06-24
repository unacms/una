<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

class BxFilesMenuSnippetMeta extends BxBaseModTextMenuSnippetMeta
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_files';

        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemSize($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        $aFile = $this->_oModule->getContentFile($this->_aContentInfo);

        if (!$aFile) return 0;

        return $this->getUnitMetaItemText(_t_format_size($aFile['size']));
    }
}

/** @} */

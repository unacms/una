<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Convos Convos
 * @ingroup     TridentModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

class BxCnvGrid extends BxTemplGrid
{
    protected $MODULE;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->MODULE = 'bx_convos';
        parent::__construct ($aOptions, $oTemplate);
        $this->_sDefaultSortingOrder = 'DESC';

        if ($iFolderId = (int)bx_get('folder_id'))
            $this->addMarkers(array(
                'folder_id' => $iFolderId,
                'profile_id' => bx_get_logged_profile_id(),
            ));
    }

    public function performActionCompose()
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_ADD_ENTRY']);

        header('Location:' . $sUrl);
    }

    protected function _getActionAdd ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_ADD_ENTRY']);

        $a['attr']['onclick'] = "document.location='$sUrl'";
        unset($a['attr']['bx_grid_action_independent']);

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getCellHeaderWrapper ($sKey, $aField, $sHeader, $sAttr)
    {
        $sHeader = '<span>' . $sHeader . '</span>';
        return parent::_getCellHeaderWrapper ($sKey, $aField, $sHeader, $sAttr);
    }

    protected function _getCellPreview ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault ('', $sKey, $aField, $aRow);
    }

    protected function _getCellLastReplyTimestamp ($mixedValue, $sKey, $aField, $aRow)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $s = $oModule->_oTemplate->entryMessagePreviewInGrid ($aRow);
        return parent::_getCellDefault ($s, $sKey, $aField, $aRow);
    }

    protected function _getCellCollaborators ($mixedValue, $sKey, $aField, $aRow)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $s = $oModule->_oTemplate->entryCollaborators ($aRow);
        return parent::_getCellDefault ($s, $sKey, $aField, $aRow);
    }

    protected function _getCellComments ($mixedValue, $sKey, $aField, $aRow)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $s = $oModule->_oTemplate->getMessageLabel ($aRow);
        return parent::_getCellDefault ('<div class="bx-cnv-grid-field-messages"><span>' . (1 + $mixedValue) . '</span>' . $s . '</div>', $sKey, $aField, $aRow);
    }

    protected function _delete ($mixedId)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);

        if ($sErrorMsg = $oModule->deleteConvo ($mixedId))
            return false;

        return $oModule->_oDb->moveConvo((int)$mixedId, bx_get_logged_profile_id(), BX_CNV_FOLDER_TRASH);
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $oModule = BxDolModule::getInstance($this->MODULE);
        $oModule->_oTemplate->addJs('main.js');
        $oModule->_oTemplate->addCss(array('main-media-tablet.css', 'main-media-desktop.css'));
    }
}

/** @} */

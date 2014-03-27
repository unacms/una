<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Messages Messages
 * @ingroup     DolphinModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxTemplGrid');

class BxMsgGrid extends BxTemplGrid 
{
    protected $MODULE;

    public function __construct ($aOptions, $oTemplate = false) 
    {
        $this->MODULE = 'bx_messages';
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
        bx_import('BxDolModule');
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        bx_import('BxDolPermalinks');
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_ADD_ENTRY']);

        header('Location:' . $sUrl);
    }

    protected function _getActionAdd ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array()) 
    {
        bx_import('BxDolModule');
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        bx_import('BxDolPermalinks');
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_ADD_ENTRY']);

        $a['attr']['onclick'] = "document.location='$sUrl'";
        unset($a['attr']['bx_grid_action_independent']);

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getCellPreview ($mixedValue, $sKey, $aField, $aRow) 
    {
        bx_import('BxDolModule');
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = &$oModule->_oConfig->CNF;

        bx_import('BxDolPermalinks');
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aRow[$CNF['FIELD_ID']]);

        $s = strmaxtextlen($aRow['text'], 100);
        $s = '<a href="' . $sUrl . '">' . $s . '</a>';
        if ($aRow['comments'] - $aRow['read_comments'] > 0)
            $s = '<b>' . $s . '</b>';
        return parent::_getCellDefault ($s, $sKey, $aField, $aRow);
    }

    protected function _getCellLastReplyTimestamp ($mixedValue, $sKey, $aField, $aRow) 
    {
        $s = bx_time_js($mixedValue, BX_FORMAT_DATE);
        if ($aRow['comments'] - $aRow['read_comments'] > 0)
            $s = '<b>' . $s . '</b>';
        return parent::_getCellDefault ($s, $sKey, $aField, $aRow);
    }

    protected function _getCellCollaborators ($mixedValue, $sKey, $aField, $aRow)
    {
        bx_import('BxDolModule');
        $oModule = BxDolModule::getInstance($this->MODULE);
        $s = $oModule->_oTemplate->entryCollaborators ($aRow);
        return parent::_getCellDefault ($s, $sKey, $aField, $aRow);
    }

    protected function _getCellComments ($mixedValue, $sKey, $aField, $aRow) 
    {
        if ($aRow['comments'] - $aRow['read_comments'] > 0)
            $mixedValue = _t('_bx_msg_x_new_messages', 1 + $mixedValue, $aRow['comments'] - $aRow['read_comments']);
        else
            $mixedValue = 1 + $mixedValue;

        return parent::_getCellDefault ($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _delete ($mixedId) 
    {
        bx_import('BxDolModule');
        $oModule = BxDolModule::getInstance($this->MODULE);

        if ($sErrorMsg = $oModule->deleteMessage ($mixedId))
            return false;
    
        return $oModule->_oDb->moveMessage((int)$mixedId, bx_get_logged_profile_id(), BX_MSG_FOLDER_TRASH);
    }
}

/** @} */

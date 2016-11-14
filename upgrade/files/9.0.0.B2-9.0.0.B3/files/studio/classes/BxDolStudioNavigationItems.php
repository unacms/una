<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioNavigationItems extends BxTemplStudioGrid
{
    protected $sModule = '';
    protected $sSet = '';

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->oDb = new BxDolStudioNavigationQuery();

        $sModule = bx_get('module');
        if(!empty($sModule)) {
            $this->sModule = bx_process_input($sModule);
            $this->_aQueryAppend['module'] = $this->sModule;
        }

        $sSet = bx_get('set');
        if(!empty($sSet)) {
            $this->sSet = bx_process_input($sSet);
            $this->_aQueryAppend['set'] = $this->sSet;
        }
    }

    function deleteById($iId)
    {
        $iId = (int)$iId;

        $aItem = array();
        $iItem = $this->oDb->getItems(array('type' => 'by_id', 'value' => $iId), $aItem);
        if($iItem != 1 || empty($aItem))
            return false;

        return $this->deleteByItem($aItem);
    }

    function deleteByItem(&$aItem)
    {
        if(is_numeric($aItem['icon']) && (int)$aItem['icon'] != 0) {
            if(!BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES)->deleteFile((int)$aItem['icon'], 0))
                return false;
        }

        if((int)$this->_delete($aItem['id']) <= 0)
            return false;

        $oLanguage = BxDolStudioLanguagesUtils::getInstance();
        $oLanguage->deleteLanguageString($aItem['title_system']);
        $oLanguage->deleteLanguageString($aItem['title']);

        return true;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(empty($this->sSet))
            return array();

        $this->_aOptions['source'] .= $this->oDb->prepareAsString(" AND `set_name`=?", $this->sSet);
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */

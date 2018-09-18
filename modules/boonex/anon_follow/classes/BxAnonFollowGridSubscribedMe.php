<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    AnonymousFollow Anonymous Follow
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAnonFollowGridSubscribedMe extends BxDolGridSubscribedMe
{
    protected $MODULE;
    protected $_oModule;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->MODULE = 'bx_anon_follow';
        $this->_oModule = BxDolModule::getInstance($this->MODULE);
        parent::__construct ($aOptions, $oTemplate);
        $CNF = $this->_oModule->_oConfig->CNF;
        
        $this->addMarkers(array(
            'join_connections2' => 'INNER JOIN `' . $CNF['TABLE_ENTRIES'] . '` AS `c2` ON `c`.`' . $CNF['FIELD_INITIATOR'] . '` = `c2`.`' . $CNF['FIELD_INITIATOR'] . '` AND `c`.`' . $CNF['FIELD_CONTENT'] . '` = `c2`.`' . $CNF['FIELD_CONTENT'] . '` '
        ));
    }
    
    protected function _getCellName($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = strip_tags(htmlspecialchars_decode($mixedValue));
        $aTitle = array();
        $oProfile = BxDolProfile::getInstance($aRow['id']);
        $aProfile = $oProfile->getInfo();
        $oModule = BxDolModule::getInstance($aProfile['type']);
        $aFields = $oModule->serviceGetSearchableFieldsExtended();
        $aProfileData = $oModule->serviceGetContentInfoById($aProfile['content_id']);
        $sShowFields = $aProfile['type'] == 'bx_persons' ? getParam('bx_anon_follow_persons_fields') : getParam('bx_anon_follow_orgs_fields');
        $aShowFields = explode(',', $sShowFields);
        $sTitle = '';
        foreach($aShowFields as $aField){
            $sValue = "";
            if (isset($aProfileData[$aField]))
            {
                if (isset($aFields[$aField]) && $aFields[$aField]['type'] == 'select' && substr_count($aFields[$aField]['values'], '#!')){
                    $aValuesList = BxDolForm::getDataItems(str_replace('#!', '', 'mylist'));
                    if (isset($aValuesList[$aProfileData[$aField]])){
                        $sValue = $aValuesList[$aProfileData[$aField]];
                    }
                }
                else{
                    $sValue = $aProfileData[$aField];
                }
            }
            if (trim($sValue) != '')
                $aTitle[] = $sValue;
        }
        $sTitle = implode(getParam('bx_anon_follow_fields_separator'), $aTitle);
        if ($sTitle == '')
        {
            $sTitle = _t('_bx_anon_follow_txt_grid_title_default');
        }
        return parent::_getCellDefault ($sTitle, $sKey, $aField, $aRow);
    }
    
    protected function _getCellActions($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault ('', $sKey, $aField, $aRow);
    }
}

/** @} */

<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    MediaManager MediaManager
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMediaInstaller extends BxBaseModTextInstaller
{
    protected $_oModule;
    
    function __construct($aConfig)
    {
        parent::__construct($aConfig);   
    }
    
    public function enable($aParams)
    {
        $aResult = parent::enable($aParams);
        if($aResult['result'])
            $this->_saveSettings();
        return $aResult;
    }
    
    public function disable($aParams)
    {
        $aResult = parent::disable($aParams);
        if($aResult['result'])
            $this->_restoreSettings();
        return $aResult;
    }
    
    private function _saveSettings()
    {
    	$this->oDb->query("INSERT INTO `bx_media_input_settings`(`input_id`, `value`, `values`) SELECT `id`, `value`, `values` FROM `sys_form_inputs` WHERE `type` = 'files'");
        $aInputs = $this->oDb->getAll("SELECT `id`, `value`, `values` FROM `sys_form_inputs` WHERE `type` = 'files'");
        foreach($aInputs as $aInput){
            $aValue = array('bx_media_uploader');
            $aValues = unserialize($aInput['values']);
            $aValues['bx_media_uploader'] = '_bx_media_uploader_title';
            
            $aBindings = array(
                'id' => $aInput['id'],
                'value' => serialize($aValue),
                'values' => serialize($aValues)
            );
            $sQuery = "UPDATE `sys_form_inputs` SET `value` = :value, `values`  = :values WHERE `id` = :id";
            $this->oDb->query($sQuery, $aBindings);
        }
    }
    
    private function _restoreSettings()
    {
        $aInputs = $this->oDb->getAll("SELECT `id`, `input_id`, `value`, `values` FROM `bx_media_input_settings`");
        foreach($aInputs as $aInput){
            $aBindings = array(
                'id' => $aInput['input_id'],
                'value' => $aInput['value'],
                'values' => $aInput['values']
            );
            $sQuery = "UPDATE `sys_form_inputs` SET `value` = :value, `values` = :values WHERE `id` = :id";
            $this->oDb->query($sQuery, $aBindings);
            
            $sQuery = "DELETE FROM `bx_media_input_settings` WHERE `id` = :id";
            $aBindings = array(
                'id' => $aInput['id'],
            );
            $this->oDb->query($sQuery, $aBindings);
        }
    }
}

/** @} */

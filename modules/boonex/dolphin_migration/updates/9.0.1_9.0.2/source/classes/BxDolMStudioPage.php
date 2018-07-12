<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    DolphinMigration  Dolphin Migration
 * @ingroup     UnaModules
 *
 * @{
 */

 require_once('BxDolMTransfers.php');

class BxDolMStudioPage extends BxTemplStudioModule
{    
	/**
	 *  @var ref $_oModule main module reference
	 */
	protected $_oModule;
	
	function __construct($sModule = "", $sPage = "")
    {
		$this -> MODULE = 'bx_dolphin_migration';
		parent::__construct($sModule, $sPage);
	
		$this -> _oModule = BxDolModule::getInstance($sModule);			
		
		$this->aMenuItems = array(
            array('name' => 'settings', 'icon' => 'cogs', 'title' => '_bx_dolphin_migration_cpt_settings'),
            array('name' => 'config', 'icon' => 'exchange-alt', 'title' => '_bx_dolphin_migration_cpt_transfer_data'),
        );	
		
		$this -> _oModule -> _oTemplate -> addStudioJs(array('transfer.js', 'BxDolGrid.js'));
		$this -> _oModule -> _oTemplate -> addStudioCss(array('main.css'));	
     } 
	
	public function saveData($sPath)
	{
		$aConfig = array();		
		if (substr($sPath, strlen($sPath) - 1 ) != DIRECTORY_SEPARATOR)
			$sPath .= DIRECTORY_SEPARATOR;

	   // get settings from Dolphin's header file info;
		$sConfigFile = "{$sPath}inc" . DIRECTORY_SEPARATOR . "header.inc.php";
		if (file_exists($sConfigFile))
		{
			$sFile = @file_get_contents($sConfigFile);
			preg_match_all('/\s*\$(db|dir)\[[\'"](.*)[\'"]\]\s*=\s*[\'"](.*)[\'"];/im', $sFile, $aData);
			for($i=0; $i < sizeof($aData[1]); $i++)
				if (($aData[1][$i] == 'dir' && $aData[2][$i] == 'root') || $aData[1][$i] == 'db')
					$aConfig[$aData[2][$i]] = trim($aData[3][$i], "'");
		}
		
		if (empty($aConfig))
			return MsgBox( _t('_bx_dolphin_migration_error_config_file_was_not_found'));
		
      if(!empty($aConfig)) {
			$this -> _oModule -> _oDb -> saveConfig($aConfig);
			
			$this -> _oModule -> createMigration();
			if ($this -> _oModule -> initDb())
				$this -> _oModule -> createMigration();
			else
				return MsgBox( _t('_bx_dolphin_migration_error_data_was_not_set'), 2);
       }
       else
			return MsgBox( _t('_bx_dolphin_migration_error_data_was_not_set'), 2);
              

		return MsgBox( _t('_bx_dolphin_migration_data_was_set'), 2);			
	}
	
	 protected function getSettings()
    {
        $aForm = array(
			'params' => array(
				'db' => array(
					'submit_name' => 'save'
				)
			),
            'inputs' => array (
                'path' => array(
                    'type' => 'text',
                    'name' => 'path',
                    'caption' => $this -> _oModule -> _oDb -> isConfigInstalled() ? _t('_bx_dolphin_migration_defined_path') : _t('_bx_dolphin_migration_cpt_put_path'),
                    'value' => $this -> _oModule -> _oDb -> getExtraParam('root'),
					'attrs_wrap'
                ),   
				
				'save' => array (
                    'type' => 'submit',
                    'name' => 'save',
                    'value' => _t('_bx_dolphin_migration_cpt_' . ($this -> _oModule -> _oDb -> getExtraParam('root') ? 'update' : 'save'))					
                )
            )
        );
        
		
		if ($this -> _oModule -> _oDb -> isConfigInstalled())
				$aForm['inputs']['save']['attrs'] = array('onclick' => "javascript:return confirm('" . bx_js_string(_t('_bx_dolphin_migration_reupload_data')) . "');");
		
		$oForm = new BxTemplStudioFormView($aForm);	
		
		$sMessage = '';
		if ($oForm -> isSubmitted()) 
			$sMessage = $this -> saveData($oForm -> getCleanValue('path'));		
		
        return $sMessage . $oForm -> getCode();
    }
	
	protected function getConfig()
	{		
		$oGrid = BxDolMTransfers::getObjectInstance('bx_dolphin_migration_transfers'); 
		if ($oGrid)	
			return $oGrid -> getCode(); // print grid object 		
        
		return MsgBox(_t('_bx_dolphin_migration_installation_problem'));
	}
}

/** @} */

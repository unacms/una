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

bx_import('BxDolProfile');
bx_import('BxDolTranscoderImage');
bx_import('BxDolStorage');
	
require_once('BxDolMData.php');	
	
class BxDolMMemLevels extends BxDolMData
{
    public function __construct(&$oMigrationModule, &$mDb)
    {
		parent::__construct($oMigrationModule, $mDb);
		$this -> _sModuleName = 'membership_levels';
		$this -> _sTableWithTransKey = 'sys_acl_levels';
    }

	public function getTotalRecords()
	{
		$iPaidMemberships = $this -> _mDb -> getOne("SELECT COUNT(*) FROM `" . $this -> _oConfig -> _aMigrationModules[$this -> _sModuleName]['table_name'] . "` WHERE `Active` = 'yes' AND `Purchasable` = 'yes'");
		$iMemberWithSetMembership = $this -> _mDb -> getOne("SELECT COUNT(*) FROM `sys_acl_levels_members`");
	    return array($iPaidMemberships, $iMemberWithSetMembership);
	}
	
	public function runMigration()
	{
	    if (!$this -> getTotalRecords())
		{
			  $this -> setResultStatus(_t('_bx_dolphin_migration_no_data_to_transfer'));
	          return BX_MIG_SUCCESSFUL;
		}	
		
		$this -> setResultStatus(_t("_bx_dolphin_migration_started_migration_{$this -> _sModuleName}"));

        $iPaidCount = $this -> transferMembershipLevels();
        $aResult = $this -> _mDb -> getAll("SELECT * FROM `sys_acl_levels_members` ORDER BY `IDMember` ASC");
        foreach($aResult as $iKey => $aValue)
        {
            $iProfileId = $this -> getProfileId($aValue['IDMember']);
            if($iProfileId)
            {
                $sQuery = $this -> _oDb -> prepare(
                    "INSERT INTO
                     		`sys_acl_levels_members`
                     	SET
                     		`IDMember` = ?,
                            `IDLevel` = ?,
                            `DateStarts` = ?,
                            `DateExpires` = ?,
                            `TransactionID` = ?
                     ",
                    $iProfileId,
                    $this-> getMembershipById($aValue['IDLevel']),
                    $aValue['DateStarts'],
                    $aValue['DateExpires'],
                    $aValue['TransactionID']
                );

                if ($this -> _oDb -> query($sQuery))
                    $this -> _iTransferred++;
            }
        }

        $this -> setResultStatus(_t("_bx_dolphin_migration_started_migration_{$this -> _sModuleName}_finished", $iPaidCount, $this -> _iTransferred));
		return BX_MIG_SUCCESSFUL;
    }

    private function getMembershipById($iOldId){
        $aCommon = array(1 => 1, 2 => 3);
        if (isset($aCommon[$iOldId]))
            return $aCommon[$iOldId];

        return $this -> _oDb -> getOne("SELECT `ID` FROM `sys_acl_levels` WHERE `{$this -> _sTransferFieldIdent}` = :id LIMIT 1", array('id' => $iOldId));
    }

	private function transferMembershipIcon($sIconName)
    {
       if (!$sIconName)
           return 'user';

       $iId = 0;
       $sImagePath = $this -> _oDb -> getExtraParam('root') . 'media' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'membership' . DIRECTORY_SEPARATOR . $sIconName;
       if($sImagePath){
			$oStorage = BxDolStorage::getObjectInstance('sys_images');
			$iId = $oStorage->storeFileFromPath($sImagePath, false);
	   }

		return $iId;
    }

    /**
     * Transfer all purchasable membership levels
     */
    private function transferMembershipLevels()
    {
        $iCounter = 0;
        $aMembershipLevels = $this -> _mDb -> getAll("SELECT * FROM `sys_acl_levels` WHERE `Active` = 'yes' AND `Purchasable` = 'yes'");
        if (empty($aMembershipLevels))
            return false;

        $this -> createMIdField();
        $iOrder = $this -> _oDb -> getOne("SELECT MAX(`Order`) FROM `sys_acl_levels` LIMIT 1");
        foreach($aMembershipLevels as $iKey => $aLevel)
        {
            if ($this -> isItemExisted($aLevel['ID'], 'ID'))
                continue;

            $sName = strtolower($aLevel['Name']);
            $sLangKeyName = "_adm_prm_txt_level_{$sName}";
            $sLangKeyDescription = '';
            foreach($this -> _aLanguages as $sLangKey => $sValue) {
                $this->_oLanguage->addLanguageString($sLangKeyName, $aLevel['Name'], $this->_aLanguages[$sLangKey], 1, !$aLevel['Description']);

                if ($aLevel['Description']) {
                    $sLangKeyDescription = '_sys_form_input_description_' . (time() + $iCounter);
                    $this->_oLanguage->addLanguageString($sLangKeyDescription, $aLevel['Description'], $this->_aLanguages[$sLangKey], 1, true);
                }
            }


            $sQuery = $this -> _oDb -> prepare("INSERT INTO `sys_acl_levels` 
                                                SET 
                                                `Name` = ?,
                                                `Icon` = ?,
                                                `Description` = ?,
                                                `Active` = 'yes',
                                                `Order` = ?
                                                 ",
                $sLangKeyName,
                $this -> transferMembershipIcon($aLevel['Icon']),
                $sLangKeyDescription,
                $iOrder++
            );

            if ($this -> _oDb -> query($sQuery) && $iId = $this -> _oDb -> lastId()) {
                  $this -> setMID($iId, $aLevel['ID']);
                  $aPrices = $this -> _mDb -> getAll("SELECT * FROM `sys_acl_level_prices` WHERE `IDLevel` =  :id", array('id' => $aLevel['ID']));
                  foreach($aPrices as $iKey => $aPrice){
                      $sQuery = $this -> _oDb -> prepare("REPLACE INTO `bx_acl_level_prices` 
                                                SET 
                                                    `level_id` = ?,
                                                    `name` = ?,
                                                    `period` = ?,
                                                    `period_unit`='day',                                       
                                                    `price` = ?,
                                                    `order` = ?
                                                 ",
                          $iId,
                          "{$sName}-{$aPrice['Days']}-day",
                          $aPrice['Days'],
                          $aPrice['Price'],
                          $aPrice['id']
                      );

                      $this -> _oDb -> query($sQuery);
                  }

                $iCounter++;
            }
        }

        return $iCounter;
    }

    public function removeContent()
    {
        if (!$this -> _oDb -> isTableExists($this -> _sTableWithTransKey) || !$this -> _oDb -> isFieldExists($this -> _sTableWithTransKey, $this -> _sTransferFieldIdent))
            return false;

        $iNumber = 0;
        $aLevels = $this -> _oDb -> getAll("SELECT * FROM `{$this -> _sTableWithTransKey}` WHERE `{$this -> _sTransferFieldIdent}` !=0");
        if (!empty($aLevels) && $this -> _oDb -> isTableExists('bx_acl_level_prices'))
        {
            foreach($aLevels as $iKey => $aLevel){
                $this->_oDb->query("DELETE FROM `bx_acl_level_prices` WHERE `level_id` = :id", array('id' => $aLevel['ID']));

                if(is_numeric($aLevel['Icon']))
                    BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES)->deleteFile((int)$aLevel['Icon'], 0);

                $this->_oLanguage->deleteLanguageString($aLevel['Name']);
                $this->_oLanguage->deleteLanguageString($aLevel['Description']);
                $this->_oDb->query("DELETE FROM `{$this -> _sTableWithTransKey}` WHERE `{$this -> _sTransferFieldIdent}` = :id", array('id' => $aLevel[$this -> _sTransferFieldIdent]));
                $this->_oDb->query("DELETE FROM `sys_acl_levels_members` WHERE `IDLevel` = :id", array('id' => $aLevel['ID']));
            }
            $iNumber++;
        }

        parent::removeContent();
        return $iNumber;
    }
	
}

	
/** @} */

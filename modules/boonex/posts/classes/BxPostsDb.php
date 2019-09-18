<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Posts Posts
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxPostsDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
    
    public function getCategories($sModule, $iProfileId = 0)
    {
        $sQuery = "";
        if ($iProfileId > 0){
            $sQuery = $this->prepare("SELECT *, `value` as `key`, `Value` as `value` FROM `sys_categories` WHERE `module` = ? AND (`author` = 0 OR `author` = ?) AND (status = 'active') ORDER BY `Value` DESC", $sModule, $iProfileId);
        }
        else{
            $sQuery = $this->prepare("SELECT *, `value` as `key` FROM `sys_categories` WHERE `module` = ? AND (status = 'active') ORDER BY `Value` DESC", $sModule);
        }
        return $this->getAllWithKey($sQuery, 'value');
    }
    
    public function getItemsNumByCategories($sValue)
    {
        $CNF = &$this->_oConfig->CNF;
        $sQuery = "SELECT COUNT(*) FROM `" . $CNF['TABLE_ENTRIES'] . "` WHERE CONCAT(',', `" . $CNF['FIELD_MULTICAT'] . "`, ',') LIKE '%," . $sValue . ",%'";
        return $this->getOne($sQuery);
    }
    
    
    public function getCategoryInfoById($iId)
    { 
        $sQuery = $this->prepare("SELECT * FROM `sys_categories` WHERE `id` = ?", $iId);
        return $this->getFirstRow($sQuery);
    }
    
    public function addCategory($sModule, $iProfileId, $sValue)
    {
        $sStatus = getParam('bx_posts_auto_activation_for_categories') == 'on' ? 'active' : 'hidden';
        $aBindings = array(
            'value' => $sValue,
            'module' => $sModule,
            'author' => $iProfileId 
        );
        $sQuery = "SELECT COUNT(*) FROM `sys_categories` WHERE `value` = :value AND `module` = :module AND (`author` = 0 OR `author` = :author)";

        if((int)$this->getOne($sQuery, $aBindings) == 0){   
            $aBindings['status'] = $sStatus;
            $aBindings['added'] = time();
            $sQuery = "INSERT INTO `sys_categories` (`value`, `module`, `status`, `added`, `author`) VALUES(:value, :module, :status, :added, :author)";
            return $this->query($sQuery, $aBindings);
        }
        return false;
    }
}

/** @} */

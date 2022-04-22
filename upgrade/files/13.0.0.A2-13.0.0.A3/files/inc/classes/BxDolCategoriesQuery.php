<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolCategories
 */
class BxDolCategoriesQuery extends BxDolDb
{
    protected $_sTableCategories;

    public function __construct()
    {
    	parent::__construct();

        $this->_sTableCategories = 'sys_categories';
    }

    public function getData($aParams = array())
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

        $sSelectClause = "`sc`.*";
        $sJoinClause = $sWhereClause = $sGroupClause = "";
        $sOrderClause = "`sc`.`added` ASC";

        if(isset($aParams['count_only']) && $aParams['count_only'] === true) {
            $aMethod['name'] = 'getOne';
            $sSelectClause = "COUNT(`sc`.`id`)";
        }

        switch($aParams['type']) {
            case 'by_module':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'value';
                $aMethod['params'][2] = array(
                    'module' => $aParams['module']
                );

                $sSelectClause = "`sc`.*, `sc`.`value` as `key`, `sc`.`Value` as `value`";
                $sWhereClause = " AND `sc`.`module` = :module AND (`sc`.`status` = 'active')";
                $sOrderClause = "`sc`.`Value` ASC";
                break;
                
            case 'by_module_and_author':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'value';
                $aMethod['params'][2] = array(
                    'module' => $aParams['module'],
                    'author' => $aParams['author'],
                );
                
                $sSelectClause = "`sc`.*, `sc`.`value` as `key`, `sc`.`Value` as `value`";
                $sWhereClause = " AND `sc`.`module` = :module AND (`sc`.`author` = 0 OR `sc`.`author` = :author) AND (`sc`.`status` = 'active')";
                $sOrderClause = "`sc`.`Value` ASC";
                break;    
                
            case 'by_module_with_num':
                $aMethod['name'] = 'getAll';
                $aMethod['params'][1] = array(
                    'module' => $aParams['module']
                );
                
                $oModule = BxDolModule::getInstance($aParams['module']);
                $CNF = $oModule->_oConfig->CNF;
                
                $sSelectClause = "`sc`.`value`, COUNT(`sc`.`id`) as `num`";
                $sJoinClause = "INNER JOIN `sys_categories2objects` `soc` ON `sc`.`id` =  `soc`.`category_id`";
                if (isset($CNF['FIELD_STATUS']))
                    $sJoinClause .= "INNER JOIN `" . $CNF['TABLE_ENTRIES'] . "` `data` ON `soc`.`object_id` =  `data`.`id` AND `data`.`" . $CNF['FIELD_STATUS'] . "` = 'active'";
                $sWhereClause = " AND `sc`.`status` = 'active' AND `soc`.`module` = :module";
                $sGroupClause = "`sc`.`id`";
                $sOrderClause = "`num` DESC";
                break;
                
            case 'by_module&context_with_num':
                $aMethod['name'] = 'getAll';
                $aMethod['params'][1] = array(
                    'module' => $aParams['module'],
                    'context_id' => -$aParams['context_id'],
                );
                
                $oModule = BxDolModule::getInstance($aParams['module']);
                $CNF = $oModule->_oConfig->CNF;
                
                $sSelectClause = "`sc`.`value`, COUNT(`sc`.`id`) as `num`";
                $sJoinClause = " INNER JOIN `sys_categories2objects` `soc` ON `sc`.`id` =  `soc`.`category_id`";
                $sJoinClause .= " INNER JOIN `" . $CNF['TABLE_ENTRIES'] . "` `data` ON `soc`.`object_id` =  `data`.`id` AND `data`.`" . $CNF['FIELD_ALLOW_VIEW_TO'] . "` = :context_id";
                if (isset($CNF['FIELD_STATUS']))
                    $sJoinClause .= " AND `data`.`" . $CNF['FIELD_STATUS'] . "` = 'active' ";
                $sWhereClause = " AND `sc`.`status` = 'active' AND `soc`.`module` = :module";
                $sGroupClause = "`sc`.`id`";
                $sOrderClause = "`num` DESC";
                break;
                
            case 'by_module_and_object':
                $aMethod['name'] = 'getColumn';
                $aMethod['params'][1] = array(
                    'module' => $aParams['module'],
                    'object_id' => $aParams['object_id']
                );
                
                $sSelectClause = "`sc`.`value`";
                $sJoinClause = "INNER JOIN `sys_categories2objects` `soc` ON `sc`.`id` =  `soc`.`category_id`";
                $sWhereClause = " AND `sc`.`status` = 'active' AND `soc`.`module` = :module AND `soc`.`object_id` = :object_id";
                $sOrderClause = "`added` DESC";
                break;
                
            case 'value_and_module':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                    'value' => $aParams['value'],
                    'module' => $aParams['module'],
                );

                $sWhereClause = " AND `sc`.`value` = :value AND `sc`.`module` = :module";
                break;  
                
            case 'id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause = " AND `sc`.`id` = :id";
                break;
        }

        if(!empty($sGroupClause))
            $sGroupClause = "GROUP BY " . $sGroupClause;

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " 
            FROM `" . $this->_sTableCategories . "` AS `sc`" . $sJoinClause . " 
            WHERE 1" . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
    
    public function delete($sModule, $iObject)
    {
        $sQuery = "DELETE FROM `sys_categories2objects` WHERE `module` = :module AND `object_id` = :object_id";
        $aBindings = array(
           'module' => $sModule,
           'object_id' => $iObject,
        );
        $this->query($sQuery, $aBindings);
    }
    
    public function add($sModule, $iProfileId, $sValue, $iObject, $bAutoActivation)
    {
        $sStatus = $bAutoActivation ? 'active' : 'hidden';
        $aBindings = array(
            'value' => $sValue,
            'module' => $sModule,
            'author' => $iProfileId 
        );
        $sQuery = "SELECT id FROM `sys_categories` WHERE `value` = :value AND `module` = :module AND (`author` = 0 OR `author` = :author)";
        $iCategoryId = (int)$this->getOne($sQuery, $aBindings);
        
        if($iCategoryId == 0){   
            $aBindings['status'] = $sStatus;
            $aBindings['added'] = time();
            $sQuery = "INSERT INTO `sys_categories` (`value`, `module`, `status`, `added`, `author`) VALUES(:value, :module, :status, :added, :author)";
            $this->query($sQuery, $aBindings);
            $iCategoryId = $this->lastId();
        }

        $sQuery = "INSERT INTO `sys_categories2objects` (`module`, `object_id`, `category_id`) VALUES(:module, :object_id, :category_id)";
        $aBindings = array(
           'module' => $sModule,
           'object_id' => $iObject,
           'category_id' => $iCategoryId,
        );
        $this->query($sQuery, $aBindings);
        
        return $iCategoryId;
    }
}

/** @} */

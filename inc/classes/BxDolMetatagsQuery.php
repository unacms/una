<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Database queries for metatags objects.
 * @see BxDolMetatag
 */
class BxDolMetatagsQuery extends BxDolDb
{
    protected $_aObject;

    public function __construct($aObject)
    {
        parent::__construct();
        $this->_aObject = $aObject;
    }

    static public function getMetatagsObject ($sObject)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_metatags` WHERE `object` = ?", $sObject);
        $aObject = $oDb->getRow($sQuery);
        if (!$aObject || !is_array($aObject))
            return false;

        return $aObject;
    }



    public function keywordsAdd($mixedContentId, $aKeywords, $bDeletePreviousKeywords = true)
    {
        if ($bDeletePreviousKeywords)
            $this->keywordsDelete($mixedContentId);

        $i = 0;
        foreach ($aKeywords as $sKeyword) {
            $sQuery = $this->prepare("INSERT INTO `{$this->_aObject['table_keywords']}` SET `object_id` = ?, `keyword` = ?", $mixedContentId, trim($sKeyword, '#'));
            $i += ($this->query($sQuery) ? 1 : 0);
        }
        return $i;
    }

    public function keywordsDelete($mixedContentId)
    {
        return $this->metaDelete($this->_aObject['table_keywords'], $mixedContentId);
    }

    public function keywordsGet($mixedContentId)
    {
        $sQuery = $this->prepare("SELECT `keyword` FROM `{$this->_aObject['table_keywords']}` WHERE `object_id` = ?", $mixedContentId);
        return $this->getColumn($sQuery);
    }

    public function keywordsGetSQLParts($sContentTable, $sContentField, $mixedKeyword)
    {
        if(!is_array($mixedKeyword))
            $mixedKeyword = array($mixedKeyword);

        return array(
            'where' => !empty($mixedKeyword) ? ' AND `tt`.`keyword` IN (' . $this->implode_escape($mixedKeyword) . ')' : '',
            'join' => 'INNER JOIN `' . $this->_aObject['table_keywords'] . '` AS `tt` ON `' . $sContentTable . '`.`' . $sContentField . '`=`tt`.`object_id`'
        );
    }

    public function keywordsPopularList($iLimit)
    {
        $sQuery = $this->prepare("SELECT `keyword`, COUNT(*) as `count` FROM `{$this->_aObject['table_keywords']}` GROUP BY `keyword` ORDER BY `count` DESC LIMIT ?", $iLimit);
        return $this->getPairs($sQuery, 'keyword', 'count');
    }    



    public function locationsAdd($mixedContentId, $fLat, $fLng, $sCountryCode, $sState, $sCity, $sZip, $sStreet, $sStreetNumber)
    {
        $this->locationsDelete($mixedContentId);
        if (!$fLat && !$fLng)
            return true;

        $sQuery = $this->prepare("INSERT INTO `{$this->_aObject['table_locations']}` SET `object_id` = ?, `lat` = ?, `lng` = ?, `country` = ?, `state` = ?, `city` = ?, `zip` = ?, `street` = ?, `street_number` = ?", $mixedContentId, $fLat, $fLng, $sCountryCode, $sState, $sCity, $sZip, $sStreet, $sStreetNumber);
        return $this->query($sQuery);
    }
    
    public function locationsDelete($mixedContentId)
    {
        return $this->metaDelete($this->_aObject['table_locations'], $mixedContentId);
    }

    public function locationGet($mixedContentId)
    {
        $sQuery = $this->prepare("SELECT * FROM `{$this->_aObject['table_locations']}` WHERE `object_id` = ?", $mixedContentId);
        return $this->getRow($sQuery);
    }

    public function locationsGetSQLParts($sContentTable, $sContentField, $sCountry = false, $sState = false, $sCity = false, $sZip = false, $aBounds = array())
    {
        $aFields = array('country' => 'sCountry', 'state' => 'sState', 'city' => 'sCity', 'zip' => 'sZip', 'bounds' => 'aBounds');

        $aWhere = array();
        $sWhereBounds = '';
        foreach ($aFields as $sIndex => $sVar) {
            if (!$$sVar)
                continue;

            if ('bounds' == $sIndex) {
                $sWhereBounds = $this->prepareAsString("AND `tl`.`lat` != 0 AND `tl`.`lng` != 0 AND `tl`.`lat` > ? AND `tl`.`lat` < ? AND `tl`.`lng` > ? AND `tl`.`lng` < ?", ${$sVar}['min_lat'], ${$sVar}['max_lat'], ${$sVar}['min_lng'], ${$sVar}['max_lng']);
            } 
            else {
                $aWhere['tl`.`' . $sIndex] = $$sVar;
            }
        }

        return array(
            'where' => $sWhereBounds . (!empty($aWhere) ? ' AND ' . $this->arrayToSQL($aWhere, ' AND ') : ''),
            'join' => 'INNER JOIN `' . $this->_aObject['table_locations'] . '` AS `tl` ON `' . $sContentTable . '`.`' . $sContentField . '`=`tl`.`object_id`'
        );
    }

    protected function metaDelete($sTable, $mixedContentId)
    {
        $sQuery = $this->prepare("DELETE FROM `{$sTable}` WHERE `object_id` = ?", $mixedContentId);
        return $this->query($sQuery);
    }
}

/** @} */

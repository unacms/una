<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
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



    public function keywordsAdd($mixedContentId, $aKeywords)
    {
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

    public function keywordsPopularList($iLimit)
    {
        $sQuery = $this->prepare("SELECT `keyword`, COUNT(*) as `count` FROM `{$this->_aObject['table_keywords']}` GROUP BY `keyword` ORDER BY `count` DESC LIMIT ?", $iLimit);
        return $this->getPairs($sQuery, 'keyword', 'count');
    }    



    public function locationsAdd($mixedContentId, $fLat, $fLng, $sCountryCode, $sState, $sCity, $sZip)
    {
        $this->locationsDelete($mixedContentId);
        if (!$fLat && !$fLng)
            return true;

        $sQuery = $this->prepare("INSERT INTO `{$this->_aObject['table_locations']}` SET `object_id` = ?, `lat` = ?, `lng` = ?, `country` = ?, `state` = ?, `city` = ?, `zip` = ?", $mixedContentId, $fLat, $fLng, $sCountryCode, $sState, $sCity, $sZip);
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


    protected function metaDelete($sTable, $mixedContentId)
    {
        $sQuery = $this->prepare("DELETE FROM `{$sTable}` WHERE `object_id` = ?", $mixedContentId);
        return $this->query($sQuery);
    }
}

/** @} */

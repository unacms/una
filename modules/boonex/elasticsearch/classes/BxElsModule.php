<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    ElasticSearch ElasticSearch
 * @ingroup     UnaModules
 *
 * @{
 */

class BxElsModule extends BxBaseModGeneralModule
{
    protected $_oApi;

    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        bx_import('Api', $aModule);
        $this->_oApi = new BxElsApi();
    }

    public function actionDebug()
    {
        bx_import('Api', $this->_aModule);
        $o = new BxElsApi();

        // search all
        // $mixed = $o->api('/testdata/_search?q=*'); 
        // $mixed = $o->api('/testdata/_search', ['query' => ['match_all' => (object)[]]]);

        // search for the term
        // $mixed = $o->api('/testdata/_search', ['query' => ['simple_query_string' => ['query' => 'palm']]]);
        // $mixed = $o->api('/testdata/_search?q=palm');

        // search in a field
        // $mixed = $o->api('/testdata/_search', ['query' => ['simple_query_string' => ['query' => 'palm', 'fields' => ['name']]]]);

        // delete indexed doc
        // $mixed = $o->api('/testdata/trees/23', [], 'delete'); 

        // get indexed doc
        // $mixed = $o->api('/testdata/trees/23'); 

        // index a doc
        // $mixed = $o->indexData('testdata', 'trees', 23, ['name' => 'apple tree', 'fruits' => 'apples']); 
        // $mixed = $o->indexData('testdata', 'trees', 24, ['name' => 'palm', 'fruits' => 'coconuts']);

        // get indexes
        // $mixed = $o->api('/_cat/indices'); 

        // add index
        // $mixed = $o->api('/testdata', [], 'PUT'); 

        // test
        $mixed = $o->api('/_cat/health'); 

        echo '<pre>';
        if (null === $mixed)
            echo $o->getErrorMsg();
        else
            var_dump($mixed);
        echo '</pre>';
    }

    public function serviceSearch($sTerm, $sIndex = '')
    {
        if(empty($sIndex))
            $sIndex = $this->_oConfig->getIndex();

        $mixedResult = $this->_oApi->searchData($sIndex, $sTerm);
        if(!$mixedResult || !is_array($mixedResult))
            return false;

        return $mixedResult['hits'];
    }

    public function serviceGet($iContentId, $sType, $sIndex = '')
    {
        if(empty($sIndex))
            $sIndex = $this->_oConfig->getIndex();

        return $this->_oApi->getData($sIndex, $sType, $iContentId);
    }

    public function serviceAdd($iContentId, $mixedContenInfo)
    {
        if(is_array($mixedContenInfo)) {
            $sMethod = 'getObjectInstanceByAlert' . bx_gen_method_name($mixedContenInfo['alert_type']);
            $mixedContenInfo = BxDolContentInfo::$sMethod($mixedContenInfo['unit'], $mixedContenInfo['action']);
        }

        if(!($mixedContenInfo instanceof BxDolContentInfo))
            return false;

        $CNF = &$this->_oConfig->CNF;

        $aInfo = array(
            $CNF['FIELD_AUTHOR'] => $mixedContenInfo->getContentAuthor($iContentId),
            $CNF['FIELD_ADDED'] => $mixedContenInfo->getContentDateAdded($iContentId),
            $CNF['FIELD_CHANGED'] => $mixedContenInfo->getContentDateChanged($iContentId),
            $CNF['FIELD_LINK'] => $mixedContenInfo->getContentLink($iContentId),
            $CNF['FIELD_TITLE'] => $mixedContenInfo->getContentTitle($iContentId),
            $CNF['FIELD_TEXT'] => $mixedContenInfo->getContentDesc($iContentId),
        );
        $aInfo = array_merge($aInfo, $mixedContenInfo->getContentInfo($iContentId));

        $mixedResult = $this->_oApi->indexData($this->_oConfig->getIndex(), $mixedContenInfo->getName(), $iContentId, $aInfo);
        if($mixedResult === null)
            return false;

        return $mixedResult['result'] == 'created';
    }

    public function serviceUpdate($iContentId, $mixedContenInfo)
    {
        if(is_array($mixedContenInfo)) {
            $sMethod = 'getObjectInstanceByAlert' . bx_gen_method_name($mixedContenInfo['alert_type']);
            $mixedContenInfo = BxDolContentInfo::$sMethod($mixedContenInfo['unit'], $mixedContenInfo['action']);
        }

        if(!($mixedContenInfo instanceof BxDolContentInfo))
            return false;

        $CNF = &$this->_oConfig->CNF;

        $aInfo = array(
            $CNF['FIELD_AUTHOR'] => $mixedContenInfo->getContentAuthor($iContentId),
            $CNF['FIELD_ADDED'] => $mixedContenInfo->getContentDateAdded($iContentId),
            $CNF['FIELD_CHANGED'] => $mixedContenInfo->getContentDateChanged($iContentId),
            $CNF['FIELD_LINK'] => $mixedContenInfo->getContentLink($iContentId),
            $CNF['FIELD_TITLE'] => $mixedContenInfo->getContentTitle($iContentId),
            $CNF['FIELD_TEXT'] => $mixedContenInfo->getContentDesc($iContentId),
        );
        $aInfo = array_merge($aInfo, $mixedContenInfo->getContentInfo($iContentId));

        $mixedResult = $this->_oApi->updateData($this->_oConfig->getIndex(), $mixedContenInfo->getName(), $iContentId, $aInfo);
        if($mixedResult === null)
            return false;

        return $mixedResult['result'] == 'updated';
    }

    public function serviceDelete($iContentId, $mixedContenInfo)
    {
        if(is_array($mixedContenInfo)) {
            $sMethod = 'getObjectInstanceByAlert' . bx_gen_method_name($mixedContenInfo['alert_type']);
            $mixedContenInfo = BxDolContentInfo::$sMethod($mixedContenInfo['unit'], $mixedContenInfo['action']);
        }

        if(!($mixedContenInfo instanceof BxDolContentInfo))
            return false;

        $CNF = &$this->_oConfig->CNF;

        bx_import('Api', $this->_aModule);
        $oApi = new BxElsApi();

        $mixedResult = $oApi->deleteData($this->_oConfig->getIndex(), $mixedContenInfo->getName(), $iContentId);
        if($mixedResult === null)
            return false;

        return $mixedResult['result'] == 'deleted';
    }
}

/** @} */

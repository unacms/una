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

    public function actionDebug($sType)
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

        // compound searchs
        /*
        $mixed = $o->api('/testdata/_search', array(
        	'query' => array(
            	'bool' => array(
            		'filter' => array(
                        array('term' => array('featured' => '0')),
                        array('term' => array('allow_view_to' => '3')),
                        array(
                            'dis_max' => array(
                                'queries' => array(
                                    array('term' => array('title' => 'uupdd')),
                                    array('term' => array('text' => 'uupdd'))
                                )
                            ),
                        )
                    ),
                ))
            ));

		$mixed = $this->api('/testdata/_search', array(
        	'query' => array(
            	'dis_max' => array(
            		'queries' => array(
            			array('simple_query_string' => array('query' => 'uupd')),
                        array('term' => array('title' => 'uupdd')),
                        array('term' => array('text' => 'uupdd')),
                        array(
                            'bool' => array(
                                'filter' => array(
                                    array('term' => array('featured' => '0')),
                                    array('term' => array('allow_view_to' => '3')),   
                                )
                            ),
                        )
                    ),
                ))
            ));
		*/

        // delete index
        //$mixed = $o->api('/' . $this->_oConfig->getIndex(), array(), 'delete'); 

        // delete indexed doc
        //$mixed = $o->api('/' . $this->_oConfig->getIndex() . '/trees/23', [], 'delete'); 

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
        //$mixed = $o->api('/_cat/health'); 
        
        // mapping put
        //$mixed = $o->api('/' . $this->_oConfig->getIndex() . '/_mapping/bx_forum', array('properties' => array('featured' => array('type' => 'long'))));

        // mapping get
        $mixed = $o->api('/' . $this->_oConfig->getIndex() . '/_mapping' . (!empty($sType) ? '/' . $sType : ''));

        echo '<pre>';
        if (null === $mixed)
            echo $o->getErrorMsg();
        else
            var_dump($mixed);
        echo '</pre>';
    }

    public function serviceSearchSimple($sTerm, $sType = '', $sIndex = '')
    {
        if(empty($sIndex))
            $sIndex = $this->_oConfig->getIndex();

        $mixedResult = $this->_oApi->searchSimple($sIndex, $sType, $sTerm);
        if(!$mixedResult || !is_array($mixedResult))
            return false;

        return $mixedResult['hits'];
    }

    /*
     * Condition #1: keyword search
     * $aCondition = array('val' => 'test');
     * 
     * Condition #2: `featured`='1'
     * $aCondition = array('fld' => 'featured', 'val' => 0);
     * 
     * Condition #3: (`title`='test' OR `text`='test' OR (`featured`='1' AND `allow_view_to`='3'))
     * $aCondition = array('grp' => true, 'opr' => 'OR', 'cnds' => array(
     * 		array('fld' => 'title', 'val' => 'test'),
     * 		array('fld' => 'text', 'val' => 'test'),
     * 		array('grp' => true, 'opr' => 'AND', 'cnds' => array(
     * 			array('fld' => 'featured', 'val' => 1),
     * 			array('fld' => 'allow_view_to', 'val' => 3)
     * 		)),
     * 		array('simple_query_string' => array('query' => 'test')),
     * ));
     * 
     * Condition #4: ((`title`='test' OR `text`='test') AND `featured`='1' AND `allow_view_to`='3')
     * $aCondition = array('grp' => true, 'opr' => 'AND', 'cnds' => array(
     * 		array('grp' => true, 'opr' => 'OR', 'cnds' => array(
     * 			array('fld' => 'title', 'val' => 'test'),
     * 			array('fld' => 'text', 'val' => 'test'),
     * 		)),
     * 		array('fld' => 'featured', 'val' => 0),
     * 		array('fld' => 'allow_view_to', 'val' => 3)
     * ));
     * 
     */
    public function serviceSearchExtended($aCondition, $aSelection = array(), $sType = '', $sIndex = '')
    {
        if(empty($sIndex))
            $sIndex = $this->_oConfig->getIndex();

        $mixedResult = $this->_oApi->searchExtended($sIndex, $sType, $aCondition, $aSelection);
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
            $CNF['FIELD_TEXT'] => $mixedContenInfo->getContentText($iContentId),
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
            $CNF['FIELD_TEXT'] => $mixedContenInfo->getContentText($iContentId),
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

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
        $this->_oApi = new BxElsApi($this);
    }

    public function actionDebug($sType = '', $sIndex = '')
    {
        if(empty($sIndex))
            $sIndex = $this->_oConfig->getIndex();

        // search all
        $mixed = $this->_oApi->api('/' . $sIndex . (!empty($sType) ? '/' . $sType : '') . '/_search?q=*');
        // $mixed = $this->_oApi->api('/testdata/_search', ['query' => ['match_all' => (object)[]]]);

        // search for the term
        // $mixed = $this->_oApi->api('/testdata/_search', ['query' => ['simple_query_string' => ['query' => 'palm']]]);
        // $mixed = $this->_oApi->api('/testdata/_search?q=palm');

        // search in a field
        // $mixed = $this->_oApi->api('/testdata/_search', ['query' => ['simple_query_string' => ['query' => 'palm', 'fields' => ['name']]]]);

        // compound searchs
        /*
        $mixed = $this->_oApi->api('/testdata/_search', array(
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
        //$mixed = $this->_oApi->api('/' . $this->_oConfig->getIndex(), array(), 'delete'); 

        // delete indexed doc
        //$mixed = $this->_oApi->api('/' . $this->_oConfig->getIndex() . '/trees/23', [], 'delete'); 

        // get indexed doc
        // $mixed = $this->_oApi->api('/testdata/trees/23'); 

        // index a doc
        // $mixed = $this->_oApi->indexData('testdata', 'trees', 23, ['name' => 'apple tree', 'fruits' => 'apples']); 
        // $mixed = $this->_oApi->indexData('testdata', 'trees', 24, ['name' => 'palm', 'fruits' => 'coconuts']);

        // get indexes
        // $mixed = $this->_oApi->api('/_cat/indices'); 

        // add index
        // $mixed = $this->_oApi->api('/testdata', [], 'PUT'); 

        // test
        //$mixed = $this->_oApi->api('/_cat/health'); 
        
        // mapping put
        //$mixed = $this->_oApi->api('/' . $sIndex . '/_mapping/bx_forum', array('properties' => array('featured' => array('type' => 'long'))));

        // mapping get
        //$mixed = $this->_oApi->api('/' . $sIndex . '/_mapping' . (!empty($sType) ? '/' . $sType : ''));

        echo '<pre>';
        if (null === $mixed)
            echo $this->_oApi->getErrorMsg();
        else
            var_dump($mixed);
        echo '</pre>';
    }

    /**
     * @page service Service Calls
     * @section bx_elasticsearch ElasticSearch
     * @subsection bx_elasticsearch-other Other
     * @subsubsection bx_elasticsearch-is_configured is_configured
     * 
     * @code bx_srv('bx_elasticsearch', 'is_configured', [...]); @endcode
     * 
     * Checkes whether the module is correctly configured or not. 
     *
     * @return boolean value determining where the module is configured or not.
     * 
     * @see BxElsModule::serviceIsConfigured
     */
    /** 
     * @ref bx_elasticsearch-is_configured "is_configured"
     */
    public function serviceIsConfigured()
    {
        $CNF = &$this->_oConfig->CNF;

        return isset($CNF['PARAM_API_URL']) && getParam($CNF['PARAM_API_URL']) != '';
    }

    /**
     * @page service Service Calls
     * @section bx_elasticsearch ElasticSearch
     * @subsection bx_elasticsearch-search Search
     * @subsubsection bx_elasticsearch-search_simple search_simple
     * 
     * @code bx_srv('bx_elasticsearch', 'search_simple', [...]); @endcode
     * 
     * Perform a simple search by term. Index and Type to search in can be specified.
     *
     * @param $sTerm string value with keyword to search for.
     * @param $sType (optional) if type is specified then the search will be performed in this type only.
     * @param $sIndex (optional) if index is specified then the search will be performed in this index only.
     * @return an array with search results.
     * 
     * @see BxElsModule::serviceSearchSimple
     */
    /** 
     * @ref bx_elasticsearch-search_simple "search_simple"
     */
    public function serviceSearchSimple($sTerm, $sType = '', $sIndex = '')
    {
        if(empty($sIndex))
            $sIndex = $this->_oConfig->getIndex();

        $mixedResult = $this->_oApi->searchSimple($sIndex, $sType, $sTerm);
        if(!$mixedResult || !is_array($mixedResult))
            return false;

        return $mixedResult['hits'];
    }

    /**
     * @page service Service Calls
     * @section bx_elasticsearch ElasticSearch
     * @subsection bx_elasticsearch-search Search
     * @subsubsection bx_elasticsearch-search_extended search_extended
     * 
     * @code bx_srv('bx_elasticsearch', 'search_extended', [...]); @endcode
     * 
     * Perform an extended search by provided conditions. Index and Type to search in can be specified.
     *
     * @param $aCondition an array with conditions to construct search request from.
     * @param $aSelection (optional) an array with custom condition. 
     * @param $sType (optional) if type is specified then the search will be performed in this type only.
     * @param $sIndex (optional) if index is specified then the search will be performed in this index only.
     * @return an array with search results.
     * 
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
     * @see BxElsModule::serviceSearchExtended
     */
    /** 
     * @ref bx_elasticsearch-search_extended "search_extended"
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

    /**
     * @page service Service Calls
     * @section bx_elasticsearch ElasticSearch
     * @subsection bx_elasticsearch-manage Manage
     * @subsubsection bx_elasticsearch-get get
     * 
     * @code bx_srv('bx_elasticsearch', 'get', [...]); @endcode
     * 
     * Get item's data by content ID and type.
     *
     * @param $iContentId integer value with content ID.
     * @param $sType a string with content type.
     * @param $sIndex (optional) if index is specified then the item will be searched in this index only.
     * @return an array with item's data.
     * 
     * @see BxElsModule::serviceGet
     */
    /** 
     * @ref bx_elasticsearch-get "get"
     */
    public function serviceGet($iContentId, $sType, $sIndex = '')
    {
        if(empty($sIndex))
            $sIndex = $this->_oConfig->getIndex();

        return $this->_oApi->getData($sIndex, $sType, $iContentId);
    }

    /**
     * @page service Service Calls
     * @section bx_elasticsearch ElasticSearch
     * @subsection bx_elasticsearch-manage Manage
     * @subsubsection bx_elasticsearch-add add
     * 
     * @code bx_srv('bx_elasticsearch', 'add', [...]); @endcode
     * 
     * Add item to store in Elasticsearch.
     *
     * @param $iContentId integer value with content ID.
     * @param $mixedContentInfo mixed value with content info to store.
     * @param $sIndex (optional) if index is specified then the item will be stored in this index.
     * @return boolean value determining where the item was added or not.
     * 
     * @see BxElsModule::serviceAdd
     */
    /** 
     * @ref bx_elasticsearch-add "add"
     */
    public function serviceAdd($iContentId, $mixedContentInfo, $sIndex = '')
    {
        if(is_array($mixedContentInfo)) {
            $sMethod = 'getObjectInstanceByAlert' . bx_gen_method_name($mixedContentInfo['alert_type']);
            $mixedContentInfo = BxDolContentInfo::$sMethod($mixedContentInfo['unit'], $mixedContentInfo['action']);
        }

        if(!($mixedContentInfo instanceof BxDolContentInfo))
            return false;

        $CNF = &$this->_oConfig->CNF;

        if(empty($sIndex))
            $sIndex = $this->_oConfig->getIndex();

        $aInfo = array(
            $CNF['FIELD_AUTHOR'] => $mixedContentInfo->getContentAuthor($iContentId),
            $CNF['FIELD_ADDED'] => $mixedContentInfo->getContentDateAdded($iContentId),
            $CNF['FIELD_CHANGED'] => $mixedContentInfo->getContentDateChanged($iContentId),
            $CNF['FIELD_LINK'] => $mixedContentInfo->getContentLink($iContentId),
            $CNF['FIELD_TITLE'] => $mixedContentInfo->getContentTitle($iContentId),
            $CNF['FIELD_TEXT'] => $mixedContentInfo->getContentText($iContentId),
        );
        $aInfo = array_merge($aInfo, $mixedContentInfo->getContentInfo($iContentId));

        $mixedResult = $this->_oApi->indexData($sIndex, $mixedContentInfo->getName(), $iContentId, $this->_prepareToIndex($aInfo));
        if($mixedResult === null)
            return false;

        return $mixedResult['result'] == 'created';
    }

    /**
     * @page service Service Calls
     * @section bx_elasticsearch ElasticSearch
     * @subsection bx_elasticsearch-manage Manage
     * @subsubsection bx_elasticsearch-update update
     * 
     * @code bx_srv('bx_elasticsearch', 'update', [...]); @endcode
     * 
     * Update item in Elasticsearch storage.
     *
     * @param $iContentId integer value with content ID.
     * @param $mixedContentInfo mixed value with new content info.
     * @param $sIndex (optional) if index is specified then the item will be updated in this index.
     * @return boolean value determining where the item was updated or not.
     * 
     * @see BxElsModule::serviceUpdate
     */
    /** 
     * @ref bx_elasticsearch-update "update"
     */
    public function serviceUpdate($iContentId, $mixedContentInfo, $sIndex = '')
    {
        if(is_array($mixedContentInfo)) {
            $sMethod = 'getObjectInstanceByAlert' . bx_gen_method_name($mixedContentInfo['alert_type']);
            $mixedContentInfo = BxDolContentInfo::$sMethod($mixedContentInfo['unit'], $mixedContentInfo['action']);
        }

        if(!($mixedContentInfo instanceof BxDolContentInfo))
            return false;

        $CNF = &$this->_oConfig->CNF;

        if(empty($sIndex))
            $sIndex = $this->_oConfig->getIndex();

        $aInfo = array(
            $CNF['FIELD_AUTHOR'] => $mixedContentInfo->getContentAuthor($iContentId),
            $CNF['FIELD_ADDED'] => $mixedContentInfo->getContentDateAdded($iContentId),
            $CNF['FIELD_CHANGED'] => $mixedContentInfo->getContentDateChanged($iContentId),
            $CNF['FIELD_LINK'] => $mixedContentInfo->getContentLink($iContentId),
            $CNF['FIELD_TITLE'] => $mixedContentInfo->getContentTitle($iContentId),
            $CNF['FIELD_TEXT'] => $mixedContentInfo->getContentText($iContentId),
        );
        $aInfo = array_merge($aInfo, $mixedContentInfo->getContentInfo($iContentId));

        $mixedResult = $this->_oApi->updateData($sIndex, $mixedContentInfo->getName(), $iContentId, $this->_prepareToIndex($aInfo));
        if($mixedResult === null)
            return false;

        return $mixedResult['result'] == 'updated';
    }

    /**
     * @page service Service Calls
     * @section bx_elasticsearch ElasticSearch
     * @subsection bx_elasticsearch-manage Manage
     * @subsubsection bx_elasticsearch-delete delete
     * 
     * @code bx_srv('bx_elasticsearch', 'delete', [...]); @endcode
     * 
     * Delete item from Elasticsearch storage.
     *
     * @param $iContentId integer value with content ID.
     * @param $mixedContentInfo mixed value with content info which is needed during deletion.
     * @param $sIndex (optional) if index is specified then the item will be deleted from this index.
     * @return boolean value determining where the item was deleted or not.
     * 
     * @see BxElsModule::serviceDelete
     */
    /** 
     * @ref bx_elasticsearch-delete "delete"
     */
    public function serviceDelete($iContentId, $mixedContentInfo, $sIndex = '')
    {
        if(is_array($mixedContentInfo)) {
            $sMethod = 'getObjectInstanceByAlert' . bx_gen_method_name($mixedContentInfo['alert_type']);
            $mixedContentInfo = BxDolContentInfo::$sMethod($mixedContentInfo['unit'], $mixedContentInfo['action']);
        }

        if(!($mixedContentInfo instanceof BxDolContentInfo))
            return false;

        $CNF = &$this->_oConfig->CNF;

        if(empty($sIndex))
            $sIndex = $this->_oConfig->getIndex();

        $mixedResult = $this->_oApi->deleteData($sIndex, $mixedContentInfo->getName(), $iContentId);
        if($mixedResult === null)
            return false;

        return $mixedResult['result'] == 'deleted';
    }

    /**
     * @page service Service Calls
     * @section bx_elasticsearch ElasticSearch
     * @subsection bx_elasticsearch-manage Manage
     * @subsubsection bx_elasticsearch-index index
     * 
     * @code bx_srv('bx_elasticsearch', 'delete', [...]); @endcode
     * 
     * Index data in Elasticsearch storage.
     *
     * @param $sIndex string value with storage's index name which will be indexed.
     * @param $sType (optional) if type is specified then data in this type will be indexed only.
     * @return boolean value determining where the data was indexed or not.
     * 
     * @see BxElsModule::serviceIndex
     */
    /** 
     * @ref bx_elasticsearch-index "index"
     */
    public function serviceIndex($sIndex, $sType = '')
    {
        if(empty($sIndex))
            $sIndex = $this->_oConfig->getIndex();

        $aTypes = !empty($sType) ? array($sType) : array_keys(BxDolContentInfo::getSystems());
        if(empty($aTypes) || !is_array($aTypes))
            return false;

        foreach($aTypes as $sType) {
            $oContentInfo = BxDolContentInfo::getObjectInstance($sType);
            if(!$oContentInfo)
                continue;

            $aIds = $oContentInfo->getAll(array('type' => 'all_ids'));
            if(empty($aIds) || !is_array($aIds))
                continue;

            foreach($aIds as $iId)
                $this->serviceAdd($iId, $oContentInfo);
        }

        return true;
    }

    protected function _prepareToIndex($mixed)
    {
        if(!is_array($mixed))
            return strip_tags($mixed);

        foreach($mixed as $mixedKey => $mixedValue)
            $mixed[$mixedKey] = strip_tags($mixedValue);

        return $mixed;
    }
}

/** @} */

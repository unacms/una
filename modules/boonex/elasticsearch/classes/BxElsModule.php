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
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
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
    
    public function serviceAdd($sString, $iLanguageId = 0, $iOrig = 1)
    {
    }

    public function serviceEdit($iKeyId, $sString, $iLanguageId = 0, $bMarkAsOrig = false, $bRemoveOther = false)
    {
    }

    public function serviceDelete($iKeyId)
    {
    }
}

/** @} */

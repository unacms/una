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

class BxElsResponse extends BxDolAlertsResponse
{
    protected $_sModule;
    protected $_oModule;

    public function __construct()
    {
        parent::__construct();

        $this->_sModule = 'bx_elasticsearch';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    public function response($oAlert)
    {
        $aAlertTypes = array('add', 'update', 'delete');
        foreach($aAlertTypes as $sAlertType) {
            $sMethodType = bx_gen_method_name($sAlertType);

            $sMethod = 'getObjectInstanceByAlert' . $sMethodType;
            $oContentInfo = BxDolContentInfo::$sMethod($oAlert->sUnit, $oAlert->sAction);
            if(!$oContentInfo)
                continue;

            $sMethod = 'service' . $sMethodType;
            $iContentId = $oAlert->iObject;
            if(in_array($oAlert->sAction, array('commentPost', 'commentUpdated', 'commentRemoved')))
                $iContentId = $oAlert->aExtras['comment_id'];
            
            $this->_oModule->$sMethod($iContentId, $oContentInfo);
        }       
    }
}

/** @} */

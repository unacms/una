<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolRelation extends BxDolConnection
{
    protected static $_sParamEnabled = 'sys_relations_enable';
    protected static $_sParamTypes = 'sys_relations';

    protected $_sParamDivider;
    protected $_sPreList;

    protected function __construct($aObject)
    {
        parent::__construct($aObject);

        $this->_oQuery = new BxDolRelationQuery($aObject);

        $this->_sParamDivider = '_';
        $this->_sPreList = 'sys_relations';
    }

    /**
     * Whether the Relations feature is enabled globaly or not.
     * @return boolean
     */
    public static function isEnabled()
    {
        return getParam(self::$_sParamEnabled) == 'on';
    }

    /**
     * Add new relation.
     * @param $mixedContent content to make relation with or an array with content and relation type
     * @return array
     */
    public function actionAdd($mixedContent = 0, $iInitiator = false)
    {
        if(empty($mixedContent))
            $mixedContent = bx_process_input($_POST['id'], BX_DATA_INT);

        $iContent = 0;
        $iRelation = 0;
        if(is_array($mixedContent)) {
            $iContent = (int)$mixedContent['content'];
            $iRelation = (int)$mixedContent['relation'];
        }
        else
            $iContent = (int)$mixedContent;

        $iInitiator = $iInitiator ? (int)$iInitiator : (int)bx_get_logged_profile_id();

        $aResult = parent::actionAdd($iContent, $iInitiator);
        if(empty($iRelation) || (isset($aResult['err']) && $aResult['err'] !== false))
            return $aResult;

        $this->_oQuery->updateConnection($iInitiator, $iContent, array(
            'relation' => $iRelation
        ));

        return $aResult;
    }
    
    /**
     * Confirm relation request without creation of retroactive relation.
     * @param $iContent content to make relation with
     * @return array
     */
    public function actionConfirm($iContent = 0, $iInitiator = false)
    {
        if(!$iContent)
            $iContent = bx_process_input($_POST['id'], BX_DATA_INT);

        return $this->_action($iContent, $iInitiator ? $iInitiator : bx_get_logged_profile_id(), 'confirmConnection', '_sys_conn_err_connection_does_not_exists');
    }

    /**
     * Remove relation without removing a retroactive relation. This method is wrapper for @see removeConnection to be called from @see conn.php upon AJAX request to this file.
     * @param $iContent content to make connection to, in most cases some content id, or other profile id in case of friends
     * @return array
     */
    public function actionRemove($iContent = 0, $iInitiator = false)
    {
        if(!$iContent)
            $iContent = bx_process_input($_POST['id'], BX_DATA_INT);

        return $this->_action($iInitiator ? $iInitiator : bx_get_logged_profile_id(), $iContent, 'removeConnection', '_sys_conn_err_connection_does_not_exists');
    }

    /**
     * Add new connection.
     * @param $iInitiator initiator of the connection, in most cases some profile id
     * @param $iContent content to make connection to, in most cases some content id, or other profile id in case of friends
     * @return true - if connection was added, false - if connection already exists or error occured
     */
    public function addConnection($iInitiator, $iContent, $aParams = [])
    {
        $bResult = parent::addConnection($iInitiator, $iContent, $aParams);
        if($bResult && !empty($aParams['relation']))
            $this->_oQuery->updateConnection($iInitiator, $iContent, [
                'relation' => (int)$aParams['relation']
            ]);

        return $bResult;
    }

    /**
     * Confirm relation request without creation of retroactive relation.
     * @param $iInitiator initiator of the connection, in most cases some profile id
     * @param $iContent content to make connection to, in most cases some content id, or other profile id in case of friends
     * @return true - if connection was added, false - if connection already exists or error occured
     */
    public function confirmConnection($iInitiator, $iContent)
    {
        $iMutual = 1;
        if(!$this->_oQuery->updateConnectionMutual((int)$iInitiator, (int)$iContent, $iMutual))
            return false;

        bx_alert($this->_sObject, 'connection_confirmed', 0, bx_get_logged_profile_id(), array(
            'initiator' => (int)$iInitiator,
            'content' => (int)$iContent,
            'mutual' => (int)$iMutual,
            'object' => $this,
        ));

        return true;
    }

    /**
     * Compound function, which calls getCommonContentExt, getConnectedContentExt or getConnectedInitiatorsExt depending on $sContentType
     * @param $sContentType content type to get BX_CONNECTIONS_CONTENT_TYPE_CONTENT, BX_CONNECTIONS_CONTENT_TYPE_INITIATORS or BX_CONNECTIONS_CONTENT_TYPE_COMMON
     * @param $iId1 one content or initiator
     * @param $iId2 second content or initiator only in case of BX_CONNECTIONS_CONTENT_TYPE_COMMON content type
     * @param $isMutual get mutual connections only
     * @return array of available connections
     */
    public function getConnectionsAsArrayExt($sContentType, $iId1, $iId2, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE)
    {
        if (BX_CONNECTIONS_CONTENT_TYPE_COMMON == $sContentType)
            return $this->getCommonContentExt($iId1, $iId2, $isMutual, $iStart, $iLimit, $iOrder);

        if (BX_CONNECTIONS_CONTENT_TYPE_INITIATORS == $sContentType)
            $sMethod = 'getConnectedInitiatorsExt';
        else
            $sMethod = 'getConnectedContentExt';

        return $this->$sMethod($iId1, $isMutual, $iStart, $iLimit, $iOrder);
    }

    /**
     * Get common content (full info) between two initiators
     * @param $iInitiator1 one initiator
     * @param $iInitiator2 second initiator
     * @param $isMutual get mutual connections only
     * @return array of available connections
     */
    public function getCommonContentExt($iInitiator1, $iInitiator2, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE)
    {
        return $this->_oQuery->getCommonContentExt($iInitiator1, $iInitiator2, $isMutual, $iStart, $iLimit, $iOrder);
    }

    /**
     * Get connected initiators (full info)
     * @param $iContent content of the connection
     * @param $isMutual get mutual connections only
     * @return array of available connections
     */
    public function getConnectedInitiatorsExt($iContent, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE)
    {
        return $this->_oQuery->getConnectedInitiatorsExt($iContent, $isMutual, $iStart, $iLimit, $iOrder);
    }

    /**
     * Get connected content (full info)
     * @param $iInitiator initiator of the connection
     * @param $isMutual get mutual connections only
     * @return array of available connections
     */
    public function getConnectedContentExt($iInitiator, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE)
    {
        return $this->_oQuery->getConnectedContentExt($iInitiator, $isMutual, $iStart, $iLimit, $iOrder);
    }

    /**
     * Check whether connection between Initiator and Content can be established.
     */
    public function checkAllowedConnect($iInitiator, $iContent, $isPerformAction = false, $isMutual = false, $isInvertResult = false, $isSwap = false)
    {
        if(!$this->isRelationAvailable($iInitiator, $iContent))
            return _t('_sys_txt_access_denied');

        $mixedResult = $this->checkAllowedConnectCustom($iInitiator, $iContent, $isPerformAction, $isMutual, $isInvertResult, $isSwap);
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return $mixedResult;

        return parent::checkAllowedConnect($iInitiator, $iContent, $isPerformAction, $isMutual, $isInvertResult, $isSwap);
    }

    /**
     * Custom check action method which can be overwritten.
     * Currently only friends can establish relations.
     */
    public function checkAllowedConnectCustom($iInitiator, $iContent, $isPerformAction = false, $isMutual = false, $isInvertResult = false, $isSwap = false)
    {
        if(!BxDolConnection::getObjectInstance('sys_profiles_friends')->isConnected($iInitiator, $iContent, true))
            return _t('_sys_txt_access_denied');

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function isRelationAvailableFromProfile($sModule)
    {
        $sModule .= $this->_sParamDivider;

        $aTypes = $this->getRelationTypes();
        foreach($aTypes as $sType)
            if(substr($sType, 0, strlen($sModule)) == $sModule)
                return true;

        return false;
    }

    public function isRelationAvailableWithProfile($sModule)
    {
        $sModule = $this->_sParamDivider . $sModule;

        $aTypes = $this->getRelationTypes();
        foreach($aTypes as $sType)
            if(substr($sType, -strlen($sModule)) == $sModule)
                return true;

        return false;
    }
    
    public function isRelationAvailableBetweenProfiles($sModuleInitiator, $sModuleContent)
    {
        $aTypes = $this->getRelationTypes();
        if(in_array($sModuleInitiator . $this->_sParamDivider . $sModuleContent, $aTypes))
            return true;

        return false;
    }

    public function isRelationAvailable($iInitiator, $iContent)
    {
        $oInitiator = BxDolProfile::getInstance($iInitiator);
        $oContent = BxDolProfile::getInstance($iContent);
        if(!$oInitiator || !$oContent)
            return false;

        return $this->isRelationAvailableBetweenProfiles($oInitiator->getModule(), $oContent->getModule());
    }

    public function getRelations($iInitiator, $iContent, &$aSuggestions = array())
    {
        $aRelations = BxDolFormQuery::getDataItems($this->_sPreList, false, BX_DATA_VALUES_ALL);

        bx_alert($this->_sObject, 'get_relations', 0, bx_get_logged_profile_id(), array(
            'initiator' => (int)$iInitiator,
            'content' => (int)$iContent,
            'pre_list' => $this->_sPreList,
            'relations' => &$aRelations
        ));

        if($this->isConnected($iContent, $iInitiator)) {
            $iRelation = $this->getRelation($iContent, $iInitiator);
            if(!empty($iRelation) && !empty($aRelations[$iRelation]['Data']))
                $aSuggestions = unserialize($aRelations[$iRelation]['Data']);
        }

        return $aRelations;
    }

    public function getRelation($iInitiator, $iContent)
    {
        $aConnection = $this->_oQuery->getConnection ($iInitiator, $iContent);
        if(empty($aConnection) || !is_array($aConnection))
            return 0;

        return (int)$aConnection['relation'];
    }

    public function getRelationTranslation($iValue, $sUseValues = BX_DATA_VALUES_DEFAULT)
    {
        $aRelations = BxDolFormQuery::getDataItems($this->_sPreList, false, $sUseValues);

        return !empty($aRelations[$iValue]) ? $aRelations[$iValue] : _t('_uknown');
    }

    public function getRelationTypes()
    {
        $sParam = getParam(self::$_sParamTypes);
        if(empty($sParam))
            return array();

        return explode(',', $sParam);
    }
}
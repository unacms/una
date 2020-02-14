<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

class BxForumGrid extends BxTemplGrid
{
    protected $_oModule;
    protected $_sDefaultSource; 

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_oModule = BxDolModule::getInstance('bx_forum');

        parent::__construct ($aOptions, $oTemplate);

        $this->_sDefaultSortingOrder = 'DESC';
        $this->_sDefaultSource = $this->_aOptions['source'];
    }

    public function setBrowseParams($aParams)
    {
        parent::setBrowseParams($aParams);

    	$sField = 'added';
    	if(!empty($this->_aBrowseParams['type']))
            switch($this->_aBrowseParams['type']) {
                case 'new':
                case 'index':
                case 'author':
                case 'favorite':
                case 'category':
                    $sField = 'added';
                    break;

                case 'featured':
                    $sField = 'featured';
                    break;

                case 'updated':
                    $sField = 'changed';
                    break;

                case 'latest':
                    $sField = 'lr_timestamp';
                    break;

                case 'popular':
                    $sField = 'views';
                    break;
            }

        $this->_aOptions['field_order'] = $sField;
        $this->_aOptions['paginate_per_page'] = !empty($this->_aBrowseParams['per_page']) ? (int)$this->_aBrowseParams['per_page'] : (int)$this->_oModule->_oDb->getParam('bx_forum_per_page_browse');
    }

    public function getCode ($isDisplayHeader = true)
    {
        $sCode = parent::getCode($isDisplayHeader);
        if(empty($sCode))
            return '';

    	return $this->_oModule->_oTemplate->parseHtmlByName('units.html', array(
            'code' => $sCode,
            'js_code' => $this->_oModule->_oTemplate->getJsCode('main')
        ));
    }

    protected function _getActionAdd ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if($this->_oModule->checkAllowedAdd() !== CHECK_ACTION_RESULT_ALLOWED)
            return '';

        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->_oModule->_oConfig->CNF['URI_ADD_ENTRY']);

        unset($a['attr']['bx_grid_action_independent']);
        $a['attr'] = array_merge($a['attr'], array(
    		"onclick" => "window.open('" . $sUrl . "','_self');"
    	));

        return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getRowHead ()
    {
    	return array();
    }

    protected function _getCellDefault ($mixedValue, $sKey, $aField, $aRow)
    {
        $aField['attr_cell']['class'] = 'bx-grid-table-cell-' . $sKey;

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellAuthor($mixedValue, $sKey, $aField, $aRow)
    {
    	$mixedValue = $this->_oModule->_oTemplate->getEntryAuthor($aRow);

        return self::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellText($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_oModule->_oTemplate->getEntryPreview($aRow);

        return self::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();

        $this->_oModule->_oTemplate->addJs(array('main.js'));
        $this->_oModule->_oTemplate->addCss(array('main.css', 'main-media-phone.css', 'main-media-tablet.css', 'main-media-desktop.css'));
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
    	$sSelectClause = $sJoinClause = $sWhereClause = $sGroupByClause = '';

    	//--- Check status
    	$sWhereClause .= " AND `" . $this->_oModule->_oConfig->CNF['FIELD_STATUS'] . "`='active'";
    	$sWhereClause .= " AND `" . $this->_oModule->_oConfig->CNF['FIELD_STATUS_ADMIN'] . "`='active'";

    	//--- Check privacy
    	$iAuthorId = 0;
    	if(!empty($this->_aBrowseParams['author'])) {
            $oProfileAuthor = BxDolProfile::getInstance((int)$this->_aBrowseParams['author']);
            if($oProfileAuthor)
                $iAuthorId = $oProfileAuthor->id();
    	}

        $sPrivacy = $this->_oModule->_oConfig->CNF['OBJECT_PRIVACY_VIEW'];
        $oPrivacy = BxDolPrivacy::getObjectInstance($sPrivacy);
        $aCondition = $oPrivacy ? $oPrivacy->getContentPublicAsSQLPart($iAuthorId) : array();
        if(isset($aCondition['join']))
            $sJoinClause .= $aCondition['join'];
        if(isset($aCondition['where']))
            $sWhereClause .= $aCondition['where'];

        //--- Check browse params
        if(!empty($this->_aBrowseParams['select']) && is_array($this->_aBrowseParams['select'])) {
            $sSelectClauseBrowse = $this->_getSqlSelectFromGroup($this->_aBrowseParams['select']);
            if(!empty($sSelectClauseBrowse))
                $sSelectClause .= ", " . $sSelectClauseBrowse;
        }

        if(!empty($this->_aBrowseParams['join']) && is_array($this->_aBrowseParams['join'])) {
            $sJoinClauseBrowse = $this->_getSqlJoinFromGroup($this->_aBrowseParams['join']);
            if(!empty($sJoinClauseBrowse))
                $sJoinClause .= " " . $sJoinClauseBrowse;
        }

        if(!empty($this->_aBrowseParams['where']) && is_array($this->_aBrowseParams['where'])) {
            $sWhereClauseBrowse = $this->_getSqlWhereFromGroup($this->_aBrowseParams['where']);
            if(!empty($sWhereClauseBrowse))
                $sWhereClause .= " AND " . $sWhereClauseBrowse;
        }

        if(!empty($this->_aBrowseParams['group_by']) && is_array($this->_aBrowseParams['group_by'])) {
            $sGroupByClauseBrowse = $this->_getSqlGroupByFromGroup($this->_aBrowseParams['group_by']);
            if(!empty($sGroupByClauseBrowse))
                $sGroupByClause .= " GROUP BY " . $sGroupByClauseBrowse;
        }

        $this->_aOptions['source'] = sprintf($this->_sDefaultSource, $sSelectClause, $sJoinClause, $sWhereClause, $sGroupByClause);
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    protected function _getDataSqlOrderClause($sOrderByFilter, $sOrderField, $sOrderDir, $bFieldsOnly = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

    	$sOrder = parent::_getDataSqlOrderClause($sOrderByFilter, $sOrderField, $sOrderDir, true);
        if(!empty($this->_aBrowseParams['type']))
            switch($this->_aBrowseParams['type']) {
                case 'top':
                    $aPartsUp = $aPartsDown = array(0);
                    if(($oVote = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], 0, false)) !== false && $oVote->isEnabled()) {
                        $aVote = $oVote->getSystemInfo();
                        if(!empty($aVote['trigger_table']) && !empty($aVote['trigger_field_count']))
                            $aPartsUp[] = '`' . $aVote['trigger_table'] . '`.`' . $aVote['trigger_field_count'] . '`';
                    }

                    if(($oReaction = BxDolVote::getObjectInstance($CNF['OBJECT_REACTIONS'], 0, false)) !== false && $oReaction->isEnabled()) {
                        $aReaction = $oReaction->getSystemInfo();
                        if(!empty($aReaction['trigger_table']) && !empty($aReaction['trigger_field_count']))
                            $aPartsUp[] = '`' . $aReaction['trigger_table'] . '`.`' . $aReaction['trigger_field_count'] . '`';
                    }

                    if(($oScore = BxDolScore::getObjectInstance($CNF['OBJECT_SCORES'], 0, false)) !== false && $oScore->isEnabled()) {
                        $aScore = $oScore->getSystemInfo();
                        if(!empty($aScore['trigger_table']) && !empty($aScore['trigger_field_cup']) && !empty($aScore['trigger_field_cdown'])) {
                            $aPartsUp[] = '`' . $aScore['trigger_table'] . '`.`' . $aScore['trigger_field_cup'] . '`';
                            $aPartsDown[] = '`' . $aScore['trigger_table'] . '`.`' . $aScore['trigger_field_cdown'] . '`';
                        }
                    }

                    $sOrder = pow(10, 8) . ' * ((' . implode(' + ', $aPartsUp) . ') - (' . implode(' + ', $aPartsDown) . ')) / (UNIX_TIMESTAMP() - `' . $this->_aOptions['table'] . '`.`' . $CNF['FIELD_ADDED'] . '`) ' . $this->_sDefaultSortingOrder;
                    break;

                case 'partaken':
                    $sOrder = 'MAX(`tco`.`cmt_time`) ' . $this->_sDefaultSortingOrder;
                    break;
            }           

    	return " ORDER BY `" . $CNF['FIELD_STICK'] . "` DESC, " . $sOrder;
    }

    protected function _getSqlSelectFromGroup($aGrp)
    {
        if(!isset($aGrp['grp']) || (bool)$aGrp['grp'] !== true)
            return $this->_getSqlSelectFromCondition($aGrp);

        $sResult = "";
        if(empty($aGrp['cnds']) || !is_array($aGrp['cnds']))
            return $sResult;

        $sOprGrp = ", ";
        foreach($aGrp['cnds'] as $aCnd) {
            $sResultCnd = $this->_getSqlSelectFromCondition($aCnd);
            if(!empty($sResultCnd))
                $sResult .= $sOprGrp . $sResultCnd;
        }

        return trim($sResult, $sOprGrp);
    }

    protected function _getSqlSelectFromCondition($aCnd)
    {
    	$sResult = "";
    	if(!isset($aCnd['fld']))
            return $sResult;

        $sResult = "`" . $aCnd['fld'] . "`";
        if(!empty($aCnd['tbla']))
            $sResult = "`" . $aCnd['tbla'] . "`." . $sResult;
        else if(!empty($aCnd['tbl']))
            $sResult = "`" . $aCnd['tbl'] . "`." . $sResult;

        return $sResult;
    }

    protected function _getSqlJoinFromGroup($aGrp)
    {
        if(!isset($aGrp['grp']) || (bool)$aGrp['grp'] !== true)
            return $this->_getSqlJoinFromCondition($aGrp);

        $sResult = "";
        if(empty($aGrp['cnds']) || !is_array($aGrp['cnds']))
            return $sResult;

        $sOprGrp = " ";
        foreach($aGrp['cnds'] as $aCnd) {
            $sResultCnd = $this->_getSqlJoinFromCondition($aCnd);
            if(!empty($sResultCnd))
                $sResult .= $sOprGrp . $sResultCnd;
        }

        return trim($sResult, $sOprGrp);
    }

    protected function _getSqlJoinFromCondition($aCnd)
    {
    	$sResult = "";
    	if(!isset($aCnd['tp'], $aCnd['tbl1'], $aCnd['fld1'], $aCnd['fld2']))
            return $sResult;

        $sTbl1A = $aCnd['tbl1'];
        if(!empty($aCnd['tbl1a']))
            $sTbl1A = $aCnd['tbl1a'];

        $sField1 = "`" . $sTbl1A . "`.`" . $aCnd['fld1'] . "`";

        $sField2 = "`" . $aCnd['fld2'] . "`";
        if(!empty($aCnd['tbl2a']))
            $sField2 = "`" . $aCnd['tbl2a'] . "`." . $sField2;
        else if(!empty($aCnd['tbl2']))
            $sField2 = "`" . $aCnd['tbl2'] . "`." . $sField2;

        return $aCnd['tp'] . " JOIN `" . $aCnd['tbl1'] . "` AS `" . $sTbl1A . "` ON (" . $sField1 . " = " . $sField2 . ")";
    }

    protected function _getSqlWhereFromGroup($aGrp)
    {
        if(!isset($aGrp['grp']) || (bool)$aGrp['grp'] !== true)
            return $this->_getSqlWhereFromCondition($aGrp);

        $sResult = "";
        if(!isset($aGrp['opr'], $aGrp['cnds']) || empty($aGrp['cnds']) || !is_array($aGrp['cnds']))
            return $sResult;

        $sOprGrp = " " . $aGrp['opr'] . " ";
        foreach($aGrp['cnds'] as $aCnd) {
            $sMethod = '_getSqlWhereFrom' . (isset($aGrp['grp']) && (bool)$aGrp['grp'] === true ? 'Group' : 'Condition');
            $sResultCnd = $this->$sMethod($aCnd);
            if(!empty($sResultCnd))
                $sResult .= $sOprGrp . $sResultCnd;
        }

        $sResult = trim($sResult, $sOprGrp);
        if(!empty($sResult))
            $sResult = "(" . $sResult . ")";

    	return $sResult;
    }

    protected function _getSqlWhereFromCondition($aCnd)
    {
        $sResult = "";
        if(!isset($aCnd['fld'], $aCnd['val'], $aCnd['opr']))
            return $sResult;

        $sMethod = '_getSqlWhereFromCondition' . bx_gen_method_name($aCnd['fld']);
        if(method_exists($this, $sMethod)) {
            $mixedResult = $this->$sMethod($aCnd);
            if(is_array($mixedResult) && isset($mixedResult['fld'], $mixedResult['val'], $mixedResult['opr']))
                $aCnd = $mixedResult;
            else
                return $mixedResult;
        }
        
        $sField = "`" . $aCnd['fld'] . "`";
        if(!empty($aCnd['tbl']))
            $sField = "`" . $aCnd['tbl'] . "`." . $sField;

        switch($aCnd['opr']) {
            case 'IN':
                if(empty($aCnd['val']) || !is_array($aCnd['val']))
                    break;

                $sResult .= $sField . " IN (" . $this->_oModule->_oDb->implode_escape($aCnd['val']) . ")";
                break;

            case 'LIKE':
                $sResult .= $sField . " LIKE " . $this->_oModule->_oDb->escape('%' . $aCnd['val'] . '%');
                break;

            default:
                $sResult .= $sField . " " . $aCnd['opr'] . " " . $this->_oModule->_oDb->escape($aCnd['val']);
        }

        return $sResult;
    }

    protected function _getSqlGroupByFromGroup($aGrp)
    {
        if(!isset($aGrp['grp']) || (bool)$aGrp['grp'] !== true)
            return $this->_getSqlGroupByFromCondition($aGrp);

        $sResult = "";
        if(empty($aGrp['cnds']) || !is_array($aGrp['cnds']))
            return $sResult;

        $sOprGrp = ", ";
        foreach($aGrp['cnds'] as $aCnd) {
            $sResultCnd = $this->_getSqlGroupByFromCondition($aCnd);
            if(!empty($sResultCnd))
                $sResult .= $sOprGrp . $sResultCnd;
        }

        return trim($sResult, $sOprGrp);
    }

    protected function _getSqlGroupByFromCondition($aCnd)
    {
    	$sResult = "";
    	if(!isset($aCnd['fld']))
            return $sResult;

        $sResult = "`" . $aCnd['fld'] . "`";
        if(!empty($aCnd['tbla']))
            $sResult = "`" . $aCnd['tbla'] . "`." . $sResult;
        else if(!empty($aCnd['tbl']))
            $sResult = "`" . $aCnd['tbl'] . "`." . $sResult;

        return $sResult;
    }

    protected function _getSqlWhereFromConditionAuthorComment($aCnd)
    {
        $aEntriesIds = $this->_oModule->_oDb->getComments(array('type' => 'entries_author_search', 'author' => $aCnd['val']));
        if(!empty($aEntriesIds) && is_array($aEntriesIds))
            return array('fld' => 'id', 'val' => $aEntriesIds, 'opr' => 'IN');

        return '';
    }

    protected function _getSqlWhereFromConditionKeywordComment($aCnd)
    {
        $aEntriesIds = $this->_oModule->_oDb->getComments(array('type' => 'entries_keyword_search', 'keyword' => $aCnd['val']));
        if(!empty($aEntriesIds) && is_array($aEntriesIds))
            return array('fld' => 'id', 'val' => $aEntriesIds, 'opr' => 'IN');

        return '';
    }
}

/** @} */

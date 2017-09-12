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

				case 'top':
					$sField = 'comments';
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
    	return $this->_oModule->_oTemplate->getJsCode('main') . parent::getCode($isDisplayHeader);
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

    protected function _getCellAuthor($mixedValue, $sKey, $aField, $aRow)
    {
    	$mixedValue = $this->_oModule->_oTemplate->getEntryAuthor($aRow);

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellLrTimestamp($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_oModule->_oTemplate->getEntryPreview($aRow);

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellComments($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_oModule->_oTemplate->getEntryLabel($aRow, array('show_count' => 1));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();

        $this->_oModule->_oTemplate->addJs(array('main.js'));
        $this->_oModule->_oTemplate->addCss(array('main-media-tablet.css', 'main-media-desktop.css'));
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
    	$sJoinClause = $sWhereClause = '';

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
        if(!empty($this->_aBrowseParams['join']) && is_array($this->_aBrowseParams['join'])) {
			$sJoinClauseBrowse = $this->_getSqlJoinGroup($this->_aBrowseParams['join']);
			if(!empty($sJoinClauseBrowse))
				$sJoinClause .= " " . $sJoinClauseBrowse;
		}

		if(!empty($this->_aBrowseParams['where']) && is_array($this->_aBrowseParams['where'])) {
			$sWhereClauseBrowse = $this->_getSqlWhereFromGroup($this->_aBrowseParams['where']);
			if(!empty($sWhereClauseBrowse))
				$sWhereClause .= " AND " . $sWhereClauseBrowse;
		}

		$this->_aOptions['source'] = sprintf($this->_sDefaultSource, $sJoinClause, $sWhereClause);
		return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    protected function _getDataSqlOrderClause($sOrderByFilter, $sOrderField, $sOrderDir, $bFieldsOnly = false)
    {
    	$sOrder = parent::_getDataSqlOrderClause($sOrderByFilter, $sOrderField, $sOrderDir, true);

    	return " ORDER BY `" . $this->_oModule->_oConfig->CNF['FIELD_STICK'] . "` DESC, " . $sOrder;
    }

    protected function _getSqlJoinGroup($aGrp)
    {
    	if(!isset($aGrp['grp']) || (bool)$aGrp['grp'] !== true)
			return $this->_getSqlJoinCondition($aGrp);

		$sResult = "";
    	if(empty($aGrp['cnds']) || !is_array($aGrp['cnds']))
    		return $sResult;

		$sOprGrp = " ";
    	foreach($aGrp['cnds'] as $aCnd) {
    		$sResultCnd = $this->_getSqlJoinCondition($aCnd);
    		if(!empty($sResultCnd))
				$sResult .= $sOprGrp . $sResultCnd;
    	}

    	return trim($sResult, $sOprGrp);
    }

    protected function _getSqlJoinCondition($aCnd)
    {
    	$sResult = "";
    	if(!isset($aCnd['tp'], $aCnd['tbl1'], $aCnd['fld1'], $aCnd['fld2']))
    		return $sResult;

        $sField1 = "`" . $aCnd['tbl1'] . "`.`" . $aCnd['fld1'] . "`";

        $sField2 = "`" . $aCnd['fld2'] . "`";
        if(!empty($aCnd['tbl2']))
            $sField2 = "`" . $aCnd['tbl2'] . "`." . $sField2;

		return $aCnd['tp'] . " JOIN `" . $aCnd['tbl1'] . "` ON (" . $sField1 . " = " . $sField2 . ")";
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
}

/** @} */

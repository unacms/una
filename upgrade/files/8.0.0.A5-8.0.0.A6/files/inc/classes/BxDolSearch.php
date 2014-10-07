<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

define('BX_DOL_SEARCH_RESULTS_PER_PAGE_DEFAULT', 10);
define('BX_DOL_SEARCH_KEYWORD_PAGE', 'site-search-page');

/**
 * @page objects
 * @section search Search
 * @ref BxDolSearch
 */

/**
 * Search class for different content.
 *
 * Allows present content in main search area for all modules.
 *
 * To add taking part content from your module to search area you need add a record to `sys_objects_search` table:
 * @code
 *   ID - autoincremented id for internal usage
 *   ObjectName - your unique module name, with vendor prefix, lowercase and spaces are underscored
 *   Title - language key for module's choicer in global search form
 *   ClassName - class name which is responsible for search process ad showing result for found results
 *   ClassPath - file where your ClassName is stored (if class is stored in template folder then you can use key {tmpl})
 * @endcode
 *
 * For using this class you should have unit of BxDolSearchResult class (see description below) with method processing which is responsible for processing of search request.
 *
 * Example of usage can be seen in the default Dolphin's modules (photos, sounds, events, sites, etc).
 *
 * Memberships/ACL:
 * Doesn't depend on user's membership.
 *
 * Alerts:
 * no alerts available
 *
 */
class BxDolSearch extends BxDol
{
    protected $aClasses = array(); ///< array of all search classes
    protected $aChoice  = array(); ///< array of current search classes which were choosen in search area

    protected $_bLiveSearch = false;
    protected $_sMetaType = '';

    /**
     * Constructor
     * @param array $aChoice - array of choosen classes (will take a part only existing in `sys_objects_search` table)
     */
    public function __construct ($aChoice = '')
    {
        parent::__construct();

        $this->aClasses = BxDolDb::getInstance()->fromCache('sys_objects_search', 'getAllWithKey',
           'SELECT `ID` as `id`,
                   `Title` as `title`,
                   `ClassName` as `class`,
                   `ClassPath` as `file`,
                   `ObjectName`
            FROM `sys_objects_search`', 'ObjectName'
        );

        if (is_array($aChoice) && !empty($aChoice)) {
            foreach ($aChoice as $sValue) {
                if (isset($this->aClasses[$sValue]))
                    $this->aChoice[$sValue] = $this->aClasses[$sValue];
            }
        } else {
            $this->aChoice = $this->aClasses;
        }
    }

    /**
     * create units for all classes and calling their processing methods
     */
    public function response ()
    {
    	bx_import('BxTemplSearchResult');		

        $sCode = '';
        foreach ($this->aChoice as $sKey => $aValue) {
        	$sClassName = 'BxTemplSearchResult';
	        if(!empty($aValue['class'])) {
	            $sClassName = $aValue['class'];
	            if(!empty($aValue['file']))
	                require_once(BX_DIRECTORY_PATH_ROOT . $aValue['file']);
	            else
	                bx_import($sClassName);
	        }

            $oEx = new $sClassName();
            $oEx->setId($aValue['id']);
            $oEx->setLiveSearch($this->_bLiveSearch);
            $oEx->setMetaType($this->_sMetaType);
            $sCode .= $oEx->processing();
        }

        return $sCode;
    }

    public function getEmptyResult ()
    {
        $sKey = _t('_Empty');
        return DesignBoxContent($sKey, MsgBox($sKey), 1);
    }

    protected function getKeyTitlesPairs ()
    {
        $a = array();
        foreach ($this->aChoice as $sKey => $r)
            $a[$sKey] = _t($r['title']);
        return $a;
    }

    public function setLiveSearch($bLiveSearch)
    {
        $this->_bLiveSearch = $bLiveSearch;
    }

    public function setMetaType($s)
    {
        $aMetaTypes = array('location_country', 'location_country_city', 'mention', 'keyword');
        if (in_array($s, $aMetaTypes))
            $this->_sMetaType = $s;
    }

}

/*
 * Search class for processing search requests and displaying search results.
 *
 * Allows present content from modules on search params or internal (via fields of class) conditions.
 *
 * Example of usage (you can see example in any of default Dolhpin's modules):
 *
 * 1. Extends your own search class from this one (or from BxBaseSearchResult or BxBaseSearchResultSharedMedia classes)
 * 2. Set necessary fields of class (using as example BxFilesSearch):
 *
 * @code
 *
 *  // main field of search class
 *  $this->aCurrent = array(
 *
 *      // name of module
 *      'name' => 'bx_files',
 *
 *      // language key with name of module
 *      'title' => '_bx_files',
 *
 *      // main content table
 *      'table' => 'bx_files_main',
 *
 *      // array of all fields which can be choosen for select in result query
 *      'ownFields' => array('ID', 'Title', 'Uri', 'Desc', 'Date', 'Size', 'Ext', 'Views', 'Rate', 'RateCount', 'Type'),
 *
 *      // array of fields which take a part in search by keyword (global search and related words only - in other cases leave it blank)
 *      'searchFields' => array('Title', 'Tags', 'Desc', 'Categories'),
 *
 *      // array of join tables
 *      //   'type' - type of join
 *      //   'table' - join table
 *      //   'mainField' - field from main table for 'on' condition
 *      //   'onField' - field from joining table for 'on' condition
 *      //   'joinFields' - array of fields from joining table
 *
 *      'join' => array(
 *          'profile' => array(
 *              'type' => 'left',
 *              'table' => 'Profiles',
 *              'mainField' => 'Owner',
 *              'onField' => 'ID',
 *              'joinFields' => array('NickName'),
 *          ),
 *          // ...
 *      ),
 *
 *      // array of search parameters
 *      //   'value' - value of search parameter (can be number, string or array)
 *      //   'field' - field which will take value of search, may be lefy as blank then in search query will be pasted to WHERE conditon via operator AGAINST
 *      //   'operator' - operator between field and value (can be 'in', 'not in', '>', '<', 'against', 'like' and '=' by default),
 *      //   'paramName' - GET param which will keep 'value' for pagianation
 *
 *      'restriction' => array(
 *          'activeStatus' => array('value'=>'approved', 'field'=>'Status', 'operator'=>'=', 'paramName' => 'status'),
 *          'albumType' => array('value'=>'', 'field'=>'Type', 'operator'=>'=', 'paramName'=>'albumType', 'table'=>'sys_albums'),
 *      ),
 *
 *      // array of pagination
 *      //   'perPage' - units per page
 *      //   'start' - show search results starting from this number
 *      //   'num' - number of element to show on current page + 1
 *
 *      'paginate' => array('perPage' => 10, 'start' => 0, 'num' => 11),
 *
 *      // sort mode - by default it was last (DESC by date)
 *      'sorting' => 'last',
 *
 *      // mode of units presentation in search result, can be 'short' or 'full' view
 *      'view' => 'full',
 *
 *      // field of id
 *      'ident' => 'ID',
 *
 *      // rss feed array
 *      'rss' => array(
 *          'title' => '',
 *          'link' => '',
 *          'image' => '',
 *          'profile' => 0,
 *          'fields' => array (
 *              'Link' => '',
 *              'Title' => 'Title',
 *              'DateTimeUTS' => 'Date',
 *              'Desc' => 'Desc',
 *              'Photo' => '',
 *          ),
 *      ),
 *  );
 *
 *  // array of fields renamings
 *  $aPseud - filling in costructor by function _getPseud
 *
 *  // unique identificator from `sys_objects_search` table
 *  $id;
 *
 * @endcode
 *
 * Memberships/ACL:
 * Doesn't depend on user's membership.
 *
 * Alerts:
 * no alerts available
 *
 */

class BxDolSearchResult implements iBxDolReplaceable
{
    public $aCurrent; ///< search results configuration

    protected $aPseud; ///< array of fields renamings
    protected $id; ///< unique identificator of block on mixed search results page
    protected $bDisplayEmptyMsg = false; ///< display empty message instead of nothing, when no results
    protected $sDisplayEmptyMsgKey = ''; ///< custom empty message language key, instead of default "empty" message
    protected $bProcessPrivateContent = true; ///< check each item for privacy, if view isn't allowed then display private view instead
    protected $bForceAjaxPaginate = false; ///< force ajax paginate

    protected $_bLiveSearch = false;
    protected $_sMetaType = '';

    protected $_aMarkers = array (); ///< markers to replace somewhere, usually title and browse url (defined in custom class)

    /**
     * constructor
     * filling identificator field
     */
    function __construct ()
    {
        if (isset($this->aPseud['id']))
            $this->aCurrent['ident'] = $this->aPseud['id'];
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($sId)
    {
        $this->id = $sId;
    }

    public function setAjaxPaginate($b = true)
    {
        $this->bForceAjaxPaginate = $b;
    }

    public function setLiveSearch($bLiveSearch)
    {
        $this->_bLiveSearch = $bLiveSearch;
    }

    public function setMetaType($s)
    {
        $this->_sMetaType = $s;
    }

    /**
     * Display empty message if there is no content, custom empty message can be used.
     * @param $b - boolan value to enable or disable 'empty' message
     * @param $sLangKey [optional] - custom 'empty' message
     */
    public function setDisplayEmptyMsg($b, $sLangKey = '')
    {
        $this->bDisplayEmptyMsg = $b;
        if ($sLangKey)
           $this->sDisplayEmptyMsgKey = $sLangKey;
    }

    /**
     * Perform privacy checking for every unit
     * @param $b - boolan value to enable or disable privacy checking
     */
    public function setProcessPrivateContent ($b)
    {
        $this->bProcessPrivateContent = $b;
    }

    /**
     * Get html box of search results (usually used in grlobal search)
     * @return html code
     */
    function processing ()
    {
        $sCode = $this->displayResultBlock();
        if ($this->aCurrent['paginate']['num'] > 0) {
            $sPaginate = $this->showPagination();
            $sCode = $this->displaySearchBox($sCode, $sPaginate);
        } else {
            $sCode = $this->bDisplayEmptyMsg ? $this->displaySearchBox(MsgBox(_t($this->sDisplayEmptyMsgKey ? $this->sDisplayEmptyMsgKey : '_Empty'))) : '';
        }
        return $sCode;
    }

    /**
     * Get html output of search result
     * @return html code
     */
    function displayResultBlock ()
    {
        $aData = $this->getSearchData();
        if (count($aData) > 0) {
            $sCode .= $this->addCustomParts();
            foreach ($aData as $iKey => $aValue) {
                $sCode .= $this->displaySearchUnit($aValue);
            }
        }
        return $sCode;
    }

    /**
     * Add different code before html output (usually redeclared)
     * no return result
     */
    function addCustomParts ()
    {

    }

    /**
     * Get XML string for rss output
     */
    function rss ()
    {
        if (!isset($this->aCurrent['rss']['fields']) || !isset($this->aCurrent['rss']['link']))
            return '';

        $aData = $this->getSearchData();
        $f = &$this->aCurrent['rss']['fields'];
        if ($aData) {
            foreach ($aData as $k => $a)
                $aData[$k][$f['Link']] = $this->getRssUnitLink ($a);
        }

        bx_import('BxDolRssFactory');
        $oRss = new BxDolRssFactory ();

        return $oRss->GenRssByCustomData(
            $aData,
            isset($this->aCurrent['rss']['title']) && $this->aCurrent['rss']['title'] ? $this->aCurrent['rss']['title'] : $this->aCurrent['title'],
            $this->aCurrent['rss']['link'],
            $this->aCurrent['rss']['fields'],
            isset($this->aCurrent['rss']['image']) ? $this->aCurrent['rss']['image'] : '',
            isset($this->aCurrent['rss']['profile']) ? $this->aCurrent['rss']['profile'] : 0
        );
    }

    /**
     * Output RSS XML with XML header
     */
    function outputRSS ()
    {
        header('Content-Type: text/xml; charset=UTF-8');
        echo $this->rss();
    }

    /**
     * Return rss unit link (redeclared)
     */
    function getRssUnitLink (&$a)
    {
        // override this functions to return permalink to rss unit
    }

    /**
     * Naming fields in query's body
     * @param string  $sFieldName  name of field
     * @param string  $sTableName  name of field's table
     *                             $param string $sOperator of field's calculation (like MAX)
     * @param boolean $bRenameMode indicator for renaming and unsetting fields from field of class $this->aPseud
     *                             return $sqlUnit sql code and unsetting elements from aPseud field
     */
    function setFieldUnit ($sFieldName, $sTableName, $sOperator = '', $bRenameMode = true)
    {
        if (!empty($sOperator))
            $sqlUnit  = "$sOperator(`$sTableName`.`$sFieldName`)";
        else
            $sqlUnit  = "`$sTableName`.`$sFieldName`";

        if (isset($this->aPseud) && $bRenameMode !== false) {
            $sKey = array_search($sFieldName, $this->aPseud);
            if ($sKey !== false) {
                $sqlUnit .= " as `$sKey`";
                unset($this->aPseud[$sKey]);
            }
        }
        return $sqlUnit . ', ';
    }

    /**
     * Get html code of of every search unit
     * @param array $aData array of every search unit
     *                     return html code
     */
    function displaySearchUnit ($aData)
    {

    }

    /**
     * Get html code of search box with search results
     * @param string $sCode html code of search results
     *                      $param $sPaginate html code of paginate
     *                      return html code
     */
    function displaySearchBox ($sCode, $sPaginate = '')
    {

    }

    /**
     * Get html code of pagination
     */
    function showPagination ($bAdmin = false, $bChangePage = true, $bPageReload = true)
    {

    }

    /**
     * Get array of data with search results
     * return array with data
     */
    function getSearchData ()
    {
        $this->aPseud = $this->_getPseud();
        $this->setConditionParams();
        if ($this->aCurrent['paginate']['num'] > 0) {
            $aData = $this->getSearchDataByParams();
            return $aData;
        }
        return array();
    }

    /**
     * Get array with code for sql elements
     * @param boolean #bREnameMode indicator of renmaing fields
     * return array with joinFields, ownFields, groupBy and join elements
     */
    function getJoins ($bRenameMode = true)
    {
        $aSql = array();
        // joinFields & join
        if (isset($this->aCurrent['join']) && is_array($this->aCurrent['join'])) {
            $aSql = array('join'=>'', 'ownFields'=>'', 'joinFields'=>'', 'groupBy'=>'');
            foreach ($this->aCurrent['join'] as $sKey => $aValue) {
                $sAlias = isset($aValue['table_alias']) ? $aValue['table_alias'] : $aValue['table'];
                $sTableAlias = isset($aValue['table_alias']) ? " AS {$aValue['table_alias']} " : '';

                if (is_array($aValue['joinFields'])) {
                    foreach ($aValue['joinFields'] as $sValue) {
                        $aSql['joinFields'] .= $this->setFieldUnit($sValue, $sAlias, isset($aValue['operator']) ? $aValue['operator'] : null, $bRenameMode);
                    }
                }
                // group by
                if (isset($aValue['groupTable']))
                    $aSql['groupBy'] =  "GROUP BY `{$aValue['groupTable']}`.`{$aValue['groupField']}`, ";
                $sOn = isset($aValue['mainTable']) ? $aValue['mainTable'] : $this->aCurrent['table'];
                $aSql['join'] .= " {$aValue['type']} JOIN `{$aValue['table']}` $sTableAlias ON `{$sAlias}`.`{$aValue['onField']}`=`$sOn`.`{$aValue['mainField']}`";
                $aSql['ownFields'] .= $this->setFieldUnit($aValue['mainField'], $sOn, '', $bRenameMode);
            }
            $aSql['joinFields'] = trim($aSql['joinFields'], ', ');
            $aSql['groupBy'] = trim($aSql['groupBy'], ', ');
        }
        return $aSql;
    }

    /**
     * Concat sql parts of query, run it and return result array
     * @param $aParams addon param
     * return $aData multivariate array
     */
    function getSearchDataByParams ($aParams = '')
    {
        $aSql = array('ownFields'=>'', 'joinFields'=>'', 'order'=>'');

        // searchFields
        foreach ($this->aCurrent['ownFields'] as $sValue)
            $aSql['ownFields'] .= $this->setFieldUnit($sValue, $this->aCurrent['table']);

        // joinFields & join
        $aJoins = $this->getJoins();
        if (!empty($aJoins)) {
            $aSql['ownFields'] .= $aJoins['ownFields'];
            $aSql['ownFields'] .= $aJoins['joinFields'];
            $aSql['join'] = $aJoins['join'];
            $aSql['groupBy'] = $aJoins['groupBy'];
        } 

        $aSql['ownFields'] = trim($aSql['ownFields'], ', ');

        // from
        $aSql['from'] = " FROM `{$this->aCurrent['table']}`";

        // where
        $aSql['where'] = $this->getRestriction();

        // limit
        $aSql['limit'] = $this->getLimit();

        // sorting
        $this->setSorting();

        $aSort = $this->getSorting($this->aCurrent['sorting']);
        foreach ($aSort as $sKey => $sValue)
            $aSql[$sKey] .= $sValue;

        // execution
        $sqlQuery = "SELECT " . $aSql['ownFields'];

        $sqlQuery .= ' ' . $aSql['from'];

        if (isset($aSql['join']))
            $sqlQuery .= ' ' . $aSql['join'];

        if (isset($aSql['where']))
            $sqlQuery .= ' ' . $aSql['where'];

        if (isset($aSql['groupBy']))
            $sqlQuery .= ' ' . $aSql['groupBy'];

        if (isset($aSql['order']))
            $sqlQuery .= ' ' . $aSql['order'];

        if (isset($aSql['limit']))
            $sqlQuery .= ' ' . $aSql['limit'];

        // echoDbg($sqlQuery);
        $aRes = BxDolDb::getInstance()->getAll($sqlQuery);
        return $aRes;
    }

    /**
     * Set class fields condition params and paginate array
     */
    function setConditionParams()
    {
        // keyword
        $sKeyword = bx_process_input(bx_get('keyword'));
        if ($sKeyword !== false)
            $this->aCurrent['restriction']['keyword'] = array(
                'value' => $sKeyword,
                'field' => '',
                'operator' => 'against'
            );

        // owner
        if (isset($_GET['ownerName'])) {
            $sName = bx_process_input($_GET['ownerName']);
            $iUser = (int)BxDolProfileQuery::getInstance()->getIdByNickname($sName);
            BxDolMenu::getInstance()->setCurrentProfileID($iUser);
        } elseif (isset($_GET['userID']))
            $iUser = bx_process_input($_GET['userID'], BX_DATA_INT);

        if (!empty($iUser))
            $this->aCurrent['restriction']['owner']['value'] = $iUser;
        
        // meta info
        if ($this->_sMetaType && !empty($this->aCurrent['object_metatags'])) {
            bx_import('BxDolMetatags');
            $o = BxDolMetatags::getObjectInstance($this->aCurrent['object_metatags']);            
            if ($o) {
                unset($this->aCurrent['restriction']['keyword']);
                switch ($this->_sMetaType) {
                    case 'location_country':
                        $o->locationsSetSearchCondition($this, $sKeyword);
                        break;
                    case 'location_country_city':
                        $o->locationsSetSearchCondition($this, $sKeyword, bx_process_input(bx_get('state')), bx_process_input(bx_get('city')));
                        break;
                    case 'mention':
                        // TODO:
                        break;
                    case 'keyword':
                        $o->keywordsSetSearchCondition($this, $sKeyword);
                        break;
                }
            }
        }

        $this->setPaginate();
        $iNum = $this->getNum();
        if ($iNum > 0) {
            $this->aCurrent['paginate']['num'] = $iNum;
        } else {
           $this->aCurrent['paginate']['num'] = 0;
        }
    }

    /**
     * Check number of records on current page
     * return number of records on current page + 1
     */
    function getNum ()
    {
        $aJoins = $this->getJoins(false);
        $sqlQuery =  "SELECT * FROM `{$this->aCurrent['table']}` " . (isset($aJoins['join']) ? $aJoins['join'] : '' ). $this->getRestriction() . (isset($aJoins['groupBy']) ? $aJoins['groupBy'] : '') . ' ' . $this->getLimit(true);
        return count(BxDolDb::getInstance()->getAll($sqlQuery));
    }

    /**
     * Check restriction params and make condition part of query
     * return $sqlWhere sql code of query for WHERE part
     */
    function getRestriction ()
    {
        $oDb = BxDolDb::getInstance();
        $sqlWhere = '';
        if (isset($this->aCurrent['restriction'])) {
            $aWhere[] = '1';
            foreach ($this->aCurrent['restriction'] as $sKey => $aValue) {
                $sqlCondition = '';
                if (isset($aValue['operator']) && isset($aValue['value']) && $aValue['value'] !== '' && $aValue['value'] !== false && $aValue['value'] !== null) {
                   $sFieldTable = isset($aValue['table']) ? $aValue['table'] : $this->aCurrent['table'];
                   $sqlCondition = "`{$sFieldTable}`.`{$aValue['field']}` ";
                   switch ($aValue['operator']) {
                       case 'against':
                            $aCond = isset($aValue['field']) && strlen($aValue['field']) > 0 ? $aValue['field'] : $this->aCurrent['searchFields'];
                            $sqlCondition = !empty($aCond) ? $this->getSearchFieldsCond($aCond, $aValue['value']) : "";
                            break;
                       case 'like':
                            $sqlCondition .= "LIKE '%" . $oDb->escape($aValue['value']) . "%'";
                            break;
                       case 'in':
                       case 'not in':
                            $sValuesString = $this->getMultiValues($aValue['value']);
                            $sqlCondition .= strtoupper($aValue['operator']) . '('.$sValuesString.')';
                            break;
                       default:
                               $sqlCondition .= $aValue['operator'] . (isset($aValue['no_quote_value']) && $aValue['no_quote_value'] ?  $aValue['value'] : "'" . $oDb->escape($aValue['value']) . "'");
                       break;
                    }
                }
                if (strlen($sqlCondition) > 0)
                    $aWhere[] = $sqlCondition;
            }
            $sqlWhere .= "WHERE ". implode(' AND ', $aWhere);
        }
        return $sqlWhere;
    }

    /**
     * Get limit part of query
     * return $sqlFrom code for limit part pf query
     */
    function getLimit ($isAddPlusOne = false)
    {
        if (!isset($this->aCurrent['paginate']))
            return;

        $sqlFrom = (int)$this->aCurrent['paginate']['start'] > 0 ? (int)$this->aCurrent['paginate']['start'] : 0;
        $sqlTo = $this->aCurrent['paginate']['perPage'] + ($isAddPlusOne ? 1 : 0);
        if ($sqlTo > 0)
            return 'LIMIT ' . $sqlFrom . ', ' . $sqlTo;
    }

    /**
     * Set sorting field of class
     */
    function setSorting ()
    {
        $this->aCurrent['sorting'] = isset($_GET[$this->aCurrent['name'] . '_mode']) ? $_GET[$this->aCurrent['name'] . '_mode'] : $this->aCurrent['sorting'];
    }

    /**
     * Get sorting part of query according current sorting mode
     * @param string $sSortType sorting type
     *                          return array with sql elements order and ownFields
     */
    function getSorting ($sSortType = 'last')
    {
        $aOverride = $this->getAlterOrder();
        if (is_array($aOverride) && !empty($aOverride))
            return $aOverride;

       $aSql = array();
       switch ($sSortType) {
           case 'rand':
                $aSql['order'] = "ORDER BY RAND()";
                break;
           case 'score':
                if (is_array($this->aCurrent['restriction']['keyword'])) {
                    $sPseud = '';
                    if ('on' == getParam('useLikeOperator')) {
                        $aSql['order'] = "ORDER BY `score` DESC";
                        $sPseud = 'score';
                    }
                    $aSql['ownFields'] .= $this->getSearchFieldsCond($this->aCurrent['searchFields'], $this->aCurrent['restriction']['keyword']['value'], $sPseud) . ',';
                }
                break;
           case 'none':
                $aSql['order'] = "";
                break;
           default:
                $aSql['order'] = "ORDER BY `date` DESC";
        }
        return $aSql;
    }

    /**
     * Return own varaint for sorting (redeclare if necessary)
     * return array of sql elements
     */
    function getAlterOrder ()
    {
        return array();
    }

    /**
     * Set paginate fields of class according GET params 'start' and 'per_page'
     * forcePage is need for setting most important number of current page
     */
    function setPaginate ()
    {
        $this->aCurrent['paginate']['perPage'] = (isset($_GET['per_page']) && (int)$_GET['per_page'] != 0) ? (int)$_GET['per_page'] : $this->aCurrent['paginate']['perPage'];
        if (empty($this->aCurrent['paginate']['perPage']))
            $this->aCurrent['paginate']['perPage'] = BX_DOL_SEARCH_RESULTS_PER_PAGE_DEFAULT;

        $this->aCurrent['paginate']['start'] = isset($this->aCurrent['paginate']['forceStart'])
            ? (int)$this->aCurrent['paginate']['forceStart']
            : (empty($_GET['start']) ? 0 : (int)$_GET['start']);

        if ($this->aCurrent['paginate']['start'] < 0)
            $this->aCurrent['paginate']['start'] = 0;
    }

    /**
     * Get sql where condition for search fields
     * @param array of search fields
     * @param string $sKeyword keyword value for search
     * @param string sPseud for setting new name for generated set of fields in query
     *                         return sql code of WHERE part in query
     */
    function getSearchFieldsCond ($aFields, $sKeyword, $sPseud = '')
    {
        if (!$sKeyword)
            return '';

        $sTable = empty($this->aCurrent['tableSearch']) ? $this->aCurrent['table'] : $this->aCurrent['tableSearch'];

        $oDb = BxDolDb::getInstance();

        $bLike = getParam('useLikeOperator');

        $sKeyword = $oDb->escape($sKeyword);

        if (!is_array($aFields))
            $aFields = array($aFields);

        if ($bLike == 'on') {

            $sKeyword = '%' . preg_replace('/\s+/', '%', $sKeyword) . '%';

            $sSqlWhere = '';
            foreach ($aFields as $sValue)
                $sSqlWhere .= "`{$sTable}`.`$sValue` LIKE  '$sKeyword' OR ";

            $sSqlWhere = '(' . trim($sSqlWhere, 'OR ') . ')';

        } else {

            $sSqlWhere = '';
            foreach ($aFields as $sValue)
                $sSqlWhere .= "`{$sTable}`.`$sValue`, ";

            $sSqlWhere = trim($sSqlWhere, ', ');
            $sSqlWhere = " MATCH({$sSqlWhere}) AGAINST ('{$sKeyword}') ";

            if (!empty($sPseud))
                $sSqlWhere .= " AS `$sPseud` ";
        }

        return $sSqlWhere;
    }

    /**
     * Get set from several values for 'in' and 'not in' operators
     * @param $aValues array of values
     * return sql code for field with operator IN (NOT IN)
     */
    function getMultiValues ($aValues)
    {
        $oDb = BxDolDb::getInstance();
        return $oDb->implode_escape($aValues);
    }

    /**
     * System method for filling aPseud array.
     * Fill field aPseud for current class (if you will use own getSearchUnit methods then not necessary to redeclare).
     */
    function _getPseud ()
    {

    }

    /**
     * Add replace markers. Markers are replaced in titles and browse urls
     * @param $a array of markers as key => value
     * @return true on success or false on error
     */
    public function addMarkers ($a)
    {
        if (empty($a) || !is_array($a))
            return false;
        $this->_aMarkers = array_merge ($this->_aMarkers, $a);
        return true;
    }

    /**
     * Replace provided markers in a string
     * @param $mixed string or array to replace markers in
     * @return string where all occured markers are replaced
     */
    protected function _replaceMarkers ($mixed)
    {
        return bx_replace_markers($mixed, $this->_aMarkers);
    }
}

/** @} */

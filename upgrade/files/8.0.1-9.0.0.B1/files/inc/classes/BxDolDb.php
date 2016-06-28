<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

define('BX_DB_MODE_SILENT', PDO::ERRMODE_SILENT);
define('BX_DB_MODE_EXCEPTION', PDO::ERRMODE_EXCEPTION);

define('BX_DB_ERR_CONNECT_FAILD', 1);
define('BX_DB_ERR_QUERY_ERROR', 2);
define('BX_DB_ERR_ESCAPE', 3);

define('BX_PDO_STATE_NOT_EXECUTED', NULL);
define('BX_PDO_STATE_SUCCESS', '00000');

class BxDolDb extends BxDol implements iBxDolSingleton
{	
    protected static $_rLink;
    protected static $_aDbCacheData;

    protected static $_aParams;
    protected static $_sParamsCacheName = 'sys_options';
    protected static $_sParamsCacheNameMixed = 'sys_options_mixed';

    protected static $_sErrorKey = 'bx_db_error';
    protected static $_aErrors = array(
    	BX_DB_ERR_CONNECT_FAILD => 'Database connect failed',
    	BX_DB_ERR_QUERY_ERROR => 'Database query error',
    	BX_DB_ERR_ESCAPE => 'Escape string error'
    );

	protected $_bPdoPersistent;
	protected $_iPdoFetchType;
	protected $_iPdoErrorMode;

	protected $_bErrorChecking;
    protected $_aError;

	protected $_sHost, $_sPort, $_sSocket, $_sDbname, $_sUser, $_sPassword, $_sCharset, $_sStorageEngine;

    protected $_oStatement = null;
    protected $_oDbCacheObject = null;

    /**
     * set database parameters and connect to it
     */
    protected function __construct($aDbConf = false)
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

		$this->_bPdoPersistent = true;
        $this->_iPdoFetchType = PDO::FETCH_ASSOC;
        $this->_iPdoErrorMode = BX_DB_MODE_EXCEPTION;

        $this->_bErrorChecking = true;
        $this->_aError = array();

        $this->_sStorageEngine = 'MYISAM';

        $this->_sCharset = 'utf8';
        if($aDbConf === false) {
            $this->_sHost = BX_DATABASE_HOST;
            $this->_sPort = BX_DATABASE_PORT;
            $this->_sSocket = BX_DATABASE_SOCK;
            $this->_sDbname = BX_DATABASE_NAME;
            $this->_sUser = BX_DATABASE_USER;
            $this->_sPassword = BX_DATABASE_PASS;
        } 
        else {
            $this->_sHost = $aDbConf['host'];
            $this->_sPort = $aDbConf['port'];
            $this->_sSocket = $aDbConf['sock'];
            $this->_sDbname = $aDbConf['name'];
            $this->_sUser = $aDbConf['user'];
            $this->_sPassword = $aDbConf['pwd'];
            if(isset($aDbConf['charset']))
            	$this->_sCharset = $aDbConf['charset'];
            if(isset($aDbConf['error_checking']))
            	$this->_bErrorChecking = $aDbConf['error_checking'];
        }

        @set_exception_handler(array($this, 'pdoExceptionHandler'));
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance($aDbConf = false, &$sError = null)
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__])) {
            if($aDbConf === false && !defined('BX_DATABASE_HOST'))
                return null;

            $o = new BxDolDb($aDbConf);
            $sErrorMessage = $o->connect();
            if($sErrorMessage) {
                if($sError !== null)
                    $sError = $sErrorMessage;

                return null;
            }

			$GLOBALS['bxDolClasses'][__CLASS__] = $o;
        }

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    public static function getLink()
    {
    	return self::$_rLink;
    }

    /**
     * connect to database with appointed parameters
     */
    public function connect()
    {
    	if(self::$_rLink)
    		return;

    	try {
	    	$sDsn = "mysql:host=" . $this->_sHost . ";";
	   		$sDsn .= $this->_sPort ? "port=" . $this->_sPort . ";" : "";
	   		$sDsn .= $this->_sSocket ? "unix_socket=" . $this->_sSocket . ";" : "";
	    	$sDsn .= "dbname=" . $this->_sDbname . ";charset=" . $this->_sCharset;

	        self::$_rLink = new PDO($sDsn, $this->_sUser, $this->_sPassword, array(
				PDO::ATTR_ERRMODE => $this->_iPdoErrorMode,
				PDO::ATTR_DEFAULT_FETCH_MODE => $this->_iPdoFetchType,
				PDO::ATTR_PERSISTENT => $this->_bPdoPersistent
	        ));

	    	$this->pdoExec("SET NAMES 'utf8'");
	        $this->pdoExec("SET sql_mode = ''");
			$this->pdoExec("SET storage_engine=" . $this->_sStorageEngine);

			self::$_aDbCacheData = array();
    	}
    	catch (PDOException $oException) {
    		$oException->errorInfo[self::$_sErrorKey] = array(
    			'code' => BX_DB_ERR_CONNECT_FAILD,
    			'message' => $oException->getMessage(),
    			'trace' => $oException->getTrace()
    		);

    		throw $oException;
    	}
    }

    /**
     * close mysql connection
     */
    public function disconnect()
    {
        self::$_rLink = null;
    }

    /**
     * check mysql connection
     */
    public function ping()
    {
    	try {
    		$this->pdoQuery("SELECT 1");
    	}
    	catch (PDOException $e) {
    		return false;
    	}

    	return true;
    }

    /**
     * Can be used to execute queries which shouldn't return data
     */
    public function pdoExec($sQuery)
    {
    	return self::$_rLink->exec($sQuery);
    }

    /**
     * Executes query and returns PDOStatement object or false 
     */
	public function pdoQuery($sQuery)
    {
    	return self::$_rLink->query($sQuery);
    }

    /**
     * database query exception handler for exceptions appeared out of the try/catch block
     */
    public function pdoExceptionHandler($oException)
    {
    	if(!($oException instanceof PDOException))
    		return;

    	$this->error($oException->errorInfo[self::$_sErrorKey]);
    	return;
    }

    /**
     * get mysql option
     */
    function getOption($sName)
    {
    	$oStatement = $this->pdoQuery("SELECT @@{$sName}");
    	return $this->getOne($oStatement);
    }

    /**
     * execute sql query and return one value result
     */
    public function getOne($oStatement, $aBindings = array(), $iIndex = 0)
    {
        if(!$oStatement)
			return false;
		else if(!($oStatement instanceof PDOStatement) && is_string($oStatement))
			$oStatement = $this->prepare($oStatement);

		$aResult = array();
		if($this->res($oStatement, $aBindings))
            $aResult = $oStatement->fetch(PDO::FETCH_NUM);

        return is_array($aResult) && count($aResult) ? $aResult[$iIndex] : false;
    }

    /**
     * execute sql query and return one row result
     */
    function getRow($oStatement, $aBindings = array(), $iFetchType = PDO::FETCH_ASSOC)
    {
    	$aResult = array();
        if(!$oStatement)
            return $aResult;
		else if(!($oStatement instanceof PDOStatement) && is_string($oStatement))
			$oStatement = $this->prepare($oStatement);

        if(!in_array($iFetchType, array(PDO::FETCH_NUM, PDO::FETCH_ASSOC, PDO::FETCH_BOTH)))
            $iFetchType = $this->_iPdoFetchType;

        if(!$this->res($oStatement, $aBindings))
        	return array();

		$aResult = $oStatement->fetch($iFetchType);
		if($aResult === false)
        	return array();

        return $aResult;
    }

    /**
     * execute sql query and return a column as result
     */
    function getColumn($oStatement, $aBindings = array(), $iFetchColumnNumber = 0)
    {
    	$aResult = array();
        if(!$oStatement)
            return $aResult;
		else if(!($oStatement instanceof PDOStatement) && is_string($oStatement))
			$oStatement = $this->prepare($oStatement);

        if(!$this->res($oStatement, $aBindings))
        	return array();

		$aResult = $oStatement->fetchAll(PDO::FETCH_COLUMN, $iFetchColumnNumber);
		if($aResult === false)
        	return array();

        return $aResult;
    }

	/**
     * execute sql query and return the first row of result
     * and keep $array type and poiter to all data
     */
    public function getFirstRow($oStatement, $aBindings = array(), $iFetchType = PDO::FETCH_ASSOC)
    {
        if(!$oStatement)
            return array();
		else if(!($oStatement instanceof PDOStatement) && is_string($oStatement))
			$oStatement = $this->prepare($oStatement);

        if(!in_array($iFetchType, array(PDO::FETCH_NUM, PDO::FETCH_ASSOC, PDO::FETCH_BOTH)))
            $iFetchType = $this->_iPdoFetchType;

        if(!$this->res($oStatement, $aBindings)) 
        	return array();

        $aResult = $oStatement->fetch($iFetchType);
        if($aResult === false)
        	return array();

		$this->_oStatement = $oStatement;
        return $aResult;
    }

    /**
     * return next row of pointed last getFirstRow calling data
     */
    public function getNextRow($iFetchType = PDO::FETCH_ASSOC)
    {
    	if(!$this->_oStatement)
            return array();

		if(!in_array($iFetchType, array(PDO::FETCH_NUM, PDO::FETCH_ASSOC, PDO::FETCH_BOTH)))
            $iFetchType = $this->_iPdoFetchType;

		$aResult = $this->_oStatement->fetch($iFetchType);
		if($aResult !== false)
			return $aResult;

		$this->_oStatement = null;
    	return array();
    }

	/**
     * execute sql query and return table of records as result
     */
    public function getAll($oStatement, $aBindings = array(), $iFetchType = PDO::FETCH_ASSOC)
    {
    	$aResult = array();
        if(!$oStatement)
            return $aResult;
		else if(!($oStatement instanceof PDOStatement) && is_string($oStatement))
			$oStatement = $this->prepare($oStatement);

        if(!in_array($iFetchType, array(PDO::FETCH_NUM, PDO::FETCH_ASSOC, PDO::FETCH_BOTH)))
            $iFetchType = $this->_iPdoFetchType;

        if(!$this->res($oStatement, $aBindings))
        	return array();

		$aResult = $oStatement->fetchAll($iFetchType);
		if($aResult === false)
        	return array();

        return $aResult;
    }

    /**
     * Executes sql query and returns table of records as result.
     * 
     * @deprecated use getAll instead.
     */
    public function fillArray($oStatement, $aBindings = array(), $iFetchType = PDO::FETCH_ASSOC)
    {
    	return $this->getAll($oStatement, $aBindings);
    }

	/**
     * execute sql query and return table of records as result
     */
    public function getAllWithKey($oStatement, $sFieldKey, $aBindings = array())
    {
    	$aResult = array();
        if(!$oStatement)
            return $aResult;
		else if(!($oStatement instanceof PDOStatement) && is_string($oStatement))
			$oStatement = $this->prepare($oStatement);

        $aRow = $this->getFirstRow($oStatement, $aBindings, PDO::FETCH_ASSOC);
        while(!empty($aRow)) {
        	$aResult[$aRow[$sFieldKey]] = $aRow;

        	$aRow = $this->getNextRow(PDO::FETCH_ASSOC);
        }

        return $aResult;
    }

    /**
     * execute sql query and return table of records as result
     */
    public function getPairs($oStatement, $sFieldKey, $sFieldValue, $aBindings = array())
    {
    	$aResult = array();
        if(!$oStatement)
            return $aResult;
		else if(!($oStatement instanceof PDOStatement) && is_string($oStatement))
			$oStatement = $this->prepare($oStatement);

        $aRow = $this->getFirstRow($oStatement, $aBindings, PDO::FETCH_ASSOC);
        while(!empty($aRow)) {
        	$aResult[$aRow[$sFieldKey]] = $aRow[$sFieldValue];

        	$aRow = $this->getNextRow(PDO::FETCH_ASSOC);
        }

        return $aResult;
    }

    /**
     * return number of affected rows in current mysql result
     * 
     * NOTE: PDOStatement::rowCount works for SELECT queries in MySQL.
     * So, this method should be rewritten if the other DB engine will be used.
     */
    public function getNumRows($oStatement = null)
    {
    	if($oStatement && ($oStatement instanceof PDOStatement))
    		return $oStatement->rowCount();

    	if($this->_oStatement && ($this->_oStatement instanceof PDOStatement))
    		return $this->_oStatement->rowCount();

    	return 0;
    }

    /**
     * returns the number of rows affected by the last DELETE, INSERT, or UPDATE statement. 
     */
    public function getAffectedRows($oStatement = null)
    {
        return $this->getNumRows($oStatement);
    }

    public function lastId()
    {
        return self::$_rLink->lastInsertId();
    }

    /**
     * execute any query return number of rows affected/false
     */
    public function query($oStatement, $aBindings = array(), $bVerbose = null)
    {
    	if(!$oStatement)
            return false;
		else if(!($oStatement instanceof PDOStatement) && is_string($oStatement))
			$oStatement = $this->prepare($oStatement);

        if($this->res($oStatement, $aBindings, $bVerbose))
            return $oStatement->rowCount();

        return false;
    }

    /**
     * execute any query
     */
    public function res($oStatement, $aBindings = array(), $bVerbose = null)
    {
		if(!$oStatement || !($oStatement instanceof PDOStatement))
            return false;

		if($oStatement->errorCode() == BX_PDO_STATE_SUCCESS)
			return true;

        if(isset($GLOBALS['bx_profiler']))
        	$GLOBALS['bx_profiler']->beginQuery($oStatement->queryString);

		$bResult = $this->executeStatement($oStatement, $aBindings, $bVerbose);

		//if mysql connection is lost - reconnect and try again
        if(!$bResult && !$this->ping()) {
            $this->disconnect();
            $this->connect();

            $bResult = $this->executeStatement($oStatement, $aBindings, $bVerbose);
        }

        if(isset($GLOBALS['bx_profiler']))
        	$GLOBALS['bx_profiler']->endQuery($bResult);

		//is needed for SILENT mode
		if(!$bResult && !empty($this->_aError))
			$this->error($this->_aError);

        return $bResult;
    }

    /**
     * get mysql server info
     */
    public function getServerInfo()
    {
    	return self::$_rLink->getAttribute(PDO::ATTR_SERVER_VERSION);
    }

    /**
     * get list of tables in database
     */
    public function listTables()
    {
    	$oStatement = $this->pdoQuery("SHOW TABLES FROM " . BX_DATABASE_NAME);

        return $this->getColumn($oStatement);
    }

    public function getFields($sTable)
    {
    	$oStatement = $this->pdoQuery("SHOW COLUMNS FROM `" . $sTable . "`");
    	$aFields = $this->getAll($oStatement);

        $aResult = array('original' => array(), 'uppercase' => array());
        foreach($aFields as $aField) {
            $aResult['original'][] = $aField['Field'];
            $aResult['uppercase'][] = strtoupper($aField['Field']);
        }

        return $aResult;
    }

    public function isFieldExists($sTable, $sFieldName)
    {
        $aFields = $this->getFields($sTable);
        return in_array(strtoupper($sFieldName), $aFields['uppercase']);
    }

    public function error($aError)
    {
    	$sErrorType = self::$_aErrors[$aError['code']];

    	$bVerbose = isset($aError['verbose']) ? (bool)$aError['verbose'] : $this->_bErrorChecking;
        if(!$bVerbose) {
			$this->log($sErrorType . ': ' . $aError['message']);
			return;
        }

        if(defined('BX_DB_FULL_VISUAL_PROCESSING') && BX_DB_FULL_VISUAL_PROCESSING) {
            $sOutput = '<div style="border:2px solid red;padding:4px;width:600px;margin:0px auto;">';
            $sOutput .= '<div style="text-align:center;background-color:red;color:white;font-weight:bold;">Error</div>';
            $sOutput .= '<div style="text-align:center;">' . $sErrorType . '</div>';
            if(defined('BX_DB_FULL_DEBUG_MODE') && BX_DB_FULL_DEBUG_MODE)
				$sOutput .= $this->errorOutput($aError);
            $sOutput .= '</div>';
        } 

        if(defined('BX_DB_DO_EMAIL_ERROR_REPORT') && BX_DB_DO_EMAIL_ERROR_REPORT) {
            $sSiteTitle = $this->getParam('site_title');

            $sMailBody = "Database error in " . $sSiteTitle . "<br /><br /> \n";
            $sMailBody .= $this->errorOutput($aError);
            $sMailBody .= "<hr />Auto-report system";

            sendMail($this->getParam('site_email'), "Database error in " . $sSiteTitle, $sMailBody, 0, array(), BX_EMAIL_SYSTEM, 'html', true);
        }

        bx_show_service_unavailable_error_and_exit($sOutput);
    }

    protected function isParamInCache($sKey)
    {
        return is_array(self::$_aParams) && isset(self::$_aParams[$sKey]);
    }

    protected function cacheParams($bForceCacheInvalidate = false)
    {
        if ($bForceCacheInvalidate)
            $this->cacheParamsClear();

        self::$_aParams = $this->fromCache(self::$_sParamsCacheName, 'getPairs', "SELECT `name`, `value` FROM `sys_options`", "name", "value");

        $aMixed = $this->fromCache(self::$_sParamsCacheNameMixed, 'getPairs', "SELECT `tmo`.`option` AS `option`, `tmo`.`value` AS `value` FROM `sys_options_mixes2options` AS `tmo` INNER JOIN `sys_options_mixes` AS `tm` ON `tmo`.`mix_id`=`tm`.`id` AND `tm`.`active`='1'", "option", "value");
        if(!empty($aMixed))
        	self::$_aParams = array_merge(self::$_aParams, $aMixed);

        if (empty(self::$_aParams)) {
            self::$_aParams = array ();
            return false;
        }

        return true;
    }

    public function cacheParamsClear()
    {
        return $this->cleanCache(self::$_sParamsCacheName);
    }

    public function isParam($sKey, $bFromCache = true)
    {
        if ($bFromCache && $this->isParamInCache($sKey))
           return true;

        $sQuery = $this->prepare("SELECT `name` FROM `sys_options` WHERE `name` = ? LIMIT 1", $sKey);
        return $this->getOne($sQuery) == $sKey;
    }

    public function addParam($sName, $sValue, $iKateg, $sDesc, $sType)
    {
        $sQuery = $this->prepare("INSERT INTO `sys_options` SET `category_id` = ?, `name` = ?, `caption` = ?, `value` = ?, `type` = ?", $iKateg, $sName, $sDesc, $sValue, $sType);
        $this->query($sQuery);

        // renew params cache
        $this->cacheParams(true);
    }

    public function getParam($sKey, $bFromCache = true)
    {
        if (!$sKey)
            return false;

        if ($bFromCache && $this->isParamInCache($sKey)) {
            return self::$_aParams[$sKey];
        } else {
        	$sQuery = $this->prepare("SELECT `tmo`.`value` AS `value` FROM `sys_options_mixes2options` AS `tmo` INNER JOIN `sys_options_mixes` AS `tm` ON `tmo`.`mix_id`=`tm`.`id` AND `tm`.`active`='1' WHERE `tmo`.`option`=? LIMIT 1", $sKey);
			$mixedValue = $this->getOne($sQuery);
			if($mixedValue !== false)
				return $mixedValue;

            $sQuery = $this->prepare("SELECT `value` FROM `sys_options` WHERE `name` = ? LIMIT 1", $sKey);
            return $this->getOne($sQuery);
        }
    }

    public function setParam($sKey, $mixedValue, $iMixId = 0)
    {
    	if(empty($iMixId))
        	$sQuery = $this->prepare("UPDATE `sys_options` SET `value` = ? WHERE `name` = ? LIMIT 1", $mixedValue, $sKey);
        else
        	$sQuery = $this->prepare("REPLACE INTO `sys_options_mixes2options` SET `option` = ?, `mix_id` = ?, `value` = ?", $sKey, $iMixId, $mixedValue);

        $bResult = (int)$this->query($sQuery) > 0;

        // renew params cache
        $bResult &= $this->cacheParams(true);

        return $bResult;
    }

    public function setTimezone($sTimezone)
    {
        $oDate = new DateTime('now', new DateTimeZone($sTimezone));
        return $this->pdoExec('SET time_zone = "' . $oDate->format('P') . '"') !== false;
    }

    public function getEncoding()
    {
    	$oStatement = $this->pdoQuery('SELECT @@character_set_database');
    	if($oStatement !== false)
    		return $this->getOne($oStatement);

    	return false;
    }

    public function setErrorChecking ($b)
    {
        $this->_bErrorChecking = $b;
    }

    /**
     * Cache functions.
     */
    public function getDbCacheObject ()
    {
        if($this->_oDbCacheObject != null)
			return $this->_oDbCacheObject;

		$sEngine = $this->getParam('sys_db_cache_engine');
		$this->_oDbCacheObject = bx_instance('BxDolCache'.$sEngine);
		if(!$this->_oDbCacheObject->isAvailable())
			$this->_oDbCacheObject = bx_instance('BxDolCacheFile');

		return $this->_oDbCacheObject;
    }

    public function genDbCacheKey ($sName)
    {
        return 'db_' . $sName . '_' . bx_site_hash() . '.php';
    }

    public function fromCache ($sName, $sFunc)
    {
        $aArgs = func_get_args();
        array_shift ($aArgs); // shift $sName
        array_shift ($aArgs); // shift $sFunc

        if (!$this->getParam('sys_db_cache_enable'))
            return call_user_func_array (array ($this, $sFunc), $aArgs); // pass other function parameters as database function parameters

        $oCache = $this->getDbCacheObject ();

        $sKey = $this->genDbCacheKey($sName);

        $mixedRet = $oCache->getData($sKey);

        if ($mixedRet !== null) {

            return $mixedRet;

        } else {

            $mixedRet = call_user_func_array (array ($this, $sFunc), $aArgs); // pass other function parameters as database function parameters

            $oCache->setData($sKey, $mixedRet);
        }

        return $mixedRet;
    }

    public function cleanCache ($sName)
    {
        $oCache = $this->getDbCacheObject();

        $sKey = $this->genDbCacheKey($sName);

        return $oCache->delData($sKey);
    }

    public function &fromMemory ($sName, $sFunc)
    {
        if(array_key_exists($sName, self::$_aDbCacheData))
			return self::$_aDbCacheData[$sName];

		$aArgs = func_get_args();
		array_shift($aArgs); // shift $sName
		array_shift($aArgs); // shift $sFunc
		self::$_aDbCacheData[$sName] = call_user_func_array (array ($this, $sFunc), $aArgs); // pass other function parameters as database function parameters

		return self::$_aDbCacheData[$sName];
    }

    public function cleanMemory ($sName)
    {
        if(!isset(self::$_aDbCacheData[$sName])) 
        	return false;

		unset(self::$_aDbCacheData[$sName]);
		return true;
    }

    /**
     * It escapes string to pass to mysql query.
     * Try to use "prepare" function always (@see BxDolDb::prepare), use "escape" only if "prepare" function is not possible at all.
     * Also consider using "implode_escape" function (@see BxDolDb::implode_escape).
     *
     * @param  string  $s string to escape
     * @return escaped string whcich is ready to pass to SQL query.
     */
    public function escape($s)
    {
    	try {
    		$s = self::$_rLink->quote($s);
    	}
    	catch (PDOException $oException) {
    		$oException->errorInfo[self::$_sErrorKey] = array(
    			'code' => BX_DB_ERR_ESCAPE,
    			'message' => $oException->getMessage(),
    			'trace' => $oException->getTrace()
    		);

    		throw $oException;
    	}

        return $s;
    }

    /**
     * This function is usefull when you need to form array of parameters to pass to IN(...) SQL construction.
     * Example:
     * @code
     * $a = array(2, 4.5, 'apple', 'car');
     * $s = "SELECT * FROM `t` WHERE `a` IN (" . $oDb->implode_escape($a) . ")";
     * echo $s; // outputs: SELECT * FROM `t` WHERE `a` IN (2, 4.5, 'apple', 'car')
     * @endcode
     *
     * @param $mixed array or parameters or just one paramter
     * @return string which is ready to pass to IN(...) SQL construction
     */
    public function implode_escape ($mixed)
    {
        if (is_array($mixed)) {
            $s = '';
            foreach ($mixed as $v)
                $s .= (is_numeric($v) ? $v : $this->escape($v)) . ',';
            if ($s)
                return substr($s, 0, -1);
            else
                return 'NULL';
        }

        return is_numeric($mixed) ? $mixed : ($mixed ? $this->escape($mixed) : 'NULL');
    }

    /**
     * @deprecated
     */
    public function unescape ($mixed)
    {
        if(is_array($mixed)) {
            foreach($mixed as $k => $v)
				$mixed[$k] = $this->getOne("SELECT '$v'");

            return $mixed;
        } 
        else
            return $this->getOne("SELECT '$mixed'");
    }

    /**
     * Prepare SQL query before execution if some arguments are need to be passed to it.
     * All parameters marked with question (?) symbol in SQL query are replaced with parameters passed after SQL query parameter.
     * Parameters are properly excaped and surrounded by qutes if needed.
     * Example:
     * @code
     * $sSql = $oDb->prepare("SELECT `a`, `b` from `t` WHERE `c` = ? and `d` = ?", 12, 'aa');
     * echo $sSql;// outputs: SELECT `a`, `b` from `t` WHERE `c` = 12 and `d` = 'aa'
     * $a = $oDb->getAll($sSql);
     * @endcode
     *
     * @param  string $sQuery SQL query, parameters for replacing are marked with ? symbol
     * @param  mixed  $mixed  any number if parameters to replace, number of parameters whould match number of ? symbols in SQL query
     * @return PDOStatement object with SQL query ready for execution
     */
    public function prepare($sQuery)
    {
    	if(!self::$_rLink)
    		return false;

        $aArgs = func_get_args();
        $sQuery = array_shift($aArgs);

        $oStatement = self::$_rLink->prepare($sQuery);

        $iIndex = 1;
        foreach($aArgs as $mixedArg) {
        	if(is_null($mixedArg))
				$iValueType = PDO::PARAM_NULL;
            else if(is_numeric($mixedArg))
                $iValueType = PDO::PARAM_INT;
            else
                $iValueType = PDO::PARAM_STR;

        	$oStatement->bindValue($iIndex++, $mixedArg, $iValueType);
        }

        return $oStatement;
    }

    /**
     * Prepare SQL query before execution if some arguments are need to be passed to it.
     * All parameters marked with question (?) symbol in SQL query are replaced with parameters passed after SQL query parameter.
     * Parameters are properly excaped and surrounded by qutes if needed.
     * Example:
     * @code
     * $sSql = $oDb->prepare("SELECT `a`, `b` from `t` WHERE `c` = ? and `d` = ?", 12, 'aa');
     * echo $sSql;// outputs: SELECT `a`, `b` from `t` WHERE `c` = 12 and `d` = 'aa'
     * $a = $oDb->getAll($sSql);
     * @endcode
     *
     * @param  string $sQuery SQL query, parameters for replacing are marked with ? symbol
     * @param  mixed  $mixed  any number if parameters to replace, number of parameters whould match number of ? symbols in SQL query
     * @return string with SQL query. 
     */
    function prepareAsString($sQuery)
    {
        $aArgs = func_get_args();
        $sQuery = array_shift($aArgs);

        $iPos = 0;
        foreach ($aArgs as $mixedArg) {
            if (is_null($mixedArg))
                $s = 'NULL';
            elseif (is_numeric($mixedArg))
                $s = $mixedArg;
            else
                $s = $this->escape($mixedArg);

            $i = bx_mb_strpos($sQuery, '?', $iPos);
            $sQuery = bx_mb_substr_replace($sQuery, $s, $i, 1);
            $iPos = $i + get_mb_len($s);
        }

        return $sQuery;
    }

    /**
     * Convert array of key => values to SQL query.
     * Array keys are field names and array values are field values.
     * @param $a array
     * @param $sDiv fields separator, by default it is ',', another useful value is ' AND '
     * @return part of SQL query string
     */
    public function arrayToSQL($a, $sDiv = ',')
    {
        $s = '';
        foreach($a as $k => $v)
            $s .= "`{$k}` = " . $this->escape($v) . $sDiv;

        return trim($s, $sDiv);
    }

    protected function log($s)
    {
        if (defined('BX_DIRECTORY_PATH_LOGS')) {
            $sPath = BX_DIRECTORY_PATH_LOGS;
        }
        else {
            $sDirName = pathinfo(__FILE__, PATHINFO_DIRNAME);
            $sPath = $sDirName . '/../../logs/';
        }
        return file_put_contents($sPath . 'db.err.log', date('Y-m-d H:i:s') . "\t" . $s . "\n", FILE_APPEND);
    }

    public function executeSQL($sPath, $aReplace = array (), $isBreakOnError = true)
    {
        if(!file_exists($sPath) || !($rHandler = fopen($sPath, "r")))
            return array(array ('query' => "fopen($sPath, 'r')", 'error' => 'file not found or permission denied'));

		self::$_rLink->setAttribute(PDO::ATTR_ERRMODE, BX_DB_MODE_SILENT);

        $sQuery = "";
        $sDelimiter = ';';
        $aResult = array();
        while(!feof($rHandler)) {
            $sStr = trim(fgets($rHandler));

            if(empty($sStr) || $sStr[0] == "" || $sStr[0] == "#" || ($sStr[0] == "-" && $sStr[1] == "-"))
                continue;

            //--- Change delimiter ---//
            if(strpos($sStr, "DELIMITER //") !== false || strpos($sStr, "DELIMITER ;") !== false) {
                $sDelimiter = trim(str_replace('DELIMITER', '', $sStr));
                continue;
            }

            $sQuery .= $sStr;

            //--- Check for multiline query ---//
            if(substr($sStr, -strlen($sDelimiter)) != $sDelimiter)
                continue;

            //--- Execute query ---//
            if ($aReplace)
                $sQuery = str_replace($aReplace['from'], $aReplace['to'], $sQuery);
            if($sDelimiter != ';')
                $sQuery = str_replace($sDelimiter, "", $sQuery);

            if($this->query(trim($sQuery), array(), false) === false) {
                $aResult[] = array('query' => $sQuery, 'error' => $this->_aError['message']);
                if ($isBreakOnError)
                    break;
            }

            $sQuery = "";
        }
        fclose($rHandler);

        self::$_rLink->setAttribute(PDO::ATTR_ERRMODE, $this->_iPdoErrorMode);

        return empty($aResult) ? true : $aResult;
    }

    protected function executeStatement($oStatement, $aBindings = array(), $bVerbose = null)
    {
    	$bResult = false;

    	switch (self::$_rLink->getAttribute(PDO::ATTR_ERRMODE)) {
    		case PDO::ERRMODE_SILENT:
    			$bResult = $this->executeStatementSilent($oStatement, $aBindings, $bVerbose);
    			break;

    		case PDO::ERRMODE_EXCEPTION:
    			$bResult = $this->executeStatementException($oStatement, $aBindings, $bVerbose);
    			break;
    	}

    	return $bResult;
    }

    protected function executeStatementException($oStatement, $aBindings = array(), $bVerbose = null)
    {
    	$bResult = false;

    	try {
			$bResult = $oStatement->execute(!empty($aBindings) && is_array($aBindings) ? $aBindings : null);
		}
		catch (PDOException $oException) {
			$aError = $oStatement->errorInfo();

			$oException->errorInfo[self::$_sErrorKey] = array(
				'code' => BX_DB_ERR_QUERY_ERROR,
				'message' => !empty($aError[2]) ? $aError[2] : $oException->getMessage(),
				'query' => $oStatement->queryString,
				'trace' => $oException->getTrace(),
				'verbose' => $bVerbose
			);

			throw $oException;
		}

		return $bResult;
    }

    protected function executeStatementSilent($oStatement, $aBindings = array(), $bVerbose = null)
    {
    	$bResult = $oStatement->execute(!empty($aBindings) && is_array($aBindings) ? $aBindings : null);
    	if($bResult)
    		return true;

		$aError = $oStatement->errorInfo();

        $aTrace = debug_backtrace();
        unset($aTrace[0]);

		$this->_aError = array(
			'code' => BX_DB_ERR_QUERY_ERROR,
			'message' => !empty($aError[2]) ? $aError[2] : '',
			'query' => $oStatement->queryString,
			'trace' => $aTrace,
			'verbose' => $bVerbose
		);

		return false;
    }

	protected function errorOutput($aError)
    {
		$aErrorLocation = array();

        if(!empty($aError['query']) && !empty($aError['trace']))
            foreach($aError['trace'] as $aCall )
                if(isset($aCall['args']) && is_array($aCall['args']))
                    foreach($aCall['args'] as $argNum => $argVal)
                        if((is_string($argVal) && strcmp($argVal, $aError['query']) == 0) || ($argVal instanceof PDOStatement && strcmp($argVal->queryString, $aError['query']) == 0)) {
                            $aErrorLocation['file'] = isset($aCall['file']) ? $aCall['file'] : (isset($aCall['class']) ? 'class: ' . $aCall['class'] : 'undefined');
                            $aErrorLocation['line'] = isset($aCall['line']) ? $aCall['line'] : 'undefined';
                            $aErrorLocation['function'] = $aCall['function'];
                            $aErrorLocation['arg'] = $argNum;
                        }

        $sOutput = '';
        if(!empty($aError['query']))
            $sOutput .= '<p><b>Query:</b><br />' . $aError['query'] . '</p>';

        if(!empty($aError['message']))
            $sOutput .= '<p><b>Mysql error:</b><br />' . $aError['message'] . '</p>';

		if(!empty($aErrorLocation))
			$sOutput .= '<p><b>Location:</b><br />The error was found in <b>' . $aErrorLocation['function'] . '</b> function in the file <b>' . $aErrorLocation['file'] . '</b> at line <b>' . $aErrorLocation['line'] . '</b>.</p>';

		if(!empty($aError['trace'])) {
			$sBackTrace = print_r($aError['trace'], true);
            $sBackTrace = str_replace('[_sUser:protected] => ' . BX_DATABASE_USER, '[_sUser:protected] => *****', $sBackTrace);
            $sBackTrace = str_replace('[_sPassword:protected] => ' . BX_DATABASE_PASS, '[_sPassword:protected] => *****', $sBackTrace);

			$sOutput .= '<div><b>Debug backtrace:</b></div><div style="overflow:scroll;height:300px;border:1px solid gray;"><pre>' . htmlspecialchars_adv($sBackTrace) . '</pre></div>';
		}

		if(!empty(self::$_aParams)) {
			$sSettings = var_export(self::$_aParams, true);

			$sOutput .= '<div><b>Settings:</b></div><div style="overflow:scroll;height:300px;border:1px solid gray;"><pre>' . htmlspecialchars_adv($sSettings) . '</pre></div>';
		}

		$sOutput .= '<p><b>Called script:</b><br />' . $_SERVER['PHP_SELF'] . '</p>';

		if(!empty($_REQUEST)) {
			$sRequest = var_export($_REQUEST, true);

			$sOutput .= '<p><b>Request parameters:</b><br /><pre>' . htmlspecialchars_adv($sRequest) . '</pre></p>';
		}

        return $sOutput;
    }
}

/**
 * Create the very first instance and initiate connetion to database.
 */
BxDolDb::getInstance();

function getParam($sParamName, $bUseCache = true)
{
    return BxDolDb::getInstance()->getParam($sParamName, $bUseCache);
}

function setParam($sParamName, $sParamVal)
{
    return BxDolDb::getInstance()->setParam($sParamName, $sParamVal);
}

/** @} */

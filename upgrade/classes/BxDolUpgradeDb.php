<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaUpgrade UNA Upgrade Script
 * @{
 */

define('BX_UPGRADE_DB_FULL_VISUAL_PROCESSING', true);
define('BX_UPGRADE_DB_FULL_DEBUG_MODE', true);

define('BX_UPGRADE_DB_ERR_CONNECT_FAILD', 1);
define('BX_UPGRADE_DB_ERR_QUERY_ERROR', 2);
define('BX_UPGRADE_DB_ERR_ESCAPE', 3);

define('BX_UPGRADE_PDO_STATE_SUCCESS', '00000');

class BxDolUpgradeDb
{	
    protected static $_rLink;
    protected static $_aDbCacheData;

    protected static $_aParams;
    protected static $_sParamsCacheName = 'sys_options';
    protected static $_sParamsCacheNameMixed = 'sys_options_mixed';

    protected static $_sErrorKey = 'bx_db_error';
    protected static $_aErrors = array(
    	BX_UPGRADE_DB_ERR_CONNECT_FAILD => 'Database connect failed',
    	BX_UPGRADE_DB_ERR_QUERY_ERROR => 'Database query error',
    	BX_UPGRADE_DB_ERR_ESCAPE => 'Escape string error'
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
    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

		$this->_bPdoPersistent = true;
        $this->_iPdoFetchType = PDO::FETCH_ASSOC;
        $this->_iPdoErrorMode = PDO::ERRMODE_EXCEPTION;

        $this->_bErrorChecking = true;
        $this->_aError = array();

        $this->_sStorageEngine = 'MYISAM';

        $this->_sCharset = 'utf8mb4';

        $this->_sHost = is_array(BX_DATABASE_HOST) ? array_values(BX_DATABASE_HOST)[0] : BX_DATABASE_HOST;
        $this->_sPort = is_array(BX_DATABASE_PORT) ? array_values(BX_DATABASE_PORT)[0] : BX_DATABASE_PORT;
        $this->_sSocket = is_array(BX_DATABASE_SOCK) ? array_values(BX_DATABASE_SOCK)[0] : BX_DATABASE_SOCK;
        $this->_sDbname = is_array(BX_DATABASE_NAME) ? array_values(BX_DATABASE_NAME)[0] : BX_DATABASE_NAME;
        $this->_sUser = is_array(BX_DATABASE_USER) ? array_values(BX_DATABASE_USER)[0] : BX_DATABASE_USER;
        $this->_sPassword = is_array(BX_DATABASE_PASS) ? array_values(BX_DATABASE_PASS)[0] : BX_DATABASE_PASS;

        @set_exception_handler(array($this, 'pdoExceptionHandler'));
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__])) {
            if(!defined('BX_DATABASE_HOST'))
                return null;

            $o = new BxDolUpgradeDb();
            $o->connect();

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

	    	$this->pdoExec("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'");
            $this->pdoExec("SET sql_mode = ''");

            $sVer = $this->getVersion();
            $sStorageEngine = !$sVer || version_compare($sVer, '5.7.5', '>=') ? 'default_storage_engine' : 'storage_engine';
            $this->pdoExec("SET $sStorageEngine=" . $this->_sStorageEngine);

			self::$_aDbCacheData = array();
    	}
    	catch (PDOException $oException) {
    		$oException->errorInfo[self::$_sErrorKey] = array(
    			'code' => BX_UPGRADE_DB_ERR_CONNECT_FAILD,
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

		if($oStatement->errorCode() == BX_UPGRADE_PDO_STATE_SUCCESS)
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
     * get mysql version
     */
    public function getVersion()
    {
        $s = $this->getOne("SELECT VERSION()");
        return preg_match("/([0-9\.]+)/", $s, $m) ? $m[1] : false;
    }
    
    /**
     * get list of tables in database
     */
    public function listTables()
    {
    	$oStatement = $this->pdoQuery("SHOW TABLES FROM `" . BX_DATABASE_NAME . "`");

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

	public function isIndexExists($sTable, $sIndexName)
	{
        $aIndexes = $this->getAll("SHOW INDEXES FROM `" . $sTable . "`");
        foreach ($aIndexes as $aIndex)
			if ($aIndex['Key_name'] == $sIndexName)
				return true;

		return false;
    }
    
    public function error($aError)
    {
    	$sErrorType = self::$_aErrors[$aError['code']];

    	$bVerbose = isset($aError['verbose']) ? (bool)$aError['verbose'] : $this->_bErrorChecking;
        if(!$bVerbose) {
			$this->log($sErrorType . ': ' . $aError['message']);
			return;
        }

        if(defined('BX_UPGRADE_DB_FULL_VISUAL_PROCESSING') && BX_UPGRADE_DB_FULL_VISUAL_PROCESSING) {
            $sOutput = '<div style="border:2px solid red;padding:4px;width:600px;margin:0px auto;">';
            $sOutput .= '<div style="text-align:center;background-color:red;color:white;font-weight:bold;">Error</div>';
            $sOutput .= '<div style="text-align:center;">' . $sErrorType . '</div>';
            if(defined('BX_UPGRADE_DB_FULL_DEBUG_MODE') && BX_UPGRADE_DB_FULL_DEBUG_MODE)
				$sOutput .= $this->errorOutput($aError);
            $sOutput .= '</div>';
            echo $sOutput;
        }
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
    			'code' => BX_UPGRADE_DB_ERR_ESCAPE,
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

    public function getCache ($sName, $sFunc)
    {
        return false;
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

		self::$_rLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

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
				'code' => BX_UPGRADE_DB_ERR_QUERY_ERROR,
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
			'code' => BX_UPGRADE_DB_ERR_QUERY_ERROR,
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

/** @} */

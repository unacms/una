<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

define('BX_PDO_STATE_NOT_EXECUTED', NULL);
define('BX_PDO_STATE_SUCCESS', '00000');

class BxDolDb extends BxDol implements iBxDolSingleton
{
	protected $_bPdoPersistent;
	protected $_iPdoFetchType;
	protected $_iPdoErrorMode;

	protected $_bErrorChecking;
    protected $_sErrorMessage;

    protected $_sStorageEngine;

	protected $_sHost, $_sPort, $_sSocket, $_sDbname, $_sUser, $_sPassword, $_sCharset;
	

	
	
    
    protected $_rLink, $_rCurrentRes;

    protected $_oDbCacheObject = null;
    protected $_aParams = null;
    protected $_sParamsCacheName = 'sys_options';
    protected $_sParamsCacheNameMixed = 'sys_options_mixed';

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
        $this->_iPdoErrorMode = PDO::ERRMODE_EXCEPTION; //PDO::ERRMODE_SILENT

        $this->_bErrorChecking = true;
        $this->_sErrorMessage = '';

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

        // connect to db automatically
        if (empty($GLOBALS['bx_db__rLink'])) {
            $this->connect();
            $GLOBALS['gl_db_cache'] = array();
        }
        else
            $this->_rLink = $GLOBALS['bx_db__rLink'];

        @set_exception_handler(array($this, 'pdoQueryExceptionHandler'));
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
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__])) {
            if (false === $aDbConf && !defined('BX_DATABASE_HOST'))
                return null;
            $o = new BxDolDb($aDbConf);
            $sErrorMessage = $o->connect();
            if ($sErrorMessage) {
                if ($sError !== null)
                    $sError = $sErrorMessage;
                return null;
            } else {
                $GLOBALS['bxDolClasses'][__CLASS__] = $o;
            }
        }

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * connect to database with appointed parameters
     */
    public function connect()
    {
    	try {
	    	$sDsn = "mysql:host=" . $this->_sHost . ";";
	   		$sDsn .= $this->_sPort ? "port=" . $this->_sPort . ";" : "";
	   		$sDsn .= $this->_sSocket ? "unix_socket=" . $this->_sSocket . ";" : "";
	    	$sDsn .= "dbname=" . $this->_sDbname . ";charset=" . $this->_sCharset;
	
	        $this->_rLink = new PDO($sDsn, $this->_sUser, $this->_sPassword, array(
				PDO::ATTR_ERRMODE => $this->_iPdoErrorMode,
				PDO::ATTR_DEFAULT_FETCH_MODE => $this->_iPdoFetchType,
				PDO::ATTR_PERSISTENT => $this->_bPdoPersistent
	        ));

	    	$this->_rLink->exec("SET NAMES 'utf8'");
	        $this->_rLink->exec("SET sql_mode = ''");
			$this->_rLink->exec("SET storage_engine=" . $this->_sStorageEngine);
    	}
    	catch (PDOException $e) {
    		$this->_sErrorMessage = $e->getMessage();
    		$this->error('Database connect failed');
    		return;
    	}

        $GLOBALS['bx_db__rLink'] = $this->_rLink;
        return;
    }

    /**
     * close mysql connection
     */
    public function close()
    {
        $this->_rLink = null;
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
    	return $this->_rLink->exec($sQuery);
    }

    /**
     * Executes query and returns PDOStatement object or false 
     */
	public function pdoQuery($sQuery)
    {
    	return $this->_rLink->query($sQuery);
    }

    /**
     * database query exception handler for exceptions appeared out of the try/catch block
     */
    public function pdoQueryExceptionHandler($oException)
    {
		$this->_sErrorMessage = $oException->getMessage();
    	$this->error('Database query error');
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

        return count($aResult) ? $aResult[$iIndex] : false;
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

        if($this->res($oStatement, $aBindings))
            $aResult = $oStatement->fetch($iFetchType);

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

        if($this->res($oStatement, $aBindings))
			$aResult = $oStatement->fetchAll(PDO::FETCH_COLUMN, $iFetchColumnNumber);

        return $aResult;
    }

	/**
     * execute sql query and return the first row of result
     * and keep $array type and poiter to all data
     */
    public function getFirstRow($oStatement, $aBindings = array(), $iFetchType = PDO::FETCH_ASSOC)
    {
    	$aResult = array();
        if(!$oStatement)
            return $aResult;
		else if(!($oStatement instanceof PDOStatement) && is_string($oStatement))
			$oStatement = $this->prepare($oStatement);

        if(!in_array($iFetchType, array(PDO::FETCH_NUM, PDO::FETCH_ASSOC, PDO::FETCH_BOTH)))
            $iFetchType = $this->_iPdoFetchType;

        if($this->res($oStatement, $aBindings))
            $aResult = $oStatement->fetch($iFetchType);

        return $aResult;
    }

    /**
     * return next row of pointed last getFirstRow calling data
     */
    public function getNextRow($oStatement, $iFetchType = PDO::FETCH_ASSOC)
    {
    	if(!$oStatement)
            return array();
		else if(!($oStatement instanceof PDOStatement) && is_string($oStatement))
			$oStatement = $this->prepare($oStatement);

		if(!in_array($iFetchType, array(PDO::FETCH_NUM, PDO::FETCH_ASSOC, PDO::FETCH_BOTH)))
            $iFetchType = $this->_iPdoFetchType;

    	return $oStatement->fetch($iFetchType);
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

        if($this->res($oStatement, $aBindings))
        	$aResult = $oStatement->fetchAll($iFetchType);

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
        while($aRow !== false) {
        	$aResult[$aRow[$sFieldKey]] = $aRow;

        	$aRow = $this->getNextRow($oStatement, PDO::FETCH_ASSOC);
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
        while($aRow !== false) {
        	$aResult[$aRow[$sFieldKey]] = $aRow[$sFieldValue];

        	$aRow = $this->getNextRow($oStatement, PDO::FETCH_ASSOC);
        }

        return $aResult;
    }

    /**
     * return number of affected rows in current mysql result
     * 
     * NOTE: PDOStatement::rowCount works for SELECT queries in MySQL.
     * So, this method should be rewritten if the other DB engine will be used.
     */
    public function getNumRows($oStatement)
    {
    	return $oStatement->rowCount();
    }

    /**
     * returns the number of rows affected by the last DELETE, INSERT, or UPDATE statement. 
     */
    public function getAffectedRows($oStatement)
    {
        return $oStatement->rowCount();
    }

    public function lastId()
    {
        return $this->_rLink->lastInsertId();
    }

    /**
     * execute any query return number of rows affected/false
     */
    public function query($oStatement, $aBindings = array())
    {
    	if(!$oStatement)
            return false;
		else if(!($oStatement instanceof PDOStatement) && is_string($oStatement))
			$oStatement = $this->prepare($oStatement);

        if($this->res($oStatement, $aBindings))
            return $oStatement->rowCount();

        return false;
    }

    /**
     * execute any query
     */
    public function res($oStatement, $aBindings = array(), $bErrorChecking = true)
    {
		if(!$oStatement || !($oStatement instanceof PDOStatement))
            return false;

		if($oStatement->errorCode() == BX_PDO_STATE_SUCCESS)
			return true;

        if(isset($GLOBALS['bx_profiler']))
        	$GLOBALS['bx_profiler']->beginQuery($oStatement->queryString);

		$bResult = $oStatement->execute(!empty($aBindings) && is_array($aBindings) ? $aBindings : null);
        //$bResult = !empty($aBindings) && is_array($aBindings) ? $oStatement->execute($aBindings) : $oStatement->execute();

        // we need to remeber last error message since mysql_ping will reset it on the next line !
        $this->_sErrorMessage = $bResult == false ? $oStatement->errorInfo() : '';

		//if mysql connection is lost - reconnect and try again
        if(!$bResult && !$this->ping()) {
            $this->close();

            $sErrorMessage = $this->connect();
            if($sErrorMessage)
                $this->error($sErrorMessage, true);

            $bResult = $oStatement->execute(!empty($aBindings) && is_array($aBindings) ? $aBindings : null);
        }

        if(isset($GLOBALS['bx_profiler']))
        	$GLOBALS['bx_profiler']->endQuery($bResult);

        if(!$bResult && $bErrorChecking)
            $this->error('Database query error', false, $oStatement->queryString);

        return $bResult;
    }

    /**
     * get mysql server info
     */
    public function getServerInfo()
    {
    	return $this->_rLink->getAttribute(PDO::ATTR_SERVER_VERSION);
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
    	$oStatement = $this->pdoQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE  TABLE_SCHEMA = '" . $this->_sDbname . "' AND TABLE_NAME = '" . $sTable . "'");
    	$aFieldNames = $this->getColumn($oStatement);

        $aResult = array('original' => array(), 'uppercase' => array());
        foreach($aFieldNames as $sFieldName) {
            $aResult['original'][] = $sFieldName;
            $aResult['uppercase'][] = strtoupper($sFieldName);
        }

        return $aResult;
    }

    public function isFieldExists($sTable, $sFieldName)
    {
        $aFields = $this->getFields($sTable);
        return in_array(strtoupper($sFieldName), $aFields['uppercase']);
    }

    public function getErrorMessage ()
    {
		if(!empty($this->_sErrorMessage))
			return $this->_sErrorMessage;

		$aError = $this->_rLink->errorInfo();
        if(!empty($aError[2]))
            return $aError[2];

        return 'Database error';
    }

    public function error($sText, $isForceErrorChecking = false, $sSqlQuery = '')
    {
        if ($this->_bErrorChecking || $isForceErrorChecking)
            $this->genMySQLErr($sText, $sSqlQuery);
        else
            $this->log($sText . ': ' . $this->getErrorMessage());
    }

    protected function isParamInCache($sKey)
    {
        return is_array($this->_aParams) && isset($this->_aParams[$sKey]);
    }

    protected function cacheParams($bForceCacheInvalidate = false)
    {
        if ($bForceCacheInvalidate)
            $this->cacheParamsClear();

        $this->_aParams = $this->fromCache($this->_sParamsCacheName, 'getPairs', "SELECT `name`, `value` FROM `sys_options`", "name", "value");

        $aMixed = $this->fromCache($this->_sParamsCacheNameMixed, 'getPairs', "SELECT `tmo`.`option` AS `option`, `tmo`.`value` AS `value` FROM `sys_options_mixes2options` AS `tmo` INNER JOIN `sys_options_mixes` AS `tm` ON `tmo`.`mix_id`=`tm`.`id` AND `tm`.`active`='1'", "option", "value");
        if(!empty($aMixed))
        	$this->_aParams = array_merge($this->_aParams, $aMixed);

        if (empty($this->_aParams)) {
            $this->_aParams = array ();
            return false;
        }

        return true;
    }

    public function cacheParamsClear()
    {
        return $this->cleanCache($this->_sParamsCacheName);
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
            return $this->_aParams[$sKey];
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

    public function getEncoding()
    {
    	$oStatement = $this->pdoQuery('SELECT @@character_set_database');
    	if($oStatement === false)
    		return $this->error('Database get encoding error');

    	return $this->getOne($oStatement);
    }

    public function genMySQLErr($sOutput, $sQuery = '')
    {
        $sParamsOutput = false;
        $sFoundError = '';

        $aBackTrace = debug_backtrace();
        unset( $aBackTrace[0] );

        if( $sQuery ) {
            //try help to find error

            $aFoundError = array();

            foreach( $aBackTrace as $aCall ) {

                // truncating global settings since it repeated many times and output it separately
                if (isset($aCall['object']) && is_a($aCall['object'], 'BxDolDb')) {
                    if (false === $sParamsOutput)
                        $sParamsOutput = var_export($aCall['object']->_aParams, true);
                    $aCall['object']->_aParams = '[truncated]';
                }

                if (isset($aCall['args']) && is_array($aCall['args'])) {
                    foreach( $aCall['args'] as $argNum => $argVal ) {
                        if( is_string($argVal) and strcmp( $argVal, $sQuery ) == 0 ) {
                            $aFoundError['file']     = isset($aCall['file']) ? $aCall['file'] : (isset($aCall['class']) ? 'class: ' . $aCall['class'] : 'undefined');
                            $aFoundError['line']     = isset($aCall['line']) ? $aCall['line'] : 'undefined';
                            $aFoundError['function'] = $aCall['function'];
                            $aFoundError['arg']      = $argNum;
                        }
                    }
                }
            }

            if( $aFoundError ) {
                $sFoundError = <<<EOJ
Found error in the file '<b>{$aFoundError['file']}</b>' at line <b>{$aFoundError['line']}</b>.<br />
Called '<b>{$aFoundError['function']}</b>' function.<br /><br />
EOJ;
            }
        }

        if (defined('BX_DB_FULL_VISUAL_PROCESSING') && BX_DB_FULL_VISUAL_PROCESSING) {
            ob_start();

            ?>
                <div style="border:2px solid red;padding:4px;width:600px;margin:0px auto;">
                    <div style="text-align:center;background-color:red;color:white;font-weight:bold;">Error</div>
                    <div style="text-align:center;"><?php echo $sOutput; ?></div>
            <?php
            if (defined('BX_DB_FULL_DEBUG_MODE') && BX_DB_FULL_DEBUG_MODE)
                echo $this->verboseErrorOutput ($sQuery, $sFoundError, $aBackTrace, $sParamsOutput);
            ?>
                </div>
            <?php

            $sOutput = ob_get_clean();
        } 

        if (defined('BX_DB_DO_EMAIL_ERROR_REPORT') && BX_DB_DO_EMAIL_ERROR_REPORT) {
            $sSiteTitle = $this->getParam('site_title');
            $sMailBody = "Database error in " . $sSiteTitle . "<br /><br /> \n";

            $sMailBody .= $this->verboseErrorOutput ($sQuery, $sFoundError, $aBackTrace, $sParamsOutput);
            $sMailBody .= "<hr />Auto-report system";

            sendMail($this->getParam('site_email'), "Database error in " . $sSiteTitle, $sMailBody, 0, array(), BX_EMAIL_SYSTEM, 'html', true);
        }

        bx_show_service_unavailable_error_and_exit($sOutput);
    }

    protected function verboseErrorOutput ($sQuery, $sFoundError, &$aBackTrace, $sParamsOutput)
    {
        ob_start();

        if (!empty($sQuery))
            echo "<div><b>Query:</b><br />{$sQuery}</div>";

        if ($this->_rLink)
            echo '<div><b>Mysql error:</b><br />' . $this->getErrorMessage() . '</div>';

        echo '<div style="overflow:scroll;height:300px;border:1px solid gray;">';
            echo $sFoundError;
            echo "<b>Debug backtrace:</b><br />";

            $sBackTrace = print_r($aBackTrace, true);
            $sBackTrace = str_replace('[password] => ' . BX_DATABASE_PASS, '[password] => *****', $sBackTrace);
            $sBackTrace = str_replace('[user] => ' . BX_DATABASE_USER, '[user] => *****', $sBackTrace);

            echo '<pre>' . htmlspecialchars_adv($sBackTrace) . '</pre>';

            if ($sParamsOutput) {
                echo '<hr />';
                echo "<b>Settings:</b><br />";
                echo '<pre>' . htmlspecialchars_adv($sParamsOutput) . '</pre>';
            }

            echo "<b>Called script:</b> " . $_SERVER['PHP_SELF'] . "<br />";
            echo "<b>Request parameters:</b><br />";
            echoDbg( $_REQUEST );
        echo '</div>';

        return ob_get_clean();
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
        if ($this->_oDbCacheObject != null) {
            return $this->_oDbCacheObject;
        } else {
            $sEngine = $this->getParam('sys_db_cache_engine');
            $this->_oDbCacheObject = bx_instance ('BxDolCache'.$sEngine);
            if (!$this->_oDbCacheObject->isAvailable())
                $this->_oDbCacheObject = bx_instance ('BxDolCacheFile');
            return $this->_oDbCacheObject;
        }
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
        if (!$this->getParam('sys_db_cache_enable'))
            return true;

        $oCache = $this->getDbCacheObject();

        $sKey = $this->genDbCacheKey($sName);

        return $oCache->delData($sKey);
    }

    public function & fromMemory ($sName, $sFunc)
    {
        if (array_key_exists($sName, $GLOBALS['gl_db_cache'])) {
            return $GLOBALS['gl_db_cache'][$sName];

        } else {
            $aArgs = func_get_args();
            array_shift ($aArgs); // shift $sName
            array_shift ($aArgs); // shift $sFunc
            $GLOBALS['gl_db_cache'][$sName] = call_user_func_array (array ($this, $sFunc), $aArgs); // pass other function parameters as database function parameters
            return $GLOBALS['gl_db_cache'][$sName];

        }
    }

    public function cleanMemory ($sName)
    {
        if (isset($GLOBALS['gl_db_cache'][$sName])) {
            unset($GLOBALS['gl_db_cache'][$sName]);
            return true;
        }
        return false;
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
    		$s = $this->_rLink->quote($s);
    	}
    	catch (PDOException $e) {
    		$this->error('Escape string error');
    		return false;
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
    	if(!$this->_rLink)
    		return false;

        $aArgs = func_get_args();
        $sQuery = array_shift($aArgs);

        $oStatement = $this->_rLink->prepare($sQuery);

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
        return file_put_contents(BX_DIRECTORY_PATH_LOGS . 'db.err.log', date('Y-m-d H:i:s') . "\t" . $s . "\n", FILE_APPEND);
    }

    public function executeSQL($sPath, $aReplace = array (), $isBreakOnError = true)
    {
        if(!file_exists($sPath) || !($rHandler = fopen($sPath, "r")))
            return array(array ('query' => "fopen($sPath, 'r')", 'error' => 'file not found or permission denied'));

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
            $rResult = $this->res(trim($sQuery), false);
            if(!$rResult) {
                $aResult[] = array('query' => $sQuery, 'error' => $this->getErrorMessage());
                if ($isBreakOnError)
                    break;
            }

            $sQuery = "";
        }
        fclose($rHandler);

        return empty($aResult) ? true : $aResult;
    }
}

function getParam($sParamName, $bUseCache = true)
{
    return BxDolDb::getInstance()->getParam($sParamName, $bUseCache);
}

function setParam($sParamName, $sParamVal)
{
    return BxDolDb::getInstance()->setParam($sParamName, $sParamVal);
}

/** @} */

<?php defined('BX_DOL') or defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

class BxDolDb extends BxDol implements iBxDolSingleton {

    var $error_checking = true;
    var $error_message;
    var $host, $port, $socket, $dbname, $user, $password, $link;
    var $current_res, $current_arr_type;

    var $oDbCacheObject = null;

    /**
     * set database parameters and connect to it
     */
    protected function BxDolDb($aDbConf = false) {

        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::BxDol();

        if (false === $aDbConf) {
            $this->host = BX_DATABASE_HOST;
            $this->port = BX_DATABASE_PORT;
            $this->socket = BX_DATABASE_SOCK;
            $this->dbname = BX_DATABASE_NAME;
            $this->user = BX_DATABASE_USER;
            $this->password = BX_DATABASE_PASS;
        } else {
            $this->host = $aDbConf['host'];
            $this->port = $aDbConf['port'];
            $this->socket = $aDbConf['sock'];
            $this->dbname = $aDbConf['name'];
            $this->user = $aDbConf['user'];
            $this->password = $aDbConf['pwd'];
            $this->error_checking = isset($aDbConf['error_checking']) ? $aDbConf['error_checking'] : true;
        }

        $this->current_arr_type = MYSQL_ASSOC;

        // connect to db automatically
        if (empty($GLOBALS['bx_db_link'])) {
            $this->connect();
            $GLOBALS['gl_db_cache'] = array();
        } else {
            $this->link = $GLOBALS['bx_db_link'];
        }
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone() {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    public function __get($sName)
    {
        if ('oParams' == $sName) {
            bx_import('BxDolParams');
            return BxDolParams::getInstance($this);
        }

        $aTrace = debug_backtrace();
        trigger_error('Undefined property via __get(): ' . $sName . ' in ' . $aTrace[0]['file'] . ' on line ' . $aTrace[0]['line'], E_USER_NOTICE);

        return null;
    }

    public function __isset($sName)
    {
        if ('oParams' == $sName)
            return true;
        return false;
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance($aDbConf = false, &$sError = null) {
        if (!isset($GLOBALS['bxDolClasses'][__CLASS__])) {
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
    function connect()
    {
        $full_host = $this->host;
        $full_host .= $this->port ? ':'.$this->port : '';
        $full_host .= $this->socket ? ':'.$this->socket : '';

        $this->link = @mysql_pconnect($full_host, $this->user, $this->password);
        if (!$this->link)
            return 'Database connect failed';

        if (!$this->select_db())
            return 'Database select failed';

        mysql_query("SET NAMES 'utf8'", $this->link);
        mysql_query("SET sql_mode = ''", $this->link);

        $GLOBALS['bx_db_link'] = $this->link;

        return '';
    }

    function select_db()
    {
        return @mysql_select_db($this->dbname, $this->link);
    }

    /**
     * close mysql connection
     */
    function close()
    {
        mysql_close($this->link);
    }

    /**
     * get mysql server info
     */
    function getServerInfo()
    {
        return mysql_get_server_info($this->link);
    }

    /**
     * get mysql option
     */
    function getOption($sName)
    {
        return $this->getOne("SELECT @@{$sName}");
    }

    /**
     * execute sql query and return one row result
     */
    function getRow($query, $arr_type = MYSQL_ASSOC)
    {
        if(!$query)
            return array();
        if($arr_type != MYSQL_ASSOC && $arr_type != MYSQL_NUM && $arr_type != MYSQL_BOTH)
            $arr_type = MYSQL_ASSOC;
        $res = $this->res ($query);
        $arr_res = array();
        if($res && mysql_num_rows($res))
        {
            $arr_res = mysql_fetch_array($res, $arr_type);
            mysql_free_result($res);
        }
        return $arr_res;
    }
    /**
     * execute sql query and return a column as result
     */
    function getColumn($sQuery) {
        if(!$sQuery)
            return array();

        $rResult = $this->res($sQuery);

        $aResult = array();
        if($rResult) {
            while($aRow = mysql_fetch_array($rResult, MYSQL_NUM))
                $aResult[] = $aRow[0];
            mysql_free_result($rResult);
        }
        return $aResult;
    }

    /**
     * execute sql query and return one value result
     */
    function getOne($query, $index = 0)
    {
        if(!$query)
            return false;
        $res = $this->res ($query);
        $arr_res = array();
        if($res && mysql_num_rows($res))
            $arr_res = mysql_fetch_array($res);
        if(count($arr_res))
            return $arr_res[$index];
        else
            return false;
    }

    /**
     * execute sql query and return the first row of result
     * and keep $array type and poiter to all data
     */
    function getFirstRow($query, $arr_type = MYSQL_ASSOC)
    {
        if(!$query)
            return array();
        if($arr_type != MYSQL_ASSOC && $arr_type != MYSQL_NUM)
            $this->current_arr_type = MYSQL_ASSOC;
        else
            $this->current_arr_type = $arr_type;
        $this->current_res = $this->res ($query);
        $arr_res = array();
        if($this->current_res && mysql_num_rows($this->current_res))
            $arr_res = mysql_fetch_array($this->current_res, $this->current_arr_type);
        return $arr_res;
    }

    /**
     * return next row of pointed last getFirstRow calling data
     */
    function getNextRow()
    {
        $arr_res = mysql_fetch_array($this->current_res, $this->current_arr_type);
        if($arr_res)
            return $arr_res;
        else
        {
            mysql_free_result($this->current_res);
            $this->current_arr_type = MYSQL_ASSOC;
            return array();
        }
    }

    /**
     * return number of affected rows in current mysql result
     */
    function getNumRows($res = false)
    {
        if ($res)
            return (int)@mysql_num_rows($res);
        elseif (!$this->current_res)
            return (int)@mysql_num_rows($this->current_res);
        else
            return 0;
    }

    /**
     * execute any query return number of rows affected/false
     */
    function getAffectedRows()
    {
        return mysql_affected_rows($this->link);
    }

    /**
     * execute any query return number of rows affected/false
     */
    function query($query)
    {
        $res = $this->res($query);
        if ($res)
            return mysql_affected_rows($this->link);
        return false;
    }

    /**
     * execute any query
     */
    function res($query, $error_checking = true)
    {
        if(!$query)
            return false;

        if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->beginQuery($query);

        $res = @mysql_query($query, $this->link);

        if (false === $res)
            $this->error_message = @mysql_error($this->link); // we need to remeber last error message since mysql_ping will reset it on the next line !
        else
            $this->error_message = '';

        if (!$res && !@mysql_ping($this->link)) { // if mysql connection is lost - reconnect and try again
            @mysql_close($this->link);
            $sErrorMessage = $this->connect();
            if ($sErrorMessage)
                $this->error($sErrorMessage, true);
            $res = mysql_query($query, $this->link);
        }

        if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->endQuery($res);

        if (!$res && $error_checking)
            $this->error('Database query error', false, $query);
        return $res;
    }

    /**
     * execute sql query and return table of records as result
     */
    function getAll($query, $arr_type = MYSQL_ASSOC)
    {
        if(!$query)
            return array();

        if($arr_type != MYSQL_ASSOC && $arr_type != MYSQL_NUM && $arr_type != MYSQL_BOTH)
            $arr_type = MYSQL_ASSOC;

        $res = $this->res ($query);
        $arr_res = array();
        if($res)
        {
            while($row = mysql_fetch_array($res, $arr_type))
                $arr_res[] = $row;
            mysql_free_result($res);
        }
        return $arr_res;
    }

    /**
     * execute sql query and return table of records as result
     */
    function fillArray($res, $arr_type = MYSQL_ASSOC)
    {
        if(!$res)
            return array();

        if($arr_type != MYSQL_ASSOC && $arr_type != MYSQL_NUM && $arr_type != MYSQL_BOTH)
            $arr_type = MYSQL_ASSOC;

        $arr_res = array();
        while($row = mysql_fetch_array($res, $arr_type))
            $arr_res[] = $row;
        mysql_free_result($res);

        return $arr_res;
    }

    /**
     * execute sql query and return table of records as result
     */
    function getAllWithKey($query, $sFieldKey)
    {
        if(!$query)
            return array();

        $res = $this->res ($query);
        $arr_res = array();
        if($res)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $arr_res[$row[$sFieldKey]] = $row;
            }
            mysql_free_result($res);
        }
        return $arr_res;
    }

    /**
     * execute sql query and return table of records as result
     */
    function getPairs($query, $sFieldKey, $sFieldValue, $arr_type = MYSQL_ASSOC)
    {
        if(!$query)
            return array();

        $res = $this->res ($query);
        $arr_res = array();
        if($res)
        {
            while($row = mysql_fetch_array($res, MYSQL_ASSOC))
            {
                $arr_res[$row[$sFieldKey]] = $row[$sFieldValue];
            }
            mysql_free_result($res);
        }
        return $arr_res;
    }

    function lastId()
    {
        return mysql_insert_id($this->link);
    }

    function getErrorMessage () 
    {
        $s = mysql_error($this->link);
        if ($s)
            return $s;
        else
            return $this->error_message;
    }

    function error($text, $isForceErrorChecking = false, $sSqlQuery = '')
    {
        if ($this->error_checking || $isForceErrorChecking)
            $this->genMySQLErr ($text, $sSqlQuery);
        else
            $this->log($text . ': ' . $this->getErrorMessage());
    }

    function isParam($sName, $bCache = true) {
        return $this->oParams->exists($sName, $bCache);
    }

    function addParam($sName, $sValue, $iKateg, $sDesc, $sType) {
        return $this->oParams->add($sName, $sValue, $iKateg, $sDesc, $sType);
    }

    function getParam($sName, $bCache = true) {
        return $this->oParams->get($sName, $bCache);
    }

    function setParam($sName, $sValue ) {
        $this->oParams->set($sName, $sValue);
        return true;
    }

    function listTables() {
        return mysql_list_tables($GLOBALS['db']['db'], $this->link);
        //return mysql_list_tables($GLOBALS['db']['db'], $this->link) or $this->error('Database get encoding error');
    }

    function getFields($sTable) {
        $rFields = mysql_list_fields($this->dbname, $sTable, $this->link);
        $iFields = mysql_num_fields($rFields);

        $aResult = array('original' => array(), 'uppercase' => array());
        for($i = 0; $i < $iFields; $i++) {
            $sName = mysql_field_name($rFields, $i);
            $aResult['original'][] = $sName;
            $aResult['uppercase'][] = strtoupper($sName);
        }

        return $aResult;
    }

    function isFieldExists($sTable, $sFieldName) {
        $aFields = $this->getFields($sTable);
        return in_array(strtoupper($sFieldName), $aFields['uppercase']);
    }

    function getEncoding() {
        return  mysql_client_encoding($this->link) or $this->error('Database get encoding error');
    }

    function genMySQLErr( $out, $query ='' ) {
        $sParamsOutput = false;
        $sFoundError = '';

        $aBackTrace = debug_backtrace();
        unset( $aBackTrace[0] );

        if( $query )
        {
            //try help to find error

            $aFoundError = array();

            foreach( $aBackTrace as $aCall )
            {

                // truncating global settings since it repeated many times and output it separately
                if (isset($aCall['object']) && property_exists($aCall['object'], 'oParams') && property_exists($aCall['object']->oParams, '_aParams')) {
                    if (false === $sParamsOutput)
                        $sParamsOutput = var_export($aCall['object']->oParams->_aParams, true);
                    $aCall['object']->oParams->_aParams = '[truncated]';
                }

                if (isset($aCall['args']) && is_array($aCall['args']))
                {
                    foreach( $aCall['args'] as $argNum => $argVal )
                    {
                        if( is_string($argVal) and strcmp( $argVal, $query ) == 0 )
                        {
                            $aFoundError['file']     = isset($aCall['file']) ? $aCall['file'] : (isset($aCall['class']) ? 'class: ' . $aCall['class'] : 'undefined');
                            $aFoundError['line']     = isset($aCall['line']) ? $aCall['line'] : 'undefined';
                            $aFoundError['function'] = $aCall['function'];
                            $aFoundError['arg']      = $argNum;
                        }
                    }
                }
            }

            if( $aFoundError )
            {
                $sFoundError = <<<EOJ
Found error in the file '<b>{$aFoundError['file']}</b>' at line <b>{$aFoundError['line']}</b>.<br />
Called '<b>{$aFoundError['function']}</b>' function with erroneous argument #<b>{$aFoundError['arg']}</b>.<br /><br />
EOJ;
            }
        }


        bx_import('BxDolConfig');
        if (BxDolConfig::getInstance()->get('db', 'visual_processing')) {
            ?>
                <div style="border:2px solid red;padding:4px;width:600px;margin:0px auto;">
                    <div style="text-align:center;background-color:red;color:white;font-weight:bold;">Error</div>
                    <div style="text-align:center;"><?=$out?></div>
            <?
            if(BxDolConfig::getInstance()->get('db', 'debug_mode')) {
                if( strlen( $query ) )
                    echo "<div><b>Query:</b><br />{$query}</div>";

                if ($this->link)
                    echo '<div><b>Mysql error:</b><br />' . $this->getErrorMessage() . '</div>';

                echo '<div style="overflow:scroll;height:300px;border:1px solid gray;">';
                    echo $sFoundError;
                    echo "<b>Debug backtrace:</b><br />";

                    $sBackTrace = print_r($aBackTrace, true);
                    $sBackTrace = str_replace('[password] => ' . BX_DATABASE_PASS, '[password] => *****', $sBackTrace);
                    $sBackTrace = str_replace('[user] => ' . BX_DATABASE_USER, '[user] => *****', $sBackTrace);

                    echo '<pre>' . $sBackTrace . '</pre>';

                    if ($sParamsOutput) {
                        echo '<hr />';
                        echo "<b>Settings:</b><br />";
                        echo '<pre>' . htmlspecialchars_adv($sParamsOutput) . '</pre>';
                    }

                    echo "<b>Called script:</b> " . $_SERVER['PHP_SELF'] . "<br />";
                    echo "<b>Request parameters:</b><br />";
                    echoDbg( $_REQUEST );
                echo '</div>';
            }
            ?>
                </div>
            <?
        }
        else
            echo $out;

        if(BxDolConfig::getInstance()->get('db', 'error_remort_by_email')) {
            $sSiteTitle = getParam('site_title');
            $sMailBody = "Database error in " . $sSiteTitle . "<br /><br /> \n";

            if( strlen( $query ) )
                $sMailBody .= "Query:  <pre>" . htmlspecialchars_adv($query) . "</pre> ";

            if ($this->link)
                $sMailBody .= "Mysql error: " . $this->getErrorMessage() . "<br /><br /> ";

            $sMailBody .= $sFoundError. '<br /> ';

            $sBackTrace = print_r($aBackTrace, true);
            $sBackTrace = str_replace('[password] => ' . BX_DATABASE_PASS, '[password] => *****', $sBackTrace);
            $sBackTrace = str_replace('[user] => ' . BX_DATABASE_USER, '[user] => *****', $sBackTrace);
            $sMailBody .= "Debug backtrace:\n <pre>" . htmlspecialchars_adv($sBackTrace) . "</pre> ";

            if ($sParamsOutput)
                $sMailBody .= "<hr />Settings:\n <pre>" . htmlspecialchars_adv($sParamsOutput) . "</pre> ";

            $sMailBody .= "<hr />Called script: " . $_SERVER['PHP_SELF'] . "<br /> ";
            $sMailBody .= "<hr />Request parameters: <pre>" . print_r( $_REQUEST, true ) . " </pre>";
            $sMailBody .= "--\nAuto-report system\n";

            sendMail(getParam('site_email_bug_report'), "Database error in " . $sSiteTitle, $sMailBody, 0, array(), BX_EMAIL_SYSTEM, 'html', true);
        }

        exit;
    }

    function setErrorChecking ($b) {
        $this->error_checking = $b;
    }

    function getDbCacheObject () {
        if ($this->oDbCacheObject != null) {
            return $this->oDbCacheObject;
        } else {
            $sEngine = getParam('sys_db_cache_engine');
            $this->oDbCacheObject = bx_instance ('BxDolCache'.$sEngine);
            if (!$this->oDbCacheObject->isAvailable())
                $this->oDbCacheObject = bx_instance ('BxDolCacheFile');
            return $this->oDbCacheObject;
        }
    }

    function genDbCacheKey ($sName) {
        return 'db_' . $sName . '_' . bx_site_hash() . '.php';
    }

    function fromCache ($sName, $sFunc) {

        $aArgs = func_get_args();
        array_shift ($aArgs); // shift $sName
        array_shift ($aArgs); // shift $sFunc

        if (!getParam('sys_db_cache_enable'))
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

    function cleanCache ($sName) {

        if (!getParam('sys_db_cache_enable'))
            return true;

        $oCache = $this->getDbCacheObject ();

        $sKey = $this->genDbCacheKey($sName);

        return $oCache->delData($sKey);
    }

    function & fromMemory ($sName, $sFunc) {
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

    function cleanMemory ($sName) {
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
     * @param string $s string to escape
     * @return escaped string whcich is ready to pass to SQL query.
     */
    function escape ($s) {
        return mysql_real_escape_string($s, $this->link);
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
    function implode_escape ($mixed) {
        if (is_array($mixed)) {
            $s = '';
            foreach ($mixed as $v)
                $s .= (is_numeric($v) ? $v : "'" . mysql_real_escape_string($v, $this->link) . "'") . ',';
            if ($s)
                return substr($s, 0, -1);
            else
                return 'NULL';
        }
        return is_numeric($mixed) ? $mixed : ($mixed ? "'" . mysql_real_escape_string($mixed, $this->link) . "'" : 'NULL');
    }

    /**
     * @deprecated
     */
    function unescape ($mixed) {
        if (is_array($mixed)) {
            foreach ($mixed as $k => $v)
                $mixed[$k] = $this->getOne("SELECT '$v'");
            return $mixed;
        } else {
            return $this->getOne("SELECT '$mixed'");
        }
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
     * @param string $sQuery SQL query, parameters for replacing are marked with ? symbol
     * @param mixed $mixed any number if parameters to replace, number of parameters whould match number of ? symbols in SQL query
     * @return string with SQL query ready for execution
     */
    function prepare ($sQuery) {
        $aArgs = func_get_args();
        $sQuery = array_shift($aArgs);
        $iPos = 0;
        foreach ($aArgs as $mixedArg) {
            if (is_null($mixedArg))
                $s = 'NULL';
            elseif (is_numeric($mixedArg))
                $s = $mixedArg;
            else
                $s = "'" . mysql_real_escape_string($mixedArg, $this->link) . "'";

            $i = bx_mb_strpos($sQuery, '?', $iPos);
            $sQuery = bx_mb_substr_replace($sQuery, $s, $i, 1);
            $iPos = $i + get_mb_len($s);
        }
        return $sQuery;
    }

    function log ($s) {
        return file_put_contents(BX_DIRECTORY_PATH_ROOT . 'tmp/db.err.log', date('Y-m-d H:i:s') . "\t" . $s . "\n", FILE_APPEND);
    }

    function executeSQL($sPath, $aReplace = array (), $isBreakOnError = true) {

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

/** @} */

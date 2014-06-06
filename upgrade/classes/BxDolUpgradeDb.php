<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinUpgrade Dolphin Upgrade Script
 * @{
 */

define('BX_UPGRADE_DB_FULL_VISUAL_PROCESSING', true);
define('BX_UPGRADE_DB_FULL_DEBUG_MODE', true);

class BxDolUpgradeDb
{
    protected $_bErrorChecking = true;
    protected $_sHost, $_sPort, $_sSocket, $_sDbname, $_sUser, $_sPassword;
    protected $_rLink, $_rCurrentRes, $_iCurrentResType;

    /**
     * set database parameters and connect to it
     */
    function __construct()
    {
        $this->_sHost = DATABASE_HOST;
        $this->_sPort = DATABASE_PORT;
        $this->_sSocket = DATABASE_SOCK;
        $this->_sDbname = DATABASE_NAME;
        $this->_sUser = DATABASE_USER;
        $this->_sPassword = DATABASE_PASS;
        $this->_iCurrentResType = MYSQL_ASSOC;

        $this->connect();
    }

    /**
     * connect to database with appointed parameters
     */
    function connect()
    {
        $full_host = $this->_sHost;
        $full_host .= $this->_sPort ? ':'.$this->_sPort : '';
        $full_host .= $this->_sSocket ? ':'.$this->_sSocket : '';

        $this->_rLink = @mysql_pconnect($full_host, $this->_sUser, $this->_sPassword);
        if (!$this->_rLink)
            $this->error('Database connect failed', true);

        if (!$this->select_db())
            $this->error('Database select failed', true);

        $this->res("SET NAMES 'utf8'");
        $this->res("SET sql_mode = ''");
    }

    function select_db()
    {
        return @mysql_select_db($this->_sDbname, $this->_rLink) or $this->error('Cannot complete query (select_db)');
    }

    /**
     * close mysql connection
     */
    function close()
    {
        mysql_close($this->_rLink);
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
    function getColumn($sQuery) 
    {
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
            $this->_iCurrentResType = MYSQL_ASSOC;
        else
            $this->_iCurrentResType = $arr_type;
        $this->_rCurrentRes = $this->res ($query);
        $arr_res = array();
        if($this->_rCurrentRes && mysql_num_rows($this->_rCurrentRes))
            $arr_res = mysql_fetch_array($this->_rCurrentRes, $this->_iCurrentResType);
        return $arr_res;
    }

    /**
     * return next row of pointed last getFirstRow calling data
     */
    function getNextRow()
    {
        $arr_res = mysql_fetch_array($this->_rCurrentRes, $this->_iCurrentResType);
        if($arr_res)
            return $arr_res;
        else
        {
            mysql_free_result($this->_rCurrentRes);
            $this->_iCurrentResType = MYSQL_ASSOC;
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
        elseif (!$this->_rCurrentRes)
            return (int)@mysql_num_rows($this->_rCurrentRes);
        else
            return 0;
    }

    /**
     * execute any query return number of rows affected/false
     */
    function getAffectedRows()
    {
        return mysql_affected_rows($this->_rLink);
    }

    /**
     * execute any query return number of rows affected/false
     */
    function query($query)
    {
        $res = $this->res($query);
        if($res)
            return mysql_affected_rows($this->_rLink);
        return false;
    }

    /**
     * execute any query
     */
    function res($query, $bErrorChecking = true)
    {
        if(!$query)
            return false;
        if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->beginQuery($query);
        $res = mysql_query($query, $this->_rLink);
        if (isset($GLOBALS['bx_profiler'])) $GLOBALS['bx_profiler']->endQuery($res);
        if (!$res && $bErrorChecking)
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
    function getPairs($query, $sFieldKey, $sFieldValue)
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
        return mysql_insert_id($this->_rLink);
    }

    function error($text, $isForceErrorChecking = false, $sSqlQuery = '')
    {
        if ($this->_bErrorChecking || $isForceErrorChecking)
            $this->genMySQLErr ($text, $sSqlQuery);
        else
            $this->log($text.': '.mysql_error($this->_rLink));
    }

    function listTables() 
    {
        return mysql_list_tables($GLOBALS['db']['db'], $this->_rLink);
    }

    function getEncoding() 
    {
        return  mysql_client_encoding($this->_rLink) or $this->error('Database get encoding error');
    }

    function genMySQLErr( $out, $query ='' ) 
    {
        $aBackTrace = debug_backtrace();
        unset( $aBackTrace[0] );

        if( $query )
        {
            //try help to find error

            $aFoundError = array();

            foreach( $aBackTrace as $aCall )
            {
                foreach( $aCall['args'] as $argNum => $argVal )
                {
                    if( is_string($argVal) and strcmp( $argVal, $query ) == 0 )
                    {
                        $aFoundError['file']     = $aCall['file'];
                        $aFoundError['line']     = $aCall['line'];
                        $aFoundError['function'] = $aCall['function'];
                        $aFoundError['arg']      = $argNum;
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


        if( BX_UPGRADE_DB_FULL_VISUAL_PROCESSING )
        {
            ?>
                <div style="border:2px solid red;padding:4px;width:600px;margin:0px auto;">
                    <div style="text-align:center;background-color:red;color:white;font-weight:bold;">Error</div>
                    <div style="text-align:center;"><?=$out?></div>
            <?
            if( BX_UPGRADE_DB_FULL_DEBUG_MODE )
            {
                if( strlen( $query ) )
                    echo "<div><b>Query:</b><br />{$query}</div>";

                echo '<div><b>Mysql error:</b><br />'.mysql_error($this->_rLink).'</div>';
                echo '<div style="overflow:scroll;height:300px;border:1px solid gray;">';
                    echo $sFoundError;
                    echo "<b>Debug backtrace:</b><br />";
                    echoDbg( $aBackTrace );

                    echo "<b>Called script:</b> {$_SERVER['PHP_SELF']}<br />";
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

        exit;
    }

    function setErrorChecking ($b) 
    {
        $this->_bErrorChecking = $b;
    }

    function escape ($s) 
    {
        return mysql_real_escape_string($s);
    }

    function executeSQL($sPath, $aReplace = array (), $isBreakOnError = true) 
    {
        if(!file_exists($sPath) || !($rHandler = fopen($sPath, "r")))
            return array ('query' => "fopen($sPath, 'r')", 'error' => 'file not found or permission denied');

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
                $aResult[] = array('query' => $sQuery, 'error' => mysql_error($this->_rLink));
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


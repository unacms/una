<?

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

define( 'BX_UPGRADE_DB_FULL_VISUAL_PROCESSING', true );
define( 'BX_UPGRADE_DB_FULL_DEBUG_MODE', true );

class BxDolUpgradeDb
{
    var $error_checking = true;
    var $host, $port, $socket, $dbname, $user, $password, $link;
    var $current_res, $current_arr_type;

    var $oParams;

    /*
    *set database parameters and connect to it
    */
    function BxDolUpgradeDb(){

        $this->host = DATABASE_HOST;
        $this->port = DATABASE_PORT;
        $this->socket = DATABASE_SOCK;
        $this->dbname = DATABASE_NAME;
        $this->user = DATABASE_USER;
        $this->password = DATABASE_PASS;
        $this->current_arr_type = MYSQL_ASSOC;

        $this->connect();
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
            $this->error('Database connect failed', true);

        if (!$this->select_db())
            $this->error('Database select failed', true);

        $this->res("SET NAMES 'utf8'");
        $this->res("SET sql_mode = ''");
    }

    function select_db()
    {
        return @mysql_select_db($this->dbname, $this->link) or $this->error('Cannot complete query (select_db)');
    }

    /**
     * close mysql connection
     */
    function close()
    {
        mysql_close($this->link);
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
        if($res)
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
        $res = mysql_query($query, $this->link);
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

    function error($text, $isForceErrorChecking = false, $sSqlQuery = '')
    {
        if ($this->error_checking || $isForceErrorChecking)
            $this->genMySQLErr ($text, $sSqlQuery);
        else
            $this->log($text.': '.mysql_error($this->link));
    }

    function listTables() {
        return mysql_list_tables($GLOBALS['db']['db'], $this->link);
        //return mysql_list_tables($GLOBALS['db']['db'], $this->link) or $this->error('Database get encoding error');
    }

    function getEncoding() {
        return  mysql_client_encoding($this->link) or $this->error('Database get encoding error');
    }

    function genMySQLErr( $out, $query ='' ) {
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

                echo '<div><b>Mysql error:</b><br />'.mysql_error($this->link).'</div>';
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

    function setErrorChecking ($b) {
        $this->error_checking = $b;
    }

    function escape ($s) {
        return mysql_real_escape_string($s);
    }

    function executeSQL($sPath, $aReplace = array (), $isBreakOnError = true) {

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
                $aResult[] = array('query' => $sQuery, 'error' => mysql_error($this->link));
                if ($isBreakOnError)
                    break;
            }

            $sQuery = "";
        }
        fclose($rHandler);

        return empty($aResult) ? true : $aResult;
    }
}
?>

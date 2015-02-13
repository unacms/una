<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Profiler Profiler
 * @ingroup     TridentModules
 *
 * @{
 */

define ('BX_PROFILER', true);
define ('BX_PROFILER_DISPLAY', 1);

require_once(BX_DIRECTORY_PATH_MODULES . 'boonex/profiler/install/config.php');
$GLOBALS['bx_profiler_module'] = array (
    'title' => $aConfig['title'],
    'vendor' => $aConfig['vendor'],
    'path' => $aConfig['home_dir'],
    'uri' => $aConfig['home_uri'],
    'class_prefix' => $aConfig['class_prefix'],
    'db_prefix' => $aConfig['db_prefix'],
);

bx_import('Template', $GLOBALS['bx_profiler_module']);
bx_import('Config', $GLOBALS['bx_profiler_module']);

class BxProfiler extends BxDol
{
    protected $oConfig, $oTemplate;

    protected $aConf = array ();

    protected $_iTimeStart = 0;

    protected $_aQueries = array();
    protected $_sQueryIndex = 0;

    protected $_aModules = array();
    protected $_aModulesNames = array();
    protected $_aModulesLevel = 0;

    protected $_aInjections = array();
    protected $_sInjectionIndex = 0;

    protected $_sLogDateFormat = "Y-m-d H:i:s";
    protected $_sLogMaxArgLength = 64;
    protected $_sLogFilename = 64;

    function __construct($iTimeStart)
    {
        parent::__construct();

        $this->oConfig = new BxProfilerConfig ($GLOBALS['bx_profiler_module']);
        $this->oTemplate = new BxProfilerTemplate ($this->oConfig);
        $this->oTemplateAdmin = BxDolStudioTemplate::getInstance();

        $aCss = array (
            BX_DOL_URL_MODULES . 'boonex/profiler/template/css/profiler.css',
            BX_DOL_URL_PLUGINS_PUBLIC . 'jush/jush.css',
        );
        $aJs = array (
            'jquery.tablesorter.min.js',
            BX_DOL_URL_MODULES . 'boonex/profiler/js/profiler.js',
            BX_DOL_URL_PLUGINS_PUBLIC . 'jush/jush.js',
        );

        foreach ($aCss as $sCssUrl) {
            $this->oTemplate->addCss($sCssUrl);
            $this->oTemplateAdmin->addCss($sCssUrl);
        }
        foreach ($aJs as $sJsUrl) {
            $this->oTemplate->addJs($sJsUrl);
            $this->oTemplateAdmin->addJs($sJsUrl);
        }

        if (getParam ('bx_profiler_long_sql_queries_log'))
            $this->aConf['long_query'] = getParam ('bx_profiler_long_sql_queries_time');
        if (getParam ('bx_profiler_long_module_query_log'))
            $this->aConf['long_module'] = getParam ('bx_profiler_long_module_query_time');
        if (getParam ('bx_profiler_long_page_log'))
            $this->aConf['long_page'] = getParam ('bx_profiler_long_page_time');

        $this->_iTimeStart = $iTimeStart;
    }

    // output profiler debug panel
    function output ()
    {
        $iPageTIme = $this->_getCurrentDelay ();
        if (isset($this->aConf['long_page']) && $iPageTIme > $this->aConf['long_page'])
            $this->logPageOpen ($iPageTIme);

        switch (getParam('bx_profiler_show_debug_panel')) {
        case 'all':
            break;
        case 'admins':
            if (!$GLOBALS['logged']['admin'])
                return;
            break;
        case 'none':
        default:
            return;
        }

        $sContentType = $this->_getHeaderContentType();
        if ('text/html' == $sContentType) {
            echo $this->_plankMain ();
            echo $this->_plankMenus ();
            echo $this->_plankTemplates ();
            echo $this->_plankInjections ();
            echo $this->_plankPagesBlocks ();
            echo $this->_plankSql ();
            echo $this->_plankModules ();
        }
    }

    function _logBegin ($s)
    {
        $sDate = date ($this->_sLogDateFormat);
        return "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n" . $sDate . " " . $s . "\n" .
        "User ID: " . $_COOKIE['memberID']  . "\n" .
        "User role: " . ($GLOBALS['logged']['admin'] ? 'admin' : ($GLOBALS['logged']['member'] ? 'member' : 'guest')) . "\n";
    }

    function _logEnd ()
    {
        return "\n";
    }

    function _appendToLog ($s)
    {
        $f = fopen ( BX_DIRECTORY_PATH_ROOT . 'logs/bx_profiler.log', 'a');
        if (!$f)
            return;
        fwrite($f, $s);
        fclose($f);
    }

    function logSqlQuery ($iTime, $aSqlQuery, &$res)
    {
        $s  = $this->_logBegin ('LONG SQL QUERY: ' . $aSqlQuery['time']);
        $s .= "Rows: " . $aSqlQuery['rows'] . "\n";
        $s .= "Affected: " . $aSqlQuery['affected'] . "\n";
        $s .= "Query: " . $aSqlQuery['sql'] . "\n";
        if (getParam('bx_profiler_long_sql_queries_debug'))
            $s .= "Backtrace: \n" . $this->_debugBackTrace (3);
        $s .= $this->_logEnd();
        $this->_appendToLog ($s);
    }

    function logModuleQuery ($iTime, $aModuleQuery)
    {
        $s  = $this->_logBegin ('LONG MODULE QUERY: ' . $aModuleQuery['time']);
        $s .= "Module name: " . $aModuleQuery['name'] . "\n";
        $s .= "Query type: " . $aModuleQuery['type'] . "\n";
        $s .= "Class/file: " . $aModuleQuery['class/file'] . "\n";
        $s .= "Method: " . $aModuleQuery['method'] . "\n";
        if (getParam('bx_profiler_long_module_query_debug'))
            $s .= "Backtrace: \n" . $this->_debugBackTrace (3);
        $s .= $this->_logEnd();
        $this->_appendToLog ($s);
    }

    function logPageOpen ($iTime)
    {
        $s  = $this->_logBegin ('LONG PAGE OPEN: ' . $this->_formatTime($iTime, 5));
        $s .= "Request method: " . $_SERVER['REQUEST_METHOD'] . "\n";
        $s .= "Query string: " . $_SERVER['QUERY_STRING'] . "\n";
        $s .= "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
        $s .= "Script name: " . $_SERVER['SCRIPT_NAME'] . "\n";
        $s .= "PHP self: " . $_SERVER['PHP_SELF'] . "\n";
        if (getParam('bx_profiler_long_page_debug'))
            $s .= "All server vars: \n" . print_r ($_SERVER, true);
        $s .= $this->_logEnd();
        $this->_appendToLog ($s);
    }

    function beginModule($sType, $sHash, &$aModule, $sClassFile, $sMethod = '' )
    {
        ++$this->_aModulesLevel;
        $this->_aModulesNames[$aModule['title']] = isset($this->_aModulesNames[$aModule['title']]) ? $this->_aModulesNames[$aModule['title']] + 1 : 1;
        $this->_aModules[$sHash] = array (
            'name' => str_repeat("--", $this->_aModulesLevel-1) . $aModule['title'],
            'type' => $sType,
            'class/file' => $sClassFile,
            'method' => $sMethod,
            'begin' => microtime (),
            'time' => -1,
        );
    }

    function endModule($sType, $sHash)
    {
        --$this->_aModulesLevel;
        $iTime = $this->_calcTime ($this->_aModules[$sHash]['begin']);
        unset ($this->_aModules[$sHash]['begin']);
        $this->_aModules[$sHash]['time'] = $this->_formatTime($iTime, 5);
        $this->_aModules[$sHash]['raw_time'] = $iTime;
        if (isset($this->aConf['long_module']) && $iTime > $this->aConf['long_module'])
            $this->logModuleQuery ($iTime, $this->_aModules[$sHash]);
    }

    function beginInjection ($sId)
    {
        $this->_sInjectionIndex = $sId;
        $this->_aInjections[$sId]['begin'] = microtime ();
    }

    function endInjection ($sId, $aInjection)
    {
        if (!isset($this->_aInjections[$sId]))
            return;
        $iTime = $this->_calcTime ($this->_aInjections[$sId]['begin']);
        unset ($this->_aInjections[$sId]['begin']);
        $this->_aInjections[$sId]['name'] = isset($aInjection['name']) ? $aInjection['name'] : 'no-name';
        $this->_aInjections[$sId]['key'] = $aInjection['key'];
        $this->_aInjections[$sId]['replace'] = $aInjection['replace'] ? 'yes' : 'no';
        $this->_aInjections[$sId]['time'] = $this->_formatTime($iTime, 5);
        $this->_aInjections[$sId]['raw_time'] = $iTime;
    }

    function beginPageBlock ($sName, $iBlockId)
    {
        $this->_sPageBlockIndex = $iBlockId;
        $this->_aPagesBlocks[$this->_sPageBlockIndex]['name'] = $sName;
        $this->_aPagesBlocks[$this->_sPageBlockIndex]['begin'] = microtime ();
    }

    function endPageBlock ($iBlockId, $isEmpty, $isCached)
    {
        if (!$this->_sPageBlockIndex)
            return;
        $iTime = $this->_calcTime ($this->_aPagesBlocks[$this->_sPageBlockIndex]['begin']);
        unset ($this->_aPagesBlocks[$this->_sPageBlockIndex]['begin']);
        $this->_aPagesBlocks[$this->_sPageBlockIndex]['cached'] = $isCached ? 'yes' : 'no';
        $this->_aPagesBlocks[$this->_sPageBlockIndex]['empty'] = $isEmpty ? 'yes' : 'no';
        $this->_aPagesBlocks[$this->_sPageBlockIndex]['time'] = $this->_formatTime($iTime, 5);
        $this->_aPagesBlocks[$this->_sPageBlockIndex]['raw_time'] = $iTime;
    }

    function beginPage ($sName)
    {
        $this->_sPageIndex = md5 ($sName.time().rand());
        $this->_aPages[$this->_sPageIndex]['name'] = $sName;
        $this->_aPages[$this->_sPageIndex]['begin'] = microtime ();
    }

    function endPage (&$sContent)
    {
        if (!$this->_sPageIndex || !isset($this->_aPages[$this->_sPageIndex]['begin']))
            return;
        $iTime = $this->_calcTime ($this->_aPages[$this->_sPageIndex]['begin']);
        unset ($this->_aPages[$this->_sPageIndex]['begin']);
        $this->_aPages[$this->_sPageIndex]['time'] = $this->_formatTime($iTime, 5);
        $this->_aPages[$this->_sPageIndex]['raw_time'] = $iTime;
    }

    function beginTemplate ($sName, $sRand)
    {
        $this->_aTemplateIndexes[$sName.$sRand] = 1;
        $this->_aTemplates[$sName.$sRand]['name'] = $sName;
        $this->_aTemplates[$sName.$sRand]['begin'] = microtime ();
    }

    function endTemplate ($sName, $sRand, &$sContent, $isCached)
    {
        if (!isset($this->_aTemplateIndexes[$sName.$sRand]))
            return;
        $iTime = $this->_calcTime ($this->_aTemplates[$sName.$sRand]['begin']);
        unset ($this->_aTemplates[$sName.$sRand]['begin']);
        $this->_aTemplates[$sName.$sRand]['cached'] = $isCached ? 'yes' : 'no';
        $this->_aTemplates[$sName.$sRand]['time'] = $this->_formatTime($iTime, 5);
        $this->_aTemplates[$sName.$sRand]['raw_time'] = $iTime;
    }

    function beginQuery ($sSql)
    {
        $this->_sQueryIndex = md5 ($sSql.time().rand());
        $this->_aQueries[$this->_sQueryIndex]['sql'] = $sSql;
        $this->_aQueries[$this->_sQueryIndex]['begin'] = microtime ();
    }

    function endQuery (&$res)
    {
        if (!$this->_sQueryIndex)
            return;
        $iTime = $this->_calcTime ($this->_aQueries[$this->_sQueryIndex]['begin']);
        unset ($this->_aQueries[$this->_sQueryIndex]['begin']);
        $this->_aQueries[$this->_sQueryIndex]['time'] = $this->_formatTime($iTime, 5);
        $this->_aQueries[$this->_sQueryIndex]['raw_time'] = $iTime;
        $this->_aQueries[$this->_sQueryIndex]['rows'] = $res ? BxDolDb::getInstance()->getNumRows($res) : '';
        $this->_aQueries[$this->_sQueryIndex]['affected'] = $res ? BxDolDb::getInstance()->getAffectedRows($res) : '';
        if (isset($this->aConf['long_query']) && $iTime > $this->aConf['long_query'])
            $this->logSqlQuery ($iTime, $this->_aQueries[$this->_sQueryIndex], $res);
    }

    function beginMenu ($sName)
    {
        $this->_aMenus[$sName]['name'] = $sName;
        $this->_aMenus[$sName]['begin'] = microtime ();
    }

    function endMenu ($sName)
    {
        if (!isset($this->_aMenus[$sName]))
            return;
        $iTime = $this->_calcTime ($this->_aMenus[$sName]['begin']);
        unset ($this->_aMenus[$sName]['begin']);
        $this->_aMenus[$sName]['time'] = $this->_formatTime($iTime, 5);
        $this->_aMenus[$sName]['raw_time'] = $iTime;
    }

    function _getCurrentDelay ()
    {
        $i1 = explode(' ', microtime ());
        $i2 = explode(' ', $this->_iTimeStart);
        return ($i1[0]+$i1[1]) - ($i2[0]+$i2[1]);
    }

    function _plankMain ()
    {
        $sTime = $this->_formatTime($this->_getCurrentDelay ());
        if (function_exists('memory_get_usage'))
            $sMemory = $this->_formatBytes(memory_get_usage(true)) . ' of ' . ini_get('memory_limit') . ' allowed';

        return $this->oTemplate->plank(
            $this->oTemplate->nameValue('Time:', $sTime) .
            (function_exists('memory_get_usage') ? $this->oTemplate->nameValue('Memory:', $sMemory) : '') .
            $this->oTemplate->nameValue('PHP:', phpversion()) .
            $this->oTemplate->nameValue('SAPI:', php_sapi_name()) .
            $this->oTemplate->nameValue('OS:', php_uname('s r m'))
        );
    }

    function _plankTemplates ()
    {
        if (empty($GLOBALS['bx_profiler']->_aPages) && empty($GLOBALS['bx_profiler']->_aTemplates))
            return;

        $sPages = '';
        if (!empty($GLOBALS['bx_profiler']->_aPages)) {
            $iTimePages = 0;
            foreach ($GLOBALS['bx_profiler']->_aPages as $k => $r) {
                if (!isset($r['raw_time']))
                    continue;
                $iTimePages += $r['raw_time'];
                unset ($GLOBALS['bx_profiler']->_aPages[$k]['raw_time']);
            }
            $sPages = count($GLOBALS['bx_profiler']->_aPages) . ' (' . $this->_formatTime($iTimePages, 3) . ')';
        }

        $sTemplatesCached = '';
        $sTemplatesNotCached = '';
        if ($GLOBALS['bx_profiler']->_aTemplates) {
            $iTimeTemplatesCached = 0;
            $iTimeTemplatesNotCached = 0;
            $sTemplatesCached = 0;
            $sTemplatesNotCached = 0;
            foreach ($GLOBALS['bx_profiler']->_aTemplates as $k => $r) {
                if (!isset($r['raw_time']))
                    continue;
                if ('yes' == $r['cached']) {
                    $iTimeTemplatesCached += $r['raw_time'];
                    ++$sTemplatesCached;
                } else {
                    $iTimeTemplatesNotCached += $r['raw_time'];
                    ++$sTemplatesNotCached;
                }
                unset ($GLOBALS['bx_profiler']->_aTemplates[$k]['raw_time']);
            }
            if ($sTemplatesCached)
                $sTemplatesCached .= ' (' . $this->_formatTime($iTimeTemplatesCached, 3) . ')';
            if ($sTemplatesNotCached)
                $sTemplatesNotCached .= ' (' . $this->_formatTime($iTimeTemplatesNotCached, 3) . ')';
        }

        return $this->oTemplate->plank(
            ($sPages ? $this->oTemplate->nameValue('Pages:', $sPages) : '') .
            ($sTemplatesCached ? $this->oTemplate->nameValue('Templates Cached:', $sTemplatesCached) : '') .
            ($sTemplatesNotCached ? $this->oTemplate->nameValue('Templates Not Cached:', $sTemplatesNotCached) : ''),
            $this->oTemplate->table($GLOBALS['bx_profiler']->_aTemplates)
        );
    }

    function _plankInjections ()
    {
        if (!$GLOBALS['bx_profiler']->_aInjections)
            return;

        $iTimeInjections = 0;
        foreach ($GLOBALS['bx_profiler']->_aInjections as $k => $r) {
            if (!isset($r['raw_time']))
                continue;
            $iTimeInjections += $r['raw_time'];
            unset ($GLOBALS['bx_profiler']->_aInjections[$k]['raw_time']);
        }

        $sInjections = count($GLOBALS['bx_profiler']->_aInjections) . ' injection (' . $this->_formatTime($iTimeInjections, 3) . ')';

        return $this->oTemplate->plank(
            $this->oTemplate->nameValue('Injections:', $sInjections),
            $this->oTemplate->table($GLOBALS['bx_profiler']->_aInjections)
        );
    }

    function _plankPagesBlocks ()
    {
        if (empty($GLOBALS['bx_profiler']->_aPagesBlocks))
            return;

        $iTimeBlocks = 0;
        $iTimeBlocksCached = 0;
        $iTimeBlocksNotCached = 0;
        $iTimeBlocksEmpty = 0;
        $iTimeBlocksNotEmpty = 0;

        $iCountBlocksCached = 0;
        $iCountBlocksNotCached = 0;
        $iCountBlocksEmpty = 0;
        $iCountBlocksNotEmpty = 0;

        foreach ($GLOBALS['bx_profiler']->_aPagesBlocks as $k => $r) {
            if (!isset($r['raw_time']))
                continue;
            $iTimeBlocks += $r['raw_time'];
            if ($r['cached'] == 'yes') {
                $iTimeBlocksCached += $r['raw_time'];
                ++$iCountBlocksCached;
            } else {
                $iTimeBlocksNotCached += $r['raw_time'];
                ++$iCountBlocksNotCached;
            }
            if ($r['empty'] == 'yes') {
                $iTimeBlocksEmpty += $r['raw_time'];
                ++$iCountBlocksEmpty;
            } else {
                $iTimeBlocksNotEmpty += $r['raw_time'];
                ++$iCountBlocksNotEmpty;
            }
            unset ($GLOBALS['bx_profiler']->_aPagesBlocks[$k]['raw_time']);
        }

        $sBlocks = count($GLOBALS['bx_profiler']->_aPagesBlocks) . ' (' . $this->_formatTime($iTimeBlocks, 3) . ')';
        if ($iCountBlocksCached)
            $sBlocksCached = $iCountBlocksCached . ' (' . $this->_formatTime($iTimeBlocksCached, 3) . ')';
        if ($iCountBlocksNotCached)
            $sBlocksNotCached = $iCountBlocksNotCached . ' (' . $this->_formatTime($iTimeBlocksNotCached, 3) . ')';
        if ($iCountBlocksEmpty)
            $sBlocksEmpty = $iCountBlocksEmpty . ' (' . $this->_formatTime($iTimeBlocksEmpty, 3) . ')';
        if ($iCountBlocksNotEmpty)
            $sBlocksNotEmpty = $iCountBlocksNotEmpty . ' (' . $this->_formatTime($iTimeBlocksNotEmpty, 3) . ')';

        return $this->oTemplate->plank(
            $this->oTemplate->nameValue('Pages Blocks:', $sBlocks) .
            ($iCountBlocksCached ? $this->oTemplate->nameValue('Cached:', $sBlocksCached) : '') .
            ($iCountBlocksNotCached ? $this->oTemplate->nameValue('Not Cached:', $sBlocksNotCached) : '') .
            ($iCountBlocksEmpty ? $this->oTemplate->nameValue('Empty:', $sBlocksEmpty) : '') .
            ($iCountBlocksNotEmpty ? $this->oTemplate->nameValue('Not Empty:', $sBlocksNotEmpty) : ''),
            $this->oTemplate->table($GLOBALS['bx_profiler']->_aPagesBlocks)
        );
    }

    function _plankMenus ()
    {
        if (empty($GLOBALS['bx_profiler']->_aMenus))
            return;

        $iTimeMenus = 0;
        foreach ($GLOBALS['bx_profiler']->_aMenus as $k => $r) {
            if (!isset($r['raw_time']))
                continue;
            $iTimeMenus += $r['raw_time'];
            unset ($GLOBALS['bx_profiler']->_aMenus[$k]['raw_time']);
        }

        $sMenus = count($GLOBALS['bx_profiler']->_aMenus) . ' menus (' . $this->_formatTime($iTimeMenus, 3) . ')';

        return $this->oTemplate->plank(
            $this->oTemplate->nameValue('Menus:', $sMenus),
            $this->oTemplate->table($GLOBALS['bx_profiler']->_aMenus)
        );
    }

    function _plankSql ()
    {
        if (empty($GLOBALS['bx_profiler']->_aQueries))
            return;

        $iTimeQueries = 0;
        foreach ($GLOBALS['bx_profiler']->_aQueries as $k => $r) {
            if (!isset($r['raw_time']))
                continue;
            $iTimeQueries += $r['raw_time'];
            unset ($GLOBALS['bx_profiler']->_aQueries[$k]['raw_time']);
        }

        $sQueries = count($GLOBALS['bx_profiler']->_aQueries) . ' queries (' . $this->_formatTime($iTimeQueries, 3) . ')';

        return $this->oTemplate->plank(
            $this->oTemplate->nameValue('SQL:', $sQueries),
            $this->oTemplate->table($GLOBALS['bx_profiler']->_aQueries, 'sql')
        );
    }

    function _plankModules ()
    {
        if (empty($GLOBALS['bx_profiler']->_aModules))
            return;

        $iTimeModules = 0;
        foreach ($GLOBALS['bx_profiler']->_aModules as $k => $r) {
            if (!isset($r['raw_time']))
                continue;
            $iTimeModules += $r['raw_time'];
            unset ($GLOBALS['bx_profiler']->_aModules[$k]['raw_time']);
        }

        $sModules = count($GLOBALS['bx_profiler']->_aModulesNames) . ' modules loaded';
        $sModulesQueries = count($GLOBALS['bx_profiler']->_aModules) . ' modules queries (' . $this->_formatTime($iTimeModules, 3) . ')';

        return $this->oTemplate->plank(
            $this->oTemplate->nameValue('Modules:', $sModules) .
            $this->oTemplate->nameValue('Modules Queries:', $sModulesQueries),
            $this->oTemplate->table($GLOBALS['bx_profiler']->_aModules)
        );
    }

    function _formatTime ($i, $iPrecision = 3)
    {
        return round($i, $iPrecision) . ' sec';
    }

    function _formatBytes ($i)
    {
        if ($i > 1024*1024)
            return round($i/1024/1024, 1) . 'M';
        elseif ($i > 1024)
            return round($i/1024, 1) . 'K';
        else
            return $i . 'B';
    }

    function _isProfilerDisabled()
    {
        if (isset($GLOBALS['bx_profiler_disable']) || isset($_GET['bx_profiler_disable']))
            return true;
        if (
            preg_match('/gzip_loader\.php/', $_SERVER['PHP_SELF']) ||
            preg_match('/get_rss_feed\.php/', $_SERVER['PHP_SELF']) ||
            preg_match('/fields\.parse\.php/', $_SERVER['PHP_SELF']) ||
            preg_match('/flash/', $_SERVER['PHP_SELF']) ||
            preg_match('/forum/', $_SERVER['PHP_SELF'])
        )
            return true;
        return false;
    }

    function _calcTime ($begin)
    {
        $i1 = explode(' ', microtime ());
        $i2 = explode(' ', $begin);
        return ($i1[0]+$i1[1]) - ($i2[0]+$i2[1]);
    }

    function _debugPrintArray ($mixed)
    {
        $sArgs .= 'Array(';
        foreach ($mixed as $mixed2)
            $sArgs .= (is_object($mixed2) ? $this->_debugPrintObject($mixed2) : $this->_debugPrintAny($mixed2)) . ',';
        $sArgs = substr($sArgs, 0, -1);
        $sArgs .= ')';
        return $sArgs;
    }

    function _debugPrintObject ($mixed)
    {
        return get_class($mixed) . ' instance';
    }

    function _debugPrintAny ($mixed)
    {
        if (is_string)
            return "'" . (strlen($mixed) > $this->_sLogMaxArgLength ? substr($mixed, 0, $this->_sLogMaxArgLength) . '...' : $mixed) . "'";
        else
            return $mixed;
    }

    function _debugBackTrace ($iShifts = 0)
    {
        $a = debug_backtrace();
        while (--$iShifts > -1)
            array_shift($a);

        $s = '';
        foreach ($a as $r) {

            $sArgs = '';
            foreach ($r['args'] as $mixed) {
                switch (true) {
                case is_array($mixed):
                    $sArgs .= $this->_debugPrintArray($mixed);
                    break;
                case is_object($mixed):
                    $sArgs .= $this->_debugPrintObject($mixed);
                    break;
                default:
                    $sArgs .= $this->_debugPrintAny($mixed);
                }
                $sArgs .= ',';
            }
            $sArgs = substr($sArgs, 0, -1);

            $s .= "--------------------------------------\n";
            $s .= "{$r['line']} {$r['file']}\n";
            $s .= "{$r['class']}{$r['type']}{$r['function']} ({$sArgs});\n";
        }
        return $s;
    }

    function _getHeaderContentType()
    {
        $aHeaders = headers_list();
        foreach ($aHeaders as $s) {
            $a = explode(':', $s);
            if (isset($a[0]) && isset($a[1]) && 'content-type' == strtolower(trim($a[0]))) {
                $aa = explode(';', $a[1]);
                return strtolower(trim($aa[0]));
            }
        }

        return false;
    }
}

$GLOBALS['bx_profiler'] = new BxProfiler(BX_DOL_START);
if (!$GLOBALS['bx_profiler']->_isProfilerDisabled())
    register_shutdown_function (array ($GLOBALS['bx_profiler'], 'output'));

/** @} */

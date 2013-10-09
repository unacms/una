<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

require_once('./../inc/classes/BxDolIO.php');

// TODO: consider rewriting installer

class BxDolAdminTools extends BxDolIO {

    var $sTroubledElements;

    var $aInstallDirs;
    var $aInstallFiles;
    var $aFlashDirs;
    var $aFlashFiles;
    var $aPostInstallPermDirs;
    var $aPostInstallPermFiles;

    function BxDolAdminTools() {
        parent::BxDolIO();

        $this->sTroubledElements = '';

        $this->aInstallDirs = array(
            'cache',
            'cache_public',
            'tmp',
            'plugins/htmlpurifier/standalone/HTMLPurifier/DefinitionCache/Serializer',
            'plugins/htmlpurifier/standalone/HTMLPurifier/DefinitionCache/Serializer/HTML',
            'plugins/htmlpurifier/standalone/HTMLPurifier/DefinitionCache/Serializer/CSS',
            'plugins/htmlpurifier/standalone/HTMLPurifier/DefinitionCache/Serializer/Test',
            'plugins/htmlpurifier/standalone/HTMLPurifier/DefinitionCache/Serializer/URI',
        );

        $this->aInstallFiles = array(
        );

        $this->aFlashDirs = array(
        );

        $this->aFlashFiles = array(
        );

        $this->aPostInstallPermDirs = array(
        );

        $this->aPostInstallPermFiles = array(
        );
    }

    function GenCommonCode() {
        $sAdditionDir = (isAdmin()==true) ? BX_DOL_URL_ROOT : '../';
        $sMasterSwPic = $sAdditionDir . 'media/images/master_nav.gif';

        $sRet = <<<EOF
<style type="text/css">

    div.hidden {
        display:none;
    }

    .left_side_sw_caption {
        float:left;
        text-align:justify;
        width:515px;
    }

    .right_side_sw_caption {
        float:right;
        width:60px;
    }

    tr.head td {
        background-color:#FFFFFF;
        font-weight:bold;
        height:17px;
        padding:5px;
        text-align:center;
        font-size:13px;
        border-color:silver;
    }
    tr.cont td {
        height:15px;
        padding:2px 5px;
        font-size:13px;
        border-color:silver;
    }

    .install_table {
        background-color: silver;
        border-width:0px;
    }

    span.unwritable {
        color:red;
        font-weight:bold;
        margin-right:5px;
    }

    span.writable {
        color:green;
        font-weight:bold;
        margin-right:5px;
    }

    span.desired {
        font-weight:bold;
        margin-right:5px;
    }

    .install_table tr.even {
        background-color:#EDE9E9;
    }

    .install_table tr.odd {
        background-color:#FFF;
    }

    .install_table tr:hover{
        background-color:#DDD;
    }

    #btn-alls-on {
        background:transparent url({$sMasterSwPic}) no-repeat scroll -0px -0px;
        width:25px;
        height:21px;
    }
    #btn-alls-off {
        background:transparent url({$sMasterSwPic}) no-repeat scroll -0px -20px;
        width:25px;
        height:21px;
    }
    #btn-troubled-on {
        background:transparent url({$sMasterSwPic}) no-repeat scroll -24px -20px;
        width:25px;
        height:21px;
    }

    #btn-troubled-off {
        background:transparent url({$sMasterSwPic}) no-repeat scroll -24px -0px;
        width:25px;
        height:21px;
    }

    tr.head td.left_aligned {
        text-align:left;
        font-weight:bold;
    }
</style>
EOF;
        return $sRet;
    }

    function GenPermTable() {
        $sDirsC = function_exists('_t') ? _t('_adm_admtools_Directories') : 'Directories';
        $sFilesC = function_exists('_t') ? _t('_adm_admtools_Files') : 'Files';
        $sElementsC = function_exists('_t') ? _t('_adm_admtools_Elements') : 'Elements';
        $sFlashC = function_exists('_t') ? _t('_adm_admtools_Flash') : 'Flash';
        $sCurrentLevelC = function_exists('_t') ? _t('_adm_admtools_Current_level') : 'Current level';
        $sDesiredLevelC = function_exists('_t') ? _t('_adm_admtools_Desired_level') : 'Desired level';
        $sBadFilesC = function_exists('_t') ? _t('_adm_admtools_Bad_files') : 'Next files and directories have inappropriate permissions';
        $sShowOnlyBadC = function_exists('_t') ? _t('_adm_admtools_Only_bad_files') : 'Show only troubled files and directories with inappropriate permissions';
        $sShowAllC = function_exists('_t') ? _t('_adm_admtools_Show_all_files') : 'Show all files and directories';
        $sDescriptionC = function_exists('_t') ? _t('_adm_admtools_Perm_description') : 'Dolphin needs special access for certain files and directories. Please, change permissions as specified in the chart below. Helpful info about permissions is <a href="http://www.boonex.com/trac/dolphin/wiki/DetailedInstall#Permissions" target="_blank">available here</a>.';

        $this->sTroubledElements = '';

        $sInstallDirs = $this->GenArrElemPerm($this->aInstallDirs, 1);
        $sFlashDirs = $this->GenArrElemPerm($this->aFlashDirs, 1);
        $sInstallFiles = $this->GenArrElemPerm($this->aInstallFiles, 2);
        $sFlashFiles = $this->GenArrElemPerm($this->aFlashFiles, 2);

        $sAdditionDir = (isAdmin()==true) ? BX_DOL_URL_ROOT : '../';
        $sLeftAddEl = (isAdmin()==true) ? '<div class="left_side_sw_caption">'.$sDescriptionC.'</div>' : '';

        $sSpacerPic = $sAdditionDir . 'media/images/spacer.gif';

        $sRet = <<<EOF
<script type="text/javascript">
    <!--
    function callSwitcher(){
        $('table.install_table tr:not(.troubled)').toggle();
    }

    function switchToTroubled(viewType) {
        if (viewType == 'A') {
            $("#btn-alls-off").attr("id", "btn-alls-on");
            $("#btn-troubled-on").attr("id", "btn-troubled-off");
            $('table.install_table tr:not(.troubled)').show();
        } else if (viewType == 'T') {
            $("#btn-alls-on").attr("id", "btn-alls-off");
            $("#btn-troubled-off").attr("id", "btn-troubled-on");
            $('table.install_table tr:not(.troubled)').hide();
        }
        return false;
    }
    -->
</script>

<table width="100%" cellspacing="1" cellpadding="0" class="install_table">
    <tr class="head troubled">
        <td colspan="3" style="text-align:center;">
        {$sLeftAddEl}
        <div class="right_side_sw_caption">
            <a onclick="return switchToTroubled('A')" href="#"><img id="btn-alls-on" src="{$sSpacerPic}" alt="{$sShowAllC}" title="{$sShowAllC}" /></a>
            <a onclick="return switchToTroubled('T')" href="#"><img id="btn-troubled-off" src="{$sSpacerPic}" alt="{$sShowOnlyBadC}" title="{$sShowOnlyBadC}" /></a>
        </div>
        <div class="clear_both"></div>
        </td>
    </tr>
    <tr class="head">
        <td colspan="3" style="text-align:center;" class="normal_td">{$sDirsC}</td>
    </tr>
    <tr class="head">
        <td>{$sDirsC}</td>
        <td>{$sCurrentLevelC}</td>
        <td>{$sDesiredLevelC}</td>
    </tr>
    {$sInstallDirs}
    <tr class="head">
        <td>{$sFlashC} {$sDirsC}</td>
        <td>{$sCurrentLevelC}</td>
        <td>{$sDesiredLevelC}</td>
    </tr>
    {$sFlashDirs}
    <tr class="head">
        <td colspan="3" style="text-align:center;">{$sFilesC}</td>
    </tr>
    <tr class="head">
        <td>{$sFilesC}</td>
        <td>{$sCurrentLevelC}</td>
        <td>{$sDesiredLevelC}</td>
    </tr>
    {$sInstallFiles}
    <tr class="head">
        <td>{$sFlashC} {$sFilesC}</td>
        <td>{$sCurrentLevelC}</td>
        <td>{$sDesiredLevelC}</td>
    </tr>
    {$sFlashFiles}
    <tr class="head troubled">
        <td colspan="3" style="text-align:center;">{$sBadFilesC}</td>
    </tr>
    <tr class="head troubled">
        <td>{$sElementsC}</td>
        <td>{$sCurrentLevelC}</td>
        <td>{$sDesiredLevelC}</td>
    </tr>
    {$this->sTroubledElements}
</table>
EOF;
        return $sRet;
    }

    function GenArrElemPerm($aElements, $iType) { //$iType: 1 - folder, 2 - file
        if (!is_array($aElements) || empty($aElements))
            return '';
        $sWritableC = function_exists('_t') ? _t('_adm_admtools_Writable') : 'Writable';
        $sNonWritableC = function_exists('_t') ? _t('_adm_admtools_Non_Writable') : 'Non-Writable';
        $sNotExistsC = function_exists('_t') ? _t('_adm_admtools_Not_Exists') : 'Not Exists';
        $sExecutableC = function_exists('_t') ? _t('_adm_admtools_Executable') : 'Executable';
        $sNonExecutableC = function_exists('_t') ? _t('_adm_admtools_Non_Executable') : 'Non-Executable';

        $iType = ($iType==1) ? 1 : 2;

        $sElements = '';
        $i = 0;
        foreach ($aElements as $sCurElement) {
            $iCurType = $iType;

            $sAwaitedPerm = ($iCurType==1) ? $sWritableC : $sWritableC;

            $sElemCntStyle = ($i%2==0) ? 'even' : 'odd' ;
            $bAccessible = ($iCurType==1) ? self::isWritable($sCurElement) : self::isWritable($sCurElement);

            if ($sCurElement == 'flash/modules/global/app/ffmpeg.exe') {
                $sAwaitedPerm = $sExecutableC;
                $bAccessible = self::isExecutable($sCurElement);
            }

            if ($bAccessible) {
                $sResultPerm = ($iCurType==1) ? $sWritableC : $sWritableC;

                if ($sCurElement == 'flash/modules/global/app/ffmpeg.exe') {
                    $sResultPerm = $sExecutableC;
                }

                $sElements .= <<<EOF
<tr class="cont {$sElemCntStyle}">
    <td>{$sCurElement}</td>
    <td class="span">
        <span class="writable">{$sResultPerm}</span>
    </td>
    <td class="span">
        <span class="desired">{$sAwaitedPerm}</span>
    </td>
</tr>
EOF;
            } else {
                $sPerm = self::getPermissions($sCurElement);
                $sResultPerm = '';
                if ($sPerm==false) {
                    $sResultPerm = $sNotExistsC;
                } else {
                    $sResultPerm = ($iCurType==1) ? $sNonWritableC : $sNonWritableC;
                }

                if ($sCurElement == 'flash/modules/global/app/ffmpeg.exe') {
                    $sResultPerm = $sNonExecutableC;
                }

                $sPerm = '';

                $sElements .= <<<EOF
<tr class="cont {$sElemCntStyle}">
    <td>{$sCurElement}</td>
    <td class="span">
        <span class="unwritable">{$sPerm} {$sResultPerm}</span>
    </td>
    <td class="span">
        <span class="desired">{$sAwaitedPerm}</span>
    </td>
</tr>
EOF;

                $this->sTroubledElements .= <<<EOF
<tr class="cont {$sElemCntStyle} troubled">
    <td>{$sCurElement}</td>
    <td class="span">
        <span class="unwritable">{$sPerm} {$sResultPerm}</span>
    </td>
    <td class="span">
        <span class="desired">{$sAwaitedPerm}</span>
    </td>
</tr>
EOF;

            }
            $i++;
        }
        return $sElements;
    }

    function performInstalCheck() { //check requirements
        $aErrors = array();

        $aErrors[] = (ini_get('register_globals') == 0) ? '' : '<font color="red">register_globals is On (warning, you should have this param in Off state, or your site will unsafe)</font>';
        $aErrors[] = (ini_get('safe_mode') == 0) ? '' : '<font color="red">safe_mode is On, disable it</font>';
        //$aErrors[] = (ini_get('allow_url_fopen') == 0) ? 'Off (warning, better keep this parameter in On to able register Dolphin' : '';
        $aErrors[] = (((int)phpversion()) < 4) ? '<font color="red">PHP version too old, update server please</font>' : '';
        $aErrors[] = (! extension_loaded( 'mbstring')) ? '<font color="red">mbstring extension not installed. <b>Warning!</b> Dolphin cannot work without <b>mbstring</b> extension.</font>' : '';

        if (version_compare(phpversion(), "5.2", ">") == 1) {
            $aErrors[] = (ini_get('allow_url_include') == 0) ? '' : '<font color="red">allow_url_include is On (warning, you should have this param in Off state, or your site will unsafe)</font>';
        };

        $aErrors = array_diff($aErrors, array('')); //delete empty
        if (count($aErrors)) {
            $sErrors = implode(" <br /> ", $aErrors);
            echo <<<EOF
{$sErrors} <br />
Please go to the <br />
<a href="http://www.boonex.com/trac/dolphin/wiki/GenDolTShooter">Dolphin Troubleshooter</a> <br />
and solve the problem.
EOF;
            exit;
        }
    }

    function GenCacheEnginesTable() {

        $sRet = '<table width="100%" cellspacing="1" cellpadding="0" class="install_table">';
        $sRet .= '
<tr class="head troubled">
    <td></td>
    <td class="center_aligned">' . _t('_sys_adm_installed') . '</td>
    <td class="center_aligned">' . _t('_sys_adm_available') . '</td>
</tr>';

        $aEngines = array ('File', 'EAccelerator', 'Memcache', 'APC', 'XCache');
        foreach ($aEngines as $sEngine) {
            $oCacheObject = @bx_instance ('BxDolCache' . $sEngine);
            $sRet .= '
<tr class="head troubled">
    <td class="left_aligned">' . $sEngine . '</td>
    <td class="center_aligned">' . (@$oCacheObject->isInstalled() ? '<font color="green">' . _t('_Yes') . '</font>' : '<font color="red">' . _t('_No') . '</font>') . '</td>
    <td class="center_aligned">' . (@$oCacheObject->isAvailable() ? '<font color="green">' . _t('_Yes') . '</font>' : '<font color="red">' . _t('_No') . '</font>') . '</td>
</tr>';
        }

        $sRet .= '</table>';
        return $sRet;
    }

    function GenTabbedPage() {
        $sTitleC = _t('_adm_admtools_title');
        $sAuditC = _t('_adm_admtools_Audit');
        $sPermissionsC = _t('_adm_admtools_Permissions');
        $sCacheEnginesC = _t('_adm_admtools_cache_engines');

        $sAuditTab = $this->GenAuditPage();
        $sPermissionsTab = $this->GenPermTable();
        $sCacheEnginesTab = $this->GenCacheEnginesTable();

        $sBoxContent = <<<EOF
<script type="text/javascript">
    <!--
    function switchAdmPage(iPageID) {
        //make all tabs - inactive
        //mace selected tab - active
        //hide all pages
        //show selected page

        $(".dbTopMenu").children().removeClass().toggleClass("notActive");
        $("#main_menu" + iPageID).removeClass().toggleClass("active");

        $("#adm_pages").children().removeClass().toggleClass("hidden");
        $("#adm_pages #page" + iPageID).removeClass().toggleClass("visible");

        return false;
    }
    -->
</script>

<div class="boxContent" id="adm_pages">
    <div id="page0" class="visible">{$sAuditTab}</div>
    <div id="page1" class="visible">{$sPermissionsTab}</div>
    <div id="page3" class="hidden">
        <iframe frameborder="0" width="100%" height="800" scrolling="auto" src="host_tools.php?get_phpinfo=true"></iframe>
    </div>
    <div id="page4" class="hidden">{$sCacheEnginesTab}</div>
</div>
EOF;

        $sActions = <<<EOF
<div class="dbTopMenu">
    <div class="active" id="main_menu0"><span><a href="#" class="top_members_menu" onclick="switchAdmPage(0); return false;">{$sAuditC}</a></span></div>
    <div class="notActive" id="main_menu3"><span><a href="#" class="top_members_menu" onclick="switchAdmPage(3); return false;">phpinfo</a></span></div>
    <div class="notActive" id="main_menu4"><span><a href="#" class="top_members_menu" onclick="switchAdmPage(4); return false;">{$sCacheEnginesC}</a></span></div>
</div>
EOF;

        $sWrappedBox = $GLOBALS['oAdmTemplate']->parseHtmlByName('design_box_content.html', array('content' => $sBoxContent));
        return DesignBoxContent($sTitleC, $sWrappedBox, 1, $sActions);
    }

    //************
    function isFolderReadWrite($filename) {
        clearstatcache();

        $aPathInfo = pathinfo(__FILE__);
        $filename = $aPathInfo['dirname'] . '/../../' . $filename;

        return (@file_exists($filename . '/.') && is_readable( $filename ) && is_writable( $filename ) ) ? true : false;
    }

    function isFileReadWrite($filename) {
        clearstatcache();

        $aPathInfo = pathinfo(__FILE__);
        $filename = $aPathInfo['dirname'] . '/../../' . $filename;

        return (is_file($filename) && is_readable( $filename ) && is_writable( $filename ) ) ? true : false;
    }

    function isFileExecutable($filename) {
        clearstatcache();

        $aPathInfo = pathinfo(__FILE__);
        $filename = $aPathInfo['dirname'] . '/../../' . $filename;

        return (is_file($filename) && is_executable( $filename ) ) ? true : false;
    }

    //************

    function isAllowUrlInclude() {
        if (version_compare(phpversion(), "5.2", ">") == 1) {
            $sAllowUrlInclude = ini_get('allow_url_include');
            return !($sAllowUrlInclude == 0);
        };
        return false;
    }


    function GenAuditPage() {

        $sDolphinPath = BX_DIRECTORY_PATH_ROOT;

        $sEmailToCkeckMailSending = getParam('site_email');

        $sLatestDolphinVer = file_get_contents("http://rss.boonex.com/");
        if (preg_match ('#<dolphin>([\.0-9]+)</dolphin>#', $sLatestDolphinVer, $m))
            $sLatestDolphinVer = $m[1];
        else
            $sLatestDolphinVer = 'undefined';

        $sMinPhpVer = '5.2.0';
        $sMinMysqlVer = '4.1.2';

        $a = unserialize(file_get_contents("http://www.php.net/releases/index.php?serialize=1"));
        $sLatestPhpVersion = $a[5]['version'];
        $sLatestPhp52Version = '5.2.17';

        $aPhpSettings = array (
            'allow_url_fopen' => array('op' => '=', 'val' => true, 'type' => 'bool'),
            'allow_url_include' => array('op' => '=', 'val' => false, 'type' => 'bool'),
            'magic_quotes_gpc' => array('op' => '=', 'val' => false, 'type' => 'bool', 'warn' => 1),
            'memory_limit' => array('op' => '>=', 'val' => 128*1024*1024, 'type' => 'bytes', 'unlimited' => -1),
            'post_max_size' => array('op' => '>=', 'val' => 50*1024*1024, 'type' => 'bytes', 'warn' => 1),
            'upload_max_filesize' => array('op' => '>=', 'val' => 50*1024*1024, 'type' => 'bytes', 'warn' => 1),
            'register_globals' => array('op' => '=', 'val' => false, 'type' => 'bool'),
            'safe_mode' => array('op' => '=', 'val' => false, 'type' => 'bool'),
            'short_open_tag' => array('op' => '=', 'val' => true, 'type' => 'bool'),
            'disable_functions' => array('op' => '=', 'val' => ''),
            'php module: curl' => array('op' => 'module', 'val' => 'curl'),
            'php module: gd' => array('op' => 'module', 'val' => 'gd'),
            'php module: mbstring' => array('op' => 'module', 'val' => 'mbstring'),
            'php module: openssl' => array('op' => 'module', 'val' => 'openssl', 'warn' => 1),
            'php module: ftp' => array('op' => 'module', 'val' => 'ftp', 'warn' => 1),
        );

        $aMysqlSettings = array (
            'key_buffer_size' => array('op' => '>=', 'val' => 128*1024, 'type' => 'bytes'),
            'query_cache_limit' => array('op' => '>=', 'val' => 1000000),
            'query_cache_size' => array('op' => '>=', 'val' => 16*1024*1024, 'type' => 'bytes'),
            'query_cache_type' => array('op' => 'strcasecmp', 'val' => 'on'),
            'max_heap_table_size' => array('op' => '>=', 'val' => 16*1024*1024, 'type' => 'bytes'),
            'tmp_table_size' => array('op' => '>=', 'val' => 16*1024*1024, 'type' => 'bytes'),
            'thread_cache_size ' => array('op' => '>', 'val' => 0),
        );

        $aRequiredApacheModules = array (
            'rewrite_module' => 'mod_rewrite',
        );

        $aDolphinOptimizationSettings = array (

            'DB cache' => array('enabled' => 'sys_db_cache_enable', 'cache_engine' => 'sys_db_cache_engine', 'check_accel' => true),

            'Page blocks cache' => array('enabled' => 'sys_pb_cache_enable', 'cache_engine' => 'sys_pb_cache_engine', 'check_accel' => true),

            'Member menu cache' => array('enabled' => 'always_on', 'cache_engine' => 'sys_mm_cache_engine', 'check_accel' => true),

            'Templates Cache' => array('enabled' => 'sys_template_cache_enable', 'cache_engine' => 'sys_template_cache_engine', 'check_accel' => true),

            'CSS files cache' => array('enabled' => 'sys_template_cache_css_enable', 'cache_engine' => '', 'check_accel' => false),

            'JS files cache' => array('enabled' => 'sys_template_cache_js_enable', 'cache_engine' => '', 'check_accel' => false),

            'Compression for CSS/JS cache' => array('enabled' => 'sys_template_cache_compress_enable', 'cache_engine' => '', 'check_accel' => false),
        );

        ob_start();
?>
<style>
.ok {
    color:green;
}
.fail {
    color:red;
}
.warn {
    color:orange;
}
.undef {
    color:gray;
}
.code {
    border:1px solid #090;
    color:#090;
    padding:10px;
    margin:10px;
    width:550px;
    overflow:scroll;
}
</style>
<h2>Software requirements</h2>
<ul>
    <li><b>PHP</b>: 
        <?php 
        $sPhpVer = PHP_VERSION;
        echo $sPhpVer . ' - '; 
        if (version_compare($sPhpVer, $sMinPhpVer, '<'))
            echo '<b class="fail">FAIL</b> (your version is incompatible with Dolphin, must be at least ' . $sMinPhpVer . ')';
        elseif (version_compare($sPhpVer, '5.3.0', '>=') && version_compare($sPhpVer, '6.0.0', '<') && !version_compare($sPhpVer, $sLatestPhpVersion, '>='))
            echo '<b class="warn">WARNING</b> (your PHP version is outdated, upgrade to the latest ' . $sLatestPhpVersion . ' maybe required)';
        elseif (version_compare($sPhpVer, '5.2.0', '>=') && version_compare($sPhpVer, '5.3.0', '<') && !version_compare($sPhpVer, $sLatestPhp52Version, '>='))
            echo '<b class="warn">WARNING</b> (your PHP version is outdated, upgrade to the latest ' . $sLatestPhp52Version . ' maybe required)';
        else
            echo '<b class="ok">OK</b>';
        
        ?>
        <ul>
        <?php 
        foreach ($aPhpSettings as $sName => $r) {
            $a = $this->checkPhpSetting($sName, $r);
            echo "<li>$sName = " . $this->format_output($a['real_val'], $r) ." - ";
            if ($a['res'])
                echo '<b class="ok">OK</b>';
            elseif ($r['warn'])
                echo "<b class='warn'>WARNING</b> (should be {$r['op']} " . $this->format_output($r['val'], $r) . ")"; 
            else
                echo "<b class='fail'>FAIL</b> (must be {$r['op']} " . $this->format_output($r['val'], $r) . ")"; 
            echo "</li>\n";
        } 
        ?>
        </ul>
    </li>
    <li><b>MySQL</b>: 
        <?php
            $sMysqlVer = mysql_get_server_info($GLOBALS['bx_db_link']);
            echo $sMysqlVer . ' - ';
            if (preg_match ('/^(\d+)\.(\d+)\.(\d+)/', $sMysqlVer, $m)) {
                $sMysqlVer = "{$m[1]}.{$m[2]}.{$m[3]}";
                if (version_compare($sMysqlVer, $sMinMysqlVer, '<'))
                    echo '<b class="fail">FAIL</b> (your version is incompatible with Dolphin, must be at least ' . $sMinMysqlVer . ')';
                else
                    echo '<b class="ok">OK</b>';
            } else {
                echo '<b class="undef">UNDEFINED</b>';
            }
        ?>
    </li>
    <li><b>Web-server</b>:
        <?php
            echo $_SERVER['SERVER_SOFTWARE'];
        ?>
        <ul>
            <?php
                foreach ($aRequiredApacheModules as $sName => $sNameCompiledName)
                    echo '<li>' . $sName . ' - ' . $this->checkApacheModule($sName, $sNameCompiledName) . '</li>';
            ?>           
        </ul>
    </li> 
    <li><b>OS</b>:
        <?php
            echo php_uname();
        ?>
    </li> 
</ul>




<h2>Hardware requirements</h2>
<p>
    Hardware requirements can not be determined automatically - <a href="#manual_audit">manual server audit</a> may be reqired.
</p>


<h2>Site setup</h2>
<ul>
    <li>
        <b>Dolphin version</b> = 
        <?php            
            $sDolphinVer = BX_DOL_VERSION . '.' . BX_DOL_BUILD;
            echo $sDolphinVer . ' - ';
            if (!version_compare($sDolphinVer, $sLatestDolphinVer, '>='))
                echo '<b class="warn">WARNING</b> (your Dolphin version is outdated please upgrade to the latest ' . $sLatestDolphinVer . ' version)';
            else
                echo '<b class="ok">OK</b>';
        ?>
    </li>
    <li>
        <b>files and folders permissions</b>
        <br />
        Please <a href="javascript:void(0);" onclick="switchAdmPage(1);">click here</a> to find out if dolphin permissions are correct.
    </li>
    <li>
        <b>ffmpeg</b>
        <pre class="code"><?php echo `{$sDolphinPath}flash/modules/global/app/ffmpeg.exe 2>&1`;?></pre>
        if you don't know if output is correct then <a href="#manual_audit">manual server audit</a> may be reqired.
    </li>
    <li>
        <script language="javascript">
            function bx_sys_adm_audit_test_email() {
                $('#bx-sys-adm-audit-test-email').html('Sending...');
                $.post('<?php echo BX_DOL_URL_STUDIO; ?>host_tools.php?action=audit_send_test_email', function(data) {
                    $('#bx-sys-adm-audit-test-email').html(data);
                });
            }
        </script>
        <b>mail sending - </b>
        <span id="bx-sys-adm-audit-test-email"><a href="javascript:void(0);" onclick="bx_sys_adm_audit_test_email()">click here</a> to send test email to <?php echo $sEmailToCkeckMailSending; ?></span>
    </li>
    <li>
        <b>cronjobs</b>
        <pre class="code"><?php echo `crontab -l 2>&1`;?></pre>
        if you are unsure if output is correct then <a href="#manual_audit">manual server audit</a> may be reqired.
    </li>
    <li>
        <b>media server</b>
        <br />
        Please follow <a href="<?php echo BX_DOL_URL_STUDIO; ?>flash.php">this link</a> to check media server settings. Also you can try video chat - if video chat is working then most probably that flash media server is working correctly, however it doesn't guarantee that all other flash media server application will work.
    </li>
    <li>
        <b>forums</b>
        <br />
        Please follow <a href="<?php echo BX_DOL_URL_ROOT; ?>forum/">this link</a> to check if forum is functioning properly. If it is working but '[L[' signs are displayed everywhere, then you need to <a href="<?php echo BX_DOL_URL_ROOT; ?>forum/?action=goto&manage_forum=1">compile language file</a> (you maybe be need to compile language file separately for every language and template you have).
    </li>
</ul>




<h2>Site optimization</h2>
<ul>
    <li><b>PHP</b>: 
        <ul>
            <li><b>php accelerator</b> = 
            <?php
                $sAccel = $this->getPhpAccelerator();
                if (!$sAccel)
                    echo 'NO - <b class="warn">WARNING</b> (Dolphin can be much faster if you install some php accelator))';
                else
                    echo $sAccel . ' - <b class="ok">OK</b>';
            ?>
            </li>
            <li><b>php setup</b> = 
            <?php
                $sSapi = php_sapi_name();
                echo $sSapi . ' - ';
                if (0 == strncasecmp('cgi', $sSapi, 3))
                    echo '<b class="warn">WARNING</b> (your PHP setup maybe very inefficient, <a href="?action=phpinfo">please check it for sure</a> and try to switch to mod_php, apache dso module or FastCGI)';
                else
                    echo '<b class="ok">OK</b>';
            ?>
            </li>
        </ul>
    </li>
    <li><b>MySQL</b>: 
        <ul>
            <?php                
                foreach ($aMysqlSettings as $sName => $r) {
                    $a = $this->checkMysqlSetting($sName, $r, $l);
                    echo "<li><b>$sName</b> = " . $this->format_output($a['real_val'], $r) ." - " . ($a['res'] ? '<b class="ok">OK</b>' : "<b class='fail'>FAIL</b> (must be {$r['op']} " . $this->format_output($r['val'], $r) . ")") . "</li>\n";
                } 
            ?>
        </ul>
    </li>
    <li><b>Web-server</b>: 
        <ul>
            <li>
                <b>User-side caching for static conten</b> = 
                <a href="<?php echo $this->getUrlForGooglePageSpeed('LeverageBrowserCaching'); ?>">click here to check it in Google Page Speed</a>
                <br />
                If it is not enabled then please consider implement this optimization, since it improve perceived site speed and save the bandwidth, refer to <a target="_blank" href="http://www.boonex.com/trac/dolphin/wiki/HostingServerSetupRecommendations#Usersidecachingforstaticcontent">this tutorial</a> on how to do this.
                <br />
                <?php
                    $sName = 'expires_module';
                    echo 'To apply this optimization you need to have <b>' . $sName . '</b> Apache module - ' . $this->checkApacheModule($sName);
                ?> 
            </li>
            <li>
                <b>Server-side content compression</b> = can be checked <a href="#manual_audit">manually</a> or in "Page Speed" tool build-in into browser.
                <br />
                If it is not enabled then please consider implement this optimization, since it improve perceived site speed and save the bandwidth, refer to <a href="http://www.boonex.com/trac/dolphin/wiki/HostingServerSetupRecommendations#Serversidecontentcompression">this tutorial</a> on how to do this.
                </textarea>
                <br />
                <?php
                    $sName = 'deflate_module';                
                    echo 'To apply this optimization you need to have <b>' . $sName . '</b> Apache module - ' . $this->checkApacheModule($sName);
                ?> 
            </li>
        </ul>
    </li>
    <li><b>Dolphin</b>: 
        <ul>
            <?php
                
                foreach ($aDolphinOptimizationSettings as $sName => $a) {

                    echo "<li><b>$sName</b> = ";

                    echo ('always_on' == $a['enabled'] || getParam($a['enabled'])) ? 'On' : 'Off';

                    if ($a['cache_engine'])
                        echo " (" . getParam($a['cache_engine']) . ' based cache engine)'; 

                    echo ' - ';

                    if ('always_on' != $a['enabled'] && !getParam($a['enabled']))
                        echo '<b class="fail">FAIL</b> (please enable this cache in Dolphin Admin Panel -> Settings -> Advanced Settings)';
                    elseif ($a['check_accel'] && !$this->getPhpAccelerator() && 'File' == getParam($a['cache_engine']))
                        echo '<b class="warn">WARNING</b> (installing php accelerator will speed-up file cache)';
                    else
                        echo '<b class="ok">OK</b>';
                    
                    echo "</li>\n";
                }

            ?>
        </ul>
    </li>
</ul>

<a name="manual_audit"></a>
<h2>Manual Server Audit</h2>
<p>
    Some things can not be determined automatically, manual server audit is required to check it. If you don't know how to do it by yourself you can submit <a target="_blank" href="http://www.boonex.com/help/tickets">BoonEx Server Audit Request</a>. Also if you are owner of <a target="_blank" href="http://www.boonex.com/enterprise">Enterprise package</a> - you have 1 free Server Audit Request.
</p>

<?php

        return ob_get_clean();
    }

    function checkPhpSetting($sName, $a) {

        $mixedVal = ini_get($sName);
        $mixedVal = $this->format_input ($mixedVal, $a);

        switch ($a['op']) {
            case 'module':
                $bResult = extension_loaded($a['val']);
                $mixedVal = $bResult ? $a['val'] : '';
                break;
            case '>':
                $bResult = (isset($a['unlimited']) && $mixedVal == $a['unlimited']) ? true : ($mixedVal > $a['val']);
                break;
            case '>=':
                $bResult = (isset($a['unlimited']) && $mixedVal == $a['unlimited']) ? true :($mixedVal >= $a['val']);
                break;
            case '=':
            default:
                $bResult = ($mixedVal == $a['val']);
        }
        return array ('res' => $bResult, 'real_val' => $mixedVal);
    }

    function checkMysqlSetting($sName, $a, $l) {    

        $mixedVal = $this->mysqlGetOption($sName, $l);
        $mixedVal = $this->format_input ($mixedVal, $a);

        switch ($a['op']) {
            case '>':
                $bResult = ($mixedVal > $a['val']);
                break;
            case '>=':
                $bResult = ($mixedVal >= $a['val']);
                break;
            case 'strcasecmp':
                $bResult = 0 == strcasecmp($mixedVal, $a['val']);
                break;
            case '=':
            default:
                $bResult = ($mixedVal == $a['val']);
        }
        return array ('res' => $bResult, 'real_val' => $mixedVal);
    }

    function format_output ($mixedVal, $a) {
        switch ($a['type']) {
            case 'bool':
                return $mixedVal ? 'On' : 'Off';
            default:
                return $mixedVal;
        }
    }

    function format_input ($mixedVal, $a) {
        switch ($a['type']) {
            case 'bytes':
                return $this->format_bytes ($mixedVal);
            default:
                return $mixedVal;
        }
    }

    function format_bytes($val) {
        return return_bytes($val);
    }

    function checkApacheModule ($sModule, $sNameCompiledName = '') {
        $a = array (
            'deflate_module' => 'mod_deflate',
            'expires_module' => 'mod_expires',
        );
        if (!$sNameCompiledName && isset($a[$sModule]))
            $sNameCompiledName = $a[$sModule];

        if (function_exists('apache_get_modules')) {

	    	$aModules = apache_get_modules();
            $ret = in_array($sNameCompiledName, $aModules);

        } else {

            $sApachectlPath = trim(`which apachectl`); 
            if (!$sApachectlPath)
                $sApachectlPath = trim(`which apache2ctl`); 
            if (!$sApachectlPath)
                $sApachectlPath = trim(`which /usr/local/apache/bin/apachectl`);     
            if (!$sApachectlPath)
                $sApachectlPath = trim(`which /usr/local/apache/bin/apache2ctl`);
            if (!$sApachectlPath)
                return '<b class="undef">UNDEFINED</b> (try to check manually: apachectl -M 2>&1 | grep ' . $sModule . ')';

            $ret = (boolean)`$sApachectlPath -M 2>&1 | grep $sModule`;
            if (!$ret)
                $ret = (boolean)`$sApachectlPath -l 2>&1 | grep $sNameCompiledName`;
        }

        return $ret ? '<b class="ok">OK</b>' : '<b class="fail">FAIL</b> (You will need to install ' . $sModule . ' for Apache)';
    }


    function getPhpAccelerator () {   
        $aAccelerators = array (
            'eAccelerator' => array('op' => 'module', 'val' => 'eaccelerator'),
            'APC' => array('op' => 'module', 'val' => 'apc'),
            'XCache' => array('op' => 'module', 'val' => 'xcache'),
        );
        foreach ($aAccelerators as $sName => $r) {
            $a = $this->checkPhpSetting($sName, $r);
            if ($a['res'])
                return $sName;
        }
        return false;
    }

    function mysqlGetOption ($s, $l) {
        return db_value("SELECT @@{$s}", $l);
    }

    function getUrlForGooglePageSpeed ($sRule) {
        $sUrl = urlencode(BX_DOL_URL_ROOT);
        return 'http://pagespeed.googlelabs.com/#url=' . $sUrl . '&mobile=false&rule=' . $sRule;
    }

    function sendTestEmail () {
        $sEmailToCkeckMailSending = getParam('site_email');
        $mixedRet = sendMail($sEmailToCkeckMailSending, 'Audit Test Email', 'Sample text for testing<br /><u><b>Sample text for testing</b></u>', '', array(), BX_EMAIL_SYSTEM);
        if (!$mixedRet)
            return '<b class="fail">FAIL</b> (mail send failed)';
        else
            return 'test mail was send, please check ' . $sEmailToCkeckMailSending . ' mailbox';
    }

}
/** @} */

<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

require_once('./../inc/classes/BxDolIO.php');

// TODO: consider rewriting installer

class BxDolStudioTools extends BxDolIO
{

    protected $sTroubledElements;

    public $aInstallDirs;
    public $aInstallFiles;
    public $aPostInstallPermDirs;
    public $aPostInstallPermFiles;

    function __construct()
    {
        parent::__construct();

        $this->sTroubledElements = '';

        $this->aInstallDirs = array(
            'cache',
            'cache_public',
            'tmp',
            'plugins/htmlpurifier/standalone/HTMLPurifier/DefinitionCache/Serializer',
        );

        $this->aInstallFiles = array(
        );

        $this->aPostInstallPermDirs = array(
        );

        $this->aPostInstallPermFiles = array(
        );
    }

    function GenCommonCode()
    {
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
        font-weight:normal;
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

    /**
     * Generate permissions table for modules
     * @param $iType - 1: folder, 2: file
     * @return HTML 
     */ 
    function GenPermTableForModules($iType) 
    {
        $aList = array ();
        bx_import('BxDolModuleDb');
        $oDbModules = new BxDolModuleDb();
        $aModules = $oDbModules->getModules();
        foreach ($aModules as $a) {
            if (empty($a['path']) || !include(BX_DIRECTORY_PATH_MODULES . $a['path'] . 'install/config.php'))
                continue;
            if (empty($aConfig['install_permissions']) || !is_array($aConfig['install_permissions']['writable']))
                continue;
            foreach ($aConfig['install_permissions']['writable'] as $sPath) {
                if (1 == $iType && is_dir(BX_DIRECTORY_PATH_MODULES . $a['path'] . $sPath))
                    $aList[] = basename(BX_DIRECTORY_PATH_MODULES) . '/' . $a['path'] . $sPath;
                elseif (2 == $iType && is_file(BX_DIRECTORY_PATH_MODULES . $a['path'] . $sPath))
                    $aList[] = basename(BX_DIRECTORY_PATH_MODULES) . '/' . $a['path'] . $sPath;
            }
        }
        return $this->GenArrElemPerm($aList, $iType);
    }

    function GenPermTable($isShowModules = false)
    {
        $sModulesDirsC = function_exists('_t') ? _t('_adm_admtools_modules_dirs') : 'Modules Directories';
        $sModulesFilesC = function_exists('_t') ? _t('_adm_admtools_modules_files') : 'Modules Files';
        $sDirsC = function_exists('_t') ? _t('_adm_admtools_Directories') : 'Directories';
        $sFilesC = function_exists('_t') ? _t('_adm_admtools_Files') : 'Files';
        $sElementsC = function_exists('_t') ? _t('_adm_admtools_Elements') : 'Elements';
        $sCurrentLevelC = function_exists('_t') ? _t('_adm_admtools_Current_level') : 'Current level';
        $sDesiredLevelC = function_exists('_t') ? _t('_adm_admtools_Desired_level') : 'Desired level';
        $sBadFilesC = function_exists('_t') ? _t('_adm_admtools_Bad_files') : 'The following files and directories have inappropriate permissions';
        $sShowOnlyBadC = function_exists('_t') ? _t('_adm_admtools_Only_bad_files') : 'Show only files and directories with inappropriate permissions';
        $sDescriptionC = function_exists('_t') ? _t('_adm_admtools_Perm_description') : 'Dolphin needs special access for certain files and directories. Please, change permissions as specified in the chart below. Helpful info about permissions is <a href="http://www.boonex.com/trac/dolphin/wiki/DetailedInstall#Permissions" target="_blank">available here</a>.';

        $this->sTroubledElements = '';

        $sInstallDirs = $this->GenArrElemPerm($this->aInstallDirs, 1);
        $sInstallFiles = $this->GenArrElemPerm($this->aInstallFiles, 2);
        if ($isShowModules) {
            $sModulesDirs = $this->GenPermTableForModules(1);
            $sModulesFiles = $this->GenPermTableForModules(2);
            if ($sModulesDirs)
                $sModulesDirs = "
                    <tr class='head'>
                        <td>{$sModulesDirsC}</td>
                        <td>{$sCurrentLevelC}</td>
                        <td>{$sDesiredLevelC}</td>
                    </tr>" . $sModulesDirs;
            if ($sModulesFiles)
                $sModulesFiles = "
                    <tr class='head'>
                        <td>{$sModulesFilesC}</td>
                        <td>{$sCurrentLevelC}</td>
                        <td>{$sDesiredLevelC}</td>
                    </tr>" . $sModulesFiles;
        }
        $sAdditionDir = (isAdmin()==true) ? BX_DOL_URL_ROOT : '../';
        $sLeftAddEl = (isAdmin()==true) ? '<div class="left_side_sw_caption">'.$sDescriptionC.'</div>' : '';

        $sSpacerPic = $sAdditionDir . 'media/images/spacer.gif';

        $sRet = <<<EOF
<script type="text/javascript">
    <!--
    function callSwitcher()
    {
        $('table.install_table tr:not(.troubled)').toggle();
    }

    function switchToTroubled(e)
    {
        if (!e.checked) {
            $("#btn-alls-off").attr("id", "btn-alls-on");
            $("#btn-troubled-on").attr("id", "btn-troubled-off");
            $('table.install_table tr:not(.troubled)').show();
        } else  {
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
            <input type="checkbox" id="bx-install-permissions-show-erros-only" onclick="switchToTroubled(this)" /> <label for="bx-install-permissions-show-erros-only">$sShowOnlyBadC</label>
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
    {$sModulesDirs}
    <tr class="head">
        <td colspan="3" style="text-align:center;">{$sFilesC}</td>
    </tr>
    <tr class="head">
        <td>{$sFilesC}</td>
        <td>{$sCurrentLevelC}</td>
        <td>{$sDesiredLevelC}</td>
    </tr>
    {$sInstallFiles}
    {$sModulesFiles}
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

    //$iType: 1 - folder, 2 - file
    function GenArrElemPerm($aElements, $iType)
    { 
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
            $bAccessible = ($iCurType==1) ? $this->isWritable($sCurElement) : $this->isWritable($sCurElement);

            if ($sCurElement == 'flash/modules/global/app/ffmpeg.exe') {
                $sAwaitedPerm = $sExecutableC;
                $bAccessible = $this->isExecutable($sCurElement);
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
                $sPerm = $this->getPermissions($sCurElement);
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

    //check requirements
    function performInstalCheck()
    {
        $aErrors = array();

        $aErrors[] = (ini_get('register_globals') == 0) ? '' : '<font color="red">register_globals is On (warning, you should have this param in Off state, or your site will unsafe)</font>';
        $aErrors[] = (ini_get('safe_mode') == 0) ? '' : '<font color="red">safe_mode is On, disable it</font>';
        $aErrors[] = (((int)phpversion()) < 4) ? '<font color="red">PHP version too old, update server please</font>' : '';
        $aErrors[] = (!extension_loaded( 'mbstring')) ? '<font color="red">mbstring extension not installed. <b>Warning!</b> Dolphin cannot work without <b>mbstring</b> extension.</font>' : '';
        $aErrors[] = (ini_get('short_open_tag') == 0 && version_compare(phpversion(), "5.4", "<") == 1) ? '<font color="red">short_open_tag is Off (must be On!)<b>Warning!</b> Dolphin cannot work without <b>short_open_tag</b>.</font>' : '';

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

    function GenCacheEnginesTable()
    {
        $sRet = '<table width="100%" cellspacing="1" cellpadding="0" class="install_table">';
        $sRet .= '
<tr class="head troubled">
    <td></td>
    <td class="center_aligned">' . _t('_sys_adm_installed') . '</td>
    <td class="center_aligned">' . _t('_sys_adm_cache_support') . '</td>
</tr>';

        $aEngines = array ('File', 'Memcache', 'APC', 'XCache');
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

    function GenTabbedPage($isShowModules = false)
    {
        $sTitleC = _t('_adm_admtools_title');
        $sAuditC = _t('');
        $sPermissionsC = _t('');
        $sCacheEnginesC = _t('');

        $sAuditTab = $this->GenAuditPage();
        $sPermissionsTab = $this->GenPermTable($isShowModules);
        $sCacheEnginesTab = $this->GenCacheEnginesTable();

        $sBoxContent = <<<EOF
<script type="text/javascript">
    <!--
    function switchAdmPage(oLink)
    {
        var sType = $(oLink).attr('id').replace('main_menu', '');
        var sName = '#page' + sType;

        $(oLink).parent('.notActive').hide().siblings('.notActive:hidden').show().siblings('.active').hide().siblings('#' + $(oLink).attr('id') + '-act').show();
        $(sName).siblings('div:visible').bx_anim('hide', 'fade', 'slow', function(){
            $(sName).bx_anim('show', 'fade', 'slow');
        });

        return false;
    }
    -->
</script>

<div class="boxContent" id="adm_pages">
    <div id="page0" class="visible">{$sAuditTab}</div>
    <div id="page1" class="hidden">{$sPermissionsTab}</div>
    <div id="page2" class="hidden">
        <iframe frameborder="0" width="100%" height="800" scrolling="auto" src="host_tools.php?get_phpinfo=true"></iframe>
    </div>
    <div id="page3" class="hidden">{$sCacheEnginesTab}</div>
</div>
EOF;

        $aTopItems = array(
            'main_menu0' => array('href' => 'javascript:void(0)', 'onclick' => 'javascript:switchAdmPage(this)', 'title' => _t('_adm_admtools_Audit'), 'active' => 1),
            'main_menu1' => array('href' => 'javascript:void(0)', 'onclick' => 'javascript:switchAdmPage(this)', 'title' => _t('_adm_admtools_Permissions'), 'active' => 0),
            'main_menu2' => array('href' => 'javascript:void(0)', 'onclick' => 'javascript:switchAdmPage(this)', 'title' => _t('_adm_admtools_phpinfo'), 'active' => 0),
        );

        return DesignBoxAdmin($sTitleC, $sBoxContent, $aTopItems, '', 11);
    }

    //************
    function isFolderReadWrite($filename)
    {
        clearstatcache();

        $aPathInfo = pathinfo(__FILE__);
        $filename = $aPathInfo['dirname'] . '/../../' . $filename;

        return (@file_exists($filename . '/.') && is_readable( $filename ) && is_writable( $filename ) ) ? true : false;
    }

    function isFileReadWrite($filename)
    {
        clearstatcache();

        $aPathInfo = pathinfo(__FILE__);
        $filename = $aPathInfo['dirname'] . '/../../' . $filename;

        return (is_file($filename) && is_readable( $filename ) && is_writable( $filename ) ) ? true : false;
    }

    function isFileExecutable($filename)
    {
        clearstatcache();

        $aPathInfo = pathinfo(__FILE__);
        $filename = $aPathInfo['dirname'] . '/../../' . $filename;

        return (is_file($filename) && is_executable( $filename ) ) ? true : false;
    }

    //************

    function isAllowUrlInclude()
    {
        if (version_compare(phpversion(), "5.2", ">") == 1) {
            $sAllowUrlInclude = ini_get('allow_url_include');
            return !($sAllowUrlInclude == 0);
        };
        return false;
    }

    function GenAuditPage()
    {
        if (!class_exists('BxDolStudioToolsAudit'))
            bx_import('BxDolStudioToolsAudit');

        $oAudit = new BxDolStudioToolsAudit();
        return $oAudit->generate();
    }

}
/** @} */

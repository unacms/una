<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

define('BX_DOL_AUDIT_FAIL', 'fail');
define('BX_DOL_AUDIT_WARN', 'warn');
define('BX_DOL_AUDIT_UNDEF', 'undef');
define('BX_DOL_AUDIT_OK', 'ok');

class BxDolStudioToolsAudit extends BxDol
{
    protected $aType2ClassCSS;
    protected $aType2Title;

    protected $sLatestPhp53Version;
    protected $sMinPhpVer;
    protected $aPhpSettings;
    protected $iPhpErrorReporting;

    protected $sMinMysqlVer;
    protected $aMysqlOptimizationSettings;

    protected $aDolphinOptimizationSettings;

    protected $aRequiredApacheModules;

    function __construct()
    {
        parent::__construct();

        $this->aType2ClassCSS = array (
            BX_DOL_AUDIT_FAIL => 'fail',
            BX_DOL_AUDIT_WARN => 'warn',
            BX_DOL_AUDIT_UNDEF => 'undef',
            BX_DOL_AUDIT_OK => 'ok',
        );

        $this->aType2Title = array (
            BX_DOL_AUDIT_FAIL => 'FAIL',
            BX_DOL_AUDIT_WARN => 'WARNING',
            BX_DOL_AUDIT_UNDEF => 'UNDEFINED',
            BX_DOL_AUDIT_OK => 'OK',
        );

        $this->sLatestPhp53Version = '5.3.28';
        $this->sMinPhpVer = '5.2.0';
        $this->aPhpSettings = array (
            'allow_url_fopen' => array('op' => '=', 'val' => true, 'type' => 'bool'),
            'allow_url_include' => array('op' => '=', 'val' => false, 'type' => 'bool'),
            'magic_quotes_gpc' => array('op' => '=', 'val' => false, 'type' => 'bool', 'warn' => 1),
            'memory_limit' => array('op' => '>=', 'val' => 128*1024*1024, 'type' => 'bytes', 'unlimited' => -1),
            'post_max_size' => array('op' => '>=', 'val' => 2*1024*1024, 'type' => 'bytes', 'warn' => 1),
            'upload_max_filesize' => array('op' => '>=', 'val' => 2*1024*1024, 'type' => 'bytes', 'warn' => 1),
            'register_globals' => array('op' => '=', 'val' => false, 'type' => 'bool'),
            'safe_mode' => array('op' => '=', 'val' => false, 'type' => 'bool'),
            'short_open_tag' => array('op' => '=', 'val' => true, 'type' => 'bool'),
            'disable_functions' => array('op' => '=', 'val' => ''),
            'php module: curl' => array('op' => 'module', 'val' => 'curl'),
            'php module: gd' => array('op' => 'module', 'val' => 'gd'),
            'php module: mbstring' => array('op' => 'module', 'val' => 'mbstring'),
            'php module: xsl' => array('op' => 'module', 'val' => 'xsl', 'warn' => 1),
            'php module: json' => array('op' => 'module', 'val' => 'json', 'warn' => 1),
            'php module: openssl' => array('op' => 'module', 'val' => 'openssl', 'warn' => 1),
            'php module: zip' => array('op' => 'module', 'val' => 'zip', 'warn' => 1),
            'php module: ftp' => array('op' => 'module', 'val' => 'ftp', 'warn' => 1),
        );

        $this->sMinMysqlVer = '4.1.2';
        $this->aMysqlOptimizationSettings = array (
            'key_buffer_size' => array('op' => '>=', 'val' => 128*1024, 'type' => 'bytes'),
            'query_cache_limit' => array('op' => '>=', 'val' => 1000000),
            'query_cache_size' => array('op' => '>=', 'val' => 16*1024*1024, 'type' => 'bytes'),
            'query_cache_type' => array('op' => 'strcasecmp', 'val' => 'on'),
            'max_heap_table_size' => array('op' => '>=', 'val' => 16*1024*1024, 'type' => 'bytes'),
            'tmp_table_size' => array('op' => '>=', 'val' => 16*1024*1024, 'type' => 'bytes'),
            'thread_cache_size ' => array('op' => '>', 'val' => 0),
        );

        $this->aDolphinOptimizationSettings = array (
            'DB cache' => array('enabled' => 'sys_db_cache_enable', 'cache_engine' => 'sys_db_cache_engine', 'check_accel' => true),
            'Page blocks cache' => array('enabled' => 'sys_pb_cache_enable', 'cache_engine' => 'sys_pb_cache_engine', 'check_accel' => true),
            'Member menu cache' => array('enabled' => 'always_on', 'cache_engine' => 'sys_mm_cache_engine', 'check_accel' => true),
            'Templates Cache' => array('enabled' => 'sys_template_cache_enable', 'cache_engine' => 'sys_template_cache_engine', 'check_accel' => true),
            'CSS files cache' => array('enabled' => 'sys_template_cache_css_enable', 'cache_engine' => '', 'check_accel' => false),
            'JS files cache' => array('enabled' => 'sys_template_cache_js_enable', 'cache_engine' => '', 'check_accel' => false),
            'Compression for CSS/JS cache' => array('enabled' => 'sys_template_cache_compress_enable', 'cache_engine' => '', 'check_accel' => false),
        );

        $this->aRequiredApacheModules = array (
            'rewrite_module' => 'mod_rewrite',
        );
    }

    public function generate()
    {
        ob_start();

        $this->setErrorReporting();

        $this->generateStyles();
        
        $this->requirements();

        if (defined('BX_DOL_VERSION'))
            $this->siteSetup();

        $this->optimization();

        $this->manualCheck();

        $this->restoreErrorReporting();

        return ob_get_clean();
    }

    public function generateStyles() 
    {
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
</style>
        <?php
    }

    public function checkRequirements($sType = BX_DOL_AUDIT_FAIL)
    {
        $this->setErrorReporting();

        $aRet = array ();
        $aMessages = array ();
        $this->requirementsPHP(false, $aMessages);
        foreach ($aMessages as $sName => $r)
            if ($sType == $r['type'])
                $aRet[] = "$sName = " . $this->format_output($r['params']['real_val'], isset($this->aPhpSettings[$sName]) ? $this->aPhpSettings[$sName] : '') . " - " . $this->getMsgHTML($sName, $r);

        $this->restoreErrorReporting();

        return $aRet;
    }

    protected function requirements()
    {
        echo '<h1>Requirements</h1>';
        $this->requirementsPHP();
        if (class_exists('BxDolDb'))
            $this->requirementsMySQL();
        $this->requirementsWebServer();
        $this->requirementsOS();
        $this->requirementsHardware();
    }

    protected function requirementsPHP($bEcho = true, &$aOutputMessages = null)
    {
        $a = unserialize(file_get_contents("http://www.php.net/releases/index.php?serialize=1"));
        $sLatestPhpVersion = $a[5]['version'];

        if (version_compare(phpversion(), "5.4", ">=") == 1)
            unset($this->aPhpSettings['short_open_tag']);

        $aMessages = array ();

        $sPhpVer = PHP_VERSION;        
        if (empty($sLatestPhpVersion))
            $aMessages['version'] = array('type' => BX_DOL_AUDIT_UNDEF, 'msg' => 'value checking failed', 'params' => array ('real_val' => $sPhpVer));
        elseif (version_compare($sPhpVer, $this->sMinPhpVer, '<'))
            $aMessages['version'] = array('type' => BX_DOL_AUDIT_FAIL, 'msg' => 'your version is incompatible with Dolphin, must be at least ' . $this->sMinPhpVer, 'params' => array ('real_val' => $sPhpVer));
        elseif (version_compare($sPhpVer, '5.4.0', '>=') && version_compare($sPhpVer, '6.0.0', '<') && !version_compare($sPhpVer, $sLatestPhpVersion, '>='))
            $aMessages['version'] = array('type' => BX_DOL_AUDIT_WARN, 'msg' => 'your PHP version is probably outdated, upgrade to the latest ' . $sLatestPhpVersion . ' maybe required', 'params' => array ('real_val' => $sPhpVer));
        elseif (version_compare($sPhpVer, '5.2.0', '>=') && version_compare($sPhpVer, '5.4.0', '<') && !version_compare($sPhpVer, $this->sLatestPhp53Version, '>='))
            $aMessages['version'] = array('type' => BX_DOL_AUDIT_WARN, 'msg' => 'your PHP version is probably outdated, upgrade to the latest ' . $this->sLatestPhp53Version . ' maybe required', 'params' => array ('real_val' => $sPhpVer));
        else
            $aMessages['version'] = array('type' => BX_DOL_AUDIT_OK, 'params' => array ('real_val' => $sPhpVer));

        foreach ($this->aPhpSettings as $sName => $r) {
            $a = $this->checkPhpSetting($sName, $r);
            if ($a['res'])
                $aMessages[$sName] = array('type' => BX_DOL_AUDIT_OK, 'params' => $a);
            elseif (isset($r['warn']) && $r['warn'])
                $aMessages[$sName] = array('type' => BX_DOL_AUDIT_WARN, 'msg' => "should be {$r['op']} " . $this->format_output($r['val'], $r), 'params' => $a);
            else
                $aMessages[$sName] = array('type' => BX_DOL_AUDIT_FAIL, 'msg' => "must be {$r['op']} " . $this->format_output($r['val'], $r), 'params' => $a);
        }

        if (null !== $aOutputMessages)
            $aOutputMessages = $aMessages;

        if ($bEcho) {
            echo '<b>PHP</b>: ';
            echo '<ul>';
            foreach ($aMessages as $sName => $r) {
                echo "<li>$sName = " . $this->format_output($r['params']['real_val'], isset($this->aPhpSettings[$sName]) ? $this->aPhpSettings[$sName] : '') . " - ";
                echo $this->getMsgHTML($sName, $r);
                echo "</li>\n";
            }
            echo '</ul>';
        }
    }

    protected function requirementsMySQL() 
    {
        $sMysqlVer = BxDolDb::getInstance()->getServerInfo();
        if (preg_match ('/^(\d+)\.(\d+)\.(\d+)/', $sMysqlVer, $m)) {
            $sMysqlVer = "{$m[1]}.{$m[2]}.{$m[3]}";
            if (version_compare($sMysqlVer, $this->sMinMysqlVer, '<'))
                $aMessage = array('type' => BX_DOL_AUDIT_FAIL, 'msg' => 'your version is incompatible with Dolphin, must be at least ' . $this->sMinMysqlVer);
            else
                $aMessage = array('type' => BX_DOL_AUDIT_OK);
        } else {
            $aMessage = array('type' => BX_DOL_AUDIT_UNDEF, 'msg' => 'value checking failed');
        }

        echo '<b>MySQL</b>: ' . $sMysqlVer . ' - ';
        echo $this->getMsgHTML('version', $aMessage);
    }

    protected function requirementsWebServer()
    {
        echo '<b>Web-server</b>: ';
        echo $_SERVER['SERVER_SOFTWARE'];
        echo '<ul>';
        foreach ($this->aRequiredApacheModules as $sName => $sNameCompiledName)
            echo '<li>' . $sName . ' - ' . $this->checkApacheModule($sName, $sNameCompiledName) . '</li>';
        echo '</ul>';
    }

    protected function requirementsOS() 
    {
        echo '<b>OS</b>: <ul><li>' . php_uname() . '</li></ul>';
    }

    protected function requirementsHardware()
    {
        ?>
        <b>Hardware</b>: <ul><li>Hardware requirements can not be determined automatically - <a href="#manual_audit">manual server audit</a> may be required.</li></ul>
        <?php
    }

    protected function siteSetup()
    {
        $sDolphinPath = defined('BX_DIRECTORY_PATH_ROOT') ? BX_DIRECTORY_PATH_ROOT : './../';

        $sEmailToCkeckMailSending = function_exists('getParam') ? getParam('site_email') : '';

        $sLatestDolphinVer = file_get_contents("http://rss.boonex.com/");
        if (preg_match ('#<dolphin>([\.0-9]+)</dolphin>#', $sLatestDolphinVer, $m))
            $sLatestDolphinVer = $m[1];
        else
            $sLatestDolphinVer = 'undefined';

        ?>
<h1>Site setup</h1>
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
        Please <a href="javascript:void(0);" onclick="switchAdmPage($('#main_menu1'));">click here</a> to find out if dolphin permissions are correct.
    </li>
    <li>
        <b>ffmpeg</b>
        <pre class="code"><?php echo `{$sDolphinPath}flash/modules/global/app/ffmpeg.exe 2>&1`;?></pre>
        if you don't know if output is correct then <a href="#manual_audit">manual server audit</a> may be required.
    </li>
    <li>
        <script language="javascript">
            function bx_sys_adm_audit_test_email()
            {
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
        if you are unsure if output is correct then <a href="#manual_audit">manual server audit</a> may be required.
    </li>
</ul>
        <?php
    }

    protected function optimization()
    {
        echo '<h1>Site optimization</h1>';

        $this->optimizationPhp();

        if (class_exists('BxDolDb'))
            $this->optimizationMySQL();

        $this->optimizationWebServer();

        if (function_exists('getParam'))
            $this->optimizationDolphin();
    }

    protected function optimizationPhp()
    {
        ?>
        <b>PHP</b>:
        <ul>
            <li>
                <?php echo $this->optimizationPhpAccelerator(); ?>
            </li>
            <li>
                <?php echo $this->optimizationPhpSetup(); ?>
            </li>
        </ul>
        <?php
    }

    protected function optimizationPhpAccelerator()
    {            
        echo "<b>PHP accelerator</b> = ";
        $sAccel = $this->getPhpAccelerator();
        if (!$sAccel)
            echo 'NO - <b class="warn">WARNING</b> (Dolphin can be much faster if you install some PHP accelerator))';
        else
            echo $sAccel . ' - <b class="ok">OK</b>';
    }

    protected function optimizationPhpSetup()
    {
        echo "<b>PHP setup</b> = ";
        $sSapi = php_sapi_name();
        echo $sSapi . ' - ';
        if (0 == strcasecmp('cgi', $sSapi))
            echo '<b class="warn">WARNING</b> (your PHP setup maybe very inefficient, <a href="?action=phpinfo">please check it for sure</a> and try to switch to mod_php, apache dso module or FastCGI)';
        else
            echo '<b class="ok">OK</b>';
    }

    protected function optimizationMySQL()
    {
        $oDb = BxDolDb::getInstance();

        ?>
        <b>MySQL</b>:
        <ul>
            <?php
                foreach ($this->aMysqlOptimizationSettings as $sName => $r) {
                    $a = $this->checkMysqlSetting($sName, $r, $oDb);
                    echo "<li><b>$sName</b> = " . $this->format_output($a['real_val'], $r) ." - " . ($a['res'] ? '<b class="ok">OK</b>' : "<b class='fail'>FAIL</b> (must be {$r['op']} " . $this->format_output($r['val'], $r) . ")") . "</li>\n";
                }
            ?>
        </ul>
        <?php
    }

    protected function optimizationWebServer()
    {
        ?>
        <b>Web-server</b>:
        <ul>
            <li>
                <b>User-side caching for static content</b> =
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
        <?php
    }

    protected function optimizationDolphin()
    {
        ?>
        <b>Dolphin</b>:
        <ul>
            <?php

                foreach ($this->aDolphinOptimizationSettings as $sName => $a) {

                    echo "<li><b>$sName</b> = ";

                    echo ('always_on' == $a['enabled'] || getParam($a['enabled'])) ? 'On' : 'Off';

                    if ($a['cache_engine'])
                        echo " (" . getParam($a['cache_engine']) . ' based cache engine)';

                    echo ' - ';

                    if ('always_on' != $a['enabled'] && !getParam($a['enabled']))
                        echo '<b class="fail">FAIL</b> (please enable this cache in Dolphin Admin Panel -> Settings -> Advanced Settings)';
                    elseif ($a['check_accel'] && !$this->getPhpAccelerator() && 'File' == getParam($a['cache_engine']))
                        echo '<b class="warn">WARNING</b> (installing PHP accelerator will speed-up file cache)';
                    else
                        echo '<b class="ok">OK</b>';

                    echo "</li>\n";
                }

            ?>
        </ul>
        <?php
    }

    protected function manualCheck()
    {
        ?>
<a name="manual_audit"></a>
<h1>Manual Server Audit</h1>
<p>
    Some things can not be determined automatically, manual server audit is required to check it. If you don't know how to do it by yourself you can submit <a target="_blank" href="http://www.boonex.com/help/tickets">BoonEx Server Audit Request</a>.
</p>
        <?php
    }

    protected function checkPhpSetting($sName, $a)
    {
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
                $bResult = (isset($a['unlimited']) && $mixedVal == $a['unlimited']) ? true : ($mixedVal >= $a['val']);
                break;
            case '=':
            default:
                $bResult = ($mixedVal == $a['val']);
        }
        return array ('res' => $bResult, 'real_val' => $mixedVal);
    }

    protected function checkMysqlSetting($sName, $a, $oDb)
    {
        $mixedVal = $oDb->getOption($sName);
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

    protected function format_output ($mixedVal, $a)
    {
        if (isset($a['type']) && 'bool' == $a['type'])
            return $mixedVal ? 'On' : 'Off';
        else
            return $mixedVal;
    }

    protected function format_input ($mixedVal, $a)
    {
        if (isset($a['type']) && 'bytes' == $a['type']) 
            return $this->format_bytes ($mixedVal);
        else
            return $mixedVal;
    }

    protected function format_bytes($val)
    {
        return return_bytes($val);
    }

    protected function checkApacheModule ($sModule, $sNameCompiledName = '')
    {
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


    protected function getPhpAccelerator ()
    {
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

    protected function getUrlForGooglePageSpeed ($sRule)
    {
        $sUrl = urlencode(BX_DOL_URL_ROOT);
        return 'http://pagespeed.googlelabs.com/#url=' . $sUrl . '&mobile=false&rule=' . $sRule;
    }

    protected function sendTestEmail ()
    {
        $sEmailToCkeckMailSending = getParam('site_email');
        $mixedRet = sendMail($sEmailToCkeckMailSending, 'Audit Test Email', 'Sample text for testing<br /><u><b>Sample text for testing</b></u>', '', array(), BX_EMAIL_SYSTEM);
        if (!$mixedRet)
            return '<b class="fail">FAIL</b> (mail send failed)';
        else
            return 'test mail was send, please check ' . $sEmailToCkeckMailSending . ' mailbox';
    }

    protected function setErrorReporting () 
    {   
        if (version_compare(phpversion(), "5.3.0", ">=") == 1)
            $this->iPhpErrorReporting = error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
        else
            $this->iPhpErrorReporting = error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    }

    protected function restoreErrorReporting () 
    {
        error_reporting($this->iPhpErrorReporting);
    }
    
    protected function getMsgHTML($sName, $a) 
    {
        $s = '';
        $s .= '<b class="' . $this->aType2ClassCSS[$a['type']]. '">' . $this->aType2Title[$a['type']]. '</b> ';
        if (isset($a['msg']))
            $s .= '(' . $a['msg'] . ')';
        return $s;
    }
}

/** @} */

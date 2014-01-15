<?php

/**
 * This test performs test install, it can do it only if script is not installed yet.
 */
class InstallTest extends PHPUnit_Framework_TestCase
{
    protected $_aSiteConfig;

    public function __construct() 
    {
        $aPathInfo = pathinfo(__FILE__);
        $this->_aSiteConfig = array (
            'server_http_host' => 'localhost',
            'server_php_self' => '/install/index.php',
            'server_doc_root' => str_replace('tests/units/install', '', $aPathInfo['dirname']),
            'form_data' => array (
                'site_config' => true,
                'db_name' => 'test',
                'db_user' => 'root',
                'db_password' => 'root',
                'site_title' => 'Dolphin Test',
                'site_desc' => 'Dolphin Test',
                'site_email' => 'no-reply@example.com',
                'admin_email' => 'admin@example.com',
                'admin_username' => 'admin',
                'admin_password' => 'dolphin',
                'language' => 'en',
            ),
        );
    }

    protected function setUp()
    {
        // skip this test if script is already installed
        if (file_exists('../inc/header.inc.php')) 
            $this->markTestSkipped('Script is installed. Can\'t perform test install.');

        // include necessary files to perform install
        $_REQUEST['action'] = 'empty';
        require_once('../install/index.php');
    }

    public function testRequirements()
    {
        $oAudit = new BxDolStudioToolsAudit();
        $aErrors = $oAudit->checkRequirements(BX_DOL_AUDIT_FAIL);

        $this->assertEmpty($aErrors);
    }

    /**
     * @depends testRequirements
     */
    public function testPermissions()
    {
        $oAdmTools = new BxDolStudioTools();
        $bPermissionsOk = $oAdmTools->checkPermissions(false, false);

        $this->assertTrue($bPermissionsOk);
    }

    /**
     * @depends testPermissions
     */
    public function testCreateSiteConfig()
    {
        $oSiteConfig = new BxDolInstallSiteConfig($this->_aSiteConfig['server_http_host'], $this->_aSiteConfig['server_php_self'], $this->_aSiteConfig['server_doc_root'], false);
        $sErrorMessage = '';
        $mixedResult = $oSiteConfig->getFormHtml(array_merge($oSiteConfig->getAutoValues(), $this->_aSiteConfig['form_data']), false, $sErrorMessage);

        if (true !== $mixedResult)
            $this->fail($sErrorMessage ? $sErrorMessage : 'Form data was not submitted');
    }
}

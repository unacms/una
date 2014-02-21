<?php

/**
 * Test DNSURI black lists in Antispam module
 */
class BxAntispamDNSURIBlacklistsTest extends BxDolTestCase
{
    protected $_oDNSURIBlacklists;
    

    protected function setUp()
    {
        bx_import('BxDolModule');
        $oModule = BxDolModule::getInstance('bx_antispam');

        $this->_oDNSURIBlacklists = bx_instance('BxAntispamDNSURIBlacklists', array(), $oModule->_aModule);
    }

    protected function tearDown()
    {
        unset($this->_oDNSURIBlacklists);
    }

    public function providerForIsSpam()
    {
        return array(
            array("some text without urls", false), // no spam detection in text without urls
            array("Hello. \nDolphin lives on http://www.boonex.com site", false), // no spam for good urls
            array("Hello. \nDolphin lives on <a href=\"http://www.boonex.com\">BoonEx</a> site", false), // no spam for good urls
            array("Hello. \nDolphin lives on <a href='http://www.boonex.com'>BoonEx</a> site", false), // no spam for good urls
            array("Hello. \nDolphin lives on <a href=http://www.boonex.com>BoonEx</a> site", false), // no spam for good urls
            array("Hello. \nThere is spam on http://surbl-org-permanent-test-point.com site", true), // text with spammer URL
            array("Hello. \nThere is spam on <a href=\"http://surbl-org-permanent-test-point.com\">test point</a> site", true), // text with spammer URL
            array("Hello. \nThere is spam on <a href='http://surbl-org-permanent-test-point.com'>test point</a> site", true), // text with spammer URL
            array("Hello. \nThere is spam on <a href=http://surbl-org-permanent-test-point.com>test point</a> site", true), // text with spammer URL
        );
    }

    /**
     * @dataProvider providerForIsSpam
     */
    public function testIsSpam($sText, $bRes)
    {
        // check result boolean value
        $this->assertEquals($bRes, $this->_oDNSURIBlacklists->isSpam($sText));
    }
}

